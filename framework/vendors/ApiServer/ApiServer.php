<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/4/8
 * Time: 15:04
 */

class ApiServer
{
    /**
     * @param array $params
     * @return array
     */
    public static function makeParams($params = [])
    {
        if (!empty($params)) {
            $data = [];
            foreach ($params as $value) {
                $data[$value] = Yii::app()->request->getParam($value);
                if (!isset($data[$value]))
                    new ApiException(ApiException::PARAMS_LACK);
            }
            return $data;
        }
    }

    public static function checkToken($userId, $userToken)
    {
        $token = Yii::app()->redis->executeCommand("HGET", ["userToken:userId={$userId}", 'userToken']);
        if ($userToken == $token)
            return true;
        new ApiException(ApiException::USER_TOKEN_ERROR);
    }
}