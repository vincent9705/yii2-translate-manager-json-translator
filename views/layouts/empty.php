<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>

    <nav class="navbar navbar-dark bg-dark justify-content-between">
        <a class="navbar-brand">Translator</a>
    </nav>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="">

        <div class="">
            <?= $content ?>
        </div>

    </div>

<?php $this->endBody() ?>
</body>
<?php $this->endPage() ?>