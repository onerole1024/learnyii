<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Mems;
use yii\web\Cookie;

class TestController extends Controller
{


    public function actionIndex()
    {
       // echo "test me";

        //request组件对请求的参数动作等获取
      /*  $request = \Yii::$app->request;
        var_dump($request->get());
        var_dump($request->post());
        var_dump( $request->get('name',''));
        if($request->isGet){
            echo 'get 请求';
        }
        echo $request->userAgent;*/


        //response组件对响应设置
        /* $response = \Yii::$app->response;
        $response->statusCode=500;*/


        //第一个参数是指明要渲染的页面路径和名称，第二个参数是要传入到视图文件的变量
        //yii的规则是到控制器为名称的文件夹中寻找名为index【可以任意起名】的文件
        //该控制器名为TestController，所以会去views下的test文件夹中寻找index.php

        //return $this->renderPartial('index',['name'=>'pipagg','age'=>18]);
        //render和renderPartial的区别就是前者会将视图内容嵌入到一个layout文件中，默认views/layouts/main.php
        //return $this->render('index',['name'=>'pipagg','age'=>18]);

        //创建自己的layout views/test/mylayout.php
   /*     $this->layout='mylayout';
        return $this->render('index',['name'=>'pipagg','age'=>18]);*/

        //actionIndex中加入创建一条用户记录
/*        $mem = new Mems();
        $mem->name = 'pipagg';
        var_dump($mem->save());*/

        //查询name为pipagg的记录
        //$mem = Mems::find('name=:n',array(':n'=>'pipagg'))->asArray()->one();
        //$mem = Mems::find()->where(['name' => 'pipagg'])->one();
        //更为灵活的方式
        //$mem = Mems::findBySql('select * from '.Mems::tableName().' where name=:n',array(':n'=>'pipagg'))->asArray()->one();
        //var_dump($mem);
/*        $mem = Mems::find()->where(['name' => 'pipagg'])->one();
        $mem->name = 'pipaggcn';
        var_dump($mem->update());*/
        //查询出来再进行删除
    /*    $mem = Mems::find()->where(['name' => 'sky'])->one()->delete();
        var_dump($mem);*/

/*       $mem = new Mems();
        $mem->name = 'sky';
        var_dump($mem->save());*/
        //删除uid大于1的所有记录
      /*  var_dump(Mems::deleteAll('uid >:u',array(':u'=>1)));*/

        //where 条件可以很复杂
/*        $mems = Mems::find()->where(['like', 'name', 'pipagg'])->asArray()->all();
        var_dump($mems);*/


        //session操作
    /*         $session = \Yii::$app->session;
        $isactive = $session->getIsActive();
        var_dump($isactive);
        $session->open();
        $isactive = $session->getIsActive();
        var_dump($isactive);
        var_dump($session->get('name'));//NULL
        $session->set('name','pipagg');  //设置键为name 值为pipagg
        var_dump($session->get('name')); //获取键为name的值 */

        //cookie操作
        $cookies= \Yii::$app->response->cookies;
        $cookies->add(new Cookie(['name'=>'mem','value'=>'pipagg']));

    }


}
