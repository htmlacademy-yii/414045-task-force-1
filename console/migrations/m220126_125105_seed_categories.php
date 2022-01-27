<?php

use yii\db\Migration;

/**
 * Class m220126_125105_seed_categories
 */
class m220126_125105_seed_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents(__DIR__ . '/../../sqlExportData/categories.sql'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220126_125105_seed_categories cannot be reverted.\n";

        return false;
    }
}
