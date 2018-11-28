<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-29
 * Time: 上午10:03
 */
class ProjectBehavior extends CActiveRecordBehavior
{

    /**
     * @param $event
     */
    public function afterSave($event)
    {
        if ($this->Owner->isNewRecord) {
            $data = $this->Owner->getAttributes();
            $db = Yii::app()->db;
            $view_count = rand(1, 50);
            $sql = "update  {{project}} set view_count=$view_count where id=".$data['id'];
            $db->createCommand($sql)->query();
        }
    }
}