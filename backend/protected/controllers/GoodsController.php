<?php

class GoodsController extends LoginedController
{
    //商品管理
    public function actionIndex()
    {
        $category=Yii::app()->request->getParam('category');
        if($_POST){

            $product=array();
            $pageSize=Yii::app()->request->getParam('length',10);
            $start=Yii::app()->request->getParam('start');
            $title=Yii::app()->request->getParam('title');
            $status=Yii::app()->request->getParam('status');
            $type=Yii::app()->request->getParam('type');
            $page=$start / $pageSize;

            $criteria=new CDbCriteria;
            if($category){
                $goodsIdArray = CategoryModel::getCategoryGoods($category);
                $criteria->addInCondition('t.id', $goodsIdArray);
            }
            if($title){
                $criteria->addSearchCondition('t.title',$title);
            }
            if($status){
                $criteria->addCondition('t.status=:status');
                $criteria->params[':status']=$status;
            }
            if($type){
                $criteria->addCondition('t.type=:type');
                $criteria->params[':type']=$type;
            }
            $criteria->with=array("census","brand");
            //$criteria->select='t.id,t.title,brand.title,t.status,t.type,t.is_new,t.is_hot,t.created_at,census.click,census.sales,census.collection';
            $criteria->order='t.created_at DESC';
            $order=$_POST['order'];
            switch($order[0]['column']){
                case 2:
                    $criteria->order='t.status '.$order[0]['dir'];
                    break;
                case 3:
                    $criteria->order='t.type '.$order[0]['dir'];
                    break;
                case 4:
                    $criteria->order='t.is_new '.$order[0]['dir'];
                    break;
                case 5:
                    $criteria->order='t.is_hot '.$order[0]['dir'];
                    break;
                case 6:
                    $criteria->order='census.click '.$order[0]['dir'];
                    break;
                case 7:
                    $criteria->order='census.sales '.$order[0]['dir'];
                    break;
                case 8:
                    $criteria->order='census.collection'.$order[0]['dir'];
                    break;
            }
            $countCriteria = $criteria;
            $dataProvider=new CActiveDataProvider('GoodsModel',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$pageSize,
                    'currentPage'=>$page,
                ),
            ));
            $products=$dataProvider->getData();
            foreach ($products as $p) {
                switch($p->status){
                    case GoodsModel::STATUS_UNPUB:
                        $status="未发布";
                        $update = '<a href="'.$this->createUrl("goods/publish/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">发布</i></a>'.
                            '<a href="'.$this->createUrl("goods/update/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>'.
                            '<a rel="'.$this->createUrl("goods/delete/id/{$p->id}").'"  class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i> 删除</a>';
                        break;
                    case  GoodsModel::STATUS_PUB:
                        $status="已发布";
                        $update = '<a href="'.$this->createUrl("goods/update/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">基础/分类设置</i></a>'.
                            '<a href="'.$this->createUrl("goods/publish/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">型号/属性设置</i></a>'.
                            '<a href="'.$this->createUrl("goods/stock/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">库存</i></a>'.
                            '<a href="'.$this->createUrl("comment/goods/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">查看评论</i></a>'.
                            '<a rel="'.$this->createUrl("goods/down/id/{$p->id}").'"  class="btn btn-xs yellow default bootbox-down"><i class="fa fa-arrow-down"></i> 下架</a>';
                        break;
                    case  GoodsModel::STATUS_DOWN:
                        $status="已下架";
                        $update = '<a rel="'.$this->createUrl("goods/up/id/{$p->id}") . '" class="btn btn-xs default bootbox-up"><i class="fa fa-pencil">重新上架</i></a>'.
                            '<a href="'.$this->createUrl("comment/goods/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">查看评论</i></a>';
                        break;
                }
                switch($p->type){
                    case GoodsModel::TYPE_ONE:
                        $type="一类";
                        break;
                    case GoodsModel::TYPE_TWO:
                        $type="二类";
                        break;
                    case GoodsModel::TYPE_THREE:
                        $type="三类";
                        break;
                }
                $product[] = array(
                    $p->title,
                    $p->brand->title,
                    $status,
                    $type,
                    $p->is_new ? '是':'否',
                    $p->is_hot ? '是':'否',
                    $p->census->click,
                    $p->census->sales,
                    $p->census->collection,
                    $update,
                );
            }
            $recordsFiltered=$total = (int)GoodsModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered, ));
        }else{
            $this->render('index',array("category"=>$category));
        }
    }
    //商品添加
    public function actionAdd()
    {
        $model = new GoodsModel();
        if(isset($_POST['GoodsModel'])){
            if(!isset($_POST['category_bind'])|| count($_POST['category_bind'])==0){
                $this->showError("未选择商品分类，请加入分类");
            }
            $goods = GoodsModel::model()->find('title=:title',
                array(':title'=>$_POST['GoodsModel']['title']));
            if($goods){
                $this->showError("商品名称已存在");
            }
            $model->attributes = $_POST['GoodsModel'];
            $model->status = GoodsModel::STATUS_UNPUB;
            $model->created_at = time();
            $category_bind = array_unique($_POST['category_bind']);
            $transaction=Yii::app()->db->beginTransaction();
            try{
                $model->save();
                foreach ($category_bind as $obj){
                    $CategoryBindModel = new CategoryBindModel();
                    $CategoryBindModel->goods_id=$model->id;
                    $CategoryBindModel->category_id=$obj;
                    $CategoryBindModel->save();
                }
                $GoodsCensusModel = new GoodsCensusModel();
                $GoodsCensusModel->goodsid = $model->id;
                $GoodsCensusModel->click = 0;
                $GoodsCensusModel->sales = 0;
                $GoodsCensusModel->collection = 0;
                $GoodsCensusModel->save();
                $transaction->commit();//提交事务会真正的执行数据库操作
                $this->redirect(array("goods/index"));
            }catch (Exception $e) {
                $transaction->rollback();//如果操作失败, 数据回滚
                $this->showError("操作失败");
            }
        }
        $this->render('add',array('model'=>$model));
    }
    //基础信息/分类修改
    public function actionUpdate($id)
    {

        $model = $this->loadModel($id);
        if(isset($_POST['GoodsModel'])){

            if(!isset($_POST['category_bind'])|| count($_POST['category_bind'])==0){
                $this->showError("未选择商品分类，请加入分类");
            }
            $model->attributes = $_POST['GoodsModel'];
            //$model->status = $model->status;
            //$model->created_at = time();
            $category_bind = array_unique($_POST['category_bind']);
            $transaction=Yii::app()->db->beginTransaction();
            try{
                $model->save();
                CategoryBindModel::model()->deleteAll('goods_id=:goods_id',array(':goods_id'=>$id));
                foreach ($category_bind as $obj){
                    $CategoryBindModel = new CategoryBindModel();
                    $CategoryBindModel->goods_id=$id;
                    $CategoryBindModel->category_id=$obj;
                    $CategoryBindModel->save();
                }
                $transaction->commit();//提交事务会真正的执行数据库操作
                $this->redirect(array("goods/index"));
            }catch (Exception $e) {
                $transaction->rollback();//如果操作失败, 数据回滚
                $this->showError("操作失败");
            }
        }
        $categoryBindList = CategoryBindModel::model()->findAll('goods_id=:goods_id',array(':goods_id'=>$id));
        if($categoryBindList){
            $categoryBindArray = array();
            foreach ($categoryBindList as $categoryBind){
                array_push($categoryBindArray, $categoryBind->category_id);
            }
            $criteria=new CDbCriteria;
            $criteria->addInCondition('id', $categoryBindArray);
            $categoryTree = CategoryModel::getCategoryTree(CategoryModel::model()->findAll($criteria));
        }else{
            $categoryTree = false;
        }
        $this->render('update',array('model'=>$model,'categoryTree'=>$categoryTree));
    }
    //删除
    public function actionDelete($id)
    {
        $model=$this->loadModel($id);
        if($model->status!=GoodsModel::STATUS_UNPUB){
            $this->showJsonResult('删除失败,已发布商品不能删除');
        }
        $transaction=Yii::app()->db->beginTransaction();
        try{
            $model->delete();
            CategoryBindModel::model()->deleteAll('goods_id=:goods_id',array(':goods_id'=>$id));
            GoodsBindModel::model()->deleteAll('goodsid=:goodsid',array(':goodsid'=>$id));
            GoodsImagesModel::model()->deleteAll('goodsid=:goodsid',array(':goodsid'=>$id));
            GoodsAttrModel::model()->deleteAll('goodsid=:goodsid',array(':goodsid'=>$id));
            GoodsVersionModel::model()->deleteAll('goodsid=:goodsid',array(':goodsid'=>$id));
            GoodsCensusModel::model()->deleteAll('goodsid=:goodsid',array(':goodsid'=>$id));
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showJsonResult(1);
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
        }
    }
    //重新上架
    public function actionUp($id){
        $model=$this->loadModel($id);
        $transaction=Yii::app()->db->beginTransaction();
        try{
            $model->status=GoodsModel::STATUS_PUB;
            $model->save();
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showJsonResult(1);
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
        }
    }
    //下架
    public function actionDown($id){
        $model=$this->loadModel($id);
        $transaction=Yii::app()->db->beginTransaction();
        try{
            $model->status=GoodsModel::STATUS_DOWN;
            $model->save();
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showJsonResult(1);
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
        }
    }
    //型号/属性设置
    public function actionPublish($id)
    {
        $model=GoodsModel::model()->with('attr')->findByPk($id);
        if(isset($_POST['publish'])){
            $model->status=GoodsModel::STATUS_PUB;
            if($model->save()){
                $this->showSuccess('发布成功', $this->createUrl('goods/index'));
            }else{
                $this->showError("发布失败");
            }
        }
    	$this->render('publish',array('model'=>$model, 'id'=>$id));
    }
    //库存设置
    public function actionStock($id)
    {
        $model=GoodsModel::model()->findByPk($id);
        $bind =GoodsBindModel::model()->with(array("attr","version"))->findAll('t.goodsid=:goodsid',array(':goodsid'=>$id));
        if(isset($_POST['gooodsbind'])){
            $gooodsbind = $_POST['gooodsbind'];
            $transaction=Yii::app()->db->beginTransaction();
            try{
                foreach ($gooodsbind as $bind){
                    $GoodsBindModel = GoodsBindModel::model()->findByPk($bind['id']);
                    if($bind['stock']=="")$bind['stock']=0;
                    if($bind['price']==""||$bind['price']==0){
                        $bind['stock']=0;
                        $bind['price']=0;
                    }
                    $GoodsBindModel->is_non=isset($bind['is_non'])?1:0;
                    $GoodsBindModel->stock=$bind['stock'];
                    $GoodsBindModel->price=$bind['price'];

                    $GoodsBindModel->save();
                }
                $transaction->commit();//提交事务会真正的执行数据库操作
                $this->showSuccess('发布成功', $this->createUrl('goods/index'));
            }catch (Exception $e) {
                $transaction->rollback();//如果操作失败, 数据回滚
                $this->showError("发布失败");
            }
        }
        $this->render('stock',array('model'=>$model, 'bind'=>$bind));
    }

    public function loadModel($id)
    {
        $model=GoodsModel::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }
}