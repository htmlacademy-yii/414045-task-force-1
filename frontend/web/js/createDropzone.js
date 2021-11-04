var dropzone = new Dropzone("div.create__file", {
    headers: {"<?= Request::CSRF_HEADER ?>": "<?= Yii::$app->getRequest()->getCsrfToken() ?>"},
    url: "/create/upload",
    paramName: "attachmentFiles[]"
});