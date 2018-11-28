<?php
/**
 * Created by JetBrains PhpStorm.
 * User: home
 * Date: 15-8-11
 * Time: 下午6:40
 * To change this template use File | Settings | File Templates.
 */

class SetController extends LoginedController
{
    public $layout='//layouts/column2';
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','view',),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update','index'),
                'users'=>array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin','delete',),
                'users'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public function actionIndex(){
        $models=ConfModel::model()->findAll('group_id=:group_id',array(':group_id'=>2));
        $base=ConfModel::model()->findAll('group_id=:group_id',array(':group_id'=>1));
        $share=ConfModel::model()->findAll('group_id=:group_id',array(':group_id'=>3));
        $this->render('index',array('model'=>$models,'base'=>$base,'share'=>$share));
    }


    public function actionUpdate()
    {
        if ($_POST['ConfModel']) {
            $paras = $_POST['ConfModel'];
            foreach ($paras as $k => $p) {
                ConfModel::model()->updateAll(array('value' => $p), 'name=:name', array(':name' => $k));
            }
            if (isset($_POST['ProjectAttachmentModel']) && $_POST['ProjectAttachmentModel']) {
                $new_path=$_POST['ProjectAttachmentModel']['path'];
                $exitSrcs = ConfModel::model()->find('name=:name', array(':name' => ConfModel::CONFIG_CONTRACT_EXAMPLE));
                if ($new_path) {
                    //删除原来文件
                    @unlink($exitSrcs->value);
                    $exitSrcs->value = $_POST['ProjectAttachmentModel']['path'];
                    $exitSrcs->save();
                }

            }
            $this->showSuccess('修改成功', $this->createUrl('set/index'));
        }
        $this->render('index');

    }

}