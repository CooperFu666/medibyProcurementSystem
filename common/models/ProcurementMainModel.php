<?php

/**
 * This is the model class for table "ps_procurement_main".
 *
 * The followings are the available columns in table 'ps_procurement_main':
 * @property string $id
 * @property string $title
 * @property string $item_number
 * @property string $is_end_time
 * @property string $end_time
 * @property string $remark
 * @property string $result_description
 * @property integer $level
 * @property string $stop_remark
 * @property string $repricing_remark
 * @property integer $research_scope
 * @property string $status
 * @property string $create_time
 * @property string $update_time
 */
class ProcurementMainModel extends CActiveRecord
{
    const STATUS_UN_SUBMITTED = 0; //  未提交
    const STATUS_RESEARCH = 1;     //  调研中
    const STATUS_UN_PRICING = 2;   //  未定价
    const STATUS_PRICING = 3;      //  已定价
    const STATUS_PURCHASE = 4;     //  采购中
    const STATUS_APPROVAL = 5;     //  审批中(是否继续)
    const STATUS_FINISH = 6;       //  已完成
    const STATUS_CLOSE = 7;        //  已关闭

    const LEVEL_HIGH = 1;
    const LEVEL_MIDDLE = 2;
    const LEVEL_LOW = 3;

    const DOC_MY_PURCHASE = 1;      //  我的采购申请
    const DOC_DOC = 2;           //  采购档案
    const DOC_MY_RESEARCH = 3;      //  我的调研报告
    const DOC_MY_REPORT = 4;        //  我的采购报告

    const CLOSE = 0;
    const FINISH = 1;
    public static $levelArr = [
        self::LEVEL_HIGH => '高',
        self::LEVEL_MIDDLE => '中',
        self::LEVEL_LOW => '低',
    ];

