
    <ul>
        <div class="sidebar_sub">
            <p>订单管理</p>
            <?php
            foreach ($this->order as $obj){
                $class = "";
                if($obj['active'])$class = "class='siedbar_hover'";
                echo "<a href='".Yii::app()->createUrl("/{$obj['controllers']}/{$obj['action']}/")."' $class>{$obj['title']}</a>";
            }
            ?>
        </div>
        <div class="sidebar_sub">
            <p>账户中心</p>
            <?php
            foreach ($this->user as $obj){
                $class = "";
                if($obj['active'])$class = "class='siedbar_hover'";
                echo "<a href='".Yii::app()->createUrl("/{$obj['controllers']}/{$obj['action']}/")."' $class>{$obj['title']}</a>";
            }
            ?>
        </div>
        <div class="sidebar_sub">
            <p>消息中心</p>
            <?php
            foreach ($this->notify as $obj){
                $class = "";
                if($obj['active'])$class = "class='siedbar_hover'";
                echo "<a href='".Yii::app()->createUrl("/{$obj['controllers']}/{$obj['action']}/")."' $class>{$obj['title']}</a>";
            }
            ?>
        </div>
    </ul>
