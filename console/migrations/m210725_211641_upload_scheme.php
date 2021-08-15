<?php

use yii\db\Migration;

/**
 * Class m210725_211641_upload_scheme
 */
class m210725_211641_upload_scheme extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents(__DIR__ . '/../../scheme.sql'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210725_211641_upload_scheme cannot be reverted.\n";

        return false;
    }
}
