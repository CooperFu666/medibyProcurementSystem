<?php
/**
 * Created by PhpStorm.
 * User: druphliu@gmail.com
 * Date: 15-7-31
 * Time: 上午11:24
 */

class LoginedController extends AdminController{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::app()->user->isGuest) {
                $this->redirect(array('/site/login'));
            }
        }
        //权限
        $role_id = Yii::app()->user->role_id;
        $actionId = $action->getId();
        $controllerId = $action->getController()->getId();
        $access = RoleAccessModel::model()->with('access_module','access_action')
            ->count('access_action.action="'.$actionId.'" and access_module.module="'.$controllerId.'" and t.role_id='.$role_id);
       if(!$access){
           $isAjax = Yii::app()->request->isAjaxRequest;
           if($isAjax){
               die(Utils::jsonResult('-1','权限不足'));
           }else{
               $this->showError('权限不足');
           }

       }

        return true;
    }
} 