    public static $statusArr = [
        self::STATUS_UN_SUBMITTED => '未提交', //  未提交
        self::STATUS_RESEARCH => '调研中',     //  调研中
        self::STATUS_UN_PRICING => '未定价',   //  未定价
        self::STATUS_PRICING => '已定价',      //  已定价
        self::STATUS_PURCHASE => '采购中',     //  采购中
        self::STATUS_APPROVAL => '审批中',     //  审批中(是否继续)
        self::STATUS_FINISH => '已完成',       //  已完成
        self::STATUS_CLOSE => '已关闭',        //  已关闭
    ];
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_procurement_main';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('level, research_scope', 'numerical', 'integerOnly'=>true),
			array('title, remark, result_description, stop_remark, repricing_remark', 'length', 'max'=>255),
			array('item_number', 'length', 'max'=>20),
			array('is_end_time, status', 'length', 'max'=>1),
			array('end_time, create_time, update_time', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, item_number, is_end_time, end_time, remark, result_description, level, stop_remark, repricing_remark, research_scope, status, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'commodity' => array(self::HAS_MANY, 'CommodityModel', 'procurement_id', 'on'=>'t.id = commodity.procurement_id'),
            'user_action' => array(self::HAS_MANY, 'UserActionModel', 'procurement_id', 'on'=>'t.id = user_action.procurement_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => '采购项目名称',
			'item_number' => '项目编号',
			'is_end_time' => '是否限制结束时间',
			'end_time' => '采购截止时间',
			'remark' => '采购原由/备注',
			'result_description' => '采购结果说明',
			'level' => '1高2中3低',
			'stop_remark' => '不执行原因',
			'repricing_remark' => '重新定价',
			'research_scope' => '1仅国内2仅国外3全球',
			'status' => '0未提交1调研中2未定价3已定价4采购中5已完结6已关闭',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('item_number',$this->item_number,true);
		$criteria->compare('is_end_time',$this->is_end_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('result_description',$this->result_description,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('stop_remark',$this->stop_remark,true);
		$criteria->compare('repricing_remark',$this->repricing_remark,true);
		$criteria->compare('research_scope',$this->research_scope);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProcurementMainModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getInfo($ProcurementMainId)
    {
        $criteria = new CDbCriteria();
        $criteria->with = ['commodity', 'user_action'];
        $criteria->addCondition('');
        return self::model()->findByPk($ProcurementMainId, $criteria);
    }

    public static function getIndexInfo()
    {
        $params = ApiServer::makeParams(['userId', 'userToken', 'procurementId']);
        ApiServer::checkToken($params['userId'], $params['userToken']);
        $info = ProcurementMainModel::getInfo($params['procurementId']);
        $data = [];
        if (!empty($info)) {
            $data = [
                'procurementSubmitterTime' => date("Y-m-d H:i:s", $info->submit_time),
                'procurementSubmitterUser' => UserModel::getNicknameByPK($info->user_action[0]->user_id),
                'remark' => $info->remark,
                'level' => self::$levelArr[$info->level],
            ];
            if (!empty($info->commodity)) {
                $commodityLit = [];
                foreach ($info->commodity as $key => $value) {
                    $commodityLit[$key]['brandTitle'] = $value->brand_title;
                    $commodityLit[$key]['commodityId'] = $value->id;
                    $commodityLit[$key]['commodityTitle'] = $value->commodity_title;
                    $commodityLit[$key]['commodityTitleEnglish'] = $value->commodity_title_english;
                    $commodityLit[$key]['isRegister'] = $value->is_register;
                    $commodityLit[$key]['unit'] = $value->unit;
                    $commodityLit[$key]['modelTitle'] = $value->model_title;
                }
                $data['commodityList'] = $commodityLit;
            }
        }
        return $data;
    }

    public static function changeStatus($procurementId, $status, $userId)
    {
        $time = time();
        $attr = ['status' => $status, 'update_time' => $time];
        switch ($status) {
            case self::STATUS_RESEARCH :
                $attr['submit_time'] = $time;
                break;
        }
        UserModel::makeMarkForMyPurchase($procurementId);
        return self::model()->updateByPk($procurementId, $attr);
    }

    public static function getUserActionList($userActionList)
    {
        $arr = [];
        foreach ($userActionList as &$value) {
            $nickname = UserModel::getNicknameByPK($value['user_id']);
            $arr[$value['type']]['nickname'][] = $nickname;
            $arr[$value['type']]['actionTime'] = date("Y-m-d H:i:s", $value['action_time']);
        }
        if (isset($arr[UserActionModel::APPLICATION_RESEARCH]['nickname']))
            $arr[UserActionModel::APPLICATION_RESEARCH]['nickname'] = array_unique($arr[UserActionModel::APPLICATION_RESEARCH]['nickname']);
        foreach ($arr as &$value) {
            $value['nickname'] = implode(',', $value['nickname']);
        }
        return $arr;
    }

    public static function checkStatus($procurementId, $status)
    {
        $verify = ProcurementMainModel::model()->exists('id = :procurement_id AND status = :status', [
            ':procurement_id' => $procurementId,
            ':status' => $status,
        ]);
        if (!$verify)
            new ApiException(ApiException::STATUS_EXCEPTION);
    }

    public static function GetIfReload()
    {
        $params = ApiServer::makeParams(['userId', 'userToken', 'procurementId']);
        ApiServer::checkToken($params['userId'], $params['userToken']);
        //  组装
        $commodityList = CommodityModel::model()->findAll('procurement_id=:procurement_id AND 
        is_purchase=:is_purchase', [
            ':procurement_id' => $params['procurementId'],
            ':is_purchase' => CommodityModel::PURCHASE,
        ]);
        $commodityArr = $commodityRealArr = [];
        $flag = 0;
        foreach ($commodityList as $key => $value) {
            $commodityArr[$value->id] = $value->suggest_purchase_quantity;
        }
        $commodityIdStr = implode(',', array_keys($commodityArr));
        if (!empty($commodityIdStr)) {
            $commodityPurchaseList = CommodityPurchaseModel::model()->findAll("commodity_id IN({$commodityIdStr})");
            foreach ($commodityPurchaseList as $key => $value) {
                if (empty($commodityRealArr[$value->commodity_id]))
                    $commodityRealArr[$value->commodity_id] = 0;
                $commodityRealArr[$value->commodity_id] += $value->number;
            }
        }
        //  比对
        foreach ($commodityArr as $commodityId => $suggestNumber) {
            foreach ($commodityRealArr as $commodityRealId => $realNumber) {
                if ($commodityId === $commodityRealId && $realNumber < $suggestNumber) {
                    $flag = 1;
                    break;
                }
            }
        }
        //  如果需要审批
        if ($flag) {
            UserToDoModel::deleteMark($params['userId'], $params['procurementId']);
            $approvalUserIdList = FrontRoleAccessModel::model()->getDbConnection()->createCommand('SELECT role_id FROM ps_front_role_access 
            WHERE access=:access')->queryColumn([':access' => FrontRoleAccessModel::ACCESS_ROLE_APPROVAL]);
            foreach ($approvalUserIdList as $approvalUserId) {
                UserToDoModel::makeMark($approvalUserId, $params['procurementId'], UserToDoModel::STATUS_APPROVAL);
            }
        }
        return $flag;
    }

    public static function getUserActionType($procurementStatus)
    {
        switch ($procurementStatus) {
            case self::STATUS_UN_SUBMITTED :
                $userActionType = UserActionModel::APPLICATION_SUBMIT;
                break;
            case self::STATUS_RESEARCH :
                $userActionType = UserActionModel::APPLICATION_RESEARCH;
                break;
            case self::STATUS_UN_PRICING :
                $userActionType = UserActionModel::APPLICATION_PRICING;
                break;
            case self::STATUS_PRICING :
                $userActionType = UserActionModel::APPLICATION_SUGGEST;
                break;
            case self::STATUS_PURCHASE :
                $userActionType = UserActionModel::APPLICATION_PURCHASE;
                break;
            case self::STATUS_APPROVAL :
                $userActionType = UserActionModel::APPLICATION_APPROVAL;
                break;
            default :
                $userActionType = '';
        }
        return $userActionType;
    }

    public static function getCurrentUserByPK($procurementId)
    {
        $status = self::model()->getDbConnection()->createCommand('SELECT status FROM ps_procurement_main WHERE 
        id=:id')->queryScalar([':id' => $procurementId]);
        $userActionType = self::getUserActionType($status);
        $userStr = '--';
        if (!empty($userActionType) && $status != self::STATUS_APPROVAL) {
            $userStr = UserNewsModel::getUserStr($procurementId, $userActionType);
            if ($status == ProcurementMainModel::STATUS_RESEARCH) {
                $user_id = UserActionModel::model()->find('procurement_id=:procurement_id AND type=:type', [
                    ':procurement_id' => $procurementId,
                    ':type' => UserActionModel::APPLICATION_BASE_INFO,
                ])->user_id;
                $userStr .= '，' . UserModel::getNicknameByPK($user_id);
                $userStr = implode('，', array_unique(explode('，', $userStr)));
            }
        }
        if ($status == self::STATUS_APPROVAL) {
            $userList = FrontRoleAccessModel::model()->getDbConnection()->createCommand("SELECT pfu.nickname FROM ps_front_role_access t LEFT 
            JOIN ps_front_user pfu ON t.role_id=pfu.id WHERE t.access=:access")->queryColumn([':access' => FrontRoleAccessModel::ACCESS_ROLE_APPROVAL]);
            $userStr = implode(',', $userList);
        }
        return $userStr;
    }

    public static function getApplicationTime($procurementId)
    {
        $actionTime = UserActionModel::model()->getDbConnection()->createCommand('SELECT action_time FROM ps_user_action WHERE 
        procurement_id=:id AND type=:type')->queryScalar([':id' => $procurementId, ':type' => UserActionModel::APPLICATION_SUBMIT]);
        if ($actionTime) {
            $actionTime = date('Y-m-d H:i:s', $actionTime);
        } else {
            $actionTime = '--';
        }
        return $actionTime;
    }

    public static function getPrincipal($procurementId)
    {
        $userId = UserActionModel::model()->getDbConnection()->createCommand('SELECT user_id FROM ps_user_action WHERE 
        procurement_id=:procurement_id AND type=:type')->queryScalar([':procurement_id' => $procurementId, ':type' => UserActionModel::APPLICATION_PRINCIPAL]);
        $principalUser = UserModel::getNicknameByPK($userId);
        if (empty($principalUser))
            $principalUser = '';
        return $principalUser;
    }

    public static function getIndexDoc($isCount = 0, $searchParams, $offset, $limit, $userId = 0, $userActionTypeArr = [], $isAlreadyAction = false)
    {
        $select = "*, t.id AS procurementId";
        if ($isCount)
            $select = 't.id';
        $sql = "SELECT {$select} FROM ps_procurement_main AS t LEFT JOIN ps_user_action AS c ON t.id=c.procurement_id 
        LEFT JOIN ps_commodity AS pc ON t.id=pc.procurement_id ";
        $where = 'WHERE ';
        $params = [];
        if ($userId) {
            $where .= 'c.user_id=:user_id';
            $params[':user_id'] = $userId;
        }
        if (!empty($userActionTypeArr) && is_array($userActionTypeArr)) {
            $userActionTypeStr = implode(',', $userActionTypeArr);
            if (strlen($where) > 6) {
                $where .= ' AND c.type IN (:type)';
            } else {
                $where .= 'c.type IN (:type)';
            }
            $params[':type'] = $userActionTypeStr;
        }
        if ($isAlreadyAction) {
            if (strlen($where) > 6) {
                $where .= ' AND c.action_time != 0';
            } else {
                $where .= 'c.action_time != 0';
            }
        }
        if (isset($searchParams->status)) {
            $status = implode(',', $searchParams->status);
            if (strlen($where) > 6) {
                $where .= " AND t.status IN({$status})";
            } else {
                $where .= " t.status IN({$status})";
            }
        }
        if (isset($searchParams->principalId) && !empty($searchParams->principalId)) {
            if (strlen($where) > 6) {
                $where .= " AND t.principalId=:principalId ";
            } else {
                $where .= " t.principalId=:principalId ";
            }
            $params[':principalId'] = $searchParams->principalId;
        }
        if (isset($searchParams->bySome)) {
            $bySome = $searchParams->bySome;
            if (isset($bySome->procurementTitle)) {
                if (strlen($where) > 6) {
                    $where .= " AND t.title LIKE '{$bySome->procurementTitle}%'";
                } else {
                    $where .= " t.title LIKE '{$bySome->procurementTitle}%'";
                }
            }
            if (isset($bySome->model)) {
                if (strlen($where) > 6) {
                    $where .= " AND pc.model_title LIKE '{$bySome->model}%'";
                } else {
                    $where .= " pc.model_title LIKE '{$bySome->model}%'";
                }
            }
            if (isset($bySome->brand)) {
                if (strlen($where) > 6) {
                    $where .= " AND pc.brand_title LIKE '{$bySome->brand}%'";
                } else {
                    $where .= " pc.brand_title LIKE '{$bySome->brand}%'";
                }
            }
            if (isset($bySome->commodityTitle)) {
                if (strlen($where) > 6) {
                    $where .= " AND pc.commodity_title LIKE '{$bySome->commodityTitle}%'";
                } else {
                    $where .= " pc.commodity_title LIKE '{$bySome->commodityTitle}%'";
                }
            }
        }
        if (isset($searchParams->searchTime)) {
            $startTime = strtotime($searchParams->searchTime->timeArr[0]);
            $endTime = strtotime($searchParams->searchTime->timeArr[1]);
            if ($searchParams->searchTime->type == 1) { //  1申请时间2更新时间
                $timeStr = 'submit_time';
            }else {
                $timeStr = 'update_time';
            }
            if (strlen($where) > 6) {
                $where .= " AND t.{$timeStr}>=:start_time AND t.{$timeStr}<=:end_time";
            } else {
                $where .= " t.{$timeStr}>=:start_time AND t.{$timeStr}<=:end_time";
            }
            $params[':start_time'] = $startTime;
            $params[':end_time'] = $endTime;
        }
        if (strlen($where) > 6)
            $sql .= $where;
        $sql .= ' GROUP BY t.id';
        if (!$isCount) {
            if (isset($searchParams->sort)) {
                $sort = $searchParams->sort;
                switch ($sort) {
                    case 1 :    //1按更新时间近至远
                        $sql .= ' ORDER BY t.update_time DESC';
                        break;
                    case 2 :    //2按更新时间远至近
                        $sql .= ' ORDER BY t.update_time ASC';
                        break;
                    case 3 :    //3按申请时间近至远
                        $sql .= ' ORDER BY t.submit_time DESC';
                        break;
                    case 4 :    //4按申请时间远至近
                        $sql .= ' ORDER BY t.submit_time ASC';
                        break;
                }
            }
//            if ($offset) {
//                $sql .= ' OFFSET ' . $offset;
//            }
            if ($limit) {
                $sql .= " LIMIT {$offset},{$limit}";
            }
//            var_dump($params);echo $sql;die;
            return self::model()->getDbConnection()->createCommand($sql)->queryAll(true, $params);
        }
        return count(self::model()->getDbConnection()->createCommand($sql)->queryAll(true, $params));
    }

    public static function getIndexProjects($userId)
    {
        $params = [];
        $sql = "SELECT *,t.id AS procurementId FROM ps_procurement_main t LEFT JOIN ps_user_action pua ON t.id=pua.procurement_id ";
        $roleId = UserModel::model()->findByPk($userId)->role_id;
        $isGlobalNews = FrontRoleAccessModel::model()->exists('role_id=:role_id AND access=:access', [
            ':role_id' => $roleId,
            ':access' => FrontRoleAccessModel::ACCESS_GLOBAL_NEWS,
        ]);
        if (!$isGlobalNews) {
            $sql .= "WHERE pua.user_id=:user_id";
            $params = [':user_id' => $userId];
        }
        $sql .= " GROUP BY t.id ORDER BY t.update_time DESC LIMIT 10";
        return self::model()->getDbConnection()->createCommand($sql)->queryAll(true, $params);
    }

    public static function getStatusByPK($procurementId)
    {
        $sql = "SELECT status FROM ps_procurement_main WHERE id=:id";
        return self::model()->getDbConnection()->createCommand($sql)->queryScalar(['id' => $procurementId]);
    }

    public static function updateTime($procurementId)
    {
        return self::model()->updateByPk($procurementId, ['update_time' => time()]);
    }
}
