<?php

class GoodsAttrController extends LoginedController{


    public function actionAdd($id){
        $versionCount = GoodsVersionModel::model()->count('goodsid=:goodsid',array(':goodsid'=>$id));
        if($versionCount==0){
            $returnJson = array(
                "result"=>3,
                "data"=>array(),
            );
        }else{
            $model = new GoodsAttrModel();
            $title=Yii::app()->request->getParam('title');
            if(isset($title)){
                $model->title = $title;
                $model->goodsid =$id;
                if($model->validate()){
                    $transaction=Yii::app()->db->beginTransaction();
                    try{
                        $attrList = GoodsAttrModel::model()->findAll('goodsid=:goodsid',array(':goodsid'=>$id));
                        $model->save();
                        $versionList = GoodsVersionModel::model()->findAll('goodsid=:goodsid',array(':goodsid'=>$id));
                        if($versionList){
                            if(!$attrList){
                                $bindList = GoodsBindModel::model()->findAll('goodsid=:goodsid',array(':goodsid'=>$id));
                                foreach ($bindList as $bind){
                                    $bind->goods_attr_id=$model->id;
                                    $bind->save();
                                }
                            }else{
                                foreach ($versionList as $version){
                                    $GoodsBindModel = new GoodsBindModel();
                                    $GoodsBindModel->goodsid=$id;
                                    $GoodsBindModel->goods_version_id=$version->id;
                                    $GoodsBindModel->goods_attr_id=$model->id;
                                    $GoodsBindModel->save();
                                }
                            }
                        }
                        $transaction->commit();//提交事务会真正的执行数据库操作
                        $returnJson = array(
                            "result"=>1,
                            "data"=>array(
                                "id"=>$model->id,
                                "title"=>$model->title,
                            ),
                        );
                    }catch (Exception $e) {
                        $transaction->rollback();//如果操作失败, 数据回滚
                        $returnJson = array(
                            "result"=>2,
                            "data"=>array(),
                        );
                    }
                }else{
                    $returnJson = array(
                        "result"=>2,
                        "data"=>array(),
                    );
                }
            }
        }
        echo json_encode($returnJson);
    }

    public function actionUpdate(){
        $title=Yii::app()->request->getParam('title');
        $id=Yii::app()->request->getParam('id');
        if (isset($title) && isset($id)) {
            $model=$this->loadModel($id);
            $model->title = $title;
            if($model->validate() && $model->save()){
                $returnJson = array(
                    "result"=>1,
                    "data"=>array(
                        "id"=>$model->id,
                        "title"=>$model->title,
                    ),
                );
            }else{
                $returnJson = array(
                    "result"=>2,
                    "data"=>array(),
                );
            }
            echo json_encode($returnJson);
        }
    }
    //删除
    public function actionDelete(){
        $id=Yii::app()->request->getParam('id');
        $model=$this->loadModel($id);
        $transaction=Yii::app()->db->beginTransaction();
        try{
            $attrCount = GoodsAttrModel::model()->count('goodsid=:goodsid',array(':goodsid'=>$model->goodsid));
            $versionCount = GoodsVersionModel::model()->count('goodsid=:goodsid',array(':goodsid'=>$model->goodsid));
            $model->delete();
            if($attrCount==1 && $versionCount>0){
                $bindList = GoodsBindModel::model()->findAll('goodsid=:goodsid',array(':goodsid'=>$model->goodsid));
                foreach ($bindList as $bind){
                    $bind->goods_attr_id=0;
                    $bind->save();
                }
            }else{
                GoodsBindModel::model()->deleteAll('goods_attr_id=:goods_attr_id',array(':goods_attr_id'=>$id));
            }
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showJsonResult(1);
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
        }
    }
    

    
    public function loadModel($id){
    	$model = GoodsAttrModel::model()->findByPk($id);
    	if(!$model){
    		throw new CHttpException(404,'The requested page does not exist.');
    	}
    	return $model;
    }

   
    
    
    
}
