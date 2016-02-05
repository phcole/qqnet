<?php

require_once('config.inc.php'); // 配置文件
require_once('dbmodel.php');    // 数据库模型
require_once('utils.php');		// 辅助函数

if ($_SERVER["REMOTE_ADDR"] != "127.0.0.1")
	show301();

?>