<ul class="page-breadcrumb">
    <?php
    $option = null;
	$right = '';
    foreach($this->crumbs as $k=>$crumb) {
       echo "<li>";
        if($k==0):
            echo '<i class="fa fa-home home-icon"></i>';
            endif;
			echo $right;
        if(isset($crumb['url'])) {
            $option = isset($crumb['option']) ? $crumb['option'] : null;
            echo CHtml::link($crumb['name'], $crumb['url'],$option);
        } else {
            echo $crumb['name'];
        }
        echo "</li>";
		$right = '<i class="fa fa-angle-right"></i>';
    }
    ?>
</ul>