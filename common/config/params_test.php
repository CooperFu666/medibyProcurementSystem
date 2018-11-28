<?php
return [
    'adminEmail'=>'webmaster@example.com',
    'expiredTime'=>30,                                          //单位：分钟,短信校验超时时间(包括前端和后端登录)
    'wap_home_url'=>'http://8.8.8.12:93/wap',
    'base_url'=>'http://8.8.8.12:93',
    'base_backend_url'=>'http://8.8.8.12:93/backend',
    'userPasswordKey'=>'yijiaxiaojia',                          //加密key:md5('yijiaxiaojia')
    'expiresCode'=>600,                                         //access code 有效时间10分钟
    'expiresToken'=>7200,                                       //token 过期时间2小时
    'goodsPageSize'=>5,                                         //商品列表每页默认数量
    'monologPath' => '/protected/runtime/monolog.log',
    'product_library_url' => 'http://59.110.225.174:8888/',
    'tokenTimeToLive' => 86400 * 365,
    'pageSize' => 30,
];
