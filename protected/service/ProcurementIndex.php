<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/6/5
 * Time: 10:14
 */

class ProcurementIndex extends ApiServer
{
    public function actionGetWorkIndex()
    {
        $params = $this->makeParams(['userId', 'userToken']);
        $this->checkToken($params['userId'], $params['userToken']);
        $projectsData = ProcurementMainModel::getIndexProjects($params['userId']);
        $data = $projects = $news = $toDo = $isShow = $redMark = [];
        foreach ($projectsData as $key => $value) {
            $projects[$key]['level'] = ProcurementMainModel::$levelArr[$value['level']];
            $projects[$key]['procurementId'] = $value['procurementId'];
            $projects[$key]['procurementStatus'] = ProcurementMainModel::$statusArr[$value['status']];
            $projects[$key]['procurementTitle'] = $value['title'] . '(' . $value['item_number'] . ')';
            $projects[$key]['time'] = date("Y-m-d H:i:s", $value['update_time']);
            $projects[$key]['currentUser'] = ProcurementMainModel::getCurrentUserByPK($value['procurementId']);
            $projects[$key]['status'] = $value['status'];
        }
        $data['projects'] = $projects;
        $newsData = UserNewsModel::getIndexNews($params['userId']);
        foreach ($newsData as $key => $value) {
            $submitUser = UserModel::getNicknameByPK($value['user_id']);
            $news[$key]['desc'] = UserNewsModel::getStr($submitUser, $value['status'], $value['title'], $value['item_number'], $value['procurement_id']);
            $news[$key]['procurementId'] = $value['procurement_id'];
            $news[$key]['time'] = date('Y-m-d H:i:s', $value['create_time']);
            $news[$key]['status'] = $value['procurement_status'];
        }
        $data['news'] = $news;
        $toDoData = UserToDoModel::getIndexToDo($params['userId']);
        $data['toDo'] = $toDoData;
        $data['isShow']['purchaseApply'] = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_SITE_SUBMIT_PURCHASE);
        $data['isShow']['purchaseDoc'] = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_SITE_DOC);
        $data['redMark']['myPurchase'] = Yii::app()->redis->executeCommand('HLEN', ["redMarkMyPurchase:userId|{$params['userId']}"]);
        $data['redMark']['toDo'] = UserToDoModel::model()->count('user_id=:user_id', [':user_id' => $params['userId']]);
        $userInfo = UserModel::model()->findByPk($params['userId']);
        $data['isFirstLogin'] = 0;
        if ($userInfo->password == 'ce4131c301cbb262ab043535a8bec761')
            $data['isFirstLogin'] = 1;
        return $data;
    }

    public function actionIndexToDo()
    {
        $params = $this->makeParams(['userId', 'userToken', 'page']);
        $this->checkToken($params['userId'], $params['userToken']);
        $pageSize = Yii::app()->params['pageSize'];
        $totalRecord = UserToDoModel::model()->count('user_id = :user_id', [':user_id' => $params['userId']]);
         // 记算总共有多少页
        $pageNum = 0;
        if( $totalRecord ){
            if( $totalRecord % $pageSize ){
                $pageNum = (int)($totalRecord / $pageSize) + 1;
            }else{
                $pageNum = $totalRecord / $pageSize;
            }
        }
        $list = UserToDoModel::getIndexToDo($params['userId'], $params['page']);
        $data['pageNum'] = $pageNum;
        $data['pageSize'] = $pageSize;
        $data['totalRecord'] = $totalRecord;
        $data['list'] = $list;
        return $data;
    }

    public function actionIndexNews()
    {
        $params = $this->makeParams(['userId', 'userToken', 'page']);
        $this->checkToken($params['userId'], $params['userToken']);
        $pageSize = Yii::app()->params['pageSize'];
        $isGlobal = FrontRoleAccessModel::model()->exists('role_id=:role_id AND access=:access', [
            ':role_id' => UserModel::getRoleIdByPK($params['userId']),
            ':access' =>FrontRoleAccessModel::ACCESS_GLOBAL_NEWS,
        ]);
        $criteria = new CDbCriteria();
        $criteria->with = ['procurement_main'];
        $criteria->order = 't.create_time DESC';
        if (!$isGlobal) {
            $criteria->condition = 't.user_id=:user_id';
            $criteria->params = [':user_id' => $params['userId']];
            $totalRecord = UserNewsModel::model()->count('user_id = :user_id', [':user_id' => $params['userId']]);
        } else {
            $totalRecord = UserNewsModel::model()->count();
        }
        // 记算总共有多少页
        $pageNum = 0;
        if( $totalRecord ){
            if( $totalRecord % $pageSize ){
                $pageNum = (int)($totalRecord / $pageSize) + 1;
            }else{
                $pageNum = $totalRecord / $pageSize;
            }
        }
        $criteria->offset = ($params['page'] - 1) * 30;
        $criteria->limit = $pageSize;
        $res = UserNewsModel::model()->findAll($criteria);
        $data = $list = [];
        foreach ($res as $key => $value) {
            $submitUser = UserModel::getNicknameByPK($value->user_id);
            $str = UserNewsModel::getStr($submitUser, $value->status, $value->procurement_main->title,
            $value->procurement_main->item_number, $value->procurement_main->id);
            $list[$key]['str'] = $str;
            $list[$key]['time'] = date("Y-m-d H:i:s",$value->create_time);
            $list[$key]['procurementId'] = $value->procurement_id;
            $list[$key]['status'] = $value->procurement_main->status;
        }
        $data['pageNum'] = $pageNum;
        $data['pageSize'] = $pageSize;
        $data['totalRecord'] = $totalRecord;
        $data['list'] = $list;
        return $data;
    }

    public function actionIndexGetDoc()
    {
        $params = $this->makeParams(['userId', 'userToken', 'action', 'searchParams', 'page']);
        $this->checkToken($params['userId'], $params['userToken']);
        $pageSize = Yii::app()->params['pageSize'];
        $searchParams = json_decode($params['searchParams']);
        switch ($params['action']) {
            case ProcurementMainModel::DOC_MY_PURCHASE :
                //  清除红点
                Yii::app()->redis->executeCommand('DEL', ["redMarkMyPurchase:userId|{$params['userId']}"]);
                $res = ProcurementMainModel::getIndexDoc(0, $searchParams, ($params['page'] - 1) * 30, $pageSize,
                    $params['userId'], UserActionModel::$typeArr);
                $totalRecord = ProcurementMainModel::getIndexDoc(1, $searchParams, ($params['page'] - 1) * 30, $pageSize,
                    $params['userId'], UserActionModel::$typeArr);
                break;
            case ProcurementMainModel::DOC_DOC :
                $res = ProcurementMainModel::getIndexDoc(0, $searchParams, ($params['page'] - 1) * 30, $pageSize);
                $totalRecord = ProcurementMainModel::getIndexDoc(1, $searchParams, ($params['page'] - 1) * 30, $pageSize);
                break;
            case ProcurementMainModel::DOC_MY_RESEARCH :
                $res = ProcurementMainModel::getIndexDoc(0, $searchParams, ($params['page'] - 1) * 30, $pageSize,
                    $params['userId'], [UserActionModel::APPLICATION_RESEARCH], 1);
                $totalRecord = ProcurementMainModel::getIndexDoc(1, $searchParams, ($params['page'] - 1) * 30, $pageSize,
                    $params['userId'], [UserActionModel::APPLICATION_RESEARCH], 1);
                break;
            case ProcurementMainModel::DOC_MY_REPORT :
                $res = ProcurementMainModel::getIndexDoc(0, $searchParams, ($params['page'] - 1) * 30, $pageSize,
                    $params['userId'], [UserActionModel::APPLICATION_PURCHASE], 1);
                $totalRecord = ProcurementMainModel::getIndexDoc(1, $searchParams, ($params['page'] - 1) * 30, $pageSize,
                    $params['userId'], [UserActionModel::APPLICATION_PURCHASE], 1);
                break;
            default :
                $res = [];
                $totalRecord = 0;
        }
        // 记算总共有多少页
//        $totalRecord = ProcurementMainModel::model()->count();
        $pageNum = 0;
        if( $totalRecord ){
            if( $totalRecord % $pageSize ){
                $pageNum = (int)($totalRecord / $pageSize) + 1;
            }else{
                $pageNum = $totalRecord / $pageSize;
            }
        }
        $procurementList = [];
        foreach ($res as $key => $value) {
//            $procurementList[$key]['applicationTime'] = ProcurementMainModel::getApplicationTime($value['procurementId']);
            $procurementList[$key]['applicationTime'] = date("Y-m-d H:i:s", $value['submit_time']);
            $procurementList[$key]['executor'] = ProcurementMainModel::getCurrentUserByPK($value['procurementId']);
            $procurementList[$key]['level'] = ProcurementMainModel::$levelArr[$value['level']];
            $procurementList[$key]['principal'] = ProcurementMainModel::getPrincipal($value['procurementId']);
            $procurementList[$key]['procurementId'] = $value['procurementId'];
            $procurementList[$key]['procurementItemNumber'] = $value['item_number'];
            $procurementList[$key]['procurementTitle'] = $value['title'];
            $procurementList[$key]['status'] = ProcurementMainModel::$statusArr[$value['status']];
            $procurementList[$key]['updateTime'] = date('Y-m-d H:i:s',$value['update_time']);
            $procurementList[$key]['statusNo'] = $value['status'];
        }
        $data['procurementList'] = $procurementList;
        $data['pageNum'] = $pageNum;
        $data['pageSize'] = $pageSize;
        $data['totalRecord'] = $totalRecord;
        return $data;
    }

    public function actionIndexDetails()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId']);
        $this->checkToken($params['userId'], $params['userToken']);
        $data['procurementId'] = $params['procurementId'];
        $data['status'] = ProcurementMainModel::model()->getDbConnection()->createCommand('SELECT status FROM ps_procurement_main 
        WHERE id=:id')->queryScalar([':id' => $params['procurementId']]);
        return $data;
    }

    public function actionReStart()
    {
        $params = $this->makeParams(['userId', 'userToken', 'action', 'pricingAction', 'procurementId', 'researchAction', 'suggestAction', 'baseINfoAction']);
        $this->checkToken($params['userId'], $params['userToken']);
        FrontRoleAccessModel::checkAccess($params['userId'], FrontRoleAccessModel::ACCESS_BUTTON_RELOAD);
        //  检测项目状态
        $idStr = ProcurementMainModel::STATUS_FINISH . ',' . ProcurementMainModel::STATUS_CLOSE;
        if (!ProcurementMainModel::model()->exists("id=:id AND status IN ({$idStr})", [
            ':id' => $params['procurementId'],
        ]))
            new ApiException(ApiException::STATUS_EXCEPTION);
        $commodityIdList = CommodityModel::model()->getDbConnection()->createCommand('SELECT id FROM ps_commodity WHERE procurement_id=
        :procurement_id')->queryColumn([':procurement_id' => $params['procurementId']]);
        $commodityIdStr = implode(',', $commodityIdList);
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        $time = time();
        try {
            //  已关闭
            if ($params['action'] == ProcurementMainModel::CLOSE) {
                ProcurementMainModel::model()->updateByPk($params['procurementId'], [
                    'result_description' => '',
                    'stop_remark' => '',
                    'repricing_remark' => '',
                    'status' => ProcurementMainModel::STATUS_RESEARCH,
                    'update_time' => $time,
                ]);
                CommodityPurchaseModel::model()->deleteAll("commodity_id IN ({$commodityIdStr})");
                CommodityTargetPriceModel::model()->deleteAll("commodity_id IN ({$commodityIdStr})");
                UserResearchModel::model()->deleteAll("procurement_id=:procurement_id", [':procurement_id' => $params['procurementId']]);
                //  生成新授权数据
                UserActionModel::model()->deleteAll("procurement_id=:procurement_id AND type NOT IN(" . UserActionModel::APPLICATION_PRINCIPAL . ',' . UserActionModel::APPLICATION_SUBMIT . ")", [':procurement_id' => $params['procurementId']]);
                $procurementMainId = $params['procurementId'];
            } else { // 已完成
                $criteria = new CDbCriteria();
                $criteria->with = ['commodity', 'user_action'];
                $old = ProcurementMainModel::model()->findByPk($params['procurementId'], $criteria);
                $procurementMainModel = new ProcurementMainModel();
                $procurementMainModel->status = ProcurementMainModel::STATUS_RESEARCH;
                $procurementMainModel->title = $old->title;
                $procurementMainModel->level = $old->level;
                require_once('ProcurementFront.php');
                $procurementMainModel->item_number = (new ProcurementFront)->actionGenerateCode()['procurementItemNumber'];
                $procurementMainModel->is_end_time = $old->is_end_time;
                $procurementMainModel->end_time = $old->end_time;
                $procurementMainModel->remark = $old->remark;
                $procurementMainModel->create_time = $time;
                $procurementMainModel->update_time = $time;
                $procurementMainModel->submit_time = $old->submit_time;
                $procurementMainModel->save();
                $procurementMainId = $procurementMainModel->getDbConnection()->getLastInsertID();
                if (!empty($old->commodity)) {
                    foreach ($old->commodity as $key => $value) {
                        $commodityModel = new CommodityModel();
                        $commodityModel->procurement_id = $procurementMainId;
                        $commodityModel->brand_title = $value->brand_title;
                        $commodityModel->model_title = $value->model_title;
                        $commodityModel->apply_purchase_quantity = $value->apply_purchase_quantity;
                        $commodityModel->commodity_title = $value->commodity_title;
                        $commodityModel->commodity_title_english = $value->commodity_title_english;
                        $commodityModel->unit = $value->unit;
                        $commodityModel->is_purchase = $value->is_purchase;
                        $commodityModel->is_register = $value->is_register;
                        $commodityModel->create_time = $time;
                        $commodityModel->update_time = $time;
                        $commodityModel->save();
                    }
                }
                foreach ($old->user_action as $userActionInfo) {
                    if ($userActionInfo->type == UserActionModel::APPLICATION_PRINCIPAL || $userActionInfo->type == UserActionModel::APPLICATION_SUBMIT)
                    {
                        $userActionModel = new UserActionModel();
                        $userActionModel->type = $userActionInfo->type;
                        $userActionModel->user_id = $userActionInfo->user_id;
                        $userActionModel->procurement_id = $procurementMainId;
                        $userActionModel->create_time = $time;
                        $userActionModel->save();
                    }
                }
            }
            if (!empty($procurementMainId))
                $params['procurementId'] = $procurementMainId;
            $userActionModel = new UserActionModel();
            $userActionModel->type = UserActionModel::APPLICATION_PRICING;
            $userActionModel->user_id = $params['pricingAction'];
            $userActionModel->procurement_id = $params['procurementId'];
            $userActionModel->create_time = $time;
            $userActionModel->save();
            $userActionModel = new UserActionModel();
            $userActionModel->type = UserActionModel::APPLICATION_SUGGEST;
            $userActionModel->user_id = $params['suggestAction'];
            $userActionModel->procurement_id = $params['procurementId'];
            $userActionModel->create_time = $time;
            $userActionModel->save();
            $userActionModel = new UserActionModel();
            $userActionModel->type = UserActionModel::APPLICATION_BASE_INFO;
            $userActionModel->user_id = $params['baseINfoAction'];
            $userActionModel->procurement_id = $params['procurementId'];
            $userActionModel->create_time = $time;
            $userActionModel->save();
            UserToDoModel::makeMark($params['baseINfoAction'], $params['procurementId'], UserToDoModel::STATUS_INFO);
            $researchList = json_decode($params['researchAction']);
            foreach ($researchList as $value) {
                $userIdList = array_unique($value->userIdLIst);
                if (count($userIdList) != count($value->userIdLIst))
                    new ApiException(ApiException::COMMON_CODE, "国内调研者/国外调研者不能重复！");
                foreach ($userIdList as $v) {
                    $userActionModel = new UserActionModel();
                    $userActionModel->type = UserActionModel::APPLICATION_RESEARCH;
                    $userActionModel->is_foreign = $value->type;
                    $userActionModel->user_id = $v;
                    $userActionModel->procurement_id = $params['procurementId'];
                    $userActionModel->create_time = $time;
                    $userActionModel->save();
                    UserToDoModel::makeMark($v, $params['procurementId'], $value->type == 0 ? UserToDoModel::STATUS_INTERVAL: UserToDoModel::STATUS_FOREIGN);
                }
            }
            $tr->commit();
            return ['flag' => 1, 'procurementId' => $procurementMainId];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()],
                GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionReStartAuth()
    {
        $params = $this->makeParams(['userId', 'userToken']);
        $this->checkToken($params['userId'], $params['userToken']);
        $data['isShow'] = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_BUTTON_RELOAD);
        return $data;
    }
}