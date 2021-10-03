<?php

declare(strict_types=1);

namespace Components\Users;

use Yii;
use frontend\models\User;

final class UserHelper
{
    public static function getCountRatingStars($rating): float
    {
        return round($rating / 100, 2);
    }

    public static function getUser(): ?User
    {
        if ($id = Yii::$app->user->getId()) {

            return User::findOne($id);
        }

        return null;
    }
}