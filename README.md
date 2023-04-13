# robot

可爱猫微信机器人框架 - HTTP 插件个人对接PHP版本。

使用框架搭配插件可以实现：自动回复，发送定时提醒消息，群管理等功能。

## 安装

~~~php
composer require loveteemo/robot
~~~

## 用法示例

本扩展不能单独使用，依赖 `windows` 下安装 `可爱猫机器人框架` + `http个人对接多语言插件` 配合使用

- 安装可爱猫机器人框架。框架安装文件自行寻找。易语言会报毒，自行处理。

- 安装http个人对接多语言版本插件，一般插件会在安装包文件里。

~~~php

use loveteemo\robot\Robot;

//登录微信机器人ID
$robotWxid = "wxid_xxx"; 

//服务器远程调用接口
$url = "http://1.1.1.1:8073/send";

$robot = new Robot($url,false);
//被动触发根据情况调整

//主动触发
$nickname = $robot->getRobotName($robotWxid);
var_dump($info);
~~~

## 具体文档

- [可爱猫论坛](https://www.ikam.cn/)
