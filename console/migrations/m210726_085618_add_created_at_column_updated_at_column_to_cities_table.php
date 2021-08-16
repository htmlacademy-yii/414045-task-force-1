<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%cities}}`.
 */
class m210726_085618_add_created_at_column_updated_at_column_to_cities_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%cities}}', 'created_at', $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn(
            '{{%cities}}',
            'updated_at',
            $this->datetime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%cities}}', 'created_at');
        $this->dropColumn('{{%cities}}', 'updated_at');
    }
}
