<?php

declare(strict_types=1);

namespace Components\Users;

use Components\Constants\UserConstants;
use Components\Exceptions\TimeException;
use Components\Locations\LocationService;
use Components\Time\TimeDifference;
use frontend\models\AccountSettingsForm;
use frontend\models\Category;
use frontend\models\UserSettings;
use frontend\models\UsersSpecialty;
use Throwable;
use Yii;
use frontend\models\User;
use yii\base\Exception;
use yii\db\StaleObjectException;

/**
 * class UserService
 *
 * @package Components/Users
 */
final class UserService
{
    /**
     * @param $rating
     * @return float
     */
    public function getCountRatingStars($rating): float
    {
        return round($rating / 100, 2);
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($id = Yii::$app->user->getId()) {
            return User::findOne($id);
        }

        return null;
    }

    /**
     * @param int $id
     * @return false|string
     */
    public function getUserLocation(int $id): bool|string
    {
        $user = User::findOne($id);

        return (new LocationService(address: $user->city->title, point: false))->getLocationPoint();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getUserCategories(int $id): array
    {
        $user = User::findOne($id);
        $userSpecialties = $user->specialties;
        $result = [];

        foreach ($userSpecialties as $specialty) {
            $result[] = $specialty->id - 1;
        }

        return $result;
    }

    /**
     * @throws StaleObjectException
     * @throws Exception
     * @throws Throwable
     */
    public function updateAccountSettings(User $user, AccountSettingsForm $accountSettings)
    {
        if ($accountSettings->validate() && $user->validate()) {
            $this->updateUserSettingsFromAccountSettings($user, $accountSettings);
            $this->updateUserFromAccountSettings($user, $accountSettings);
            $this->updateUserSpecialitiesFromAccountSettings($user, $accountSettings);
            $this->saveAvatar($user, $accountSettings);
        }
    }

    /**
     * @param User $user
     * @param AccountSettingsForm $accountSettings
     */
    public function updateUserSettingsFromAccountSettings(User $user, AccountSettingsForm $accountSettings)
    {
        $userSettings = $user->userSettings;
        $userSettings->is_message_ntf_enabled = $accountSettings->isMessageNtfEnabled;
        $userSettings->is_action_ntf_enabled = $accountSettings->isActionNtfEnabled;
        $userSettings->is_new_review_ntf_enabled = $accountSettings->isNewReviewNtfEnabled;
        $userSettings->is_hidden = $accountSettings->isHidden;
        $userSettings->is_active = $accountSettings->isActive;
        $userSettings->save();
    }

    /**
     * @param User $user
     * @param AccountSettingsForm $accountSettings
     * @throws Exception
     */
    public function updateUserFromAccountSettings(User $user, AccountSettingsForm $accountSettings)
    {
        if ($accountSettings->email) {
            $user->email = $accountSettings->email;
        }

        if ($accountSettings->address) {
            $user->full_address = $accountSettings->address;
        }

        if ($accountSettings->birthday) {
            $user->birthday = $accountSettings->birthday;
        }

        if ($accountSettings->about) {
            $user->about = $accountSettings->about;
        }

        if ($accountSettings->phone) {
            $user->phone = $accountSettings->phone;
        }

        if ($accountSettings->skype) {
            $user->skype = $accountSettings->skype;
        }

        if ($accountSettings->overMessenger) {
            $user->over_messenger = $accountSettings->overMessenger;
        }

        if ($accountSettings->password && $accountSettings->password === $accountSettings->confirmPassword) {
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($accountSettings->password);
        }

        $user->save();
    }

    /**
     * @param User $user
     * @param AccountSettingsForm $accountSettings
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function updateUserSpecialitiesFromAccountSettings(User $user, AccountSettingsForm $accountSettings)
    {
        foreach ($accountSettings->specialties as $specialty) {
            $category = Category::find()->where(['title' => $specialty])->one();
            $categoryId = $category->id;
            $userSpecialty = UsersSpecialty::find()->where([
                'user_id' => $user->id,
                'category_id' => $categoryId
            ])->one();

            if (!$userSpecialty) {
                if (!in_array($categoryId - 1, (array)$accountSettings->userSpecialties)) {
                    continue;
                }

                $userSpecialty = new UsersSpecialty();
            }

            if (!in_array($categoryId - 1, (array)$accountSettings->userSpecialties)) {
                $userSpecialty->delete();

                continue;
            }

            $userSpecialty->user_id = $user->id;
            $userSpecialty->category_id = $categoryId;
            $userSpecialty->save();
        }

        $user->role = $this->getUserRole($user->id);
        $user->save();
    }

    /**
     * @param int $userId
     * @return int
     */
    public function getUserRole(int $userId): int
    {
        $user = User::findOne($userId);
        $role = UserConstants::USER_ROLE_CUSTOMER;
        $userSpecialties = $user->specialties;

        if (count($userSpecialties) > 0) {
            $role = UserConstants::USER_ROLE_EXECUTOR;
        }

        return $role;
    }

    /**
     * @param User $user
     * @param AccountSettingsForm $accountSettings
     */
    public function saveAvatar(User $user, AccountSettingsForm $accountSettings)
    {
        if ($accountSettings->avatar !== null) {
            $accountSettings->upload($user);
        }
    }

    /**
     * @param User $user
     * @return string
     * @throws TimeException
     */
    public function getLastActivity(User $user): string
    {
        $lastAction = (new TimeDifference(date('Y-m-d h:i:s'), $user->last_activity))->getCountTimeUnits([
            'year' => 'y',
            'day' => 'd',
            'hour' => 'H',
            'minute' => 'i'
        ]);

        if ($lastAction === '') {
            return 'Онлайн';
        }

        return 'Был на сайте ' . $lastAction . 'назад';
    }

    /**
     * @param User $user
     * @return string
     * @throws TimeException
     */
    public function getTimeOnSite(User $user): string
    {
        return (new TimeDifference(date('Y-m-d h:i:s'), $user->created_at))->getCountTimeUnits([
            'year' => 'y',
            'day' => 'd'
        ]);
    }

    /**
     * @param $executorId
     * @return bool
     */
    public function isFavoriteExecutor($executorId): bool
    {
        $user = $this->getUser();
        $favoriteExecutors = $user->favoriteExecutors;

        foreach ($favoriteExecutors as $executor) {
            if ($executor->id === $executorId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $userId
     * @return void
     */
    public function saveUserSettings($userId)
    {
        $userSettings = new UserSettings();
        $userSettings->user_id = $userId;
        $userSettings->is_message_ntf_enabled = 1;
        $userSettings->is_action_ntf_enabled = 1;
        $userSettings->is_new_review_ntf_enabled = 1;
        $userSettings->is_hidden = 0;
        $userSettings->is_active = 1;
        $userSettings->save();
    }

    /**
     * @return void
     */
    public function updateLastAction()
    {
        if (!Yii::$app->user->isGuest) {
            User::updateAll(['last_activity'=>date('Y-m-d h:i:s')],['id'=>Yii::$app->user->id]);
        }
    }

    /**
     * @param int $userId
     * @return void
     */
    public function updateUserRating(int $userId)
    {
        $user = User::findOne($userId);
        $reviews = $user->reviews;
        $taskRatings = [];
        $rating = 0;

        foreach ($reviews as $review) {
            $taskRatings[] = $review->rating;
        }

        if (count($taskRatings) > 0) {
            $rating = round(array_sum($taskRatings)/count($taskRatings) * 100);
        }

        $user->rating = $rating;
        $user->save();
    }

    /**
     * @param int $executorId
     * @return bool
     */
    public function checkUserIsExecutorForCurrentUser(int $executorId): bool
    {
        $user = $this->getUser();
        $tasks = $user->tasksWhereUserIsCustomer;

        foreach ($tasks as $task) {
            if ($task->executor_id === $executorId) {
                return true;
            }
        }

        return false;
    }
}