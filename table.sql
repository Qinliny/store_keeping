CREATE TABLE `model_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nameId` int(11) NOT NULL,
  `model_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `factor` decimal(10,2) NOT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `size` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nameId` int(11) NOT NULL,
  `modelId` int(11) NOT NULL,
  `size_height` decimal(10,2) NOT NULL,
  `size_width` decimal(10,2) NOT NULL,
  `size_number` int(11) DEFAULT '0',
  `size_value` decimal(10,2) DEFAULT '0.00',
  `nullLine_number` int(11) DEFAULT '0',
  `nullLine_value` decimal(10,2) DEFAULT '0.00',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(60) NOT NULL COMMENT '密码',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态：0启用 1禁用',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员表';

CREATE TABLE `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nameId` int(11) NOT NULL,
  `modelId` int(11) NOT NULL,
  `sizeId` int(11) NOT NULL,
  `profit` int(11) NOT NULL,
  `tableList` text COLLATE utf8_unicode_ci,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nameId` int(11) NOT NULL,
  `modelId` int(11) NOT NULL,
  `sizeId` int(11) NOT NULL,
  `material_name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `material_price` decimal(10,2) NOT NULL,
  `material_dosage` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
