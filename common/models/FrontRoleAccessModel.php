<?php

/**
 * This is the model class for table "ps_front_role_access".
 *
 * The followings are the available columns in table 'ps_front_role_access':
 * @property integer $id
 * @property string $role_id
 * @property integer $access
 */
class FrontRoleAccessModel extends CActiveRecord
{
    const ACCESS_SITE_SUBMIT_PURCHASE = 1;      //在系统内有进入并填写采购申请的权限
    const ACCESS_BUTTON_NOT_PURCHASE = 2;       //在所有阶段拥有不执行该项目按钮的权限
    const ACCESS_ROLE_PRINCIPAL = 3;            //在提交调研申请时，可作为项目负责人的可选角色
    const ACCESS_ROLE_PRICING = 4;              //在提交调研申请时，可作为定价执行者的可选角色
    const ACCESS_ROLE_SUGGEST = 5;              //在提交调研申请时，可作为采购量建议者的可选角色
    const ACCESS_ROLE_APPROVAL = 6;             //当实际采购量小于建议采购量时，审批的权限
    const ACCESS_ROLE_PURCHASE = 7;             //在执行采购审批时，可作为采购执行者的可选角色
    const ACCESS_SITE_DOC = 8;                  //在系统内有进入采购档案的权限
    const ACCESS_BUTTON_RELOAD = 9;             //在已关闭/已完成的项目重新启动的权限
    const ACCESS_GLOBAL_NEWS = 10;              //能看到自所有人的项目的动态，如没有该权限则只能看到自己提交的采购申请的动态
    const ACCESS_ROLE_BASE_INFO = 11;           //能填写基础信息的人(中文名，英文名，产品规格，是否有图)
    const ACCESS_ROLE_INTERVAL_PRICE = 12;      //同上
    const ACCESS_ROLE_FOREIGN_PRICE = 13;       //同上
    const ACCESS_BUTTON_RESEARCH_SELECT = 14;   //是否显示，采购/不采购按钮
    const ACCESS_COLUMN_INTERVAL_PRICE = 15;    //该字段如没有采购档案权限，则不能勾选，勾选后能在项目详细里看到相对应的字段，项目详细里的：品牌，型号，中文名，英文名，规格/包装，注册证在拥有采购档案权限后默认是可查看字段
    const ACCESS_COLUMN_FOREIGN_PRICE = 16;     //同上
    const ACCESS_COLUMN_TARGET_PRICING = 17;    //是否显示目标价格
    const ACCESS_COLUMN_APPLY_NUMBER = 18;      //是否显示申请数量
    const ACCESS_COLUMN_SUGGEST_NUMBER = 19;    //是否显示建议数量
    const ACCESS_COLUMN_REAL_NUMBER = 20;       //是否显示实际数量
    const ACCESS_COLUMN_PURCHASE_UNIT = 21;     //是否显示采购单位
    const ACCESS_COLUMN_REAL_PRICE = 22;        //是否显示实际价格

    public $role_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_front_role_access';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_id', 'required'),
			array('access', 'numerical', 'integerOnly'=>true),
			array('role_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, role_id, access', 'safe', 'on'=>'search'),
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
            'front_role' => array(self::BELONGS_TO, 'FrontRoleModel', 'role_id', 'on'=>'t.role_id = front_role.id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'role_id' => 'Role',
			'access' => '1提交采购申请,2不执行项目,3是否可作为项目负责人,4定价执行者,5采购量建议者,6是否继续审批,7采购执行者,8采购档案,9重启采购项目,10全局动态,11产品基础信息,12国内价格,13国外价格,14选择调研/不调研,15国内价格,16国外价格,17定价,18申请采购量,19建议采购量,20实际采购量,21采购单位,22实际采购价',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('role_id',$this->role_id,true);
		$criteria->compare('access',$this->access);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrontRoleAccessModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function isExists($userId, $access)
    {
        $userInfo = UserModel::model()->findByPk($userId);
        $flag = 0;
        $isExists = FrontRoleAccessModel::model()->exists('role_id=:role_id AND access=:access', [
            ':role_id' => $userInfo->role_id,
            ':access' => $access,
        ]);
        if ($isExists)
            $flag = 1;
        return $flag;
    }

    public static function checkAccess($userId, $access)
    {
        $userInfo = UserModel::model()->findByPk($userId);
        $isExists = FrontRoleAccessModel::model()->exists('role_id=:role_id AND access=:access', [
            ':role_id' => $userInfo->role_id,
            ':access' => $access,
        ]);
        if (!$isExists)
            new ApiException(ApiException::PERMISSION_DENIED);
    }
}
