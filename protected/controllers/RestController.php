<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/4/8
 * Time: 13:35
 */

/**
 * 解决跨域，支持主流浏览器，IE8+
 */
header('Access-Control-Allow-Origin:*');//允许所有来源访问
header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

class RestController extends RestfulController
{

}
