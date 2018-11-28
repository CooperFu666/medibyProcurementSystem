<?php

/**
 * Created by PhpStorm.
 * User: druphliu@gamil.com
 * Date: 15-7-29
 * Time: 下午10:40
 */
class Category extends CWidget
{
    public $category;

    private static function GetCategory()
    {
        $categoryList = CategoryModel::getCategoryHasGoods();
        return $categoryList;
    }


    public function run()
    {
        $this->category = self::GetCategory();
        $this->render('category');
    }
} 