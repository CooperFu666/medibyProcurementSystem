<?php

/**
 * This is the model class for table "ps_user_news".
 *
 * The followings are the available columns in table 'ps_user_news':
 * @property string $id
 * @property string $user_id
 * @property string $procurement_id
 * @property integer $status
 * @property string $create_time
 */
class UserNewsModel extends CActiveRecord
{
    const STATUS_SUBMIT_PURCHASE = 1;               //有人提交了采购申请
    const STATUS_SOMEONE_SUBMIT_RESEARCH = 2;       //有人提交了调研报告
    const STATUS_EVERYONE_SUBMIT_RESEARCH = 3;      //所有人提交了调研报告
    const STATUS_WITHDRAWAL_RESEARCH = 4;           //有人调研报告被打回
    const STATUS_END_RESEARCH = 5;                  //项目被终止（调研阶段被否决）
    const STATUS_FINISH_PRICING = 6;                //项目定价完成
    const STATUS_END_PRICING = 7;                   //项目被终止（定价阶段被否决）
    const STATUS_FINISH_APPROVAL = 8;               //采购审批完成
    const STATUS_NO_PRICING = 9;                    //项目定价被否决
    const STATUS_END_APPROVAL = 10;                 //项目被终止（采购审批阶段被否决）
    const STATUS_FINISH_PURCHASE = 11;              //提交了采购报告（全部采购完成）
    const STATUS_NO_FINISH_PURCHASE = 12;           //提交了采购报告（未完成采购）
    const STATUS_APPROVAL_NO = 13;                  //已审批是否继续（否）
    const STATUS_APPROVAL_YES = 14;                 //已审批是否继续（是）
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_user_news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'numerical', 'integerOnly'=>true),
			array('user_id, procurement_id, create_time', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, procurement_id, status, create_time', 'safe', 'on'=>'search'),
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
            'procurement_main' => array(self::BELONGS_TO, 'ProcurementMainModel', 'procurement_id', 'on'=>'t.procurement_id = procurement_main.id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => '用户id',
			'procurement_id' => '关联procurement_main',
			'status' => '1有人提交了采购申请2有人提交了调研报告3所有人提交了调研报告4有人调研报告被打回5项目被终止（调研阶段被否决）6项目定价完成7项目被终止（定价阶段被否决）8采购审批完成9项目定价被否决10项目被终止（采购审批阶段被否决）11提交了采购报告（全部采购完成）12提交了采购报告（未完成采购）13已审批是否继续（否）14已审批是否继续（是）',
			'create_time' => '创建时间',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('procurement_id',$this->procurement_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserNewsModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function addNews($userId, $procurementId, $status)
    {
        $userNewsModel = new UserNewsModel();
        $userNewsModel->user_id = $userId;
        $userNewsModel->procurement_id = $procurementId;
        $userNewsModel->status = $status;
        $userNewsModel->create_time = time();
        $userNewsModel->save();
    }

    public static function getStr($submitUser, $status, $procurementMainTitle, $procurementMainItemNumber, $procurementMainId)
    {
        switch ($status) {
            case UserNewsModel::STATUS_SUBMIT_PURCHASE :
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）,该项目进入调研阶段";
            case UserNewsModel::STATUS_SOMEONE_SUBMIT_RESEARCH :
                $isForeignStr = self::getIsForeignStr($procurementMainId);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）的调研报告（{$isForeignStr}）";
            case UserNewsModel::STATUS_EVERYONE_SUBMIT_RESEARCH :
                $userStr = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_RESEARCH);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）的调研报告已提交完毕（调研者：{$userStr})，该项目进入定价阶段";
            case UserNewsModel::STATUS_WITHDRAWAL_RESEARCH :
                $userStr = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_WITHDRAWAL_RESEARCH);
                $isForeignStr = self::getIsForeignStr($procurementMainId);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）的调研报告（{$isForeignStr}）被打回（打回者：{$userStr}）";
            case UserNewsModel::STATUS_END_RESEARCH :
                $userStr = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_END_RESEARCH);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）已在调研阶段被终止（否决者：{$userStr}），该项目已关闭";
            case UserNewsModel::STATUS_FINISH_PRICING :
                $userStr = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_PRICING);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）已完成定价（定价执行者：{$userStr}），该项目进入采购审批阶段";
            case UserNewsModel::STATUS_END_PRICING :
                $userStr = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_END_PRICING);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）已在定价阶段被终止（否决者：{$userStr}）），该项目已关闭";
            case UserNewsModel::STATUS_FINISH_APPROVAL :
                $userStr = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_SUGGEST);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）已通过审批（审批执行者：{$userStr}），该项目进入采购阶段";
            case UserNewsModel::STATUS_NO_PRICING :
                $userStr = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_END_SUGGEST);
                $userPricing = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_PRICING);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）定价（定价者：{$userPricing}）被否决（否决者：{$userStr}），该项目重新进入定价阶段";
            case UserNewsModel::STATUS_END_APPROVAL :
                $userStr = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_END_SUGGEST);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）已在采购审批阶段被终止（否决者：{$userStr}）），该项目已关闭";
            case UserNewsModel::STATUS_FINISH_PURCHASE :
                $userStr = self::getUserStr($procurementMainId, UserActionModel::APPLICATION_PURCHASE);
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）已完成了采购（采购执行者：{$userStr}），该项目已完结";
            case UserNewsModel::STATUS_NO_FINISH_PURCHASE :
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）部分采购不足建议采购量，该项目进入是否继续审批阶段";
            case UserNewsModel::STATUS_APPROVAL_NO :
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）不再进行二次采购，该项目已完结";
            case UserNewsModel::STATUS_APPROVAL_YES :
                return $str = "{$submitUser}提交了采购申请{$procurementMainTitle}（{$procurementMainItemNumber}）需要进行二次采购，该项目进入重新调研阶段";
            default :
                return $str = '';
        }
    }

    public static function getIsForeignStr($procurementMainId)
    {
        $isForeignList = UserActionModel::model()->getDbConnection()->createCommand('SELECT is_foreign FROM ps_user_action 
                WHERE procurement_id=:procurement_id AND type=:type')->queryColumn([
            ':procurement_id' => $procurementMainId,
            ':type' => $procurementMainId,
        ]);
        $isForeignStr = '';
        if (in_array(UserActionModel::IS_INTERVAL, $isForeignList))
            $isForeignArr[] = '国内';
        if (in_array(UserActionModel::IS_FOREIGN, $isForeignList))
            $isForeignArr[] = '国外';
        if (!empty($isForeignArr))
            $isForeignStr = implode('与', $isForeignArr);
        return $isForeignStr;
    }

    public static function getUserStr($procurementMainId, $userActionType)
    {
        $criteria = new CDbCriteria();
        $criteria->with = ['front_user'];
        $criteria->condition = 't.procurement_id=:procurement_id AND type=:type';
        $criteria->params = [':procurement_id' => $procurementMainId, ':type' => $userActionType];
        $res = UserActionModel::model()->findAll($criteria);
        $userArr = [];
        foreach ($res as $value) {
            if (isset($value->front_user->nickname))
                $userArr[] = $value->front_user->nickname;
        }
        $userArr = array_unique($userArr);
        return implode('，', $userArr);
    }

    public static function getIndexNews($userId)
    {
        $params = [];
        $isGlobalNews = FrontRoleAccessModel::model()->exists('role_id=:role_id AND access=:access', [
            ':role_id' => UserModel::getRoleIdByPK($userId),
            ':access' => FrontRoleAccessModel::ACCESS_GLOBAL_NEWS,
        ]);
        $sql = "SELECT *,t.status,t.create_time,ppm.status AS procurement_status FROM ps_user_news t LEFT JOIN ps_procurement_main ppm ON t.procurement_id=ppm.id ";
        if (!$isGlobalNews) {
            $sql .= "WHERE user_id=:user_id ";
            $params[':user_id'] = $userId;
        }
        $sql .= ' ORDER BY t.create_time DESC';
        $sql .= ' LIMIT 10';
        return self::model()->getDbConnection()->createCommand($sql)->queryAll(true, $params);
    }
}
