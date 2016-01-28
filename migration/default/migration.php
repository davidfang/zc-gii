<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator zc\gii\migration\Generator */
/* @var $migrationName string migration name */

echo "<?php\n";
?>

use yii\db\Schema;

class <?= $migrationName ?> extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
<?php foreach ($tables as $table): ?>
        
        $this->createTable('<?= $table['name'] ?>', [
<?php foreach ($table['columns'] as $column => $definition): ?>
            <?= "'$column' => '$definition'"?>,
<?php endforeach;?>
<?php if(isset($table['primary'])): ?>
            <?= "'{$table['primary']}'" ?>,
<?php endif; ?>
<?php foreach ($table['relations'] as $key => $definition): ?>
            <?= "'$definition'" ?>,
<?php endforeach;?>
        ], $tableOptions);
<?php endforeach;?>
<?php
if(!empty($table['data'])) {
    $datas = [];
    foreach ($table['data'] as $data) {
        $datas[] = "\n            ('" . implode("','", $data) . "')";
    }
?>
    $sql = "INSERT INTO <?= $table['name'] ?> (<?="`" . implode("`,`", array_keys($table['columns'])) . "`"?>) VALUES
<?= implode(',',$datas);?>;";
        $this->execute($sql);
<?php } ?>
    }

    public function down()
    {
<?php foreach (array_reverse($tables) as $table): ?>
        $this->dropTable('<?= $table['name'] ?>');
<?php endforeach;?>
    }
}
