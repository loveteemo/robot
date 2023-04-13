<?php
use loveteemo\Robot;

require 'vendor/autoload.php';

//机器人ID
$robotWxid = "wxid_xx";
$url = "http://127.0.0.1:8073/send";
$robot = new Robot($url,false);

//获取昵称 有效 但是数据不是实时的
$info = $robot->getRobotName($robotWxid);
var_dump($info);
