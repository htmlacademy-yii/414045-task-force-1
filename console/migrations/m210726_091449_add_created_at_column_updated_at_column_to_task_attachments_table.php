<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%task_attachments}}`.
 */
class m210726_091449_add_created_at_column_updated_at_column_to_task_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%task_attachments}}',
            'created_at',
            $this->datetime()->defaultExpression('CURRENT_TIMESTAMP')
        );
        $this->addColumn(
            '{{%task_attachments}}',
            'updated_at',
            $this->datetime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%task_attachments}}', 'created_at');
        $this->dropColumn('{{%task_attachments}}', 'updated_at');
    }
}
