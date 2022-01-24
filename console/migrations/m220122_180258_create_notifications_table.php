<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notifications}}`.
 */
class m220122_180258_create_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notifications}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'content' => $this->string(128)->notNull(),
            'task_id' => $this->integer(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('idx-notifications-user-id', 'notifications', 'user_id');
        $this->addForeignKey('fk-notifications-user-id', 'notifications', 'user_id', 'users', 'id');
        $this->createIndex('idx-notifications-task-id', 'notifications', 'task_id');
        $this->addForeignKey('fk-notifications-task-id', 'notifications', 'task_id', 'tasks', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notifications}}');
    }
}
