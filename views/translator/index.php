<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use app\components\Language;

$this->title = 'Translator';
$_url = Url::to(['translate']);
$_urlDownload = Url::to(['download']);
$this->registerJs("
    var _url = '{$_url}';
	var _urlDownload = '{$_urlDownload}';
");
$this->registerJs($this->render('_script.js'));
?>

<?php $form = ActiveForm::begin([
	'id' => 'form',
    // 'action' => ['translate-download'],
	'layout' => 'horizontal',
	'method' => 'post',
	'fieldConfig' => [
		'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
		'horizontalCssClasses' => [
			'label' => 'col-sm-2',
			'wrapper' => 'col-sm-10',
		],
	],
	'options' => ['data-pjax' => false],
	'enableAjaxValidation'   => false,
	'enableClientValidation' => false,
]) ?>

<div id="form-loading" class="position-fixed w-100 h-100 justify-content-center align-items-center d-flex" style="background-color: rgba(0, 0, 0, 0.5); z-index: 9999;">
	<div class="spinner-border text-light" role="status">
		<span class="sr-only">Loading...</span>
	</div>
</div>

<div class="col-8 offset-2 pt-5">
	<div class="card box-solid p-3 bg-white rounded">

		<div class="card-body">
			<div class="col-12">
				<?= $form->field($model, "language_from", ['enableAjaxValidation' => true])->widget(Select2::class, [
					'data' => Language::languages(),
					'theme' => Select2::THEME_KRAJEE,
					'options' => [
						'multiple' => false,
						'prompt' => "- select language -"
					],
				]) ?>

				<?= $form->field($model, "language_to", ['enableAjaxValidation' => true])->widget(Select2::class, [
					'data' => Language::languages(),
					'theme' => Select2::THEME_KRAJEE,
					'options' => [
						'multiple' => false,
						'prompt' => "- Select Language -"
					],
				]) ?>

				<?= $form->field($model, 'file')->widget(FileInput::class, [
					'options' => ['accept' => 'object/json'],
					'pluginOptions' => [
						'showPreview' => false,
						'showCaption' => true,
						'showRemove' => false,
						'showUpload' => false
					]
				]); ?>
			</div>

		</div>

		<hr/>
		
		<div class="card-text">
			<?= Html::submitButton('Generate', ['class' => 'btn btn-success float-right btn-upload']) ?>
		</div>
	</div>
</div>
<?php ActiveForm::end() ?>