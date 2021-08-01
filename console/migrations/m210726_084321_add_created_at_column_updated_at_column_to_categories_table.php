<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%categories}}`.
 */
class m210726_084321_add_created_at_column_updated_at_column_to_categories_table extends
    Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'created_at', $this->datetime());
        $this->addColumn('{{%categories}}', 'updated_at', $this->datetime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%categories}}', 'created_at');
        $this->dropColumn('{{%categories}}', 'updated_at');
    }
}
