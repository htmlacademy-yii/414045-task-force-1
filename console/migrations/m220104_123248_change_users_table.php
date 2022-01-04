<?php

use yii\db\Migration;

/**
 * Class m220104_123248_change_users_table
 */
class m220104_123248_change_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('users', 'avatar_src', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('users', 'avatar_src', $this->string(128));
    }
}
