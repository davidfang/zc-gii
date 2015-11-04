<?php
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
    /**
    * 多选项配置
    * @return array
    */
    public function getOptions(){
    <?php
    $options = [];
    foreach ($tableSchema->columns as $column) {
        if (is_array($column->enumValues) && count($column->enumValues) > 0) {
           foreach ($column->enumValues as $enumValue) {
                $options[$column->name][$enumValue] = Inflector::humanize($enumValue);
            }
       }
   }
    echo '      return '.VarDumper::export($options) .";\n";
    ?>
    }
    /**
    * toolbars工具栏按钮设定
    * 字段为枚举类型时存在
    * 默认为复选项的值，
    * jsfunction默认值为changeStatus
    * @return array
    * 返回值举例：
    * [
        ['name'=>'忘却',//名称
        'jsfunction'=>'ask',//js操作方法，默认为：changeStatus
        'field'=>'status_2',//操作字段名
        'field_value'=>'3'],//修改后的值
        ]
    */
    public function getToolbars(){
        $attributeLabels = $this->attributeLabels();
        $options = $this->options;
        return [
    <?php
    foreach ($tableSchema->columns as $column) {
        if (is_array($column->enumValues) && count($column->enumValues) > 0) {
            foreach ($column->enumValues as $enumValue) {
                echo "[
                'name'=>".'$options["'.$column->name.'"]["'.$enumValue.'"]'.",
                'jsfunction'=>'changeStatus',
                'field'=>'{$column->name}',
                'field_value'=>'{$enumValue}'
                ],";
            }
        }
    }
    ?>
        ];
    }

}
