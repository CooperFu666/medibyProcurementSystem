<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/5/8
 * Time: 13:24
 */

class ProcurementFront extends ApiServer
{
    const USER_LIST_PRINCIPAL = 1;  //项目负责人列表
//    const USER_LIST_RESEARCH = 2;   //调研执行者列表
    const USER_LIST_PRICING = 3;    //定价执行者列表
    const USER_LIST_SUGGEST = 4;    //采购量建议者列表
    const USER_LIST_PURCHASE = 5;   //采购执行者列表
    const USER_LIST_RESEARCH_INTERNAL = 6;   //国内调研执行者列表
    const USER_LIST_RESEARCH_FOREIGN = 7;   //国外调研执行者列表
    const USER_LIST_BASE_INFO = 8;   //能填写基础信息用户列表

    public function actionCloseProcurement()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId', 'stopRemark']);
        $this->checkToken($params['userId'], $params['userToken']);
        FrontRoleAccessModel::checkAccess($params['userId'], FrontRoleAccessModel::ACCESS_BUTTON_NOT_PURCHASE);
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        $time = time();
        try {
            $procurementStatus = ProcurementMainModel::model()->getDbConnection()->createCommand('SELECT status FROM ps_procurement_main 
            WHERE id=:id')->queryScalar([':id' => $params['procurementId']]);
            switch ($procurementStatus) {
                case ProcurementMainModel::STATUS_RESEARCH :
                    UserActionModel::addUserAction($params['userId'], $params['procurementId'], UserActionModel::APPLICATION_END_RESEARCH);
                    $status = UserNewsModel::STATUS_END_RESEARCH;
                    break;
                case ProcurementMainModel::STATUS_UN_PRICING :
                    UserActionModel::addUserAction($params['userId'], $params['procurementId'], UserActionModel::APPLICATION_END_PRICING);
                    $status = UserNewsModel::STATUS_END_PRICING;
                    break;
                case ProcurementMainModel::STATUS_PRICING :
                    UserActionModel::addUserAction($params['userId'], $params['procurementId'], UserActionModel::APPLICATION_END_SUGGEST);
                    $status = UserNewsModel::STATUS_END_APPROVAL;
                    break;
            }
            UserToDoModel::model()->deleteAll('procurement_id=:procurement_id', [':procurement_id' => $params['procurementId']]);
            if (!empty($status))
                UserNewsModel::addNews($params['userId'], $params['procurementId'], $status);
            ProcurementMainModel::model()->updateByPk($params['procurementId'], [
                'status' => ProcurementMainModel::STATUS_CLOSE,
                'stop_remark' => $params['stopRemark'],
                'update_time' => $time,
                'stop_time' => $time,
                'stop_id' => $params['userId'],
            ]);
            //  工作台红点
            UserModel::makeMarkForMyPurchase($params['procurementId']);
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()], GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionCommonGetUserList()
    {
        $params = $this->makeParams(['userId', 'userToken', 'action']);
        $this->checkToken($params['userId'], $params['userToken']);
        $criteria = new CDbCriteria();
        $criteria->with = ['front_role'];
        switch ($params['action']) {
            case self::USER_LIST_PRINCIPAL :
                $criteria->addCondition('t.access=' . FrontRoleAccessModel::ACCESS_ROLE_PRINCIPAL);
                break;
            case self::USER_LIST_PRICING :
                $criteria->addCondition('t.access=' . FrontRoleAccessModel::ACCESS_ROLE_PRICING);
                break;
            case self::USER_LIST_SUGGEST :
                $criteria->addCondition('t.access=' . FrontRoleAccessModel::ACCESS_ROLE_SUGGEST);
                break;
            case self::USER_LIST_PURCHASE :
                $criteria->addCondition('t.access=' . FrontRoleAccessModel::ACCESS_ROLE_PURCHASE);
                break;
            case self::USER_LIST_RESEARCH_INTERNAL :
                $criteria->addCondition('t.access=' . FrontRoleAccessModel::ACCESS_ROLE_INTERVAL_PRICE);
                break;
            case self::USER_LIST_RESEARCH_FOREIGN :
                $criteria->addCondition('t.access=' . FrontRoleAccessModel::ACCESS_ROLE_FOREIGN_PRICE);
                break;
            case self::USER_LIST_BASE_INFO :
                $criteria->addCondition('t.access=' . FrontRoleAccessModel::ACCESS_ROLE_BASE_INFO);
                break;
            default :
        }
        $roleList= FrontRoleAccessModel::model()->findAll($criteria);
        $roleIdArr = [];
        foreach ($roleList as $roleInfo) {
            $roleIdArr[] = $roleInfo->role_id;
        }
        $roleIdStr = implode(',', $roleIdArr);
        $userList = UserModel::model()->findAll("role_id IN({$roleIdStr})");
        $data = [];
        foreach ($userList as $key => $value) {
            $data[$key]['userId'] = $value->id;
            $data[$key]['userName'] = $value->nickname;
        }
        return $data;
    }

    public function actionGetVerificationCode()
    {
        $verificationMark = md5(microtime() . rand(0, 1000000));
        $letters = Utils::getRandLetter();
        Yii::app()->redis->executeCommand('HSET', ["verificationCode:verificationMark={$verificationMark}", "code", "{$letters}"]);
        Yii::app()->redis->executeCommand("EXPIRE", ["verificationCode:verificationMark={$verificationMark}", 300]);
        return ['verificationCode' => $letters, 'verificationMark' => $verificationMark];
    }

    public function actionLogin()
    {
        $params = $this->makeParams(['userName', 'password', 'verificationCode', 'verificationMark']);
        $userName = $params['userName'];
        $password = $params['password'];
        $verificationMark = $params['verificationMark'];
        $verifyCode = Yii::app()->redis->executeCommand("HGET", ["verificationCode:verificationMark={$verificationMark}", 'code']);
        $letters = Utils::getRandLetter();
        Yii::app()->redis->executeCommand('HSET', ["verificationCode:verificationMark={$verificationMark}", "code", "{$letters}"]);
        //  登录验证
        if (strtolower($verifyCode) != strtolower($params['verificationCode']))
            new ApiException(ApiException::VERIFY_CODE_ERROR);
        $userInfo = UserModel::model()->find('username=:username AND password=:password', [':username' => $userName, ':password' => md5(md5($password . Yii::app()->params['userPasswordKey']))]);
        if (empty($userInfo) || !FrontRoleModel::model()->exists('id=:roleId', [':roleId' => $userInfo->role_id]))
            new ApiException(ApiException::USER_OR_PASSWORD_ERROR);
        //  成功登录
        $isFirstLogin = $userInfo->is_first_login;
        $time = time();
        $tokenTime = Yii::app()->params['tokenTimeToLive'];
        $roleName = FrontRoleModel::model()->findByPk($userInfo->role_id)->role_name;
        $userToken = Yii::app()->redis->executeCommand("HGET", ["userToken:userId={$userInfo->id}", 'userToken']);
        if (empty($userToken)) {
            $userToken = md5("username:{$userName}password:{$password}{$time}");
            Yii::app()->redis->executeCommand("HSET", ["userToken:userId={$userInfo->id}", 'userToken', "{$userToken}"]);
            Yii::app()->redis->executeCommand("EXPIRE", ["userToken:userId={$userInfo->id}", $tokenTime]);
        }
        UserModel::model()->updateByPk($userInfo->id, ['login_at' => $time]);
        $data = [
            'isFirstLogin'=> $isFirstLogin,
            'roleName' => $roleName,
            'userId' => $userInfo->id,
            'userName' => $userInfo->nickname,
            'userToken' => $userToken,
        ];
        return $data;
    }

    public function actionPasswordReset()
    {
        $params = $this->makeParams(['userId', 'userToken', 'password']);
        $oldPassword = Yii::app()->request->getParam('oldPassword');
        $this->checkToken($params['userId'], $params['userToken']);
        if (!empty($oldPassword)) {
            if (UserModel::model()->exists('id=:user_id AND password=:old_password', [
                ':user_id' => $params['userId'],
                ':old_password' => md5(md5($oldPassword . Yii::app()->params['userPasswordKey']))
            ])) {
                $flag =  UserModel::model()->updateByPk($params['userId'], ['is_first_login' => 0, 'password' => md5(md5($params['password'] . Yii::app()->params['userPasswordKey']))]);
            } else {
                $flag = 2;
            }
        } else {
            $flag = UserModel::model()->updateByPk($params['userId'], ['is_first_login' => 0, 'password' => md5(md5($params['password'] . Yii::app()->params['userPasswordKey']))]);
        }
        return ['flag' => $flag];
    }

    public function actionGetBrandList()
    {
        $params = $this->makeParams(['userId', 'userToken']);
        $this->checkToken($params['userId'], $params['userToken']);
        $client = new GuzzleHttp\Client();
        $postParams = [
            'form_params' => [
                'api' => 'library.brand',
                'apiVersion' => 'v1',
            ],
        ];
        $res = $client->request('POST', Yii::app()->params['product_library_url'], $postParams);
        if ($res->getStatusCode() !== 200)
            new ApiException(ApiException::CONNECT_ERROR);
        $brandList = json_decode($res->getBody())->data;
        $data = [];
        foreach ($brandList as $key => $value) {
            $data[$key]['brandId'] = $value->id;
            $data[$key]['brandName'] = $value->title;
        }
        return $data;
    }

    public function actionGetCommodityInfo()
    {
        $params = $this->makeParams(['userId', 'userToken', 'modelName']);
        $this->checkToken($params['userId'], $params['userToken']);
        $client = new GuzzleHttp\Client();
        $postParams = [
            'form_params' => [
                'api' => 'library.goods',
                'apiVersion' => 'v1',
                'version' => $params['modelName'],
            ],
        ];
        $res = $client->request('POST', Yii::app()->params['product_library_url'], $postParams);
        if ($res->getStatusCode() !== 200)
            new ApiException(ApiException::CONNECT_ERROR);
        $commodityInfo = json_decode($res->getBody())->data;
        $data = [];
        if (!empty($commodityInfo) && $commodityInfo->brand != '未分类') {
            $data['brandName'] = $commodityInfo->brand;
            $data['commodityName'] = $commodityInfo->name;
            $data['commodityNameEnglish'] = $commodityInfo->english_name;
            $data['unit'] = $commodityInfo->unit;
        }
        return $data;
    }

    public function actionGenerateCode()
    {
        $params = $this->makeParams(['userId', 'userToken']);
        $this->checkToken($params['userId'], $params['userToken']);
        $procurementMainModel = new ProcurementMainModel();
        $procurementMainLine = $procurementMainModel->findBySql("SELECT item_number FROM ps_procurement_main ORDER BY id DESC LIMIT 1");
        $micro = substr(microtime(), 2, 3);
        $day = date("Ymd", time());
        $item_number = $day . "{$micro}001";
        if (!empty($procurementMainLine->item_number) && substr($procurementMainLine->item_number, 0, 8) == $day) {
            $no = (substr($procurementMainLine->item_number, -3, 3) + 1);
            if (strlen($no) < 3) {
                for ($i = 0; 3 - strlen($no); $i++) {
                    $no = '0' . $no;
                }
            }
            $item_number = $day . $micro . $no;
        }
        return ['procurementItemNumber' => $item_number];
    }

    public function actionRunApplication()
    {
        $time = time();
        $params = $this->makeParams(['userId', 'userToken', 'level', 'procurementItemNumber', 'purchaseEndTime', 'purchaseList', 'purchasePrincipal', 'purchaseRemark', 'purchaseTitle']);
        $this->checkToken($params['userId'], $params['userToken']);
        $procurementMainId = Yii::app()->request->getParam('procurementId');
        FrontRoleAccessModel::checkAccess($params['userId'], FrontRoleAccessModel::ACCESS_SITE_SUBMIT_PURCHASE);
        $procurementMainModel = new ProcurementMainModel();
        $tr = $procurementMainModel->getDbConnection()->beginTransaction();
        try {
            if (empty($procurementMainId)) {   //  第一次提交
                $isExistsItemNumber = $procurementMainModel->exists('item_number = :item_number', [':item_number' => $params['procurementItemNumber']]);
                if ($isExistsItemNumber)
                    new ApiException(ApiException::DATA_DUPLICATE);
                if (empty($params['purchaseTitle']))
                    new ApiException(ApiException::COMMON_CODE, '采购项目名称不能为空！');
                $procurementMainModel->title = $params['purchaseTitle'];
                $procurementMainModel->principalId = $params['purchasePrincipal'];
                $procurementMainModel->level = $params['level'];
                $procurementMainModel->item_number = $params['procurementItemNumber'];
                if (!empty($params['purchaseEndTime'])) {
                    $procurementMainModel->is_end_time = 1;
                    $procurementMainModel->end_time = strtotime($params['purchaseEndTime']);
                }
                $procurementMainModel->remark = $params['purchaseRemark'];
                $procurementMainModel->create_time = $time;
                $procurementMainModel->update_time = $time;
                $procurementMainModel->save();
                $procurementMainId = $procurementMainModel->getDbConnection()->getLastInsertID();
                $userActionModel = new UserActionModel();
                $userActionModel->user_id = $params['userId'];
                $userActionModel->procurement_id = $procurementMainId;
                $userActionModel->type = UserActionModel::APPLICATION_SUBMIT;
                $userActionModel->action_time = $time;
                $userActionModel->create_time = $time;
                $userActionModel->save();
                $userActionModel = new UserActionModel();
                $userActionModel->user_id = $params['purchasePrincipal'];
                $userActionModel->procurement_id = $procurementMainId;
                $userActionModel->type = UserActionModel::APPLICATION_PRINCIPAL;
                $userActionModel->action_time = $time;
                $userActionModel->create_time = $time;
                $userActionModel->save();
            } else {    //  从工作台进来
                $param = [
                    'title' => $params['purchaseTitle'],
                    'level' => $params['level'],
                    'remark' => $params['purchaseRemark'],
                    'update_time' => $time,
                ];
                if (!empty($params['purchaseEndTime'])) {
                    $param['is_end_time'] = 1;
                    $param['end_time'] = strtotime($params['purchaseEndTime']);
                }
                ProcurementMainModel::model()->updateByPk($procurementMainId, $param);
            }
            if (!empty($params['purchaseList'])) {
                $purchaseList = json_decode($params['purchaseList'], true);
                CommodityModel::model()->deleteAll('procurement_id=:procurement_id', [
                    ':procurement_id' => $procurementMainId,
                ]);
                foreach ($purchaseList as $value) {
                    if (empty($value['number']))
                        new ApiException(ApiException::COMMON_CODE, '数量不能为空');
                    $commodityModel = new CommodityModel();
                    $commodityModel->procurement_id = $procurementMainId;
                    $commodityModel->brand_title = $value['brandTitle'];
                    $commodityModel->commodity_title = $value['commodityTitle'];
                    $commodityModel->commodity_title_english = $value['commodityTitleEnglish'];
                    $commodityModel->apply_purchase_quantity = $value['number'];
                    $commodityModel->unit = $value['unit'];
                    $commodityModel->model_title = $value['modelTitle'];
                    $commodityModel->is_product_library = $value['isProductLibrary'];
                    $commodityModel->create_time = $time;
                    $commodityModel->update_time = $time;
                    $commodityModel->save();
                }
            }
            $tr->commit();
            $data = [
                'flag' => 1,
                'procurementId' => $procurementMainId,
            ];
            return $data;
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()], GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionRunResearchAuth()
    {
        $params = $this->makeParams(['userId', 'userToken', 'pricingAction', 'procurementId', 'researchAction', 'suggestAction', 'baseINfoAction']);
        $this->checkToken($params['userId'], $params['userToken']);
        FrontRoleAccessModel::checkAccess($params['userId'], FrontRoleAccessModel::ACCESS_SITE_SUBMIT_PURCHASE);
        $time = time();
        $userActionModel = new UserActionModel();
        $tr = $userActionModel->getDbConnection()->beginTransaction();
        try {
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
            $userToDoArr = [];
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
                    $userToDoArr[$v][] = $value->type;
                }
            }
            UserToDoModel::makeMarkByUserIdAndIsForeign($userToDoArr, $params['procurementId']);
            //  修改采购项目状态为调研中
            ProcurementMainModel::changeStatus($params['procurementId'], ProcurementMainModel::STATUS_RESEARCH, $params['userId']);
            UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_SUBMIT_PURCHASE);
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()], GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionGetUserList()
    {
        $params = $this->makeParams(['userId', 'userToken']);
        $this->checkToken($params['userId'], $params['userToken']);
        $userList = UserModel::model()->getDbConnection()->createCommand("SELECT id,nickname FROM ps_front_user")->queryAll();
        return $userList;
    }

