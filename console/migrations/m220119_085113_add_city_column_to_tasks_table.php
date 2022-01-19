<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tasks}}`.
 */
class m220119_085113_add_city_column_to_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'city_id', 'int');
        $this->createIndex('idx-tasks-city-id', 'tasks', 'city_id');
        $this->addForeignKey('fk-tasks-city-id', 'tasks', 'city_id', 'cities', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tasks', 'city_id');
        $this->dropIndex('idx-tasks-city-id', 'tasks');
        $this->dropForeignKey('fk-tasks-city-id', 'tasks');
    }
}
