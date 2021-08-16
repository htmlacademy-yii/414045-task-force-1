<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_settings}}`.
 */
class m210726_091710_add_created_at_column_updated_at_column_to_user_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_settings}}', 'created_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn(
            '{{%user_settings}}',
            'updated_at',
            $this->datetime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_settings}}', 'created_at');
        $this->dropColumn('{{%user_settings}}', 'updated_at');
    }
}
