<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadTaskAttachmentsFiles extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 10],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->files as $file) {
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels(): array
    {
        return [
            'files' => 'файлы',
        ];
    }
}