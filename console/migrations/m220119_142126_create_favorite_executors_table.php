<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorite_executors}}`.
 */
class m220119_142126_create_favorite_executors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favorite_executors}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('idx-favorite-executors-user-id', 'favorite_executors', 'user_id');
        $this->addForeignKey('fk-favorite-executors-user-id', 'favorite_executors', 'user_id', 'users', 'id');
        $this->createIndex('idx-favorite-executors-executor-id', 'favorite_executors', 'executor_id');
        $this->addForeignKey('fk-favorite-executors-executor-id', 'favorite_executors', 'executor_id', 'users', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%favorite_executors}}');
    }
}
