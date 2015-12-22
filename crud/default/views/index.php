<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$model = new $generator->modelClass ();
$indexLists = $model->indexLists;
echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('添加 {modelClass}', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?>, ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>
    <?= '<?php echo $this->render("_toolbar", ["model" => $dataProvider]); ?>'?>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'id' => 'grid',
        //重新定义分页样式
        'layout' => '{items}{pager}',
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn', 'options' => ['id' => 'grid']],
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if(in_array($name,$indexLists)) {
            if (++$count < 6) {
                echo "            '" . $name . "',\n";
            } else {
                echo "            // '" . $name . "',\n";
            }
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        if(in_array($column->name,$indexLists)) {
            $format = $generator->generateColumnFormat($column);
            if (++$count < 6) {
                $tmp_str = '';
                //echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
            } else {
                $tmp_str = '//';
                //echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
            }

            if (in_array($column->type, ['datetime', 'timestamp', 'time', 'date'])) {
                ?>[
                'attribute' => '<?= $column->name ?>',
                'format' => 'html',
                'value' => '<?= $column->name ?>',
                'filter' => kartik\widgets\DatePicker::widget(
                    ['model' => $searchModel,
                       'name' => Html::getInputName($searchModel, '<?= $column->name ?>'),
                       'value' => $searchModel-><?= $column->name ?>,
                       'pluginOptions' => ['format' => 'yyyy-mm-dd',
                           'todayHighlight' => true,]
                    ]),
                'options' => ['style' => 'width:200px;'],

            ],
            <?php
            } elseif (is_array($column->enumValues) && !empty($column->enumValues)) {
                ?>
 [
                'attribute' => '<?= $column->name ?>',
                'format' => 'html',
                'value' => function ($model) {
                    $class = 'label-success';
                    $class = 'label-warning';
                    $class = 'label-danger';
                    $class = 'label-info';
                    return '<span class="label ' . $class . '">' . ($model->options['<?= $column->name ?>'][$model-><?= $column->name ?>]) . '</span>';
                },
                'options' => ['style' => 'width:90px;'],
                'filter' => $searchModel->options['<?= $column->name ?>'],
            ],
            <?php
            } elseif (preg_match('/^(img|image)/i', $column->name)) { ?>
            [
                'attribute' => '<?= $column->name ?>',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img(Yii::$app->homeUrl . $model-><?= $column->name ?>, ['class' => 'img-rounded', 'width' => '120px']);
                },
                'filter' => false
            ],
            <?php } else {
                echo "           $tmp_str'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
            }
        }
    }
}
?>
[
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'buttons' => [
<?php $jsfuncton = false;
                $model =  new $generator->modelClass;
                $options = $model->options;
                $buttons = '';
                foreach ($model->toolbars as $toolbar) {
                    $button = $toolbar['field'].'_'.$toolbar['field_value'];
                    $buttons .='{'.$button .'} ';
                if($toolbar['jsfunction']!='changeStatus'){
                    $jsfuncton = true;
                } ?>
                    '<?=$button?>' => function ($url, $model, $key) {
                        return $model-><?=$toolbar['field']?> == <?=$toolbar['field_value']?> ? '' : Html::button('<?=$toolbar['name']?>', ['class' => 'btn btn-primary btn-sm', 'onclick' => "javascript:<?=$toolbar['jsfunction']?>('<?=$toolbar['field'] ?>','<?=$toolbar['field_value'] ?>','{$model-><?=implode(',',$model->getTableSchema()->primaryKey);?>}');"]);
                    },
<?php } ?>
               ],
                'template' => '<?=$buttons?> {view} {update}{delete} ',
            ]
        ],
    ]);
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ])
<?php endif; ?>
    <?php if($jsfuncton){
        echo '   $this->registerJsFile("/js/'.$generator->controllerID.'.js");';
    }  ?>?>
</div>