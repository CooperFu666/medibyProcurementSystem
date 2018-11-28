<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/6/19
 * Time: 11:43
 */

class FrontRoleController extends LoginedController
{
    public function actionIndex()
    {
        if ($_POST){
            $product = [];
            $pageSize = Yii::app()->request->getParam('length',10);
            $start = Yii::app()->request->getParam('start');
            $page = $start / $pageSize;
            $criteria = new CDbCriteria;
            $criteria->select = '*';
            $criteria->order = "t.id desc";
            $criteria->with = ['front_user'];
            $countCriteria = $criteria;
            $dataProvider = new CActiveDataProvider('FrontRoleModel',array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => $pageSize,
                    'currentPage' => $page,
                ),
            ));
            $products = $dataProvider->getData();
            foreach ($products as $p) {
                $userNameArr = [];
                foreach ($p->front_user as $user) {
                    $userNameArr[] = $user->nickname;
                }
                $product[] = [
                    $p->role_name,
                    implode(',', $userNameArr),
                    "<a href={$this->createUrl('add', ['role_id' => $p->id])} class='btn btn-xs default btn-editable'><i class='fa fa-pencil'>编辑</i></a>
                    <a rel={$this->createUrl('delete', ['role_id' => $p->id])} class='btn btn-xs red default delete'><i class='fa fa-times'></i>删除</a>"
                ];
            }
            $recordsFiltered = $total = (int)FrontRoleModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        } else {
            $this->render('index');
        }
    }

    public function actionAdd()
    {
        if ($_POST) {
            $frontRoleAccessModelData = Yii::app()->request->getParam('FrontRoleModel');
            $roleId = Yii::app()->request->getParam('role_id');
            if (!empty($frontRoleAccessModelData['role_name']) && !empty($frontRoleAccessModelData['access'])) {
                $tr = FrontRoleModel::model()->getDbConnection()->beginTransaction();
                try {
                    if (empty($roleId)) {
                        $frontRoleModel = new FrontRoleModel();
                        $frontRoleModel->role_name = $frontRoleAccessModelData['role_name'];
                        $frontRoleModel->save();
                        $roleId = $frontRoleModel->getDbConnection()->getLastInsertID();
                        FrontLogModel::saveLog(FrontLogModel::TYPE_CREATE_ROLE, $frontRoleAccessModelData['role_name']);
                    } else {
                        FrontRoleModel::model()->updateByPk($roleId, ['role_name' => $frontRoleAccessModelData['role_name']]);
                        FrontLogModel::saveLog(FrontLogModel::TYPE_ALTER_ROLE, $frontRoleAccessModelData['role_name']);
                    }
                    FrontRoleAccessModel::model()->deleteAll('role_id=:role_id', [
                        ':role_id' => $roleId,
                    ]);
                    foreach ($frontRoleAccessModelData['access'] as $access) {
                        if (!empty($access)) {
                            $sql = "INSERT INTO ps_front_role_access(role_id,access) VALUE(:role_id,:access) ON DUPLICATE KEY UPDATE role_id=:role_id,access=:access";
                            FrontRoleAccessModel::model()->getDbConnection()->createCommand($sql)->execute([':role_id' => $roleId, ':access' => $access]);
                        }
                    }
                    $tr->commit();
                    $this->redirect(Yii::app()->getBaseUrl() . '/frontRole/index');
                } catch (Exception $e) {
                    $tr->rollback();
                    echo $e->getMessage();die;
                }
            }
        } else {
            $roleId = Yii::app()->request->getParam('role_id');
            $criteria = new CDbCriteria();
            $accessArr = [];
            if ($roleId) {
                $criteria->with = ['front_role_access'];
                $model = FrontRoleModel::model()->findByPk($roleId, $criteria);
                foreach ($model->front_role_access as $frontRoleAccess) {
                    $accessArr[] = $frontRoleAccess->access;
                }
            } else {
                $model = new FrontRoleModel();
            }
            $this->render('add', ['model' => $model, 'accessArr' => $accessArr]);
        }
    }

    public function actionDelete()
    {
        $roleId = Yii::app()->request->getParam('role_id');
        $tr = FrontRoleModel::model()->getDbConnection()->beginTransaction();
        $roleInfo = FrontRoleModel::model()->findByPk($roleId);
        try {
            FrontRoleModel::model()->deleteByPk($roleId);
            FrontRoleAccessModel::model()->deleteAll('role_id=:role_id', ['role_id' => $roleId]);
            FrontLogModel::saveLog(FrontLogModel::TYPE_DELETE_ROLE, $roleInfo->role_name);
            $tr->commit();
            echo json_encode(['flag' => 1]);
        } catch (Exception $e) {
            $tr->rollback();
            echo $e->getMessage();die;
        }
    }
}