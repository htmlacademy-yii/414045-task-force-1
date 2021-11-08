<?php

declare(strict_types=1);

namespace Components\Users;

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
    public function getCountRatingStars($rating): float
    {
        return round($rating / 100, 2);
    }

    public function getUser(): ?User
    {
        if ($id = Yii::$app->user->getId()) {

            return User::findOne($id);
        }

        return null;
    }

    public function getUserLocation(int $id)
    {
        $user = User::findOne($id);

        return (new LocationService($user->city->title))->getLocationPoint();
    }
}