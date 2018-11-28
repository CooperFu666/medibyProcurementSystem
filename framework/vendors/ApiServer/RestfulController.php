<?php
class RestfulController extends CController
{
    public function __construct()
    {
        $apiConfig = require_once(__ROOT__ . '/protected/config/api.php');
        $api = @$_REQUEST['api'];
        $apiVersion = @$_REQUEST['apiVersion'];
        if (!isset($api) || !isset($apiVersion)) {
            new ApiException(ApiException::PARAMS_LACK);
        }
        if (!isset($apiConfig[$apiVersion][$api])) {
            new ApiException(ApiException::API_ERROR);
        }
        $path = $apiConfig[$apiVersion][$api];
        $arr = explode('/', $path);
        $className = ucfirst($arr[0]);
        $classPath = __ROOT__. '/protected/service/' . $className . '.php';
        $function = 'action' . ucfirst($arr[1]);
        if (!file_exists($classPath))
            new ApiException(ApiException::CLASS_FUNCTION_NOT_FOUND);
        require_once($classPath);
        if (!method_exists($className, $function))
            new ApiException(ApiException::CLASS_FUNCTION_NOT_FOUND);
        $data = (new $className)->$function();
        $res = ['code' => 200, 'data' => $data, 'message' => ''];
        echo CJSON::encode($res);
    }
    public function run($actionId)
    {

    }
}