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

use loveteemo\Robot;

//机器人ID
$robotWxid = "wxid_xxx";
//请求地址
$url = "http://127.0.0.1:8073/send";
//鉴权
$key = "xx";
$robot = new Robot($url,$key,false);

//获取昵称 有效 但是数据不是实时的
$info = $robot->getRobotName($robotWxid);

var_dump($info);
~~~

## 具体文档

- [可爱猫论坛](https://www.ikam.cn/)
