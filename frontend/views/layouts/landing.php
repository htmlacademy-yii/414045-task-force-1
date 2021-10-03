<?php

/**
 * @var $this View;
 * @var $content string;
 */

use yii\web\View;

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>TaskForce</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <?php $this->head() ?>
</head>
<body class="landing">
<?php $this->beginBody() ?>
<?= $content ?>
<script src="js/main.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
