<?php

use yii\db\Migration;

/**
 * Class m220126_083053_seed_cities
 */
class m220126_083053_seed_cities extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents(__DIR__ . '/../../sqlExportData/cities.sql'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220126_083053_seed_cities cannot be reverted.\n";

        return false;
    }
}
