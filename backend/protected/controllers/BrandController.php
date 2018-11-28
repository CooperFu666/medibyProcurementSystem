<?php

class BrandController extends LoginedController{
	
    public function actionIndex() {
        if($_POST){
            $pageSize = Yii::app()->request->getParam('length',10);
            $start = Yii::app()->request->getParam('start');
            $page = $start / $pageSize;
            $criteria = new CDbCriteria;
            $criteria->order = 'id DESC ' ;//排序条件
            $total = BrandModel::model()->count($criteria);
            $dataProvider = new CActiveDataProvider('BrandModel',array(
	            'criteria'=>$criteria,
	            'pagination'=>array(
	                'pageSize'=>$pageSize,
	                'currentPage'=>$page,
	            )) 
        	);
        	$datas = $dataProvider->getData();
        	$arts = array();

            foreach ($datas as $data) {
            	$btn = '<a href="' . $this->createUrl("brand/update/id/{$data->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>';
            	$btn .= '<a rel="' . $this->createUrl("brand/delete/id/{$data->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>';
                $images = '<img src="' . $data->images . '"  height="100"/>';
            	$arts[] = array(
                    $data->title,
                    $images,
                    strip_tags($data->info),
                	$btn
                );
            }
            die(json_encode(array('data' => $arts, 'recordsTotal' => $total, 'recordsFiltered' => $total)));
        }else{
        	$this->render('index');
        }
     	
    }

    public function actionAdd(){
    	$model = new BrandModel();
        //print_r($_POST);die;
        if(isset($_POST['BrandModel'])){
        	$model->attributes = $_POST['BrandModel'] ;
        	$model->title = $_POST['BrandModel']['title'];
            $model->info =$_POST['BrandModel']['info'];
        	$model->images = $_POST['BrandModel']['images'];
	        if($model->validate()){
	            $model->save();
	            $this->showSuccess('添加成功',$this->createUrl('brand/index'));
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
    	if (isset($_POST['BrandModel'])) {
            $model->attributes = $_POST['BrandModel'] ;
            $model->title = $_POST['BrandModel']['title'];
            $model->info =$_POST['BrandModel']['info'];
            $model->images = $_POST['BrandModel']['images'];
    		if ($model->save()) {
    			$this->showSuccess('更新成功', $this->createUrl('brand/index'));
    		}
    		$this->showError('更新失败', $this->createUrl('brand/index'));
    	}else{
    		$this->render("update", array('model' => $model));
    	}
    }
    
    public function loadModel($id){
    	$model = BrandModel::model()->findByPk($id);
    	if(!$model){
    		throw new CHttpException(404,'The requested page does not exist.');
    	}
    	return $model;
    }

   
    
    
    
}
