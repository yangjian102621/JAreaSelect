<?php
/**
 * 地区采集器
 */
require 'php/mysql_class.php';
require 'php/mysql_config_class.php';

$mysql = mysql::getInstance();

$url = "https://d.jd.com/area/get?callback=getAreaListCallback";

$plist = $mysql->getList("select * from area");

foreach ($plist as $value) {
    $data = file_get_contents($url."&fid={$value['id']}");
    if ( $data ) {
        $list = json_decode(rtrim(ltrim($data, "getAreaListCallback("), ")"), true);
        foreach ($list as $val) {
            $val['pid'] = $value['id'];
            if ( $mysql->insert("area", $val) ) {
                printf("插入数据 {$val['name']}\n");
            }

            sleep(1);//睡一秒
            $tt = file_get_contents($url."&fid={$val['id']}");
            if ( $tt ) {
                $ttlist = json_decode(rtrim(ltrim($tt, "getAreaListCallback("), ")"), true);
                foreach ($ttlist as $kk) {
                    $kk['pid'] = $val['id'];
                    if ($mysql->insert("area", $kk))
                    {
                        printf("插入数据 {$kk['name']}\n");
                    }
                }
            } else {
                file_get_contents("a.log", "{$val['name']}", FILE_APPEND);
            }
        }
    } else {
        file_get_contents("a.log", "{$value['name']}", FILE_APPEND);
    }
    sleep(1);
}



