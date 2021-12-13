<?php

namespace frontend\models;

use Components\Categories\CategoryService;
use Components\Users\UserService;
use yii\base\Model;

class AccountSettingsForm extends Model
{
    public $name = '';
    public $email = '';
    public string $address = '';
    public string $birthday = '';
    public string $about = '';
    public array $userSpecialties = [];
    public array $specialties = [];
    public string $password = '';
    public string $confirmPassword = '';
    public string $phone = '';
    public string $skype = '';
    public string $overMessenger = '';
    public bool $isMessageNtfEnabled;
    public bool $isActionNtfEnabled;
    public bool $isNewReviewNtfEnabled;
    public bool $isActive;
    public bool $isHidden;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $user = User::findOne(\Yii::$app->user->id);
        $this->specialties = (new CategoryService())->getCategoryNames();
        $this->userSpecialties = (new UserService())->getUserCategories(\Yii::$app->user->id);
        $this->isMessageNtfEnabled = $user->userSettings->is_message_ntf_enabled;
        $this->isActionNtfEnabled = $user->userSettings->is_action_ntf_enabled;
        $this->isNewReviewNtfEnabled = $user->userSettings->is_new_review_ntf_enabled;
        $this->isActive = $user->userSettings->is_active;
        $this->isHidden = $user->userSettings->is_hidden;
    }


    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'email',
            'address' => 'Адрес',
            'birthday' => 'День рождения',
            'about' => 'Информация о себе',
            'password' => 'Новый пароль',
            'confirmPassword' => 'Повтор пароля',
            'phone' => 'Телефон',
            'skype' => 'Skype',
            'overMessenger' => 'Другой мессенджер',
        ];
    }

    public function rules()
    {
        return [
//            ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
//            ['password', 'string', 'min' => 8, 'tooShort' => 'Длина пароля от 8 символов'],
//            ['about', 'string'],
//            [
//                ['name', 'password', 'skype', 'overMessenger'],
//                'string',
//                'max' => 128,
//            ],
            ['email', 'string', 'max' => 64],
//            ['address', 'string', 'max' => 256],
//            ['phone', 'string', 'max' => 20],
            [
                [
                    'name',
                    'email',
                    'address',
                    'birthday',
                    'about',
                    'userSpecialties',
                    'specialties',
                    'password',
                    'confirmPassword',
                    'phone',
                    'skype',
                    'overMessenger',
                    'isMessageNtfEnabled',
                    'isActionNtfEnabled',
                    'isNewReviewNtfEnabled',
                    'isActive',
                    'isHidden'
                ],
                'safe'
            ]
        ];
    }
}