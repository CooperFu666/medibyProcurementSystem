<div class="navsub">
    <?php foreach($this->category as $key=>$category){?>
        <div class="navsub_nav" mg="category<?php echo $key ?>">
            <dl class="navsub_navtext">
                <dt>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/static/icon/<?php echo $category['icon'] ?>">
                    <a href="<?php echo Yii::app()->createUrl('/goods/list/cat/'.$category['id'])?>"><?php echo $category['title'] ?></a>
                </dt>
            </dl>
            <?php if(count($category['child'])>0){?>
            <div class="navsub_content" mg2="category<?php echo $key ?>">
                <?php foreach($category['child'] as $subCategory2){ ?>
                    <dl>
                        <dt>
                            <a href="<?php echo Yii::app()->createUrl('/goods/list/cat/'.$subCategory2['id'])?>">
                                <?php echo $subCategory2['title'] ?>
                            </a>
                        </dt>
                        <?php if(count($subCategory2['child'])>0){?>
                        <dd>
                            <?php foreach($subCategory2['child'] as $subCategory3){ ?>
                            <a href="<?php echo Yii::app()->createUrl('/goods/list/cat/'.$subCategory3['id'])?>"><?php echo $subCategory3['title'] ?></a>
                            <?php }?>
                        </dd>
                        <?php }?>
                    </dl>
                <?php }?>
            </div>
            <?php }?>
        </div>
    <?php }?>
</div>