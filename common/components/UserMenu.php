<?php

/**
 * Created by PhpStorm.
 * User: druphliu@gamil.com
 * Date: 15-7-29
 * Time: 下午10:40
 */
class UserMenu extends CWidget
{
    public $user;
    public $order;
    public $notify;

    private function GetMenu()
    {
        $action = Yii::app()->controller->action->id;
        $controller = Yii::app()->controller->id;
        $user=array(
            array("controllers"=>'user',"action"=>"index","title"=>'个人中心','active'=>false),
            array("controllers"=>'user',"action"=>"info","title"=>'用户信息','active'=>false),
            array("controllers"=>'user',"action"=>"collection","title"=>'我的收藏','active'=>false),
            array("controllers"=>'user',"action"=>"footprint","title"=>'我的足迹','active'=>false),
            array("controllers"=>'user',"action"=>"address","title"=>'收货地址','active'=>false),
            array("controllers"=>'user',"action"=>"coupon","title"=>'我的优惠券','active'=>false),
            array("controllers"=>'user',"action"=>"auth","title"=>'资质认证','active'=>false),
            array("controllers"=>'user',"action"=>"invoice","title"=>'我的发票','active'=>false),
            array("controllers"=>'user',"action"=>"contact","title"=>'联系客服','active'=>false),
        );
        $order = array(
            array("controllers"=>'order',"action"=>"index","title"=>'我的订单','active'=>false),
        );
        $notify = array(
            array("controllers"=>'notify',"action"=>"list","title"=>'系统消息','active'=>false),
        );

        foreach ($user as $key=>$obj){
            if($obj['controllers']==$controller && $obj['action']==$action)$user[$key]['active']=true;
        }
        foreach ($order as $key=>$obj){
            if($obj['controllers']==$controller && $obj['action']==$action)$order[$key]['active']=true;
        }
        foreach ($notify as $key=>$obj){
            if($obj['controllers']==$controller && $obj['action']==$action)$notify[$key]['active']=true;
        }

        $this->user = $user;
        $this->order = $order;
        $this->notify = $notify;


    }


    public function run()
    {
        $this->GetMenu();
        $this->render('usermenu');
    }
} 