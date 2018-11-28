<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	public $title;
	/**
	 * @var string the meta keywords of the current page.
	 */
	public $keywords = '';
	/**
	 * @var string the meta description of the current page.
	 */
	public $description = '';

	public $config;//配置信息

	public $cssFiles=array();
	public $jsFiles=array();

	public $codeTime=0;//验证码倒计时时间
    public $cms;


	public function beforeAction($action)
	{
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		$this->codeTime = SmsLogModel::getOverTimes();
		if (parent::beforeAction($action)) {
			$this->config = CHtml::listData(ConfModel::model()->findAll(), 'name', 'value');
		}
		$this->cms = ArticleCategoryModel::model()->with('article')->findAll();
		return true;
	}
	/**
	 * Register Meta tags to page
	 * @param string $view the view that has been rendered
	 * @param string $output the rendering result of the view. Note that this parameter is passed
	 */
	public function afterRender($view, &$output)
	{
		$cs = Yii::app()->clientScript;
		if (!empty($this->cssFiles)) {
			foreach($this->cssFiles as $cssFile){
				if(strpos($cssFile,'/static')!==false)
					$cs->registerCssFile(Yii::app()->baseUrl.$cssFile);
				else
					$cs->registerCssFile(Yii::app()->baseUrl.'/static/css/'.$cssFile);
			}
		}
		if (!empty($this->jsFiles)) {
			foreach($this->jsFiles as $jsFile){
				if(strpos($jsFile,'/static')!==false)
					$cs->registerScriptFile(Yii::app()->baseUrl.$jsFile);
				else
					$cs->registerScriptFile(Yii::app()->baseUrl.'/static/js/'.$jsFile);
			}
		}
		if (!empty($this->keywords)) {
			$cs->registerMetaTag($this->keywords, 'keywords');
		}
		if (!empty($this->description)) {
			$cs->registerMetaTag($this->description, 'description');
		}
		//$cs->registerScript('baiduAnalyticsAccount', "var _hmt = _hmt || [];(function() {  var hm = document.createElement('script');  hm.src = '//hm.baidu.com/hm.js?bcea0338456427068f9fa9fb0ac27ce9';  var s = document.getElementsByTagName('script')[0];   s.parentNode.insertBefore(hm, s);})();", CClientScript::POS_END);
		$uri = strtolower($this->id.'_'.$this->getAction()->id);
		$message = $this->getGetTips($uri);
		if($message){
			$output .= $this->renderPartial('//showTips',array('content'=>$message['content'],'type'=>$message['type'],'jumpUrl'=>$message['jumpUrl']),true);
		}

	}

	/**
	 * 消息提示设置
	 * @param $type 1:成功 2:失败
	 * @param $uri
	 * @param $content
	 */
	protected function setShowTips($type,$uri,$content,$jumpUrl='')
	{
		if($type)
			Yii::app()->user->setFlash(strtolower($uri).'_messageTips', serialize(array('type' => $type, 'content' => $content,'uri'=>$uri,'jumpUrl'=>$jumpUrl)));
	}

	/**
	 * 获取消息数据
	 * @return mixed
	 */
	private function getGetTips($uri){
		$message= '';
		if(Yii::app()->user->hasFlash($uri.'_messageTips'))
			$message= @unserialize(Yii::app()->user->getFlash($uri.'_messageTips'));
		return $message;
	}
	/*
 	* 验证登录
 	*/
	public function VerifyLogin(){
		if (Yii::app()->user->isGuest){
			$this->redirect(array("site/login"));
		}
	}
}