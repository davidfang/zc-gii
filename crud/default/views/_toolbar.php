<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator zc\gii\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="panel panel-default ads-position-toolbar">


    <?='<?'?>=Html::button('启动',['onclick'=>"javascript:changeStatus('status','1')"]) ?>
    <?='<?'?>=Html::button('暂停',['onclick'=>"javascript:changeStatus('status','-1')"]) ?>
    <?='<?'?>=Html::button('停止',['onclick'=>"javascript:changeStatus('status','-2')"]) ?>
    <?='<?'?>=Html::button('删除',['onclick'=>"javascript:changeStatus('status','0')"]) ?>


</div>
<script type="text/javascript" >
    <?='<?php'?> $this->beginBlock('toolbar') ?>
    var data = new Object();  //对象
    function changeStatus(f,v){
        data.f = f;
        data.v = v;
        data.keys = $('#grid').yiiGridView('getSelectedRows');
        console.log(data.keys);
        if (data.keys.length==0){
            alert("至少选择一项");
            return;
        }
        var url = '<?='<?'?>= Yii::$app->urlManager->createUrl('ads-postion/change-status-ajax') ?>';
        $.ajax({
            url: url,
            type:'get',//必须使用,不知道为什么
            dataType:'json',
            data:data,
            success:function(data){
                   alert(data.msg);
                console.log(data);
            }
        })
    }

    <?='<?php'?> $this->endBlock() ?>
</script>
<?='<?php'?> $this->registerJs($this->blocks['toolbar'], \yii\web\View::POS_END) ?>
