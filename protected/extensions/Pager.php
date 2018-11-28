<?php
class Pager extends CLinkPager
{
    public $edge_count = 2;
    public $ellipse_text = '...';
    public $maxButtonCount=8;
    protected function createPageButton($label,$page,$class,$hidden,$selected,$ellipse=false)
    {
        $aClass = '';
        if($selected){
            return '<span class="active">'.$label.'</span>';
        }
        if($hidden){
           return "";
        }
        $arr = explode(' ',$class);

        if(in_array( 'previous',$arr)){
            $aClass = 'previous1';
            return CHtml::link('上一页',$this->createPageUrl($page),array('id'=>'previous1','data-page'=>$page,'class'=>$aClass));
            //return '<li class="'.$class.'">'.CHtml::link($label,$this->createPageUrl($page),array('id'=>'previous1','data-page'=>$page,'class'=>$aClass)).'</li>';
        }elseif(in_array( 'next',$arr)){
            $aClass = 'next1';
            return CHtml::link('下一页',$this->createPageUrl($page),array('id'=>'next1','data-page'=>$page,'class'=>$aClass));
            //return '<li class="'.$class.'">'.CHtml::link($label,$this->createPageUrl($page),array('id'=>'next1','data-page'=>$page,'class'=>$aClass)).'</li>';
        }else{
            return CHtml::link($label,$this->createPageUrl($page),array('data-page'=>$page,'class'=>$aClass));
        }

    }

    public function registerClientScript()
    {
        if($this->cssFile!==false)
            self::registerCssFile($this->cssFile);
    }
    public static function registerCssFile($url=null)
    {

    }
    public function init()
    {
        if($this->nextPageLabel===null)
            $this->nextPageLabel=Yii::t('yii',' ');
        if($this->prevPageLabel===null)
            $this->prevPageLabel=Yii::t('yii',' ');

        if(!isset($this->htmlOptions['id']))
            $this->htmlOptions['id']=$this->getId();
        if(!isset($this->htmlOptions['class']))
            $this->htmlOptions['class']='M-box11';
    }

    public function run()
    {
        $this->registerClientScript();
        $buttons=$this->createPageButtons();
        if(empty($buttons))
        {
            return;
        }
        echo $this->header;
        echo CHtml::tag('div',array_merge($this->htmlOptions),implode("\n",$buttons));
        echo '<div style="clear: both"></div>';
        echo $this->footer;
    }


    protected function createPageButtons()
    {
        if(($pageCount=$this->getPageCount())<=1){
            return array();
        }

        list($beginPage,$endPage)=$this->getPageRange();
        $currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $buttons=array();

        // first page
        //$buttons[]=$this->createPageButton($this->firstPageLabel,0,$this->firstPageCssClass,true,$currentPage<=0,false);

        // prev page
        if(($page=$currentPage-1)<0){
            $page=0;
        }
        $buttons[]=$this->createPageButton($this->prevPageLabel,$page,$this->previousPageCssClass,$currentPage<=0,false);

        // internal pages
        $interval = $this->getInterval();

        // 产生起始点
        if($interval[0] > 0 && $this->edge_count > 0){
            $end = min($this->edge_count,$interval[0]);
            for($i=0;$i<$end;$i++) {
                $buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);
            }
            if($this->edge_count < $interval[0] && $this->ellipse_text){
                $buttons[]=$this->createPageButton($this->ellipse_text,0,$this->internalPageCssClass,false,false,true);
            }
        }

        // 产生内部链接
        for($i=$interval[0];$i<$interval[1];$i++){
            $buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);
        }

        // 产生结束点
        if($interval[1] < $pageCount && $this->edge_count > 0){
            if($pageCount - $this->edge_count > $interval[1] && $this->ellipse_text){
                $buttons[]=$this->createPageButton($this->ellipse_text,0,$this->internalPageCssClass,false,false,true);
            }
            $begin = max($pageCount - $this->edge_count,$interval[1]);
            for($i=$begin;$i<$pageCount;$i++) {
                $buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);
            }
        }

        // next page
        if(($page=$currentPage+1)>=$pageCount-1){
            $page=$pageCount-1;
        }
        $buttons[]=$this->createPageButton($this->nextPageLabel,$page,$this->nextPageCssClass,$currentPage>=$pageCount-1,false);

        // last page
        //$buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,$this->lastPageCssClass,true,$currentPage>=$pageCount-1,false);

        return $buttons;
    }
    protected function getInterval()
    {
        $max_count = $this->maxButtonCount;
        $half = ceil($max_count/2);
        $page_count = $this->pageCount;
        $current_page = $this->currentPage;
        $upper_limit = $page_count - $max_count;

        $start = $current_page > $half ? max(min($current_page - $half + 1,$upper_limit),0) : 0;
        $end = $current_page > $half ? min($current_page + $half,$page_count) : min($max_count,$page_count);
        return array($start,$end);
    }

}