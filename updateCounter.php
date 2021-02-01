<?php
/**
 * Created by PhpStorm.
 * User xzw jsjxzw@163.com
 * Date 2021/2/1
 * Time 2:03 下午
 */

error_reporting(0);

require './Sredis.php';
require './Statistics.php';

$config = [];

$conn = Sredis::getInstance($config);


while (true) {
    sleep(1);
    Statistics::updateCounter($conn, 1, 1);
}
