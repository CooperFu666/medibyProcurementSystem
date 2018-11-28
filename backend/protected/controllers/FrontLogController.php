<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/6/19
 * Time: 11:43
 */

class FrontLogController extends LoginedController
{
    public function actionIndex()
    {
        if ($_POST){
            $product = [];
            $pageSize = Yii::app()->request->getParam('length',10);
            $start = Yii::app()->request->getParam('start');
            $actionId = Yii::app()->request->getParam('action_id');
            $actionType = Yii::app()->request->getParam('action_type');
            $objStr = Yii::app()->request->getParam('obj_str');
            $date = Yii::app()->request->getParam('date_range');
            $page = $start / $pageSize;
            $criteria = new CDbCriteria;
            $criteria->select = '*';
            $criteria->with = ['backend_admin'];
            $criteria->order = 't.create_time DESC';
            if (!empty($actionId)) {
                $criteria->addCondition('backend_admin.id=:user_id');
                $criteria->params[':user_id'] = $actionId;
            }
            if (!empty($actionType)) {
                $criteria->addCondition('t.action_type=:action_type');
                $criteria->params[':action_type'] = $actionType;
            }
            if (!empty($objStr)) {
                $criteria->addCondition("t.obj_str LIKE '{$objStr}%'");
            }
            if (!empty($date)) {
                $dateArr = explode('到', $date);
                $startTime = strtotime(trim($dateArr[0]));
                $endTime = strtotime(trim($dateArr[1]));
                $criteria->addBetweenCondition('t.create_time', $startTime, $endTime);
            }
            $countCriteria = $criteria;
            $dataProvider = new CActiveDataProvider('FrontLogModel',array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => $pageSize,
                    'currentPage' => $page,
                ),
            ));
            $products = $dataProvider->getData();
            foreach ($products as $key => $p) {
                $product[] = [
                    date('Y-m-d H:i:s', $p->create_time),
                    $p->backend_admin->username . "(" . RoleModel::model()->findByPk($p->backend_admin->role_id)->name . ")",
                    FrontLogModel::$typeArr[$p->action_type],
                    $p->obj_str,
                    ''
                ];
            }
            $recordsFiltered = $total = (int)FrontLogModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        } else {
            $roleList = [0 => '全部'];
            $actionUserList = [0 => '全部'];
            $actionTypeList = array_merge([0 => '全部'], FrontLogModel::$typeArr);
            foreach (AdminModel::model()->findAll() as $ActionUser) {
                $actionUserList[$ActionUser->id] = $ActionUser->username;
            }
            foreach (FrontRoleModel::model()->findAll() as $role) {
                $roleList[$role->id] = $role->role_name;
            }
            $this->render('index', [
                'roleList' => $roleList,
                'actionUserList' => $actionUserList,
                'actionTypeList' => $actionTypeList
            ]);
        }
    }

    public function actionResetPassword()
    {
        $userId = Yii::app()->request->getParam('user_id');
        UserModel::model()->updateByPk($userId, ['password' => md5(md5('Yj123456' . Yii::app()->params['userPasswordKey']))]);
        echo json_encode(['flag' => 1]);
    }

    public function actionAdd()
    {
        $userId = Yii::app()->request->getParam('user_id');
        if ($_POST) {
            $params = Yii::app()->request->getParam('UserModel');
            if (!empty($userId)) {  //编辑
                UserModel::model()->updateByPk($userId, ['phone' => $params['phone'], 'role_id' => $params['role_name']]);
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
            }
            $this->redirect(Yii::app()->getBaseUrl() . '/frontAccount/index');
        } else {
            $roleList = [];
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