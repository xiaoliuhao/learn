<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/8
 * Time: 17:14
 * Version: learn
 */
define('DB_INSERT',1);
define('DB_REPLACE',2);
define('DB_STORE',3);

define('DB_BUCKET_SIZE',262144);
define('DB_KEY_SIZE',128);
define('DB_INDEX_SIZE',DB_KEY_SIZE + 12);

define('DB_KEY_EXISTS',1);
define('DB_FAILURE',-1);
define('DB_SUCCESS',0);