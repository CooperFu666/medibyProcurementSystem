CREATE TABLE `yj_user` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) NOT NULL COMMENT '用户手机',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `emailstatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '邮箱认证状态 1未认证2已认证',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常 2禁用',
  `type` tinyint(1) DEFAULT NULL COMMENT '类型 1类2类3类',
  `regtime` int(11) NOT NULL COMMENT '注册时间',
  `lasttime` int(11) DEFAULT '0' COMMENT '最后登陆时间',
  `appcode` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';

CREATE TABLE `yj_user_detail` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL COMMENT '用户id',
  `corporate_name` varchar(255) DEFAULT NULL COMMENT '公司名称',
  `corporate_code` varchar(255) DEFAULT NULL COMMENT '公司代码',
  `province` varchar(32) DEFAULT NULL COMMENT '省',
  `city` varchar(32) DEFAULT NULL COMMENT '市',
  `area` varchar(32) DEFAULT NULL COMMENT '区',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `corporate_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '公司性质1医疗器械分销商2公立医院3民营医院4高校5体检机构6诊所7其他',
  `contact_name` varchar(128) DEFAULT NULL COMMENT '联系人',
  `contact_phone` varchar(16) DEFAULT NULL COMMENT '联系电话',
  `contact_email` varchar(16) DEFAULT NULL COMMENT '邮箱',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户详情';

CREATE TABLE `yj_user_address` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL COMMENT '用户id',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常，2默认',
  `province` varchar(32) NOT NULL COMMENT '省',
  `city` varchar(32) NOT NULL COMMENT '市',
  `area` varchar(32) NOT NULL COMMENT '区',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `name` varchar(128) NOT NULL COMMENT '姓名',
  `phone` varchar(16) NOT NULL COMMENT '电话',
  `email` varchar(16) DEFAULT NULL COMMENT '邮箱',
  `telnumber` varchar(32) DEFAULT NULL COMMENT '固定电话',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收货地址';

CREATE TABLE `yj_user_invoice` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL COMMENT '用户id',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常，2默认',
  `corporate_name` varchar(255) NOT NULL COMMENT '公司名称',
  `regaddress` varchar(255) NOT NULL COMMENT '注册地址',
  `regphone` varchar(32) NOT NULL COMMENT '注册电话',
  `bank` varchar(255) NOT NULL COMMENT '开户银行',
  `bank_number` varchar(255) NOT NULL COMMENT '银行账户',
  `user_address_id` int(8) DEFAULT NULL COMMENT '收货地址id',
  `auth_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1未认证，2通过，3认证失败',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户发票';

CREATE TABLE `yj_user_collection` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL COMMENT '用户id',
  `goodsid` int(8) NOT NULL COMMENT '商品id',
  `created_at` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `goodsid` (`goodsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收藏';

