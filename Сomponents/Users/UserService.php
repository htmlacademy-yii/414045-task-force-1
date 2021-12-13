<?php

declare(strict_types=1);

namespace Components\Users;

use Components\Categories\CategoryService;
use Components\Locations\LocationService;
use Yii;
use frontend\models\User;

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
    public function getUserLocation(int $id)
    {
        $user = User::findOne($id);

        return (new LocationService($user->city->title))->getLocationPoint();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getUserCategories(int $id)
    {
        $user = User::findOne($id);
        $userSpecialties = $user->specialties;
        $result = [];

        foreach ($userSpecialties as $specialty) {
            $result[] = $specialty->id;
        }

        return $result;
    }
}