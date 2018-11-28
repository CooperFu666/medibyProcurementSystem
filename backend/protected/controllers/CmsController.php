<?php
/**
 * Created by JetBrains PhpStorm.
 * User: home
 * Date: 15-8-1
 * Time: 下午4:34
 * To change this template use File | Settings | File Templates.
 */

class CmsController extends LoginedController
{
    //分类管理
    public function actionCategory()
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

            $dataProvider=new CActiveDataProvider('ArticleCategoryModel',array(
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
                    $p->sort,
                    '<a rel="' . $this->createUrl("cms/categoryDelete/id/{$p->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>'.
                    '<a href="' . $this->createUrl("cms/categoryUpdate/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>'
                );
            }
            $recordsFiltered = $total = (int)ArticleCategoryModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        }else{
            $this->render('category');
        }
    }
    //分类添加
    public function actionCategoryAdd()
    {
        $model = new ArticleCategoryModel();
        $model->pid = 0;
        if(isset($_POST['ArticleCategoryModel'])){
            $nav = ArticleCategoryModel::model()->find('pid=:pid and title=:title',
                array(':pid'=>0,':title'=>$_POST['ArticleCategoryModel']['title']));
        	if($nav){
        		$this->showError("分类名称已存在");
        	}
            $model->attributes = $_POST['ArticleCategoryModel'];
            $model->is_effect = 1;
            $model->type = 1;
            if($model->save()){
                $this->redirect(array("cms/category"));
            }
        }
        $this->render('categoryAdd',array('model'=>$model));
    }
    //分类修改
    public function actionCategoryUpdate($id)
    {

        $model = ArticleCategoryModel::model()->findByPk($id);;
        if(isset($_POST['ArticleCategoryModel'])){
            $model->attributes=$_POST['ArticleCategoryModel'];
            $model->pid =0;
            if($model->save()){
                $this->redirect(array("cms/category"));
            }
        }
        $this->render("categoryUpdate",array('model'=>$model));
    }
    //分类删除
    public function actionCategoryDelete($id)
    {
        $transaction=Yii::app()->db->beginTransaction();
        try{
            ArticleModel::model()->deleteAll("cate_id=:cate_id",array(":cate_id"=>$id));
            ArticleCategoryModel::model()->deleteByPk($id);
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showJsonResult(1);
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
        }
    }
    //文章管理
    public function actionArticle()
    {
        if($_POST){
            $product=array();
            $pageSize=Yii::app()->request->getParam('length',10);
            $start=Yii::app()->request->getParam('start');
            $page=$start / $pageSize;

            $criteria=new CDbCriteria;
            $countCriteria = $criteria;

            $dataProvider=new CActiveDataProvider('ArticleModel',array(
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
                    $p->category->title,
                    '<a rel="' . $this->createUrl("cms/articleDelete/id/{$p->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>'.
                    '<a href="' . $this->createUrl("cms/articleUpdate/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>'
                );
            }
            $recordsFiltered = $total = (int)ArticleModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        }else{
            $this->render('article');
        }
    }

    //文章添加
    public function actionArticleAdd()
    {
        $model = new ArticleModel();
        if(isset($_POST['ArticleModel'])){
            $model->attributes = $_POST['ArticleModel'];
            $model->created_at = time();
            $model->is_effect = 1;
            if($model->save()){
                $this->redirect(array("cms/article"));
            }
        }
        $this->render('articleAdd',array('model'=>$model));
    }
    //文章修改
    public function actionArticleUpdate($id)
    {

        $model = ArticleModel::model()->findByPk($id);;
        if(isset($_POST['ArticleModel'])){
            $model->attributes=$_POST['ArticleModel'];
            $model->updated_at = time();
            if($model->save()){
                $this->redirect(array("cms/article"));
            }
        }
        $this->render("articleUpdate",array('model'=>$model));
    }
    //文章删除
    public function actionArticleDelete($id)
    {
        $transaction=Yii::app()->db->beginTransaction();
        try{
            ArticleModel::model()->deleteByPk($id);
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showJsonResult(1);
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
        }
    }

}