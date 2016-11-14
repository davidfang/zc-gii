# zc-gii
zc-gii
##使用说明
###安装方法
###安装
```
php composer.phar require  --prefer-dist   "zc/gii":"~2.0.0"

or add

"zc/gii": "dev-master"

```
###配置
####V1.0配置
打开配置文件，修改gii设置如下：
````php
$config['bootstrap'][] = 'gii';
    //$config['modules']['gii'] = 'yii\gii\Module';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators'=>[
            'controller' => [
                'class' => 'zc\gii\controller\Generator',
                'templates' => [
                    'zc-gii' => '@vendor/zc/gii/controller/default',
                ]
            ],
            'crud' => [
                //'class' => 'yii\gii\generators\crud\Generator',
                'class' => 'zc\gii\crud\Generator',
                'templates' => [
                    'zc-gii' => '@vendor/zc/gii/crud/default',
                ]
            ],
            'module' => [
                'class' => 'zc\gii\module\Generator',
                'templates' => [
                    'zc-gii' => '@vendor/zc/gii/module/default',
                ]
            ],
            'form' => [
                'class' => 'zc\gii\form\Generator',
                'templates' => [
                    'zc-gii' => '@vendor/zc/gii/form/default',
                ]
            ],
            'model' => [
                'class' => 'zc\gii\model\Generator',
                'templates' => [
                    'zc-gii' => '@vendor/zc/gii/model/default',
                ]
            ],
            'extension' => [
                'class' => 'zc\gii\extension\Generator',
                'templates' => [
                    'zc-gii' => '@vendor/zc/gii/extension/default',
                ]
            ],
            'migrate' => [
                'class' => 'zc\gii\migration\Generator',
                'templates' => [
                    'zc-gii' => '@vendor/zc/gii/migration/default',
                ]
            ],
        ]

    ];
````
####V2.0配置

###使用说明
1. 通过gii生成model，模板选择zc-gii；
2. 修改生成的model
3. 将getOptions()方法中要修改的字段改成对应的值；
4. 注释掉getToolbars()方法中不需要使用的操作按钮；
5. 要订制Toolbar工具栏请按getToolbars()方法中的注
释加在方法返回值中，同时在/js/文件夹下填写与控制器
同名的js文件，并写上对应的js操作方法；
6. 要上传图片，自己在web下建立upload文件夹；

####注意事项

* 数据表id字段,设置为主键自增，不要设置为必填；
* checkbox、radio、下拉列表字段在建立数据表字段时统一使用enum形式；
如需set形式在生成完代码之后修改数据库字段属性，字段名称分别以_c,_r,_d结束，
代表建立checkbox、radio、下拉列表；
* 如需要建立上传图片或文件，直接将字段名称定为img或者image或者file开头，程序会自动生成上传文件的按钮,修改model中rules规则，将image改为非必须，加上如下规则；
```php
['primaryImage', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024*1024],
```
* 日期以date形式或者以date、datetime结束，会自动生成日历选择框
* created_at和updated 创建和修改时间form表单默认不会显示，如要显示自行去掉注释


####V2.0 for starer kit
* 上传图片时需要建立图片字段，同时建立base_url字段，供上传使用
* 将model中的上传字段限制修改
Power By David Fang


