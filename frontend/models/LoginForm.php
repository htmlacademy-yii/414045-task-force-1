<?php

declare(strict_types=1);

namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';

    private ?User $user;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['password', 'validatePassword'],
            ['email', 'required', 'message' => 'введите email'],
            ['email', 'email', 'message' => 'введите корректный email'],
            ['password', 'required', 'message' => 'введите пароль'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }

    public function getUser(): ?User
    {
        if ($this->user === null) {
            $this->user = User::findOne(['email' => $this->email]);
        }

        return $this->user;
    }
}