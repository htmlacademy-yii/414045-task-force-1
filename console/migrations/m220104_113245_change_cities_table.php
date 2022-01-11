<?php

use yii\db\Migration;

/**
 * Class m220104_113245_change_cities_table
 */
class m220104_113245_change_cities_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('cities', 'location', 'POINT DEFAULT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('cities', 'location', 'POINT NOT NULL');
    }
}
