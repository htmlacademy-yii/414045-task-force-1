<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%portfolio}}`.
 */
class m210726_090650_add_created_at_column_updated_at_column_to_portfolio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%portfolio}}', 'created_at', $this->datetime());
        $this->addColumn('{{%portfolio}}', 'updated_at', $this->datetime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%portfolio}}', 'created_at');
        $this->dropColumn('{{%portfolio}}', 'updated_at');
    }
}
