<?php
/**
 * Created by JetBrains PhpStorm.
 * User: home
 * Date: 15-8-1
 * Time: 下午4:34
 * To change this template use File | Settings | File Templates.
 */

class NavController extends LoginedController
{
    public function actionIndex()
    {
        if($_POST){
            $product=array();
            $pageSize=Yii::app()->request->getParam('length',10);
            $start=Yii::app()->request->getParam('start');
            $page=$start / $pageSize;
            
            $criteria=new CDbCriteria;
            $criteria->order="t.id desc";
            $countCriteria = $criteria;
            
            $dataProvider=new CActiveDataProvider('RoleNavModel',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$pageSize,
                    'currentPage'=>$page,
                ),
            ));
            
            $products=$dataProvider->getData();
            foreach ($products as $p) {
                $product[] = array(
                    $p->name,
                    $p->icons,
                    $p->is_effect?"是":"否",
                    $p->sort,
                	'<a href="' . $this->createUrl("nav/groupIndex/id/{$p->id}") . '" class="btn btn-xs green default">子菜单</a>'.
                    '<a rel="' . $this->createUrl("nav/delete/id/{$p->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>'.
                    '<a href="' . $this->createUrl("nav/update/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>'
                );
            }
            $recordsFiltered = $total = (int)RoleNavModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        }else{
            $this->render('index');
        }
    }
    
    public function actionAdd()
    {
        $model = new RoleNavModel();
        if(isset($_POST['RoleNavModel'])){
        	$nav = RoleNavModel::model()->find('name=:name',array(':name'=>$_POST['RoleNavModel']['name']));
        	if($nav){
        		$this->showError("菜单名称已存在");
        	}
        	
            $model->attributes = $_POST['RoleNavModel'];
            if($model->save()){
                $this->redirect(array("nav/index"));
            }
        }
        $this->render('add',array('model'=>$model));
    }
    
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if(isset($_POST['RoleNavModel'])){
            $model->attributes=$_POST['RoleNavModel'];
            if($model->save()){
                $this->redirect(array("nav/index"));
            }
        }
        $this->render("update",array('model'=>$model));
    }
    
    public function actionDelete($id)
    {
        if($this->loadModel($id)->delete()){
        	RoleNavGroupModel::model()->deleteAll('nav_id=:nav_id',array(':nav_id'=>$id));
        	$this->showJsonResult(1);
        }else{
        	$this->showJsonResult(0);
        }
    }
    
    public function loadModel($id)
    {
        $model=RoleNavModel::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

    //子菜单管理
    public function actionGroupIndex($id)
    {
    	if($_POST){
    		$product=array();
    		$pageSize=Yii::app()->request->getParam('length',10);
    		$start=Yii::app()->request->getParam('start');
    		$page=$start / $pageSize;
    
    		$criteria=new CDbCriteria;
    		$criteria->addColumnCondition(array('nav_id' => $id));
    		$criteria->order="t.id desc";
    		$countCriteria = $criteria;
    
    		$dataProvider=new CActiveDataProvider('RoleNavGroupModel',array(
    			'criteria'=>$criteria,
    			'pagination'=>array(
    				'pageSize'=>$pageSize,
    				'currentPage'=>$page,
    			),
    		));
    
    		$products=$dataProvider->getData();
    		foreach ($products as $p) {
    			$product[] = array(
    				$p->name,
    				$p->icon,
    				$p->is_effect ? "是" : "否",
    				$p->sort,
    				'<a rel="' . $this->createUrl("nav/deleteGroup/id/{$p->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>'.
    				'<a href="' . $this->createUrl("nav/updateGroup/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>'
    			);
    		}
    		$recordsFiltered = $total = (int)RoleNavGroupModel::model()->count($countCriteria);
    		echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
    	}else{
    		$this->render('groupIndex', array('id' => $id));
    	}
    }

    public function actionAddGroup($id)
    {
    	$model = new RoleNavGroupModel();
    	if(isset($_POST['RoleNavGroupModel'])){
    		$group = RoleNavGroupModel::model()->find('nav_id=:nid and name=:name',
    				array(':nid'=>$id,':name'=>$_POST['RoleNavGroupModel']['name']));
    		
    		if($group){
    			$this->showError("菜单名称已存在");
    		}
    		
    		$model->attributes = $_POST['RoleNavGroupModel'];
    		if($model->save()){
    			$this->redirect(array("nav/groupIndex/id/".$id));
    		}
    	}
    	$this->render('groupAdd',array('model'=>$model, 'id'=>$id));
    }
    
    public function actionDeleteGroup($id)
    {
    	if($this->loadGroupModel($id)->delete()){
	    	$this->showJsonResult(1);
    	}else{
    		$this->showJsonResult(0);
    	}
    }
    
    public function actionUpdateGroup($id)
    {
    	$model = $this->loadGroupModel($id);
    	if(isset($_POST['RoleNavGroupModel'])){
    		$model->attributes=$_POST['RoleNavGroupModel'];
    		if($model->save()){
    			$this->redirect(array("nav/groupIndex/id/".$model->nav_id));
    		}
    	}
    	$this->render("groupUpdate",array('model'=>$model, 'id'=>$model->nav_id));
    }
    
    public function loadGroupModel($id)
    {
    	$model = RoleNavGroupModel::model()->findByPk($id);
    	if($model === null){
    		throw new CHttpException(404,'The requested page does not exist.');
    	}
    	return $model;
    }
}