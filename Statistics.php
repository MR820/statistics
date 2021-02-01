<?php
/**
 * Created by PhpStorm.
 * User xzw jsjxzw@163.com
 * Date 2021/2/1
 * Time 11:56 上午
 */


class Statistics {

    //统计 以秒为单位
    public static $preisions = [1, 5, 60, 300, 3600, 86400];


    public static function updateCounter($conn,$productId,$count) {
        $currentDate = time();
        for ($i=0;$i<count(self::$preisions);$i++) {
            //算出指定时间维度的开始时间片
            $index = self::$preisions[$i];
            $startDate = (int)($currentDate/$index)*$index;
            $hash = "product:".$productId;
            //指定时间片的精度
            $conn->hIncrBy($hash, $startDate, $count);
            //将清理的key加入一个回收的set 存储key和精度
            $conn->zAdd("recovery", 0, $hash.'_'.$index.'_'.$startDate);
        }
    }

    public static function getCounter($conn, $productId, $preision) {
        $startDate = (int)(time()/$preision-1)*$preision;
        $hash = "product:".$productId;
        return $conn->hGet($hash, $startDate);
    }

    public static function clearCounter($conn) {
        $recoveryKey = 'recovery';
        $index = 0;
        while (true) {
            //没有需要回收的数据时，cpu休息
            if ($conn->zCard($recoveryKey) <= 0) {
                sleep(49);
                continue;
            }
            //每次回收50个
            $hashs = $conn->zRange($recoveryKey, 0, 50);
            foreach ($hashs as $hash) {
                $hashArray = mb_split('_',$hash);
                //精度
                $preision = $hashArray[1];
                //开始时间片
                $startDate = $hashArray[2];
                //key
                $productKey = $hashArray[0];
                //开始时间+精度 小于当前时间 表示时间片过了 执行删除
                //通过计算可以保留3天的数据
                if ($startDate + $preision >= time()) {
                    continue;
                }else {
                    //执行删除
                        $result = $conn->hDel($productKey, $startDate);
                        $conn->zRem($recoveryKey, $hash);
//                        echo("移除了key:$productKey,过期数据:$startDate,删除结果:$result");
                        file_put_contents("log.txt",
                            "移除了key:$productKey,过期数据:$startDate,删除结果:$result\n\r",
                            FILE_APPEND);
                }
            }
        }
    }

}

