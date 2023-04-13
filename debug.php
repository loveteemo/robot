<?php
require_once "src/Robot.php";
use loveteemo\src\Robot;


$robotWxid = "wxid_xx";
$url = "http://127.0.0.1:8073/send";
$robot = new Robot($url,false);

//获取昵称 有效 但是数据不是实时的
//$info = $robot->getRobotName($robotWxid);
//var_dump($info);
//获取头像 无效 可以再登录用户信息里取
//$info = $robot->getRobotHeadImageUrl($robotWxid);
//var_dump($info);
//获取登录用户信息
$toWxid = "xx";
$info = $robot->sendTextMsg("hello",$robotWxid,$toWxid);
var_dump($info);
