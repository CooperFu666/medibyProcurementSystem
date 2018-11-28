
    <span>
    <?php
    $option = null;
    foreach($this->crumbs as $k=>$crumb) {
        if($k!=0){
           echo " > ";
        }
        if(isset($crumb['url'])) {
            $option = isset($crumb['option']) ? $crumb['option'] : null;
            echo CHtml::link($crumb['name'], $crumb['url'],$option);
        } else {
            echo $crumb['name'];
        }
    }
    ?>
    </span>
