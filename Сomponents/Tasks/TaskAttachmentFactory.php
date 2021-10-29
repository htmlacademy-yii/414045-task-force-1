<?php

namespace Components\Tasks;

use frontend\models\TaskAttachment;

class TaskAttachmentFactory
{
    public function create(int $taskId, array $fileName): TaskAttachment
    {
        $file = new TaskAttachment();
        $file->task_id = $taskId;
        $file->file_base_name = $fileName['baseName'];
        $file->file_name = $fileName['name'];
        $file->file_src = TaskAttachment::UPLOAD_DIR . $fileName['name'];

        return $file;
    }
}