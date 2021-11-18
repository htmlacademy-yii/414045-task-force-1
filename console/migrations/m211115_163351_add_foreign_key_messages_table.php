<?php

use yii\db\Migration;

/**
 * Class m211115_163351_add_foreign_key_messages_table
 */
class m211115_163351_add_foreign_key_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-messages-task_id', 'messages', 'task_id');
        $this->addForeignKey('fk-messages-task_id', 'messages', 'task_id', 'tasks', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-messages-task_id', 'messages');
        $this->dropForeignKey('fk-messages-task_id', 'messages');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211115_163351_add_foreign_key_messages_table cannot be reverted.\n";

        return false;
    }
    */
}
