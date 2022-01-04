<?php

namespace frontend\models;

use Components\Categories\CategoryService;
use Components\Users\UserService;
use yii\base\Model;
use yii\web\UploadedFile;

class AccountSettingsForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $avatar;
    public $name = '';
    public $email = '';
    public string $address = '';
    public string $birthday = '';
    public string $about = '';
    public array|string $userSpecialties = [];
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

    public function upload(User $user)
    {
        if (!$this->avatar) {
            return false;
        }

        if ($this->avatar->saveAs('uploads/avatars/' . $user->id . '_' . $this->avatar->baseName . '.' . $this->avatar->extension)) {
            $user->avatar_src = 'uploads/avatars/' . $user->id . '_' . $this->avatar->baseName . '.' . $this->avatar->extension;

            if ($user->save()) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function attributeLabels()
    {
        return [
            'avatar' => 'Сменить аватар',
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
            [['avatar'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
            ['password', 'string', 'min' => 8, 'tooShort' => 'Длина пароля от 8 символов'],
            ['about', 'string'],
            [
                ['name', 'password', 'skype', 'overMessenger'],
                'string',
                'max' => 128,
            ],
            ['email', 'string', 'max' => 64],
            ['address', 'string', 'max' => 256],
            ['phone', 'string', 'max' => 20],
            [
                [
                    'avatar',
                    'file',
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