    public function actionGetPurchaseCommodity()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId']);
        $this->checkToken($params['userId'], $params['userToken']);
        $data = $this->actionGetPricingIndex();
        $is_research = UserActionModel::model()->exists('user_id = :user_id AND procurement_id = :procurementId 
        AND type=:type AND action_time!=0', [
            ':user_id' => $params['userId'],
            ':procurementId' => $params['procurementId'],
            ':type' => UserActionModel::APPLICATION_RESEARCH,
        ]);
        $is_researcher = UserActionModel::model()->exists('user_id = :user_id AND procurement_id = :procurementId 
        AND type=:type', [
            ':user_id' => $params['userId'],
            ':procurementId' => $params['procurementId'],
            ':type' => UserActionModel::APPLICATION_RESEARCH,
        ]);
        $data['isResearch'] = $is_research ? 1 : 0;
        $data['isResearcher'] = $is_researcher ? 1 : 0;
        $data['isShowPurchaseButton'] = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_BUTTON_RESEARCH_SELECT);
        $interval = UserActionModel::model()->exists('user_id=:user_id AND procurement_id=:procurement_id 
        AND type=:type AND is_foreign=:is_foreign', [
            ':user_id' => $params['userId'],
            ':procurement_id' => $params['procurementId'],
            ':type' => UserActionModel::APPLICATION_RESEARCH,
            ':is_foreign' => UserActionModel::IS_INTERVAL,
        ]);
        $foreign = UserActionModel::model()->exists('user_id=:user_id AND procurement_id=:procurement_id 
        AND type=:type AND is_foreign=:is_foreign', [
            ':user_id' => $params['userId'],
            ':procurement_id' => $params['procurementId'],
            ':type' => UserActionModel::APPLICATION_RESEARCH,
            ':is_foreign' => UserActionModel::IS_FOREIGN,
        ]);
        $researchScope = 0;
        if ($interval)
            $researchScope = 1;
        if ($foreign)
            $researchScope = 2;
        if ($interval && $foreign)
            $researchScope = 3;
        $data['researchScope'] = $researchScope;
        $data['isBaseInfo'] = UserActionModel::checkAccess($params['userId'], $params['procurementId'], '', UserActionModel::APPLICATION_BASE_INFO);
        return $data;
    }

    public function actionRunResearchBaseInfo()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId', 'commodityList']);
        $this->checkToken($params['userId'], $params['userToken']);
        $time = time();
        $commodityList = json_decode($params['commodityList'], true);
        $commodityIdList = [];
        foreach ($commodityList as $value) {
            $commodityIdList[] = $value['commodityId'];
        }
        CommodityModel::checkCommodity($params['procurementId'], $commodityIdList);
        $tr = CommodityModel::model()->getDbConnection()->beginTransaction();
        try {
            foreach ($commodityList as $value) {
                $isRegister = $value['isRegister'];
                if ($isRegister === '')
                    $isRegister = CommodityModel::REGISTER_NOT_SELECT;
                $commodityModel = new CommodityModel();
                $attr = [
                    'commodity_title' => $value['commodityTitle'],
                    'commodity_title_english' => $value['commodityTitleEnglish'],
                    'is_register' => $isRegister,
                    'unit' => $value['unit'],
                    'update_time' => $time,
                ];
                $commodityModel->updateByPk($value['commodityId'], $attr);
            }
            ProcurementMainModel::updateTime($params['procurementId']);
            UserModel::makeMarkForMyPurchase($params['procurementId']);
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()], GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionRunResearchReport()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId', 'commodityList', 'action']);
        $this->checkToken($params['userId'], $params['userToken']);
        $time = time();
        $commodityList = json_decode($params['commodityList'], true);
        $commodityIdList = [];
        foreach ($commodityList as $value) {
            $commodityIdList[] = $value['commodityId'];
        }
        CommodityModel::checkCommodity($params['procurementId'], $commodityIdList);
        $tr = CommodityModel::model()->getDbConnection()->beginTransaction();
        try {
            $param = [
                'action' => $params['action'],
            ];
            if ($params['action']) {
                $param['action_time'] = $time;
                UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
            }
            UserActionModel::model()->updateAll($param,
                'user_id = :user_id AND procurement_id = :procurement_id AND type=:type',
                [
                    ':user_id' => $params['userId'],
                    ':procurement_id' => $params['procurementId'],
                    ':type' => UserActionModel::APPLICATION_RESEARCH,
                ]
            );
            UserResearchModel::model()->deleteAll('user_id=:user_id AND procurement_id=:procurement_id', [
                ':user_id' => $params['userId'],
                ':procurement_id' => $params['procurementId'],
            ]);
            $researchAccessTypeCheckArr = [];
            foreach ($commodityList as $value) {
                $commodityModel = new CommodityModel();
                $isRegister = $value['isRegister'];
                if ($params['action'] && $value['isRegister'] === '')
                    new ApiException(ApiException::IS_REGISTER_NOT_NULL);
                if ($value['isRegister'] === '')
                    $isRegister = CommodityModel::REGISTER_NOT_SELECT;
                $commodityModel->updateByPk($value['commodityId'], [
                    'commodity_title' => $value['commodityTitle'],
                    'commodity_title_english' => $value['commodityTitleEnglish'],
                    'stop_remark' => $value['stopRemark'],
                    'is_register' => $isRegister,
                    'unit' => $value['unit'],
                ]);
                foreach ($value['researchList'] as $v) {
                    $researchAccessTypeCheckArr[] = $v['type'];
                    $userResearchModel = new UserResearchModel();
                    $userResearchModel->user_id = $params['userId'];
                    $userResearchModel->procurement_id = $params['procurementId'];
                    $userResearchModel->commodity_id = $value['commodityId'];
                    $userResearchModel->company = $v['company'];
                    $userResearchModel->tax_price = $v['taxPrice'];
                    $userResearchModel->no_tax_price = $v['noTaxPrice'];
                    $userResearchModel->type = $v['type'];
                    $userResearchModel->research_time = $time;
                    $userResearchModel->save();
                }
            }
            $researchAccessTypeCheckArr = array_unique($researchAccessTypeCheckArr);
            foreach ($researchAccessTypeCheckArr as $researchType) {
                if ($researchType == UserResearchModel::RESEARCH_INTERNAL)
                    $access = FrontRoleAccessModel::ACCESS_ROLE_INTERVAL_PRICE;
                if ($researchType == UserResearchModel::RESEARCH_FOREIGN)
                    $access = FrontRoleAccessModel::ACCESS_ROLE_FOREIGN_PRICE;
                if (!empty($access))
                    FrontRoleAccessModel::checkAccess($params['userId'], $access);
            }
            //  如果所有拥有调研权限的用户都提交了,就修改状态到未定价，并且新增待办事宜
            if (UserActionModel::isResearchOK($params['procurementId'])) {
                ProcurementMainModel::changeStatus($params['procurementId'], ProcurementMainModel::STATUS_UN_PRICING, $params['userId']);
                $pricingUserId = UserActionModel::model()->getDbConnection()->createCommand('SELECT user_id FROM ps_user_action WHERE 
                procurement_id=:procurement_id AND type=:type')->queryScalar([
                    ':procurement_id' => $params['procurementId'],
                    ':type' => UserActionModel::APPLICATION_PRICING,
                ]);
//                UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
                UserToDoModel::makeMark($pricingUserId, $params['procurementId'], UserToDoModel::STATUS_PRICING);
//                $procurementMainData = UserActionModel::model()->find('procurement_id=:procurement_id AND type=:type', [
//                    ':procurement_id' => $params['procurementId'],
//                    ':type' => UserActionModel::APPLICATION_SUBMIT,
//                ]);
                UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_EVERYONE_SUBMIT_RESEARCH);
            }
            UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_SOMEONE_SUBMIT_RESEARCH);
            ProcurementMainModel::updateTime($params['procurementId']);
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()], GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionGetMyResearch()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId']);
        $this->checkToken($params['userId'], $params['userToken']);
        $criteria = new CDbCriteria();
        $criteria->with = ['user_research'];
        $criteria->addCondition('user_research.user_id = :user_id');
        $criteria->addCondition('user_research.procurement_id = :procurement_id');
        $criteria->params[':user_id'] = $params['userId'];
        $criteria->params[':procurement_id'] = $params['procurementId'];
        $res = CommodityModel::model()->findAll($criteria);
        $data = [];
        foreach ($res as $key => $value) {
            $data[$key]['brandTitle'] = $value->brand_title;
            $data[$key]['commodityTitle'] = $value->commodity_title;
            $data[$key]['commodityTitleEnglish'] = $value->commodity_title_english;
            $data[$key]['modelTitle'] = $value->model_title;
            foreach ($value->user_research as $k => $v) {
                switch ($v->type) {
                    case UserResearchModel::RESEARCH_INTERNAL :
                        $data[$key]['internal'][$k]['company'] = $v->company;
                        $data[$key]['internal'][$k]['noTaxPrice'] = $v->no_tax_price;
                        $data[$key]['internal'][$k]['taxPrice'] = $v->tax_price;
                        break;
                    case UserResearchModel::RESEARCH_FOREIGN :
                        $data[$key]['foreign'][$k]['company'] = $v->company;
                        $data[$key]['foreign'][$k]['noTaxPrice'] = $v->no_tax_price;
                        $data[$key]['foreign'][$k]['taxPrice'] = $v->tax_price;
                        break;
                }
            }
        }
        return $data;
    }

    public function actionWithdrawalResearch()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId']);
        $this->checkToken($params['userId'], $params['userToken']);
        //  如果项目已进入下一个状态，则不能撤回
        $procurementMainData = ProcurementMainModel::model()->findByPk($params['procurementId']);
        if ($procurementMainData['status'] != ProcurementMainModel::STATUS_RESEARCH)
            new ApiException(ApiException::CANT_WITHDRAW);
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        try {
            ProcurementMainModel::model()->updateByPk($params['procurementId'], [
                'status' => ProcurementMainModel::STATUS_RESEARCH,
                'update_time' => time(),
            ]);
            //  工作台红点
            UserModel::makeMarkForMyPurchase($params['procurementId']);
            UserActionModel::model()->updateAll(['action_time' => 0], 'user_id = :user_id AND procurement_id = :procurementId 
                AND type=:type', [
                ':user_id' => $params['userId'],
                ':procurementId' => $params['procurementId'],
                ':type' => UserActionModel::APPLICATION_RESEARCH,
            ]);
            $researchList = UserActionModel::model()->findAll('user_id = :user_id AND type = :type AND procurement_id = :procurement_id',
                [
                    ':procurement_id' => $params['procurementId'],
                    ':user_id' => $params['userId'],
                    ':type' => UserActionModel::APPLICATION_RESEARCH,
                ]
            );
            foreach ($researchList as $key => $value) {
//                $status = UserToDoModel::STATUS_INTERVAL . ',' . UserToDoModel::STATUS_FOREIGN;
//                UserToDoModel::model()->deleteAll("user_id=:user_id AND procurement_id=:procurement_id AND status IN({$status})", [
//                    ':user_id' => $params['userId'],
//                    ':procurement_id' => $params['procurementId'],
//                ]);
                UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
                UserToDoModel::makeMark($value->user_id, $params['procurementId'], $value->is_foreign ? UserToDoModel::STATUS_RE_FOREIGN: UserToDoModel::STATUS_RE_INTERVAL);
            }
            UserResearchModel::model()->deleteAll('user_id = :user_id AND procurement_id = :procurement_id',
                [
                    ':user_id' => $params['userId'],
                    ':procurement_id' => $params['procurementId'],
                ]
            );
            UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_WITHDRAWAL_RESEARCH);
            UserActionModel::model()->getDbConnection()->createCommand("INSERT INTO ps_user_action(user_id,procurement_id,type,create_time) VALUES (:user_id,:procurement_id,:type,:create_time) ON DUPLICATE KEY UPDATE procurement_id=VALUES(procurement_id)")->execute([
                ':user_id' => $params['userId'],
                ':procurement_id' => $params['procurementId'],
                ':type' => UserActionModel::APPLICATION_WITHDRAWAL_RESEARCH,
                ':create_time' => time(),
            ]);
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()], GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionIsPurchase()
    {
        $params = $this->makeParams(['userId', 'userToken', 'commodityId', 'isPurchase']);
        $this->checkToken($params['userId'], $params['userToken']);
        $stopRemark = Yii::app()->request->getParam('stopRemark');
        $param = ['is_purchase' => $params['isPurchase']];
        if (!empty($stopRemark))
            $param = array_merge(['stop_remark' => $stopRemark], $param);
        CommodityModel::model()->updateByPk($params['commodityId'], $param);
        $res = CommodityModel::model()->findByPk($params['commodityId']);
        $data['brandTitle'] = $res->brand_title;
        $data['commodityId'] = $res->id;
        $data['commodityTitle'] = $res->commodity_title;
        $data['commodityTitleEnglish'] = $res->commodity_title_english;
        $data['apply_purchase_quantity'] = $res->apply_purchase_quantity;
        $data['isRegister'] = $res->is_register;
        $data['modelTitle'] = $res->model_title;
        $data['unit'] = $res->unit;
        return $data;
    }

    public function actionGetPricingIndex()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId']);
        $this->checkToken($params['userId'], $params['userToken']);
        $criteria = new CDbCriteria();
        $criteria->with = ['commodity', 'user_action'];
        $info = ProcurementMainModel::model()->findByPk($params['procurementId'], $criteria);
        $commodityList = [];
        $isSavePurchase = 0;
        foreach ($info->commodity as $key => $value) {
            $commodityList[$key]['brandTitle'] = $value->brand_title;
            $commodityList[$key]['commodityId'] = $value->id;
            $commodityList[$key]['commodityTitle'] = $value->commodity_title;
            $commodityList[$key]['commodityTitleEnglish'] = $value->commodity_title_english;
            $commodityList[$key]['modelTitle'] = $value->model_title;
            $commodityList[$key]['isRegister'] = $value->is_register;
            $commodityList[$key]['isPurchase'] = $value->is_purchase;
            $commodityList[$key]['stopRemark'] = $value->stop_remark;
            $commodityList[$key]['isProductLibrary'] = $value->is_product_library;
            $commodityList[$key]['unit'] = $value->unit;
            $commodityList[$key]['applyPurchaseQuantity'] = $value->apply_purchase_quantity;
            $commodityList[$key]['researchList'] = UserResearchModel::getResearchList($value->id, $params['userId']);
            $commodityList[$key]['purchaseList'] = CommodityPurchaseModel::getPurchaseList($value->id);
            if (!empty($commodityList[$key]['purchaseList'])) {
                $isSavePurchase = 1;
            }
        }
        $userActionList = ProcurementMainModel::getUserActionList($info->user_action);
        $baseInfoUserId = UserActionModel::model()->find('procurement_id=:procurement_id AND 
        type=:type', [
            ':procurement_id' => $params['procurementId'],
            ':type' => UserActionModel::APPLICATION_BASE_INFO,
        ]);
        $isExistResearchData = UserResearchModel::model()->exists('procurement_id=:procurement_id', [
            ':procurement_id' => $params['procurementId'],
        ]);
        $data['isExistResearchData'] = $isExistResearchData;
        $data['baseInfoUserId'] = !empty($baseInfoUserId) ? $baseInfoUserId->user_id : 0;
        $data['stopRemark'] = $info->stop_remark;
        $data['stopUser'] = UserModel::getNicknameByPK($info->stop_id);
        $data['stopTime'] = date("Y-m-d H:i:s", $info->stop_time);
        $data['isSavePurchase'] = $isSavePurchase;
        $data['level'] = ProcurementMainModel::$levelArr[$info->level];
        $data['purchaseEndTime'] = $info->is_end_time ? date('Y-m-d H:i:s', $info->end_time) : '--';
        $data['purchaseId'] = $info->id;
        $data['purchaseItemNumber'] = $info->item_number;
        $data['purchasePrincipal'] = $userActionList[UserActionModel::APPLICATION_PRINCIPAL]['nickname'];
        $data['purchaseSubmitter'] = $userActionList[UserActionModel::APPLICATION_SUBMIT]['nickname'];
        $data['purchaseSubmitterTime'] = date("Y-m-d H:i:s", $info->submit_time);
        $data['purchaseRemark'] = $info->remark;
        $data['purchaseResultDesc'] = $info->result_description;
        $data['purchaseTitle'] = $info->title;
        $data['researchSubmitter'] = isset($userActionList[UserActionModel::APPLICATION_RESEARCH]['nickname'])
            ? $userActionList[UserActionModel::APPLICATION_RESEARCH]['nickname'] : '';
        $researchActionTime = UserActionModel::model()->findBySql('SELECT action_time FROM ps_user_action WHERE 
            procurement_id=:procurement_id AND action=' . UserActionModel::ACTION_SUBMIT . " AND type=:type ORDER BY 
            action_time DESC", [
            ':procurement_id' => $params['procurementId'],
            ':type' => UserActionModel::APPLICATION_RESEARCH,
        ]);
        $data['researchTime'] = !empty($researchActionTime) ? date('Y-m-d H:i:s', $researchActionTime->action_time) : '--';
        $data['pricingPeople'] = isset($userActionList[UserActionModel::APPLICATION_PRICING]['nickname'])
            ? $userActionList[UserActionModel::APPLICATION_PRICING]['nickname'] : '';
        $data['pricingTime'] = isset($userActionList[UserActionModel::APPLICATION_PRICING]['actionTime'])
            ? $userActionList[UserActionModel::APPLICATION_PRICING]['actionTime'] : '';
        $data['suggestPeople'] = isset($userActionList[UserActionModel::APPLICATION_SUGGEST]['nickname'])
            ? $userActionList[UserActionModel::APPLICATION_SUGGEST]['nickname'] : '';
        $data['suggestTime'] = isset($userActionList[UserActionModel::APPLICATION_SUGGEST]['actionTime'])
            ? $userActionList[UserActionModel::APPLICATION_SUGGEST]['actionTime'] : '';
        $data['purchasePeople'] = isset($userActionList[UserActionModel::APPLICATION_PURCHASE]['nickname'])
            ? $userActionList[UserActionModel::APPLICATION_SUGGEST]['nickname'] : '';
        $data['purchaseTime'] = isset($userActionList[UserActionModel::APPLICATION_PURCHASE]['actionTime'])
            ? $userActionList[UserActionModel::APPLICATION_SUGGEST]['actionTime'] : '';
        $data['commodityList'] = $commodityList;
        $data['status'] = $info->status;
        $data['isAccessButtonNotPurchase'] = FrontRoleAccessModel::isExists($params['userId'],
            FrontRoleAccessModel::ACCESS_BUTTON_NOT_PURCHASE);
        $data['isShowDetailButton'] = UserActionModel::checkAccess($params['userId'], $params['procurementId'],
            $info->status);
        $data['isBaseInfo'] = UserActionModel::checkAccess($params['userId'], $params['procurementId'],
            '',
            UserActionModel::APPLICATION_BASE_INFO
        );
        return $data;
    }

    public function actionGetResearch()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId']);
        $this->checkToken($params['userId'], $params['userToken']);
        $criteria = new CDbCriteria();
        $criteria->with = ['user_research', 'commodity_purchase', 'commodity_target_price'];
