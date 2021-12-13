<?php

declare(strict_types=1);

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_settings".
 *
 * @property int $id
 * @property int $user_id
 * @property int $is_message_ntf_enabled
 * @property int $is_action_ntf_enabled
 * @property int $is_new_review_ntf_enabled
 * @property int $is_hidden
 * @property int $is_active
 *
 * @property User $user
 */
final class UserSettings extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                [
                    'is_message_ntf_enabled',
                    'is_action_ntf_enabled',
                    'is_new_review_ntf_enabled',
                    'is_hidden',
                    'is_active',
                ],
                'required',
            ],
            [
                [
                    'is_message_ntf_enabled',
                    'is_action_ntf_enabled',
                    'is_new_review_ntf_enabled',
                    'is_hidden',
                    'is_active',
                ],
                'boolean',
            ],
            [['user_id'], 'unique'],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
            [
                [
                    'is_message_ntf_enabled',
                    'is_action_ntf_enabled',
                    'is_new_review_ntf_enabled',
                    'is_hidden',
                    'is_active',
                ],
                'safe',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'UserService ID',
            'is_message_ntf_enabled' => 'Is Message Ntf Enabled',
            'is_action_ntf_enabled' => 'Is Action Ntf Enabled',
            'is_new_review_ntf_enabled' => 'Is New Review Ntf Enabled',
            'is_hidden' => 'Is Hidden',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[UserService]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
