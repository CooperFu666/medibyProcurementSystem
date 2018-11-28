<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/4/8
 * Time: 15:04
 */
class ApiException
{
    const COMMON_CODE = 100;
    const CLASS_FUNCTION_NOT_FOUND = 101;
    const PARAMS_LACK = 102;
    const API_ERROR = 103;
    const VERIFY_CODE_ERROR = 104;
    const USER_OR_PASSWORD_ERROR = 105;
    const USER_TOKEN_ERROR = 106;
    const TRANSACTION_ROLLBACK = 107;
    const PARAMS_ERROR = 108;
    const CONNECT_ERROR = 109;
    const CANT_WITHDRAW = 110;
    const STATUS_EXCEPTION = 111;
    const DATA_EXCEPTION = 112;
    const DATA_DUPLICATE = 113;
    const PERMISSION_DENIED = 114;
    const IS_REGISTER_NOT_NULL = 115;

    public function __construct($alias, $message = '')
    {
        try {
            throw new Exception(json_encode(['code' => $alias,'data' => '', 'message' => $this->createException($alias)]));
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            GuoBangLog::setMonolog('ApiServer', "mysql rollback!", ["code:{$error->code},message:{$error->message}"], GuoBangLog::ERROR);
            $data['code'] = $alias;
            $data['data'] = '';
            $data['message'] = $this->createException($alias);
            if (!empty($message))
                $data['message'] = $message;
            exit(json_encode($data));
        }
    }

    public function createException($alias)
    {
        $exception = [
            '100' => '通用报错',
            '101' => '类或方法未找到',
            '102' => '参数不完整',
            '103' => '没有对应的接口',
            '104' => '验证码错误',
            '105' => '用户或密码错误',
            '106' => 'userToken验证失败',
            '107' => '事务回滚',
            '108' => '参数不合法',
            '109' => '连接失败',
            '110' => '不能撤回',
            '111' => '状态异常',
            '112' => '数据异常',
            '113' => '数据重复',
            '114' => '没有权限的访问',
            '115' => '请选择是否有图片',
        ];
        return $exception[$alias];
    }
}