//        $criteria->addCondition('user_research.procurement_id = :procurement_id');
        $criteria->addCondition('t.procurement_id = :procurement_id');
        $criteria->params[':procurement_id'] = $params['procurementId'];
        $commodityList = CommodityModel::model()->findAll($criteria);
        $data = [];
        $isShowForeign = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_COLUMN_FOREIGN_PRICE);
        $isShowInterval = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_COLUMN_INTERVAL_PRICE);
        $isShowTargetPrice = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_COLUMN_TARGET_PRICING);
        $isShowApplyNumber = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_COLUMN_APPLY_NUMBER);
        $isShowSuggestNumber = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_COLUMN_SUGGEST_NUMBER);
        $isShowRealNumber = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_COLUMN_REAL_NUMBER);
        $isShowPurchaseUnit = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_COLUMN_PURCHASE_UNIT);
        $isShowRealPrice = FrontRoleAccessModel::isExists($params['userId'], FrontRoleAccessModel::ACCESS_COLUMN_REAL_PRICE);
        foreach ($commodityList as $key=>$value) {
            $data[$key]['brandTitle'] = $value->brand_title;
            $data[$key]['commodityId'] = $value->id;
            $data[$key]['commodityTitle'] = $value->commodity_title;
            $data[$key]['commodityTitleEnglish'] = $value->commodity_title_english;
            $data[$key]['isRegister'] = $value->is_register;
            $data[$key]['isPurchase'] = $value->is_purchase;
            $data[$key]['modelTitle'] = $value->model_title;
            $data[$key]['number'] = '--';
            $data[$key]['unit'] = '--';
            $data[$key]['suggestQuantity'] = '--';
            $data[$key]['targetTaxPrice'] = '--';
            $data[$key]['targetNoTaxPrice'] = '--';
            if ($isShowApplyNumber)
                $data[$key]['number'] = $value->apply_purchase_quantity;
            if ($isShowPurchaseUnit)
                $data[$key]['unit'] = $value->unit;
            if ($isShowSuggestNumber)
                $data[$key]['suggestQuantity'] = $value->suggest_purchase_quantity;
            $data[$key]['researchList'] = [];
            if (!empty($value->user_research) && ($isShowForeign || $isShowInterval)) {
                $i = 0;
                foreach ($value->user_research as $k=>$v) {
                    if ($isShowForeign && $v->type == UserActionModel::IS_FOREIGN) {
                        $nickname = UserModel::getNicknameByPK($v->user_id);
                        $data[$key]['researchList'][$i]['company'] = $v->company;
                        $data[$key]['researchList'][$i]['noTaxPrice'] = !empty($v->no_tax_price) ? $v->no_tax_price : '';
                        $data[$key]['researchList'][$i]['taxPrice'] = !empty($v->tax_price) ? $v->tax_price : '';
                        $data[$key]['researchList'][$i]['nickname'] = $nickname;
                        $data[$key]['researchList'][$i]['type'] = $v->type;
                        $i++;
                    }
                    if ($isShowInterval && $v->type == UserActionModel::IS_INTERVAL) {
                        $nickname = UserModel::getNicknameByPK($v->user_id);
                        $data[$key]['researchList'][$i]['company'] = $v->company;
                        $data[$key]['researchList'][$i]['noTaxPrice'] = !empty($v->no_tax_price) ? $v->no_tax_price : '';
                        $data[$key]['researchList'][$i]['taxPrice'] = !empty($v->tax_price) ? $v->tax_price : '';
                        $data[$key]['researchList'][$i]['nickname'] = $nickname;
                        $data[$key]['researchList'][$i]['type'] = $v->type;
                        $i++;
                    }
                }
            }
            $data[$key]['realList'] = [];
            if (!empty($value->commodity_purchase)) {
                foreach ($value->commodity_purchase as $k=>$v) {
                    $data[$key]['realList'][$k]['realCompany'] = $v->company;
                    $data[$key]['realList'][$k]['realNumber'] = '--';
                    $data[$key]['realList'][$k]['realPrice'] = '--';
                    if ($isShowRealNumber)
                        $data[$key]['realList'][$k]['realNumber'] = $v->number;
                    if ($isShowRealPrice)
                        $data[$key]['realList'][$k]['realPrice'] = $v->subtotal - $v->freight != 0?sprintf('%.2f', ($v->subtotal - $v->freight) / $v->number) : 0;
                    $data[$key]['realList'][$k]['isTax'] = $v->is_tax;
                    $data[$key]['realList'][$k]['subtotal'] = $v->subtotal;
                }
            }
            if ($isShowTargetPrice) {
                $data[$key]['targetTaxPrice'] = isset($value->commodity_target_price->taxPrice) ?
                    sprintf('%.2f', $value->commodity_target_price->taxPrice) : '';
                $data[$key]['targetNoTaxPrice'] = isset($value->commodity_target_price->noTaxPrice) ?
                    sprintf('%.2f', $value->commodity_target_price->noTaxPrice) : '';
            }
        }
        return $data;
    }

    public function actionGetResearchTitle()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId']);
        $this->checkToken($params['userId'], $params['userToken']);
        $res = UserResearchModel::getResearchTitle($params['procurementId']);
        $researchTitle = [];
        foreach ($res as $key => $value) {
            $nickname = UserModel::getNicknameByPK($value['user_id']);
            $researchTitle[$key]['nickname'] = $nickname;
            $researchTitle[$key]['title'] = $value['type']? '国外价格(' . $nickname . ')': '国内价格(' . $nickname . ')';
            $researchTitle[$key]['type'] = $value['type'];
        }
        $data['researchTitle'] = $researchTitle;
        return $data;
    }

    public function actionReResearch()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId', 'researchAction', 'baseINfoAction']);
        $this->checkToken($params['userId'], $params['userToken']);
        ProcurementMainModel::checkStatus($params['procurementId'], ProcurementMainModel::STATUS_UN_PRICING);
        $time = time();
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        try {
            ProcurementMainModel::model()->updateByPk($params['procurementId'], [
                'status' => ProcurementMainModel::STATUS_RESEARCH,
                'update_time' => $time,
            ]);
            //  工作台红点
            UserModel::makeMarkForMyPurchase($params['procurementId']);
            UserResearchModel::model()->deleteAll('user_id = :user_id AND procurement_id = :procurement_id', [
                ':user_id' => $params['userId'],
                ':procurement_id' => $params['procurementId'],
            ]);
            UserActionModel::model()->deleteAll("procurement_id = :procurement_id AND type IN(" .
                UserActionModel::APPLICATION_RESEARCH . ',' . UserActionModel::APPLICATION_BASE_INFO . ")", [
                ':procurement_id' => $params['procurementId'],
            ]);
            $researchAction = json_decode($params['researchAction']);
            $userToDoArr = [];
            $userActionModel = new UserActionModel();
            $userActionModel->type = UserActionModel::APPLICATION_BASE_INFO;
            $userActionModel->user_id = $params['baseINfoAction'];
            $userActionModel->procurement_id = $params['procurementId'];
            $userActionModel->create_time = $time;
            $userActionModel->save();
            UserToDoModel::makeMark($params['baseINfoAction'], $params['procurementId'], UserToDoModel::STATUS_INFO);
            foreach ($researchAction as $value) {
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
                    $userToDoArr[$v][] = $value->type;
                }
            }
            UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
            UserToDoModel::makeMarkByUserIdAndIsForeign($userToDoArr, $params['procurementId']);
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()],
                GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionRunTargetPrice()
    {
        $params = $this->makeParams(['userId', 'userToken', 'action', 'procurementId', 'commodityList']);
        $this->checkToken($params['userId'], $params['userToken']);
        ProcurementMainModel::checkStatus($params['procurementId'], ProcurementMainModel::STATUS_UN_PRICING);
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        try {
            CommodityTargetPriceModel::saveTargetPrice(json_decode($params['commodityList']));
            ProcurementMainModel::updateTime($params['procurementId']);
            if ($params['action']) {
                ProcurementMainModel::changeStatus($params['procurementId'], ProcurementMainModel::STATUS_PRICING, $params['userId']);
                UserActionModel::model()->updateAll(['action_time' => time()], 'procurement_id = :procurement_id 
            AND type = :type', [
                    ':procurement_id' => $params['procurementId'],
                    ':type' => UserActionModel::APPLICATION_PRICING
                ]);
                $suggestUserId = UserActionModel::model()->getDbConnection()->createCommand('SELECT user_id FROM ps_user_action WHERE 
                procurement_id=:procurement_id AND type=:type')->queryScalar([
                    ':procurement_id' => $params['procurementId'],
                    ':type' => UserActionModel::APPLICATION_SUGGEST,
                ]);
                UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
                UserToDoModel::makeMark($suggestUserId, $params['procurementId'], UserToDoModel::STATUS_SUGGEST);
                UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_FINISH_PRICING);
            }
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e){
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()],
                GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionRunPurchase()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId', 'commodityList', 'purchaseId']);
        $this->checkToken($params['userId'], $params['userToken']);
        $time = time();
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        try {
            ProcurementMainModel::checkStatus($params['procurementId'], ProcurementMainModel::STATUS_PRICING);
            ProcurementMainModel::model()->updateByPk($params['procurementId'], [
                'status' => ProcurementMainModel::STATUS_PURCHASE,
                'update_time' => $time,
            ]);
            //  工作台红点
            UserModel::makeMarkForMyPurchase($params['procurementId']);
            $commodityList = json_decode($params['commodityList']);
            foreach ($commodityList as $value) {
                CommodityModel::model()->updateByPk($value->commodityId,
                    [
                        'is_purchase' => $value->isPurchase,
                        'stop_remark' => isset($value->stopRemark) ? $value->stopRemark : '',
                        'suggest_purchase_quantity' => $value->suggestQuantity,
                        'update_time' => $time,
                    ]
                );
            }
            UserActionModel::model()->updateAll(['action_time' => $time], 'procurement_id = :procurement_id 
            AND type = :type',
                [
                ':procurement_id' => $params['procurementId'],
                ':type' => UserActionModel::APPLICATION_SUGGEST
                ]
            );
            //  增加采购执行者
            $userActionModel = new UserActionModel();
            $userActionModel->user_id = $params['purchaseId'];
            $userActionModel->procurement_id = $params['procurementId'];
            $userActionModel->type = UserActionModel::APPLICATION_PURCHASE;
            $userActionModel->create_time = $time;
            $userActionModel->save();
            UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
            UserToDoModel::makeMark($params['purchaseId'], $params['procurementId'], UserToDoModel::STATUS_PURCHASE);
            UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_FINISH_APPROVAL);
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()],
                GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionRePricing()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId', 'remark']);
        $this->checkToken($params['userId'], $params['userToken']);
        ProcurementMainModel::checkStatus($params['procurementId'], ProcurementMainModel::STATUS_PRICING);
        $time = time();
        $commodityIdList = ProcurementMainModel::model()->getDbConnection()
            ->createCommand('SELECT id FROM ps_commodity WHERE procurement_id = :procurement_id')
            ->queryColumn([':procurement_id' => $params['procurementId']]);
        if (empty($commodityIdList))
            new ApiException(ApiException::DATA_EXCEPTION);
        $commodityIdStr = implode(',', $commodityIdList);
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        try {
            CommodityTargetPriceModel::model()->deleteAll("commodity_id IN({$commodityIdStr})");
            ProcurementMainModel::model()->updateByPk($params['procurementId'], [
                'status' => ProcurementMainModel::STATUS_UN_PRICING,
                'update_time' => $time,
            ]);
            //  工作台红点
            UserModel::makeMarkForMyPurchase($params['procurementId']);
            UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_NO_PRICING);
            UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
            $pricingUserId = UserActionModel::model()->find('type=:type AND procurement_id=:procurement_id', [
                ':type' => UserActionModel::APPLICATION_PRICING,
                ':procurement_id' => $params['procurementId'],
            ])->user_id;
            UserToDoModel::makeMark($pricingUserId, $params['procurementId'], UserToDoModel::STATUS_PRICING);
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()],
                GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionGetPurchaseReport()
    {
        return $this->actionGetPricingIndex();
    }

    public function actionRunPurchaseReport()
    {
        $params = $this->makeParams(['userId', 'userToken', 'action', 'procurementId', 'commodityList', 'resultDescription']);
        $this->checkToken($params['userId'], $params['userToken']);
        ProcurementMainModel::checkStatus($params['procurementId'], ProcurementMainModel::STATUS_PURCHASE);
        $commodityList = json_decode($params['commodityList']);
        $commodityIdList = [];
        $time = time();
        foreach ($commodityList as $value) {
            $commodityIdList[] = $value->commodityId;
        }
        CommodityModel::checkCommodity($params['procurementId'], $commodityIdList);
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        try {
            foreach ($commodityList as $key => $value) {
                CommodityPurchaseModel::model()->deleteAll('commodity_id=:commodity_id', [':commodity_id' =>$value->commodityId]);
                foreach ($value->purchaseList as $k => $v) {
                    $commodityPurchaseModel = new CommodityPurchaseModel();
                    $commodityPurchaseModel->commodity_id = $value->commodityId;
                    $commodityPurchaseModel->company = $v->company;
                    $commodityPurchaseModel->subtotal = $v->subtotal;
                    $commodityPurchaseModel->freight = $v->freight;
                    $commodityPurchaseModel->number = $v->number;
                    $commodityPurchaseModel->is_tax = $v->isTax;
                    $commodityPurchaseModel->create_time = $time;
                    $commodityPurchaseModel->update_time = $time;
                    $commodityPurchaseModel->save();
                }
            }
            $procurementParams = [
                'result_description' => $params['resultDescription'],
                'update_time' => $time,
            ];
            if ($params['action']) {
//                $flag = ProcurementMainModel::GetIfReload();
//                if ($flag) {    //  是否进入“是否继续”
//                    UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_NO_FINISH_PURCHASE);
//                    $status = ProcurementMainModel::STATUS_APPROVAL;
//                } else {
                    UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_FINISH_PURCHASE);
//                    $status = ProcurementMainModel::STATUS_FINISH;
//                }
                UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
                UserActionModel::model()->updateAll(['action_time' => $time], 'user_id = :user_id AND 
                procurement_id = :procurement_id AND type = :type', [
                    ':user_id' => $params['userId'],
                    ':procurement_id' => $params['procurementId'],
                    ':type' => UserActionModel::APPLICATION_PURCHASE,
                ]);
                //  工作台红点
                UserModel::makeMarkForMyPurchase($params['procurementId']);
                $procurementParams['status'] = ProcurementMainModel::STATUS_FINISH;
            }
            ProcurementMainModel::model()->updateByPk($params['procurementId'], $procurementParams);
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()],
                GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionReResearchEnd()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId']);
        $this->checkToken($params['userId'], $params['userToken']);
        //  检测项目状态
        ProcurementMainModel::checkStatus($params['procurementId'], ProcurementMainModel::STATUS_APPROVAL);
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        $time = time();
        try {
            UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
            $criteria = new CDbCriteria();
            $criteria->with = ['commodity', 'user_action'];
            $old = ProcurementMainModel::model()->findByPk($params['procurementId'], $criteria);
            $procurementMainModel = new ProcurementMainModel();
            $procurementMainModel->status = ProcurementMainModel::STATUS_UN_SUBMITTED;
            $procurementMainModel->title = $old->title;
            $procurementMainModel->level = $old->level;
            $procurementMainModel->item_number = $this->actionGenerateCode()['procurementItemNumber'];
            $procurementMainModel->is_end_time = $old->is_end_time;
            $procurementMainModel->end_time = $old->end_time;
            $procurementMainModel->remark = $old->remark;
            $procurementMainModel->create_time = $time;
            $procurementMainModel->update_time = $time;
            $procurementMainModel->submit_time = $time;
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
            if (!empty($old->user_action)) {
                foreach ($old->user_action as $key => $value) {
                    if ($value->type == UserActionModel::APPLICATION_SUBMIT || $value->type == UserActionModel::APPLICATION_PRINCIPAL) {
                        $userActionModel = new UserActionModel();
                        $userActionModel->type = $value->type;
                        $userActionModel->user_id = $value->user_id;
                        $userActionModel->procurement_id = $procurementMainId;
                        $userActionModel->create_time = $time;
                        $userActionModel->save();
                    }
                }
            }
            $tr->commit();
            return ['flag' => 1];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()],
                GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }

    public function actionPurchaseFinish()
    {
        $params = $this->makeParams(['userId', 'userToken', 'procurementId']);
        $this->checkToken($params['userId'], $params['userToken']);
        $time = time();
        $tr = ProcurementMainModel::model()->getDbConnection()->beginTransaction();
        try {
            ProcurementMainModel::checkStatus($params['procurementId'], ProcurementMainModel::STATUS_APPROVAL);
            $flag = ProcurementMainModel::model()->updateByPk($params['procurementId'], [
                'status' => ProcurementMainModel::STATUS_FINISH,
                'update_time' => $time,
            ]);
            //  工作台红点
            UserModel::makeMarkForMyPurchase($params['procurementId']);
            UserNewsModel::addNews($params['userId'], $params['procurementId'], UserNewsModel::STATUS_APPROVAL_NO);
            UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
            $tr->commit();
            return ['flag' => $flag];
        } catch (Exception $e) {
            $tr->rollback();
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", [$e->getMessage()],
                GuoBangLog::ERROR);
            new ApiException(ApiException::TRANSACTION_ROLLBACK);
        }
    }
}