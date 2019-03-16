
> 使用composer安装yii
```
//composer的安装和介绍

composer global require "fxp/composer-asset-plugin:^1.2.0"

//到web根目录创建名为learnyii项目
cd  /Users/free/www/
composer create-project yiisoft/yii2-app-basic learnyii 2.0.16
```
> 我的工作习惯会配置一个虚机以www为根目录，再配置一个虚机以当前开发中的项目为根目录，配置如下：

```
// /usr/local/etc/nginx/nginx.conf文件中最后一行 } 大括号前增加下面这句

include servers/*;

// vi /usr/local/etc/nginx/servers/dtest
// 我的www根目录
server {
    listen 80;
    server_name  www.dtest.com;
    root /Users/free/www/;

    access_log /usr/local/var/logs/nginx/default.access.log;
    location / {
    if (!-e $request_filename){
        rewrite ^/(.*) /index.php last;
    }
    index index.html index.htm index.php;
    autoindex on;
    include /usr/local/etc/nginx/conf.d/php-fpm;
    }
}
```

```

// vi /usr/local/etc/nginx/servers/dtemp
//当前学习yii的项目【learnyii】
server {
    listen 80;
    server_name  www.dtemp.com;
    root /Users/free/www/learnyii/web/;

    access_log /usr/local/var/logs/nginx/dtemp.access.log;
    location / {
        if (!-e $request_filename){
            rewrite ^/(.*) /index.php last;
        }
        index index.html index.htm index.php;
        autoindex on;
        include /usr/local/etc/nginx/conf.d/php-fpm;
    }
}



```
> 配置好虚机，nginx重新加载配置

```
sudo nginx -s reload
```
> yii 环境要求检查，是否满足条件查看

```
//从浏览器中可以看到支持情况
http://www.dtest.com/learnyii/requirements.php
//项目查看
http://www.dtemp.com/
```
> 应用结构【我创建的项目名为learnyii】：

```
learnyii/                  应用根目录
    composer.json       Composer 配置文件, 描述包信息
    config/             包含应用配置及其它配置
        console.php     控制台应用配置信息
        web.php         Web 应用配置信息
    commands/           包含控制台命令类
    controllers/        包含控制器类
    models/             包含模型类
    runtime/            包含 Yii 在运行时生成的文件，例如日志和缓存文件
    vendor/             包含已经安装的 Composer 包，包括 Yii 框架自身
    views/              包含视图文件
    web/                Web 应用根目录，包含 Web 入口文件
        assets/         包含 Yii 发布的资源文件（javascript 和 css）
        index.php       应用入口文件  //所以nginx虚机web根目录设置为/Users/free/www/learnyii/web/
    yii                 Yii 控制台命令执行脚本
```
> Yii 实现了模型-视图-控制器 (MVC)设计模式

