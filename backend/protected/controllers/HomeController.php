<?php

class HomeController extends LoginedController{
	
    public function actionIndex() {
        if($_POST){
            $pageSize = Yii::app()->request->getParam('length',10);
            $start = Yii::app()->request->getParam('start');
            $page = $start / $pageSize;
            $criteria = new CDbCriteria;
            $criteria->order = 'sort ASC ' ;//排序条件
            $total = HomeModel::model()->count($criteria);
            $dataProvider = new CActiveDataProvider('HomeModel',array(
	            'criteria'=>$criteria,
	            'pagination'=>array(
	                'pageSize'=>$pageSize,
	                'currentPage'=>$page,
	            )) 
        	);
        	$datas = $dataProvider->getData();
            $home = array();

            foreach ($datas as $data) {
            	$btn = '<a href="' . $this->createUrl("home/update/id/{$data->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>';
            	$btn .= '<a rel="' . $this->createUrl("home/delete/id/{$data->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>';
                $home[] = array(
                    $data->title,
                    $data->sort,
                	$btn
                );
            }
            die(json_encode(array('data' => $home, 'recordsTotal' => $total, 'recordsFiltered' => $total)));
        }else{
        	$this->render('index');
        }
     	
    }

    public function actionAdd(){
    	$model = new HomeModel();
        if(isset($_POST['HomeModel'])){
        	$model->attributes = $_POST['HomeModel'] ;
	        if($model->validate()){
	            $model->save();
	            $this->showSuccess('添加成功',$this->createUrl('home/index'));
	        }else{
                $this->showError("操作失败");
            }
        }else{
          $this->render('add', array('model'=>$model));
        }
    }
    
    
    public function actionDelete($id){
        $model=$this->loadModel($id);
        if($model->delete()){
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        } 
    }
    
    public function actionUpdate($id){
    	$model = $this->loadModel($id);
    	if (isset($_POST['HomeModel'])) {
    		$model->attributes = $_POST['HomeModel'];
    		if ($model->save()) {
    			$this->showSuccess('更新成功', $this->createUrl('home/index'));
    		}else{
                $this->showError("操作失败");
            }
    	}else{
    		$this->render("update", array('model' => $model));
    	}
    }
    
    public function loadModel($id){
    	$model = HomeModel::model()->findByPk($id);
    	if(!$model){
    		throw new CHttpException(404,'The requested page does not exist.');
    	}
    	return $model;
    }

   
    
    
    
}
