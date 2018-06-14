<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '比分设置';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
示例:<br/>
    俄罗斯VS沙特<BR />
    半场<BR />
    5:2<BR />
</p>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'setting')->textarea(['placeholder' => '', 'rows' => 5]) ?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
