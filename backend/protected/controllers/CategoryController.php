<?php
/**
 * Created by JetBrains PhpStorm.
 * User: home
 * Date: 15-8-1
 * Time: 下午4:34
 * To change this template use File | Settings | File Templates.
 */

class CategoryController extends LoginedController
{
    //分类管理
    public function actionIndex()
    {
        if($_POST){
            $product=array();
            $pageSize=Yii::app()->request->getParam('length',10);
            $start=Yii::app()->request->getParam('start');
            $page=$start / $pageSize;
            
            $criteria=new CDbCriteria;
            $criteria->addColumnCondition(array('pid' => 0));
            $criteria->order="t.sort asc";
            $countCriteria = $criteria;

            $dataProvider=new CActiveDataProvider('CategoryModel',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$pageSize,
                    'currentPage'=>$page,
                ),
            ));
            
            $products=$dataProvider->getData();
            foreach ($products as $p) {
                $product[] = array(
                    $p->title,
                    $p->icon,
                    $p->sort,
                	'<a href="' . $this->createUrl("category/groupIndex/id/{$p->id}") . '" class="btn btn-xs green default">子分类</a>'.
                    '<a rel="' . $this->createUrl("category/delete/id/{$p->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>'.
                    '<a href="' . $this->createUrl("goods/index/category/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">查看分类下产品</i></a>'.
                    '<a href="' . $this->createUrl("category/update/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>'
                );
            }
            $recordsFiltered = $total = (int)CategoryModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        }else{
            $this->render('index');
        }
    }
    //分类添加
    public function actionAdd()
    {
        $model = new CategoryModel();
        $model->pid = 0;
        if(isset($_POST['CategoryModel'])){
            $nav = CategoryModel::model()->find('pid=:pid and title=:title',
                array(':pid'=>0,':title'=>$_POST['CategoryModel']['title']));
        	if($nav){
        		$this->showError("分类名称已存在");
        	}
            $model->attributes = $_POST['CategoryModel'];
            if($model->save()){
                $this->redirect(array("category/index"));
            }
        }
        $this->render('add',array('model'=>$model));
    }
    //分类修改
    public function actionUpdate($id)
    {

        $model = $this->loadModel($id);
        if(isset($_POST['CategoryModel'])){
            $model->attributes=$_POST['CategoryModel'];
            $model->pid =0;
            if($model->save()){
                $this->redirect(array("category/index"));
            }
        }
        $this->render("update",array('model'=>$model));
    }
    //删除
    public function actionDelete($id)
    {
        if(CategoryModel::deleteCategory($id)){
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        }
    }
    //子分类管理
    public function actionGroupIndex($id)
    {
    	if($_POST){
    		$product=array();
    		$pageSize=Yii::app()->request->getParam('length',10);
    		$start=Yii::app()->request->getParam('start');
    		$page=$start / $pageSize;
    		$criteria=new CDbCriteria;
    		$criteria->addColumnCondition(array('pid' => $id));
            $criteria->order="t.sort asc";
    		$countCriteria = $criteria;
    		$dataProvider=new CActiveDataProvider('CategoryModel',array(
    			'criteria'=>$criteria,
    			'pagination'=>array(
    				'pageSize'=>$pageSize,
    				'currentPage'=>$page,
    			),
    		));
    
    		$products=$dataProvider->getData();

    		foreach ($products as $p) {
                if($p->type == 3){
                    $html = '<a rel="' . $this->createUrl("category/delete/id/{$p->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>'.
                        '<a href="' . $this->createUrl("goods/index/category/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">查看分类下产品</i></a>'.
                        '<a href="' . $this->createUrl("category/updategroup/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>';
                }else{
                    $html = '<a href="' . $this->createUrl("category/groupIndex/id/{$p->id}") . '" class="btn btn-xs green default">子分类</a>'.
                        '<a rel="' . $this->createUrl("category/delete/id/{$p->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>'.
                        '<a href="' . $this->createUrl("goods/index/category/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">查看分类下产品</i></a>'.
                        '<a href="' . $this->createUrl("category/updategroup/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>';
                }
    			$product[] = array(
                    $p->title,
                    $p->icon,
                    $p->sort,
                    $html
    			);
    		}
    		$recordsFiltered = $total = (int)CategoryModel::model()->count($countCriteria);
    		echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
    	}else{
            $model = $this->loadModel($id);
    		$this->render('groupIndex', array('id' => $id,"model"=>$model));
    	}
    }
    //子分类添加
    public function actionAddGroup($id)
    {
    	$model = new CategoryModel();
        $model->pid = $id;
    	if(isset($_POST['CategoryModel'])){
    		$group = CategoryModel::model()->find('pid=:pid and title=:title',
    				array(':pid'=>$id,':title'=>$_POST['CategoryModel']['title']));
    		
    		if($group){
    			$this->showError("分类名称已存在");
    		}
    		$model->attributes = $_POST['CategoryModel'];
            $parentModel = $this->loadModel($id);
            $model->type = $parentModel->type+1;
    		if($model->save()){
    			$this->redirect(array("category/groupIndex/id/".$id));
    		}
    	}
    	$this->render('groupAdd',array('model'=>$model, 'id'=>$id));
    }
    //子分类修改
    public function actionUpdateGroup($id)
    {
    	$model = $this->loadModel($id);
    	if(isset($_POST['CategoryModel'])){
    		$model->attributes=$_POST['CategoryModel'];
    		if($model->save()){
    			$this->redirect(array("category/groupIndex/id/".$model->pid));
    		}
    	}
    	$this->render("groupUpdate",array('model'=>$model, 'id'=>$model->pid));
    }

    public function loadModel($id)
    {
        $model=CategoryModel::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }
}