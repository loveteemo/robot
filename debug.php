<?php
use loveteemo\Robot;

require 'vendor/autoload.php';

//机器人ID
$robotWxid = "wxid_xxx";
//请求地址
$url = "http://127.0.0.1:8073/send";
//鉴权
$key = "xx";
$robot = new Robot($url,$key,false);

//获取昵称 有效 但是数据不是实时的
$info = $robot->getRobotName($robotWxid);
