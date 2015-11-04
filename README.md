# zc-gii
zc-gii
##使用说明
###安装方法
--安装

--配置
--使用说明
1. 通过gii生成model，模板选择zc-gii；
2. 修改生成的model
3. 将getOptions()方法中要修改的字段改成对应的值；
4. 注释掉getToolbars()方法中不需要使用的操作按钮；
5. 要订制Toolbar工具栏请按getToolbars()方法中的注
释加在方法返回值中，同时在/js/文件夹下填写与控制器同名的js文件，并写上对应的js操作方法；
--注意事项
*数据表id字段,设置为主键自增，不要设置为必填；
*checkbox、radio、下拉列表字段在建立数据表字段时统一使用enum形式；
如需set形式在生成完代码之后修改数据库字段属性，字段名称分别以_c,_r,_d结束，
代表建立checkbox、radio、下拉列表；
*如需要建立上传图片，直接将字段名称定为img或者image，程序会自动生成上传文件的按钮；
*日期以date形式或者以date、datetime结束，会自动生成日历选择框

Power By David Fang


