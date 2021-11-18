<?php

use yii\db\Migration;

/**
 * Class m211115_162919_add_task_id_column_messages_table
 */
class m211115_162919_add_task_id_column_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('messages', 'task_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('messages', 'task_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211115_162919_add_task_id_column_messages_table cannot be reverted.\n";

        return false;
    }
    */
}
