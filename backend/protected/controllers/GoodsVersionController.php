<?php

class GoodsVersionController extends LoginedController{
	
    public function actionIndex($id) {
        if($_POST){
            $pageSize = Yii::app()->request->getParam('length',10);
            $start = Yii::app()->request->getParam('start');
            $page = $start / $pageSize;
            $criteria = new CDbCriteria;
            $criteria->addCondition("goodsid=$id");
            $criteria->order = 'id ASC ' ;//排序条件
            $total = GoodsVersionModel::model()->count($criteria);
            $dataProvider = new CActiveDataProvider('GoodsVersionModel',array(
	            'criteria'=>$criteria,
	            'pagination'=>array(
	                'pageSize'=>$pageSize,
	                'currentPage'=>$page,
	            )) 
        	);
        	$datas = $dataProvider->getData();
        	$arts = array();
            foreach ($datas as $data) {
            	$btn = '<a href="' . $this->createUrl("goodsVersion/update/id/{$data->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>';
            	$btn .= '<a rel="' . $this->createUrl("goodsVersion/delete/id/{$data->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>';
                $arts[] = array(
                    $data->title,
                	$btn
                );
            }
            die(json_encode(array('data' => $arts, 'recordsTotal' => $total, 'recordsFiltered' => $total)));
        }
    }

    public function actionAdd($id){
    	$model = new GoodsVersionModel();
        if(isset($_POST['GoodsVersionModel'])){
        	$model->attributes = $_POST['GoodsVersionModel'] ;
        	$model->title = $_POST['GoodsVersionModel']['title'];
            $model->goodsid =$id;
	        if($model->validate()){
                $transaction=Yii::app()->db->beginTransaction();
                try{
                    $model->save();
                    $version_img = array_unique($_POST['version_img']);
                    foreach ($version_img as $obj){
                        $GoodsImagesModel = new GoodsImagesModel();
                        $GoodsImagesModel->goodsid=$id;
                        $GoodsImagesModel->goods_version_id=$model->id;
                        $GoodsImagesModel->images=$obj;
                        $GoodsImagesModel->save();
                    }
                    $attrList = GoodsAttrModel::model()->findAll('goodsid=:goodsid',array(':goodsid'=>$id));
                    if($attrList){
                        foreach ($attrList as $attr){
                            $GoodsBindModel = new GoodsBindModel();
                            $GoodsBindModel->goodsid=$id;
                            $GoodsBindModel->goods_version_id=$model->id;
                            $GoodsBindModel->goods_attr_id=$attr->id;
                            $GoodsBindModel->save();
                        }
                    }else{
                        $GoodsBindModel = new GoodsBindModel();
                        $GoodsBindModel->goodsid=$id;
                        $GoodsBindModel->goods_version_id=$model->id;
                        $GoodsBindModel->goods_attr_id=0;
                        $GoodsBindModel->save();
                    }
                    $transaction->commit();//提交事务会真正的执行数据库操作
                    $GoodsModel=GoodsModel::model()->findByPk($id);
                    if($GoodsModel->status==GoodsModel::STATUS_UNPUB){
                        $this->showSuccess('添加成功',$this->createUrl("goods/publish/id/".$id));
                    }else{
                        $this->showSuccess('添加成功',$this->createUrl("goods/publish/id/".$id));
                    }
                }catch (Exception $e) {
                    $transaction->rollback();//如果操作失败, 数据回滚
                    $this->showError("操作失败");
                }


	        }
       }else{
          $this->render('add', array('model'=>$model));
        }
    }

    public function actionUpdate($id){
        $model = GoodsVersionModel::model()->with("images")->findByPk($id);
        if (isset($_POST['GoodsVersionModel'])) {
            $model=$this->loadModel($id);
            $model->attributes = $_POST['GoodsVersionModel'] ;
            $model->title = $_POST['GoodsVersionModel']['title'];
            if($model->validate()){
                $transaction=Yii::app()->db->beginTransaction();
                try{
                    $model->save();
                    GoodsImagesModel::model()->deleteAll('goods_version_id=:goods_version_id',array(':goods_version_id'=>$id));
                    $version_img = array_unique($_POST['version_img']);
                    foreach ($version_img as $obj){
                        $GoodsImagesModel = new GoodsImagesModel();
                        $GoodsImagesModel->goodsid=$model->goodsid;
                        $GoodsImagesModel->goods_version_id=$model->id;
                        $GoodsImagesModel->images=$obj;
                        $GoodsImagesModel->save();
                    }
                    $transaction->commit();//提交事务会真正的执行数据库操作
                    $this->showSuccess('修改成功',$this->createUrl("goods/publish/id/".$model->goodsid));
                }catch (Exception $e) {
                    $transaction->rollback();//如果操作失败, 数据回滚
                    $this->showError("操作失败");
                }


            }
        }else{
            $this->render("update", array('model' => $model));
        }
    }
    //删除
    public function actionDelete($id){
        $model=$this->loadModel($id);
        $transaction=Yii::app()->db->beginTransaction();
        try{
            $model->delete();
            GoodsBindModel::model()->deleteAll('goods_version_id=:goods_version_id',array(':goods_version_id'=>$id));
            GoodsImagesModel::model()->deleteAll('goods_version_id=:goods_version_id',array(':goods_version_id'=>$id));
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showJsonResult(1);
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
        }
    }

    public function loadModel($id){
    	$model = GoodsVersionModel::model()->findByPk($id);
    	if(!$model){
    		throw new CHttpException(404,'The requested page does not exist.');
    	}
    	return $model;
    }

   
    
    
    
}
