<?php

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "favorite_executors".
 *
 * @property int $id
 * @property int $user_id
 * @property int $executor_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class FavoriteExecutors extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'favorite_executors';
    }
}