CREATE TABLE `yj_user_footprint` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL COMMENT '用户id',
  `goodsid` int(8) NOT NULL COMMENT '商品id',
  `created_at` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `goodsid` (`goodsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户足迹';

CREATE TABLE `yj_user_coupon` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL COMMENT '用户id',
  `couponid` int(8) NOT NULL COMMENT '优惠券id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1未使用，2已使用',
  `code` varchar(16) NOT NULL COMMENT '序列号',
  `updated_at` int(11) COMMENT '修改时间',
  `created_at` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `couponid` (`couponid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户优惠券';

CREATE TABLE `yj_user_auth` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL COMMENT '用户id',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型 1营业执照，2医疗器械许可证',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1认证中，2认证失败，3认证成功',
  `message` text COMMENT '反馈信息',
  `images` text COMMENT '图片',
  `updated_at` int(11) NOT NULL COMMENT '操作时间',
  `created_at` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户资质';

CREATE TABLE `yj_user_contact` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL COMMENT '用户id',
  `phone` varchar(16) NOT NULL COMMENT '电话',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型 1商品询价，2订单咨询，3发票咨询，4其他咨询',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1未联系 2已联系 3联系不上',
  `telnumber` varchar(32) DEFAULT NULL COMMENT '固定电话',
  `created_at` int(11) NOT NULL COMMENT '操作时间',
  `calltime` int(11) DEFAULT NULL COMMENT '回电时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='联系客服预约';

CREATE TABLE `yj_goods` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '商品名称',
  `brandid` int(8) NOT NULL COMMENT '品牌id',
  `origin` varchar(255) NOT NULL COMMENT '产地(发货地)',
  `tag` varchar(255) DEFAULT NULL COMMENT '标签',
  `info` text COMMENT '详情',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型 1类2类3类',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '最新',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热门',
  `is_search` tinyint(1) NOT NULL DEFAULT '0' COMMENT '搜索推荐',
  `created_at` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `categoryid` (`categoryid`),
  KEY `brandid` (`brandid`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品';

CREATE TABLE `yj_goods_version` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `goodsid` int(8) NOT NULL COMMENT '商品id',
  `title` varchar(255) NOT NULL COMMENT '型号名称',
  PRIMARY KEY (`id`),
  KEY `goodsid` (`goodsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品型号';

CREATE TABLE `yj_goods_attr` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `goodsid` int(8) NOT NULL COMMENT '商品id',
  `title` varchar(255) NOT NULL COMMENT '属性名称',
  PRIMARY KEY (`id`),
  KEY `goodsid` (`goodsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性';

CREATE TABLE `yj_goods_bind` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `goodsid` int(8) NOT NULL COMMENT '商品id',
  `goods_version_id` int(8) DEFAULT '0' COMMENT '型号id',
  `goods_attr_id` int(8) DEFAULT '0' COMMENT '属性id',
  `stock` int(8) DEFAULT '0' COMMENT '库存',
  `price` varchar(16) DEFAULT '0' COMMENT '价格',
  `is_non` tinyint(1) NOT NULL DEFAULT '0' COMMENT '不存在',
  PRIMARY KEY (`id`),
  KEY `goodsid` (`goodsid`),
  KEY `goods_version_id` (`goods_version_id`),
  KEY `goods_attr_id` (`goods_attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='属性绑定';

CREATE TABLE `yj_goods_images` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `goodsid` int(8) NOT NULL COMMENT '商品id',
  `goods_version_id` int(8) NOT NULL COMMENT '型号id',
  `images` text COMMENT '图片',
  PRIMARY KEY (`id`),
  KEY `goodsid` (`goodsid`),
  KEY `goods_version_id` (`goods_version_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品图集';

CREATE TABLE `yj_goods_census` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `goodsid` int(8) NOT NULL COMMENT '商品id',
  `click` int(8) NOT NULL DEFAULT '0' COMMENT '点击量',
  `sales` int(8) NOT NULL DEFAULT '0' COMMENT '销售量',
  `collection` int(8) NOT NULL DEFAULT '0' COMMENT '收藏量',
  PRIMARY KEY (`id`),
  KEY `goodsid` (`goodsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品统计';

CREATE TABLE `yj_brand` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '品牌名称',
  `info` text COMMENT '详情',
  `images` text COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='品牌';

CREATE TABLE `yj_category` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `pid` int(8) NOT NULL DEFAULT '0' COMMENT '父级id',
  `title` varchar(255) NOT NULL COMMENT '分类名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分类等级1，2，3，4……',
  `is_sidebar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '侧边栏',
  `icon` varchar(255) COMMENT '图标',
  `sort` int(11) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类';

CREATE TABLE `yj_category_bind` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品分类绑定表';

CREATE TABLE `yj_shopping_cart` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `goodsid` int(8) NOT NULL COMMENT '商品id',
  `goods_version_id` int(8) NOT NULL COMMENT '型号id',
  `goods_attr_id` int(8) NOT NULL COMMENT '属性id',
  `number` int(8) NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `goodsid` (`goodsid`),
  KEY `goods_version_id` (`goods_version_id`),
  KEY `goods_attr_id` (`goods_attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';

CREATE TABLE `yj_comment` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `goodsid` int(8) NOT NULL COMMENT '商品id',
  `orderid` int(8) NOT NULL COMMENT '订单id',
  `userid` int(10) DEFAULT NULL COMMENT '评论人UID',
  `content` text COMMENT '评论内容',
  `reply` text COMMENT '商城回复',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1待审核2通过3未通过',
  `stars` tinyint(2) DEFAULT NULL COMMENT '评论星数',
  PRIMARY KEY (`id`),
  KEY `goodsid` (`goodsid`),
  KEY `orderid` (`orderid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单评论';

CREATE TABLE `yj_coupon` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '名称',
  `discount` varchar(10) COMMENT '折扣',
  `price` varchar(10) COMMENT '价格',
  `starttime` int(11) NOT NULL COMMENT '开始时间',
  `endtime` int(11) NOT NULL COMMENT '结束时间',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '种类 1代金券 2打折卡',
  `is_limit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '限制',
  `is_register` tinyint(1) NOT NULL DEFAULT '0' COMMENT '注册领取',
  `mix_price` varchar(10) DEFAULT '0' COMMENT '满减金额',
  `categoryid` int(8) COMMENT '分类id',
  `brandid` int(8) COMMENT '品牌id',
  `number` int(8) DEFAULT '0' COMMENT '发放数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券';

CREATE TABLE `yj_notify` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `type` tinyint(2) DEFAULT '1' COMMENT '通知类型1:系统通知2：自定义通知',
  `is_read` tinyint(1) DEFAULT NULL COMMENT '是否已读1:是0：否',
  `title` varchar(150) DEFAULT NULL,
  `content` text COMMENT '内容',
  `created_at` int(10) DEFAULT NULL,
  `read_at` int(10) DEFAULT NULL COMMENT '已读时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='通知消息表';

CREATE TABLE `yj_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_user表',
  `user_address_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_user_address表',
  `user_invoice_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_user_invoice发票表',
  `user_coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_user_coupon优惠券表',
  `is_invoice` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已开发票',
  `is_coupon` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否使用优惠券',
  `is_discount` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打折,如果打折，只能针对一种商品打折',
  `coupon_price` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '优惠券总价',
  `price` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '商品实际价格',
  `pay_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付类型1公对公转账 2支付宝 3微信',
  `place_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1待付款2待发货3待收货4完成',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单主表';

CREATE TABLE `yj_order_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `express_id` int(11) NOT NULL COMMENT '快递id',
  `express_number` varchar(255) NOT NULL COMMENT '快递单号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_user_id_order_id` (`user_id`,`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单快递';

CREATE TABLE `yj_order_goods_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_order表',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_goods表',
  `goods_attr_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_goods_attr表',
  `goods_version_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_goods_version表',
  `goods_bind_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_goods_bind表',
  `count` mediumint(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `user_coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联yj_user_coupon表',
  `is_discount` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打折,如果打折，只能针对一种商品打折，并且打折数量为一',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq` (`order_id`,`goods_id`,`goods_attr_id`,`goods_version_id`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单关联表';

CREATE TABLE `yj_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `phone` char(11) NOT NULL COMMENT '手机号码',
  `email` varchar(150) NOT NULL,
  `is_system` tinyint(1) DEFAULT '0' COMMENT '是否系统管理员',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `login_at` int(11) DEFAULT NULL COMMENT '上次登录时间',
  `login_ip` varchar(15) DEFAULT NULL COMMENT '上次登录IP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台管理账号表';

CREATE TABLE `yj_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_system` tinyint(1) DEFAULT '0' COMMENT '是否系统角色',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台管理角色表';

CREATE TABLE `yj_role_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='角色权限表';

CREATE TABLE `yj_role_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `group_id` int(11) unsigned NOT NULL COMMENT '后台分组菜单分组ID',
  `module_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='动作表';

CREATE TABLE `yj_role_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `name` varchar(15) NOT NULL,
  `is_effect` tinyint(1) NOT NULL COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='模块表';

CREATE TABLE `yj_role_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `icons` varchar(30) DEFAULT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台管理菜单表';

CREATE TABLE `yj_role_nav_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `nav_id` int(11) NOT NULL COMMENT '后台导航分组ID',
  `icon` varchar(30) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `is_effect` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台菜单分组表';

CREATE TABLE `yj_sensitive_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `find` varchar(255) DEFAULT NULL COMMENT '不良词语',
  `replacement` varchar(255) DEFAULT NULL COMMENT '替换内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='敏感词';

CREATE TABLE `yj_sms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) DEFAULT NULL,
  `content` varchar(300) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '1:login 2:register 3:find 4:other',
  `created_at` int(10) NOT NULL,
  `error_code` varchar(5) DEFAULT NULL COMMENT '短信发送状态码',
  `code` int(11) DEFAULT NULL COMMENT '验证码',
  `times` int(11) DEFAULT NULL COMMENT '次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='短信日志表';

CREATE TABLE `yj_friendlink` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) DEFAULT NULL COMMENT '标题',
  `url` varchar(150) DEFAULT NULL,
  `sort` tinyint(4) unsigned DEFAULT '0',
  `is_effect` tinyint(1) unsigned DEFAULT '0' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='友情链接';

CREATE TABLE `yj_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(150) NOT NULL COMMENT 'wap图片地址（绝对路径）',
  `url` varchar(150) DEFAULT NULL COMMENT 'link地址',
  `alt` varchar(150) DEFAULT NULL COMMENT '图片alt属性',
  `sort` int(11) DEFAULT '0' COMMENT '排序值',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否显示（0：不显示， 1:wap显示 2:pc显示3:app显示）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='首页banner图管理';

CREATE TABLE `yj_express` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '快递名称',
  `code` varchar(255) DEFAULT NULL COMMENT '快递代号',
  `api` varchar(255) DEFAULT NULL COMMENT '接口',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='快递';

CREATE TABLE `yj_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `page_id` int(11) NOT NULL COMMENT '页面id',
  `is_effect` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网站菜单';

CREATE TABLE `yj_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `page_id` int(11) NOT NULL COMMENT '页面id',
  `type` tinyint(1) NOT NULL COMMENT '类型1商品列表2文章列表3文章详情',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网站页面';

CREATE TABLE `yj_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '文章标题',
  `content` text NOT NULL COMMENT ' 文章内容',
  `cate_id` int(11) NOT NULL COMMENT '文章分类ID',
  `created_at` int(11) DEFAULT NULL COMMENT '发表时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `seo_title` text COMMENT '自定义seo页面标题',
  `seo_keyword` text COMMENT '自定义seo页面keyword',
  `seo_description` text COMMENT '自定义seo页面标述',
  `summary` text COMMENT '简介',
  `is_effect` tinyint(4) NOT NULL COMMENT '有效性标识',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章';

CREATE TABLE `yj_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '分类名称',
  `brief` varchar(255) NOT NULL COMMENT '分类简介(备用字段)',
  `pid` int(11) NOT NULL COMMENT '父ID，程序分类可分二级',
  `is_effect` tinyint(4) NOT NULL COMMENT '有效性标识',
  `type` tinyint(1) NOT NULL COMMENT '类型 0:普通文章（可通前台分类列表查找到） 1.帮助文章（用于前台页面底部的站点帮助） 2.关于我们（用于前台页面公告模块的调用） 3.系统文章（自定义的一些文章，需要前台自定义一些入口链接到该文章） 所属该分类的所有文章类型与分类一致4文档文章(数据报告)',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `type` (`type`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章资讯分类表';

CREATE TABLE `yj_conf` (
  `name` varchar(150) DEFAULT NULL COMMENT '配置键名',
  `value` varchar(255) DEFAULT NULL COMMENT '配置值',
  `group_id` tinyint(3) DEFAULT NULL COMMENT '分组id，1：系统配置',
  `china_name` varchar(150) DEFAULT NULL COMMENT '中文名字',
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统配置表';

CREATE TABLE `yj_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `url` varchar(255) NOT NULL COMMENT '图片路径',
  `filename` varchar(255) NOT NULL COMMENT '文件名',
  `type` varchar(255) NOT NULL COMMENT '类型',
  `status` varchar(255) NOT NULL COMMENT '状态',
  `size` varchar(255) NOT NULL COMMENT '大小',
  `created_at` int(11) DEFAULT NULL COMMENT '发表时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='网络图片';

CREATE TABLE `yj_home` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` varchar(255) NOT NULL COMMENT '分类',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `src` varchar(255) NOT NULL COMMENT '图片链接',
  `url` varchar(255) NOT NULL COMMENT '图片路径',
  `sort` varchar(255) NOT NULL COMMENT '文件名',
  PRIMARY KEY (`id`),
  KEY `userid` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='首页设置';




