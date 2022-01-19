<?php

namespace frontend\controllers;

use Components\Routes\Route;
use Components\Users\UserService;
use frontend\models\AccountSettingsForm;
use frontend\models\Portfolio;
use frontend\models\User;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\web\UploadedFile;

class AccountController extends SecuredController
{
    public $enableCsrfValidation = false;

    /**
     * @throws StaleObjectException
     * @throws Throwable
     * @throws Exception
     */
    public function actionIndex()
    {
        $user = User::findOne(Yii::$app->user->id);
        $accountSettings = new AccountSettingsForm();

        if (Yii::$app->request->getIsPost()) {
            if (array_key_exists('file', $_FILES)) {
                $files = UploadedFile::getInstancesByName('file');
                foreach ($files as $file) {
                    $src = 'uploads/portfolio/' . $user->id . '_' . $file->name;
                    if ($file->saveAs($src)) {
                        $portfolio = new Portfolio();
                        $portfolio->user_id = $user->id;
                        $portfolio->img_src = '/' . $src;
                        $portfolio->save();
                    }
                }
            }

            $accountSettings->load(Yii::$app->request->post());
            $accountSettings->avatar = UploadedFile::getInstance($accountSettings, 'avatar');

            (new UserService())->updateAccountSettings($user, $accountSettings);

            $this->redirect(Route::getAccount());
        }

        return $this->render('index', compact('user', 'accountSettings'));
    }
}