![请求框架处理过程](https://upload-images.jianshu.io/upload_images/13253304-e399ea79d4c7b793.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)


---
请求到learnyii/web/index.php这个入口文件，该文件会加载框架应用主体，加载框架封装好的方法和类，根据请求路径，会有路由模块进行解析交给对应的控制器下的action方法进行处理，通过model类可以方便地操作数据库，拿到数据之后交给视图去显示这些数据，在视图中调用小部件【系统封装的方法和类】可以省去自己编写某些样式和结构的时间。

> 命名空间

新建项目learnyii，site控制器index action作为默认控制器和处理方法，可以看到命名空间为app\controllers，app对应learnyii文件夹代表开发主目录，controllers对应controllers文件夹，命名空间和文件夹的这样清楚的对应关系对自动加载类十分方便。拿到命名空间就可以确定类所在文件夹和文件。规则：控制器大写字母开头，Controller结尾，继承Controller，处理方法以action开头，方法名大写。如actionIndex为Index处理方法
![命名空间.png](https://upload-images.jianshu.io/upload_images/13253304-44ef0de3fafab89d.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)
> 创建自己的控制器，在controllers文件夹中创建TestController.php，访问http://www.dtemp.com/?r=test/index

```
<?php

namespace app\controllers;

use yii\web\Controller;

class TestController extends Controller
{

    public function actionIndex()
    {
       echo "test me";
    }

}
```
> [处理请求之获取请求参数](https://www.yiichina.com/doc/api/2.0/yii-web-request)

```
//\Yii这个全局类的静态变量$app包含请求组件request
$request = \Yii::$app->request;
var_dump($request->get()); //打印所有get请求参数
var_dump($request->post());//打印所有post请求参数
var_dump($request->get('name',''));//如果没有传入name参数则为空串
//request组件的isGet变量可以判断请求动作类型
if($request->isGet{
   echo 'get 请求';
}
//用户ip
echo $request->userIP;
//该组件还可以获取用户userAgent等等,很强大
```
> [返回响应数据之设置响应](https://www.yiichina.com/doc/api/2.0/yii-web-response#getHeaders()-detail)

```
php 原生有些方法可以设置响应头，在框架项目中yii对返回响应做了封装，我们可以使用response对响应做一些设置。
//响应组件
$response = \Yii::$app->response;
//设置http响应码
$response->statusCode=500;
```
> 使用视图显示数据

我们使用 echo  "test me"; 可以在页面显示这句话，实际页面有丰富的文字、图片等，我们不可能全部放在控制器中利用echo 打印出来。yii为我们准备了视图。

```
//第一个参数是指明要渲染的页面路径和名称，第二个参数是要传入到视图文件的变量
//yii的规则是到控制器为名称的文件夹中寻找名为index【可以任意起名】的文件
//该控制器名为TestController，所以会去views下的test文件夹中寻找index.php

return $this->renderPartial('index',['name'=>'pipagg','age'=>18]);

views/test/index.php 内容：

我的名字 <?= $name ;?><br/>
年龄<?= $age ;?>

```
render和renderPartial的区别就是前者会将视图内容嵌入到一个layout文件中，默认layout为views/layouts/main.php。我们很多页面都有相同的部分，比如底部备案信息，联系方式等，我们不需要每个页面都写一遍，只要写在一个文件中，然后在需要这部分内容的页面使用该layout即可。

```
return $this->render('index',['name'=>'pipagg','age'=>18]);
```

```
//创建自己的layout views/test/mylayout.php
$this->layout='mylayout';
return $this->render('index',['name'=>'pipagg','age'=>18]);
```
我们可以指定自己的layout，在views/layouts/创建mylayout.php 内容：

```
我是公共头部 <br/><br/>
<?= $content ?><br/><br/>
我是公共底部
```

> [操作数据库，增删改查](https://www.yiichina.com/doc/api/2.0/yii-db-activerecord)

```
cd ~/www/learnyii/config
vi db.php
//内容如下：
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=learnyii',
    'username' => 'freeuser',
    'password' => 'free99',
    'charset' => 'utf8',
];

//创建 learnyii 数据库，创建mems表
CREATE TABLE `mems` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `mobile` char(11) DEFAULT '' COMMENT '手机号',
  `email` varchar(60) DEFAULT '',
  `salt` char(6) DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户表'

//新建mems 表对应的 model，yii中使用model操作数据库，
<?php
namespace app\models;

class Mems extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'mems';
    }
}
//\yii\db\ActiveRecord这个类封装类操作数据库的方法
vi ~/www/learnyii/controllers/TestController.php
开头加 use app\models\Mems;
//actionIndex中加入创建一条用户记录
$mem = new Mems();
$mem->name = 'pipagg';
var_dump($mem->save());

//[查询name为pipagg的记录](https://www.yiichina.com/doc/api/2.0/yii-db-activerecord#find()-detail)
$mem = Mems::find('name=:n',array(':n'=>'pipagg'))->asArray()->one();
//$mem = Mems::find()->where(['name' => 'pipagg'])->one();

//更为灵活的方式
$mem = Mems::findBySql('select * from '.Mems::tableName().' where name=:n',array(':n'=>'pipagg'))->asArray()->one();
var_dump($mem);
//如果不使用asArray()，返回的则为ActiveRecord的对象，
//也就可以使用该对象的所有方法，比如更新方法update()
$mem = Mems::find()->where(['name' => 'pipagg'])->one();
$mem->name = 'pipaggcn';
var_dump($mem->update());
//以上查询方法都使用了'name=:n',array(':n'=>'pipagg')这种结构，是为了避免sql注入，yii封装的方法会防止sql注入
//sql注入的简单形式：
// select * from mems where uid=1 or 1=1  用户传入的uid为 '1 or 1=1'
//查询出来再进行删除
$mem = Mems::find()->where(['name' => 'sky'])->one()->delete();
var_dump($mem);
//删除uid大于1的所有记录
var_dump(Mems::deleteAll('uid >:u',array(':u'=>1)));
```
[文档解释](https://www.yiichina.com/doc/api/2.0/yii-db-activequeryinterface#asArray()-detail)：
find()会创建用来查询的yii\db\ActiveQueryInterface 实例。
并返回 yii\db\ActiveQueryInterface实例，该类包含asArray()和one()方法，调用asArray()返回$this,所以可以再调用one()
```
asArray() 设置是否按数组而不是活动记录返回查询结果。
return	$this;查询对象本身
one() 查询结果的单行,取决于asArray() 的设置，
查询结果可以是数组或活动记录对象,如果查询没有结果将返回 null
```
[where 条件可以写的很复杂](https://www.yiichina.com/doc/api/2.0/yii-db-queryinterface#where()-detail/)

```
$mems = Mems::find()->where(['like', 'name', 'pipagg'])->asArray()->all();
var_dump($mems);
```

> [session操作](https://www.yiichina.com/doc/api/2.0/yii-web-session)

[点击理解session和cookie](https://www.jianshu.com/p/9c2f4063c862)

```
$session = \Yii::$app->session;
$isactive = $session->getIsActive();
var_dump($isactive);
$session->open();
$isactive = $session->getIsActive();
var_dump($isactive);
var_dump($session->get('name'));  //NULL
$session->set('name','pipagg');  //设置键为name 值为pipagg
var_dump($session->get('name')); //获取键为name的值
```
>  [cookie操作](https://www.yiichina.com/doc/api/2.0/yii-web-response#$cookies-detail)response类中包含cookie操作方法，该方法接收[cookie类](https://www.yiichina.com/doc/api/2.0/yii-web-cookie)进行操作

```
//cookie操作
$cookies= \Yii::$app->response->cookies;
$cookies->add(new Cookie(['name'=>'mem','value'=>'pipagg']));
```

> 总结：

yii框架目录结构清晰，学习之前可以让自己先感觉下什么是MVC，为什么要使用这种结构，从php基础到简易MVC，跟着教程了解下，或者直接使用yii去感受MVC，再尝试自己构建一个MVC框架。yii的类封装层级不是很扁平，想要应用特别熟练，还需动手写项目，要了解框架源码会更需要耐心。[yii2的类文档](https://www.yiichina.com/doc/api/2.0)十分请求，在使用yii方法时要注意参数是哪个系统类还是普通数据类型，返回值是哪个系统类还是普通类型，能够级联操作的原因就是返回的是类对象，可以再次调用该对象的方法。本教程输入yii简单入门，程序地址：
https://github.com/onerole1024/learnyii.git