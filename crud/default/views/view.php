<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('编辑') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('删除') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('你确定要删除此条信息?') ?>,
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
    if (($tableSchema = $generator->getTableSchema()) === false) {
        foreach ($generator->getColumnNames() as $name) {
            echo "       '" . $name . "',\n";
        }
    } else {
        foreach ($generator->getTableSchema()->columns as $column) {
            $format = $generator->generateColumnFormat($column);
            if (is_array($column->enumValues) && !empty($column->enumValues)) { ?>
        [
        'attribute'=>'<?= $column->name ?>',
        'value'=>$model->options['<?= $column->name ?>'][$model-><?= $column->name ?>]
        ],
            <?php } elseif (preg_match('/^(img|image)/i', $column->name)) { ?>
        [
        'attribute'=>'<?= $column->name ?>',
        'format' =>'html',
        'value'=>Html::img(
                Yii::$app->glide->createSignedUrl([
                'glide/index',
                'path' => $model-><?= $column->name ?>,
                //'w' => 100
                ], true),
                ['class' => 'article-thumb img-rounded pull-left']
                )
        ],
            <?php } elseif (in_array($column->name,['created_at','updated_at'])) { ?>
                <?php  echo "       '" .$column->name .":datetime',\n" ?>
             <?php } else {
                        echo "       '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                    }
        }
    }
    ?>],
    ]) ?>

</div>
