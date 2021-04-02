/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.7.28 : Database - ladmin
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `zx_admin_group` */

DROP TABLE IF EXISTS `zx_admin_group`;

CREATE TABLE `zx_admin_group` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text COMMENT '备注',
  `menus` text COMMENT '用户组拥有的菜单id',
  `listorder` smallint(5) unsigned DEFAULT '0' COMMENT '排序',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `admin_id` smallint(3) NOT NULL DEFAULT '0' COMMENT '操作人',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `listorder` (`listorder`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分组';

/*Data for the table `zx_admin_group` */

insert  into `zx_admin_group`(`id`,`name`,`description`,`menus`,`listorder`,`updated_at`,`created_at`,`admin_id`) values (1,'演示组','只能看不能改','274,280,19,33,21,29,2,28,240,241,242,197,198,213,3,1,6,122,123',0,1583396068,0,1),(2,'管理员','拥有所有权限','274,280,281,19,33,34,35,36,66,21,29,30,31,240,241,242,244,243,2,28,197,198,199,200,201,213,3,1,79,6,7,8,58,122,124,125,123',0,1583392316,1529913257,1);

/*Table structure for table `zx_admin_group_user` */

DROP TABLE IF EXISTS `zx_admin_group_user`;

CREATE TABLE `zx_admin_group_user` (
  `admin_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '角色id',
  UNIQUE KEY `uid_group_id` (`admin_id`,`group_id`) USING BTREE,
  KEY `uid` (`admin_id`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户所属分组';

/*Data for the table `zx_admin_group_user` */

insert  into `zx_admin_group_user`(`admin_id`,`group_id`) values (3,1),(4,1),(5,1),(6,1),(35,1),(2,2),(35,2);

/*Table structure for table `zx_admin_log` */

DROP TABLE IF EXISTS `zx_admin_log`;

CREATE TABLE `zx_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_menu_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '菜单id',
  `querystring` varchar(255) DEFAULT '' COMMENT '参数',
  `data` text COMMENT 'POST数据',
  `ip` varchar(18) NOT NULL DEFAULT '',
  `admin_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '操作人',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `primary_id` int(11) DEFAULT '0' COMMENT '表中主键ID',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_menu_id` (`admin_menu_id`) USING BTREE,
  KEY `idx_admin_id` (`admin_id`) USING BTREE,
  KEY `idx_created_at` (`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Data for the table `zx_admin_log` */

insert  into `zx_admin_log`(`id`,`admin_menu_id`,`querystring`,`data`,`ip`,`admin_id`,`created_at`,`updated_at`,`primary_id`) values (1,286,'id=2',NULL,'127.0.0.1',1,1616467137,1616467137,0),(2,286,'id=4',NULL,'127.0.0.1',1,1616467142,1616467142,0),(4,7,NULL,'{\"parentid\":\"240\",\"icon\":null,\"name\":\"\\u83dc\\u5355\\u6d4b\\u8bd52\\u7ea7\",\"c\":null,\"a\":null,\"data\":null,\"listorder\":\"999\",\"status\":\"1\",\"write_log\":\"2\"}','127.0.0.1',1,1616575842,1616575842,0),(5,7,NULL,'{\"parentid\":\"240\",\"icon\":null,\"name\":\"\\u83dc\\u5355\\u6d4b\\u8bd52\\u7ea7\",\"c\":\"test\",\"a\":\"test\",\"data\":null,\"listorder\":\"999\",\"status\":\"1\",\"write_log\":\"2\"}','127.0.0.1',1,1616575873,1616575873,0),(6,7,NULL,'{\"parentid\":\"287\",\"icon\":null,\"name\":\"\\u83dc\\u5355\\u6d4b\\u8bd53\\u7ea7\",\"c\":\"test\",\"a\":\"test\",\"data\":null,\"listorder\":\"999\",\"status\":\"1\",\"write_log\":\"2\"}','127.0.0.1',1,1616575893,1616575893,0),(7,79,'id=287',NULL,'127.0.0.1',1,1616576936,1616576936,0),(8,7,NULL,'{\"parentid\":null,\"icon\":\"fa-cutlery\",\"name\":\"\\u9910\\u996e\\u7ba1\\u7406\",\"c\":\"resturant\",\"a\":\"index\",\"data\":null,\"listorder\":\"999\",\"status\":\"1\",\"write_log\":\"2\"}','127.0.0.1',1,1616577152,1616577152,0),(9,8,NULL,'{\"id\":\"290\",\"parentid\":\"289\",\"icon\":null,\"name\":\"\\u9910\\u684c\\u7ba1\\u7406\",\"c\":\"table\",\"a\":\"index\",\"data\":null,\"listorder\":\"999\",\"status\":\"1\",\"write_log\":\"2\"}','127.0.0.1',1,1616577216,1616577216,290),(10,7,NULL,'{\"parentid\":\"290\",\"icon\":null,\"name\":\"\\u8be6\\u60c5\",\"c\":\"table\",\"a\":\"info\",\"data\":null,\"listorder\":\"999\",\"status\":\"2\",\"write_log\":\"2\"}','127.0.0.1',1,1616577253,1616577253,0),(11,7,NULL,'{\"parentid\":\"290\",\"icon\":null,\"name\":\"\\u6dfb\\u52a0\",\"c\":\"table\",\"a\":\"add\",\"data\":null,\"listorder\":\"999\",\"status\":\"2\",\"write_log\":\"1\"}','127.0.0.1',1,1616577290,1616577290,0),(12,8,NULL,'{\"id\":\"294\",\"parentid\":\"290\",\"icon\":null,\"name\":\"\\u9910\\u684c\\u8be6\\u60c5\",\"c\":\"table\",\"a\":\"info\",\"data\":null,\"listorder\":\"999\",\"status\":\"1\",\"write_log\":\"2\"}','127.0.0.1',1,1616577707,1616577707,294),(33,35,NULL,'{\"id\":\"2\",\"head_img\":\"2.jpg\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\"}','127.0.0.1',1,1616744235,1616744235,2),(34,35,NULL,'{\"id\":\"2\",\"head_img\":\"2.jpg\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\"}','127.0.0.1',1,1616744262,1616744262,2),(35,35,NULL,'{\"id\":\"2\",\"head_img\":\"2.jpg\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\"}','127.0.0.1',1,1616744268,1616744268,2),(36,35,NULL,'{\"id\":\"2\",\"head_img\":\"2.jpg\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\"}','127.0.0.1',1,1616744654,1616744654,2),(37,35,NULL,'{\"id\":\"2\",\"head_img\":\"2.jpg\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\"}','127.0.0.1',1,1616744672,1616744672,2),(38,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616745958,1616745958,2),(39,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616746044,1616746044,2),(40,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616746058,1616746058,2),(41,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616746230,1616746230,2),(42,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616746347,1616746347,2),(43,35,NULL,'{\"id\":\"1\",\"realname\":\"\\u7cfb\\u7edf\",\"mobile\":\"18888873646\",\"email\":\"5552123@qq.com\",\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616746440,1616746440,1),(44,35,NULL,'{\"id\":\"1\",\"realname\":\"\\u7cfb\\u7edf\",\"mobile\":\"18888873646\",\"email\":\"5552123@qq.com\",\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616746450,1616746450,1),(45,35,NULL,'{\"id\":\"1\",\"realname\":\"\\u7cfb\\u7edf\",\"mobile\":\"18888873646\",\"email\":\"5552123@qq.com\",\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616747265,1616747265,1),(46,35,NULL,'{\"id\":\"1\",\"realname\":\"\\u7cfb\\u7edf\",\"mobile\":\"18888873646\",\"email\":\"5552123@qq.com\",\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616747439,1616747439,1),(47,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616747638,1616747638,2),(48,35,NULL,'{\"id\":\"1\",\"realname\":\"\\u7cfb\\u7edf\",\"mobile\":\"18888873646\",\"email\":\"5552123@qq.com\",\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616747676,1616747676,1),(49,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616747794,1616747794,2),(50,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616748213,1616748213,2),(51,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616749566,1616749566,2),(52,35,NULL,'{\"id\":\"2\",\"realname\":\"alfred\",\"mobile\":\"13800138000\",\"email\":\"rd_wangping@yuzhua.com\",\"group_id\":[\"2\"],\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616749813,1616749813,2),(53,35,NULL,'{\"id\":\"1\",\"realname\":\"\\u7cfb\\u7edf\",\"mobile\":\"18888873646\",\"email\":\"5552123@qq.com\",\"status\":\"1\",\"head_img\":{}}','127.0.0.1',1,1616749825,1616749825,1);

/*Table structure for table `zx_admin_menu` */

DROP TABLE IF EXISTS `zx_admin_menu`;

CREATE TABLE `zx_admin_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `parentid` smallint(6) DEFAULT '0' COMMENT '上级',
  `icon` varchar(20) DEFAULT '' COMMENT '图标',
  `m` varchar(10) NOT NULL DEFAULT 'admin' COMMENT '模块',
  `c` varchar(20) NOT NULL DEFAULT '' COMMENT 'controller',
  `a` varchar(20) NOT NULL DEFAULT '' COMMENT 'action',
  `data` varchar(50) DEFAULT '' COMMENT '更多参数',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `listorder` smallint(6) unsigned DEFAULT '999',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示1:显示,2:不显示',
  `write_log` tinyint(1) NOT NULL DEFAULT '2' COMMENT '记录日志:1记录,2不记录',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `listorder` (`listorder`) USING BTREE,
  KEY `parentid` (`parentid`) USING BTREE,
  KEY `idx_m_c_a` (`m`,`c`,`a`)
) ENGINE=InnoDB AUTO_INCREMENT=295 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Data for the table `zx_admin_menu` */

insert  into `zx_admin_menu`(`id`,`name`,`parentid`,`icon`,`m`,`c`,`a`,`data`,`group`,`listorder`,`status`,`write_log`,`updated_at`,`created_at`) values (1,'后台菜单',3,NULL,'admin','adminMenu','index',NULL,'',4,1,2,1583339383,1503299010),(2,'日志管理',0,'fa-book','admin','adminLog','index',NULL,'',5,1,2,1583339383,1503300454),(3,'系统管理',0,'fa-gears','admin','system','index',NULL,'',1000,1,2,1583339383,1503300505),(6,'菜单查看',1,NULL,'admin','adminMenu','info',NULL,'',999,2,2,1583339383,1503303676),(7,'菜单添加',1,NULL,'admin','adminMenu','add',NULL,'',999,2,1,1583339383,1503303742),(8,'菜单修改',1,NULL,'admin','adminMenu','edit',NULL,'',999,2,1,1583339383,1503303780),(19,'用户管理',0,'fa-users','admin','adminUser','index',NULL,'',1,1,2,1583339382,1503305413),(21,'角色管理',0,'fa-check-square-o','admin','adminGroup','index',NULL,'',2,1,2,1583339382,1503305466),(28,'日志详情',2,NULL,'admin','adminLog','info',NULL,'',999,2,2,1583339383,1503561164),(29,'角色详情',21,NULL,'admin','adminGroup','info',NULL,'',999,2,2,1583339382,1503655888),(30,'角色添加',21,NULL,'admin','adminGroup','add',NULL,'',999,2,1,1583339382,0),(31,'角色修改',21,NULL,'admin','adminGroup','edit',NULL,'',999,2,1,1583339382,0),(33,'用户详情',19,'','admin','adminUser','info','','',999,2,2,1583339382,0),(34,'用户添加',19,'','admin','adminUser','add','','',999,2,1,1583339382,0),(35,'用户修改',19,'','admin','adminUser','edit','','',999,2,1,1583339382,0),(36,'修改用户密码',19,'','admin','adminUser','changePwd','','',999,2,1,1583339382,0),(58,'菜单排序',1,NULL,'admin','adminMenu','setListorder',NULL,'',999,2,2,1583339383,1503657729),(66,'用户禁用',19,NULL,'admin','adminUser','status',NULL,'',999,2,1,1583339382,1504605933),(79,'菜单删除',1,NULL,'admin','adminMenu','del',NULL,'',999,2,1,1583339383,1504998588),(122,'站点管理',3,NULL,'admin','site','info',NULL,'',10,1,2,1583339383,1506336604),(123,'站点详情',122,'','admin','site','info',NULL,'',999,2,2,1583339383,1506336604),(124,'站点添加',122,'','admin','site','add',NULL,'',999,2,1,1583339383,1506336604),(125,'站点修改',122,'','admin','site','edit',NULL,'',999,2,1,1583339383,1506336604),(197,'定时任务',0,'fa-fire','admin','crontab','index',NULL,'',9,1,2,1583339383,1518159827),(198,'定时任务详情',197,'','admin','crontab','info',NULL,'',999,2,1,1583339383,1518159827),(199,'定时任务添加',197,'','admin','crontab','add',NULL,'',999,2,1,1583339383,1518159827),(200,'定时任务修改',197,'','admin','crontab','edit',NULL,'',999,2,1,1583339383,1518159827),(201,'定时任务删除',197,'','admin','crontab','del',NULL,'',999,2,1,1583339383,1518159827),(213,'计划任务禁用',197,NULL,'admin','crontab','status',NULL,'',999,2,1,1583339383,1520172047),(240,'工作管理',0,'fa-inbox','admin','workInfo','index',NULL,'',5,1,2,1583339382,1529926496),(241,'工作详情',240,'','admin','workInfo','info',NULL,'',999,2,2,1583339382,1529926496),(242,'工作添加',240,'','admin','workInfo','add',NULL,'',999,2,2,1583339382,1529926496),(243,'工作修改',240,'','admin','workInfo','edit',NULL,'',999,2,2,1583339383,1529926496),(244,'工作删除',240,'','admin','workInfo','del',NULL,'',999,2,2,1583339383,1529926496),(274,'仪表板',0,'fa-home','admin','adminHome','publicIndex',NULL,'',0,1,2,1583339382,1531572616),(280,'更新资料',274,NULL,'admin','adminHome','publicInfo',NULL,'',999,2,2,1583339382,1583335789),(281,'更新密码',274,NULL,'admin','adminHome','publicChangePwd',NULL,'',999,2,2,1583339382,1583335836),(285,'删除日志',2,NULL,'admin','adminLog','del',NULL,'',999,2,2,1583395183,1583395183),(286,'删除用户',19,NULL,'admin','adminUser','del',NULL,'',999,2,1,1583395623,1583395623),(289,'餐饮管理',0,'fa-cutlery','admin','resturant','index',NULL,'',999,1,2,1616577152,1616577152),(290,'餐桌管理',289,'','admin','tables','index',NULL,'',999,1,2,1616577216,1616577152),(291,'餐桌添加',290,'','admin','tables','add',NULL,'',999,2,1,1616577152,1616577152),(292,'餐桌修改',290,'','admin','tables','edit',NULL,'',999,2,1,1616577152,1616577152),(293,'餐桌删除',290,'','admin','tables','del',NULL,'',999,2,1,1616577152,1616577152),(294,'餐桌详情',290,NULL,'admin','tables','info',NULL,'',999,2,2,1616578282,1616577253);

/*Table structure for table `zx_admin_user` */

DROP TABLE IF EXISTS `zx_admin_user`;

CREATE TABLE `zx_admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `mobile` varchar(11) COLLATE utf8_unicode_ci DEFAULT '',
  `password` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(125) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '邮箱',
  `realname` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '真实姓名',
  `level` tinyint(1) DEFAULT '1' COMMENT '1,普通,2经理',
  `status` tinyint(1) DEFAULT '1' COMMENT '1正常,2禁用',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `setting` text COLLATE utf8_unicode_ci COMMENT '其它设置',
  `is_super` tinyint(1) DEFAULT '0' COMMENT '超级管理员,直接拥有所有权限',
  `weixin_openid` varchar(64) COLLATE utf8_unicode_ci DEFAULT '',
  `head_img` varchar(255) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '微信远程头像',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT COMMENT='后台管理员';

/*Data for the table `zx_admin_user` */

insert  into `zx_admin_user`(`id`,`name`,`mobile`,`password`,`email`,`realname`,`level`,`status`,`remember_token`,`setting`,`is_super`,`weixin_openid`,`head_img`,`created_at`,`updated_at`) values (1,'admin','18888873646','$2y$10$M54IsJQyIIZamV0ldFp7R.iF.Lah6bDeyk62eFYNZ7XFFGrYGaPn6','5552123@qq.com','系统',1,1,'aakcR48vd295bGxZdeK6P6hC7W2L8nQLpIzsURnZyNPrjdpPdSH1xWg0ioE9',NULL,1,'o7Vaw1Cry3PvKx6vKhgAm0HllkL4','http://cdn.findwp.cn/avatars/JPZBsTQd9mF4otgVzGgCbKyNU15QMbZvUPG1Vd33.jpg',0,1616749826),(2,'alfred','13800138000','$2y$10$yJO.ocrepBTC/JwVPB38COGbeYF/cJN8fup98YG5VnPGyamQa5zVa','rd_wangping@yuzhua.com','alfred',1,1,'',NULL,0,'','http://cdn.findwp.cn/avatars/eWC0DR0Pp7KwGELxqMplPkde89RUtsrJ6xHOSxrw.jpg',1616468239,1616749813);

/*Table structure for table `zx_crontab` */

DROP TABLE IF EXISTS `zx_crontab`;

CREATE TABLE `zx_crontab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '任务名',
  `code` varchar(100) NOT NULL DEFAULT '' COMMENT '代码',
  `crontab` varchar(100) NOT NULL DEFAULT '' COMMENT '时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态:1可用,2不可用',
  `description` varchar(255) DEFAULT '' COMMENT '备注',
  `admin_id` int(10) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='crontab定时任务';

/*Data for the table `zx_crontab` */

insert  into `zx_crontab`(`id`,`name`,`code`,`crontab`,`status`,`description`,`admin_id`,`created_at`,`updated_at`) values (1,'同步xxx','sync_domain_url','20 18 * * *',1,'抓取域名下所有url 地址',2,1529917283,1531577070),(2,'xxxx','sync_response_time','56 20 * * *',2,'同步 api 响应数据',2,1530788986,1531576440),(3,'工作提醒','work_reminder','* * * * *',1,'发邮件或微信提醒',2,1531305592,1583389759);

/*Table structure for table `zx_failed_jobs` */

DROP TABLE IF EXISTS `zx_failed_jobs`;

CREATE TABLE `zx_failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `zx_failed_jobs` */

/*Table structure for table `zx_jobs` */

DROP TABLE IF EXISTS `zx_jobs`;

CREATE TABLE `zx_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `zx_jobs` */

/*Table structure for table `zx_migrations` */

DROP TABLE IF EXISTS `zx_migrations`;

CREATE TABLE `zx_migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `zx_migrations` */

insert  into `zx_migrations`(`id`,`migration`,`batch`) values (1,'2018_07_08_220940_create_jobs_table',1);

/*Table structure for table `zx_site` */

DROP TABLE IF EXISTS `zx_site`;

CREATE TABLE `zx_site` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '' COMMENT '名称',
  `keywords` varchar(255) DEFAULT '' COMMENT '关键词',
  `description` varchar(255) DEFAULT '' COMMENT '说明',
  `admin_title` varchar(50) DEFAULT '' COMMENT '后台名称',
  `setting` text COMMENT '其它设置',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='站点设置';

/*Data for the table `zx_site` */

insert  into `zx_site`(`id`,`title`,`keywords`,`description`,`admin_title`,`setting`,`created_at`,`updated_at`) values (1,'laravel-admin','laravel7后台管理','QQ 5552123','后台管理','{\"queue_weixin\":\"0\",\"queue_email\":\"apim:middle\",\"alert_type\":\"email\",\"mail\":\"5552123@qq.com\"}',0,1583389383);

/*Table structure for table `zx_tables` */

DROP TABLE IF EXISTS `zx_tables`;

CREATE TABLE `zx_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '餐桌名',
  `num_id` varchar(32) NOT NULL DEFAULT '' COMMENT '餐桌编号',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '餐桌类型 1小桌 2中桌 3大桌',
  `is_show` enum('0','1') DEFAULT '1' COMMENT '是否显示',
  `is_del` enum('0','1') DEFAULT '0' COMMENT '是否删除',
  `c_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `u_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `admin_id` int(10) NOT NULL COMMENT '创建用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `zx_tables` */

insert  into `zx_tables`(`id`,`name`,`num_id`,`type`,`is_show`,`is_del`,`c_time`,`u_time`,`admin_id`) values (1,'系统桌1','001',1,'1','0',0,0,1),(2,'系统桌2','002',1,'1','0',0,0,1),(3,'系统卓3','003',1,'1','0',0,0,1),(4,'系统桌4','004',2,'1','0',0,0,1),(5,'系统桌5','005',2,'1','0',0,0,1),(6,'系统桌6','006',2,'1','0',0,0,1),(7,'系统桌7','007',3,'1','0',0,0,1),(8,'系统桌8','008',3,'1','0',0,0,1),(9,'系统桌9','009',3,'0','0',0,0,1),(10,'十全十美','010',3,'0','0',0,1616739278,1),(11,'大富大贵','011',3,'1','0',1616726524,1616739294,1);

/*Table structure for table `zx_work_info` */

DROP TABLE IF EXISTS `zx_work_info`;

CREATE TABLE `zx_work_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text COMMENT '内容',
  `reminder_status` tinyint(1) DEFAULT '1' COMMENT '是否提醒:1是,2否',
  `admin_id` tinyint(3) DEFAULT '0',
  `is_reminder` tinyint(1) DEFAULT '1' COMMENT '1未提醒,2已提醒',
  `reminder_at` int(11) DEFAULT '0' COMMENT '提醒日期',
  `is_delete` tinyint(1) DEFAULT '0',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='工作提醒';

/*Data for the table `zx_work_info` */

insert  into `zx_work_info`(`id`,`content`,`reminder_status`,`admin_id`,`is_reminder`,`reminder_at`,`is_delete`,`created_at`,`updated_at`) values (1,'12341234',1,2,2,1531562915,0,1529926576,1531563661),(2,'234',1,1,1,1583186902,1,1583158104,1583158203),(3,'56778',1,1,1,1583193600,1,1583203874,1583203880),(5,'陪孩子出去玩',1,1,1,1590969600,0,1583395687,1583395687);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
