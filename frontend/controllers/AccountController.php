<?php

namespace frontend\controllers;

use Components\Users\UserService;
use frontend\models\AccountSettingsForm;
use frontend\models\User;
use frontend\models\UsersSpecialty;
use \Yii;

class AccountController extends SecuredController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $user = User::findOne(Yii::$app->user->id);
        $accountSettings = new AccountSettingsForm();

        if (Yii::$app->request->getIsPost()) {
            $accountSettings->load(Yii::$app->request->post());

            if ($accountSettings->validate() && $user->validate()) {
                $userSettings = $user->userSettings;
                $userSettings->is_message_ntf_enabled = $accountSettings->isMessageNtfEnabled;
                $userSettings->is_action_ntf_enabled = $accountSettings->isActionNtfEnabled;
                $userSettings->is_new_review_ntf_enabled = $accountSettings->isNewReviewNtfEnabled;
                $userSettings->is_hidden = $accountSettings->isHidden;
                $userSettings->is_active = $accountSettings->isActive;
                $userSettings->save();
                $user->email = $accountSettings->email ?? $user->email;
                $user->full_address = $accountSettings->address ?? $user->full_address;
                $user->birthday = $accountSettings->birthday ?? $user->birthday;
                $user->about = $accountSettings->about ?? $user->about;

                if ($accountSettings->password && $accountSettings->password === $accountSettings->confirmPassword) {
                    $user->password = Yii::$app->getSecurity()->generatePasswordHash($accountSettings->password);
                }

                $user->phone = $accountSettings->phone ?? $user->phone;
                $user->skype = $accountSettings->skype ?? $user->skype;
                $user->over_messenger = $accountSettings->overMessenger ?? $user->over_messenger;
                $user->save();

                foreach ($accountSettings->userSpecialties as $specialty) {
                    if (!in_array($specialty, (new UserService())->getUserCategories($user->id))) {
                        $userSpecialty = new UsersSpecialty();
                        $userSpecialty->user_id = $user->id;
                        $userSpecialty->category_id = $specialty;
                        $userSpecialty->save();
                    }
                }
            }
        }

        return $this->render('index', compact('user', 'accountSettings'));
    }
}