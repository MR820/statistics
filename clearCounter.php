<?php
/**
 * Created by PhpStorm.
 * User xzw jsjxzw@163.com
 * Date 2021/2/1
 * Time 2:15 下午
 */


error_reporting(0);

require './Sredis.php';
require './Statistics.php';

$config = [];

$conn = Sredis::getInstance($config);


Statistics::clearCounter($conn);