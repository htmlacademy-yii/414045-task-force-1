<?php

namespace frontend\models;

use Yii;

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
class UserSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'is_message_ntf_enabled', 'is_action_ntf_enabled', 'is_new_review_ntf_enabled', 'is_hidden', 'is_active'], 'required'],
            [['user_id', 'is_message_ntf_enabled', 'is_action_ntf_enabled', 'is_new_review_ntf_enabled', 'is_hidden', 'is_active'], 'integer'],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'is_message_ntf_enabled' => 'Is Message Ntf Enabled',
            'is_action_ntf_enabled' => 'Is Action Ntf Enabled',
            'is_new_review_ntf_enabled' => 'Is New Review Ntf Enabled',
            'is_hidden' => 'Is Hidden',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
