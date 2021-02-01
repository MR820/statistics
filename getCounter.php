<?php
/**
 * Created by PhpStorm.
 * User xzw jsjxzw@163.com
 * Date 2021/2/1
 * Time 2:27 下午
 */

error_reporting(0);

require './Sredis.php';
require './Statistics.php';

$config = [];

$conn = Sredis::getInstance($config);

echo ("一秒钟的统计量:".Statistics::getCounter($conn,1,1)).PHP_EOL;
echo ("五秒钟的统计量:".Statistics::getCounter($conn,1,5)).PHP_EOL;
echo ("一分钟的统计量:".Statistics::getCounter($conn,1,60)).PHP_EOL;
echo ("五分钟的统计量:".Statistics::getCounter($conn,1,300)).PHP_EOL;
echo ("一小时的统计量:".Statistics::getCounter($conn,1,3600)).PHP_EOL;
echo ("一天的统计量:".Statistics::getCounter($conn,1,86400)).PHP_EOL;