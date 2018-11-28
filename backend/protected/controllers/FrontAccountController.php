<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/6/19
 * Time: 11:43
 */

class FrontAccountController extends LoginedController
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
            $criteria->with = ['front_role'];
            if (!empty($roleId)) {
                $criteria->addCondition('front_role.id=:roleId');
                $criteria->params[':roleId'] = $roleId;
            }
            $roleId = Yii::app()->request->getParam('roleId');
            $str = Yii::app()->request->getParam('str');
            if (!empty($roleId)) {
                $criteria->addCondition('front_role.id=:roleId');
                $criteria->params[':roleId'] = $roleId;
            }
            if (!empty($str)) {
                $criteria->addCondition("(t.nickname LIKE '{$str}%' OR t.username LIKE '{$str}%' OR t.phone LIKE '{$str}%')");
            }
            $countCriteria = $criteria;
            $dataProvider = new CActiveDataProvider('UserModel',array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => $pageSize,
                    'currentPage' => $page,
                ),
            ));
            $products = $dataProvider->getData();
            foreach ($products as $key => $p) {
                $product[] = [
                    $key + 1,
                    $p->nickname,
                    $p->username,
                    isset($p->front_role->role_name) ? $p->front_role->role_name : '--',
                    $p->phone,
                    !empty($p->login_at) ? date("Y-m-d H:i:s", $p->login_at) : '--',
                    "<a href={$this->createUrl('add', ['user_id' => $p->id])} class='btn btn-xs default btn-editable'><i class='fa fa-pencil'>编辑</i></a>
                    <a rel={$this->createUrl('resetPassword', ['user_id' => $p->id])} class='btn btn-xs default btn-editable reset'><i class='fa fa-pencil'></i>重置密码</a>"
                ];
            }
            $recordsFiltered = $total = (int)UserModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        } else {
            $roleList = [0 => '全部'];
            $frontRoleModelData = FrontRoleModel::model()->findAll();
            foreach ($frontRoleModelData as $role) {
                $roleList[$role->id] = $role->role_name;
            }
            $this->render('index', ['roleList' => $roleList]);
        }
    }

    public function actionResetPassword()
    {
        $userId = Yii::app()->request->getParam('user_id');
        $tr = UserModel::model()->getDbConnection()->beginTransaction();
        $userInfo = UserModel::model()->findByPk($userId);
        try {
            UserModel::model()->updateByPk($userId, ['password' => md5(md5('Yj123456' . Yii::app()->params['userPasswordKey']))]);
            $obj_str = $userInfo->username . "(" . $userInfo->nickname . ")";
            FrontLogModel::saveLog(FrontLogModel::TYPE_RESET_PASSWORD, $obj_str);
            $tr->commit();
        } catch (Exception $e) {
            $tr->rollback();
            echo $e->getMessage();die;
        }
        echo json_encode(['flag' => 1]);
    }

    public function actionAdd()
    {
        $userId = Yii::app()->request->getParam('user_id');
        if ($_POST) {
            $params = Yii::app()->request->getParam('UserModel');
            if (!empty($userId)) {  //编辑
                $userInfo = UserModel::model()->findByPk($userId);
                UserModel::model()->updateByPk($userId, ['phone' => $params['phone'], 'role_id' => $params['role_name']]);
                if ($params['role_name'] != $userInfo->role_id)
                    FrontLogModel::saveLog(FrontLogModel::TYPE_ALERT_ROLE_INFO, $userInfo->username . "(" . $userInfo->nickname . ")");
                FrontLogModel::saveLog(FrontLogModel::TYPE_ALTER_ACCOUNT, $userInfo->username . "(" . $userInfo->nickname . ")");
            } else {    //新增
                $sql = "INSERT INTO ps_front_user(username,password,nickname,role_id,phone) VALUES(:username,:password,:nickname,:role_id,:phone) ON DUPLICATE KEY UPDATE username=username";
                $param = [
                    ':username' => $params['username'],
                    ':password' => md5(md5('Yj123456' . Yii::app()->params['userPasswordKey'])),
                    ':nickname' => $params['nickname'],
                    ':role_id' => $params['role_name'],
                    ':phone' => $params['phone'],
                ];
                UserModel::model()->getDbConnection()->createCommand($sql)->execute($param);
                FrontLogModel::saveLog(FrontLogModel::TYPE_CREATE_ACCOUNT, $params['username'] . "(" . $params['nickname'] . ")");
            }
            $this->redirect(Yii::app()->getBaseUrl() . '/frontAccount/index');
        } else {
            $roleList = ['0' => '请选择'];
            $frontRoleModelData = FrontRoleModel::model()->findAll();
            foreach ($frontRoleModelData as $role) {
                $roleList[$role->id] = $role->role_name;
            }
            if ($userId) {
                $criteria = new CDbCriteria();
                $criteria->with = ['front_role'];
                $model = UserModel::model()->findByPk($userId, $criteria);
            } else {
                $model = new UserModel();
            }
            $this->render('add', ['model' => $model, 'userId' => $userId, 'roleList' => $roleList]);
        }
    }
}