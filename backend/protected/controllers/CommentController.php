<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/18
 * Time: 10:16
 */
class CommentController extends LoginedController
{


    /**
     * 关键字管理
     */
    public function ActionKeyword()
    {
        if ($_POST) {
            $service = array();
            $pageSize = Yii::app()->request->getParam('length', 10);
            $start = Yii::app()->request->getParam('start');
            $find = Yii::app()->request->getParam('find');
            $page = $start / $pageSize;
            $criteria = new CDbCriteria;
            if ($find) {
                $criteria->addSearchCondition('t.find', $find);
            }
            $countCriteria = $criteria;
            $dataProvider = new CActiveDataProvider('SensitiveWordModel', array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => $pageSize,
                    'currentPage' => $page,
                ),
            ));
            $products = $dataProvider->getData();
            foreach ($products as $k => $p) {
                //记录统计
                $service[] = array(
                    $p->id,
                    $p->find,
                    $p->replacement?$p->replacement:'*',
                    '<a rel="' . $this->createUrl("comment/delkey/id/{$p->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i> 删除</a>'
                );
            }
            $recordsFiltered = $total = (int)SensitiveWordModel::model()->count($countCriteria);
            echo json_encode(array('data' => $service, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        } else {
            $this->render('keyword');
        }
    }

    /*
    *  添加关键字
    */
    public function ActionAddkey()
    {
        $model = new SensitiveWordModel();
        if (isset($_POST['SensitiveWordModel'])) {
            $data = $model->attributes = $_POST['SensitiveWordModel'];
            $model->find = $data['find'];
            $model->replacement = $data['replacement'];
            if ($model->save() ) {
                $keywords = CHtml::listData(SensitiveWordModel::model()->findAll(),'find','replacement');
                SensitiveWords::updateWords($keywords);
                $this->showSuccess('添加成功', $this->createUrl('comment/Keyword'));
            } else {
                $this->error('添加失败');
            }
        }
        $result = array(
            'model' => $model,
        );
        $this->render('create', array('result' => $result));
    }
    /**
     * 删除关键字
     */
    public function ActionDelkey($id){
        if (SensitiveWordModel::model()->deleteByPk($id)) {
            $keywords = CHtml::listData(SensitiveWordModel::model()->findAll(),'replacement','find');
            SensitiveWords::updateWords($keywords);
            $this->showJsonResult(1);
        } else {
            $this->showJsonResult(0);
        }
    }
}