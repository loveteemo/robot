<?php

namespace loveteemo;

;

class Robot
{
  private static $instance;

  //回调类型
  private $type;

  //回调来源 用户ID 群消息事件下，为群id
  private $from_wxid;

  //回调来源 昵称
  private $from_name;

  //回调来源 用户ID 群消息事件下，为发消息的成员id
  private $final_from_wxid;

  //回调来源 来源昵称
  private $final_from_name;

  //机器人id
  private $robot_wxid;

  //消息内容
  public $msg;

  //消息类型参考文档
  private $msg_type;

  //文件地址：可直接访问的网络地址
  private $file_url;

  //时间戳
  private $time;

  //调用接口
  private $url = "http://127.0.0.1:8073/send";

  //开启鉴权之后的鉴权参数
  private $key;

  public function __construct($url = "", $key = "", $idDebug = false)
  {
    if ($url != "") {
      $this->url = $url;
    }
    if ($key != "") {
      $this->key = $key;
    }
    if ($idDebug === true) {
      $this->parseWeChat($_POST);
    }
  }

  /**
   * 单例
   * @param string $url 参数
   * @param string $key
   * @param bool $debug
   * @return Robot
   */
  public static function getInstance(string $url = "", string $key = "", bool $debug = false): Robot
  {
    if (!isset(self::$instance)) {
      $instance = new self($url, $key, $debug);
      self::$instance = $instance;
    }
    return self::$instance;
  }

  /**
   * 解析回调消息
   * @param $data
   */
  public function parseWechat($data)
  {
    $this->type = $data['type'];
    $this->from_wxid = $data['from_wxid'];
    $this->from_name = urldecode($data['from_name']);
    $this->final_from_wxid = $data['final_from_wxid'];
    $this->final_from_name = urldecode($data['final_from_name']);
    $this->robot_wxid = $data['robot_wxid'];
    $this->msg = urldecode($data['msg']);
    $this->msg_type = intval($data['msg_type']);
    $this->file_url = $data['file_url'];
    $this->time = $data['time'];
  }


  // ---  以下为功能区

  /**
   * 发送文字消息(好友或者群)
   * @param string $msg 消息内容
   * @param string $robot_wxid
   * @param string $to_wxid
   * @return array
   */
  public function sendTextMsg(string $msg, string $robot_wxid = "", string $to_wxid = "")
  {
    $data = array();
    $data['type'] = 100;
    $data['msg'] = urlencode($msg);
    $data['to_wxid'] = $to_wxid ?: $this->from_wxid;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    return $this->sendRequest($data, 'post');
  }

  /**
   * 发送群消息并艾特某人
   * @param string $robot_wxid
   * @param string $group_wxid 群id
   * @param string $at_wxid 艾特的id，群成员的id
   * @param string $at_name 艾特的昵称，群成员的昵称
   * @param string $msg 消息内容
   * @return array
   */
  public function sendGroupAtMsg(string $robot_wxid, string $group_wxid, string $at_wxid, string $at_name, string $msg)
  {
    $data = array();
    $data['type'] = 102;
    $data['msg'] = urlencode($msg);
    $data['to_wxid'] = $group_wxid;
    $data['at_wxid'] = $at_wxid;
    $data['at_name'] = $at_name;
    $data['robot_wxid'] = $robot_wxid;
    return $this->sendRequest($data, 'post');
  }

  /**
   * 发送图片消息
   * @param string $img_url 图片的绝对路径，可以是本地图片地址/网络图片地址
   * @param string $robot_wxid
   * @param string $to_wxid
   * @return array
   */
  public function sendImageMsg(string $img_url, string $robot_wxid = "", string $to_wxid = "")
  {
    $data = array();
    $data['type'] = 103;
    $data['msg'] = $img_url;
    $data['to_wxid'] = $to_wxid ?: $this->from_wxid;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    return $this->sendRequest($data, 'post');
  }

  /**
   * 发送视频消息
   * @param string $mp4_path 视频文件的绝对路径
   * @param string $robot_wxid
   * @param string $to_wxid
   * @return array
   */
  public function sendVideoMsg(string $mp4_path, string $robot_wxid = "", string $to_wxid = "")
  {
    $data = array();
    $data['type'] = 104;
    $data['msg'] = $mp4_path;
    $data['to_wxid'] = $to_wxid ?: $this->from_wxid;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    return $this->sendRequest($data, 'post');
  }

  /**
   * 发送文件消息
   * @param string $file_path 文件的绝对路径
   * @param string $robot_wxid
   * @param string $to_wxid
   * @return array
   */
  public function sendFileMsg(string $file_path, string $robot_wxid = "", string $to_wxid = "")
  {
    $data = array();
    $data['type'] = 105;
    $data['msg'] = $file_path;
    $data['to_wxid'] = $to_wxid ?: $this->from_wxid;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 发送动态表情
   * @param string $path 动态表情文件的绝对路径
   * @param string $robot_wxid
   * @param string $to_wxid
   * @return array
   */
  public function sendEmojiMsg(string $path, string $robot_wxid = "", string $to_wxid = "")
  {
    $data = array();
    $data['type'] = 106;
    $data['msg'] = $path;
    $data['to_wxid'] = $to_wxid ?: $this->from_wxid;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 发送分享链接
   * @param string $title 链接标题
   * @param string $text 链接内容
   * @param string $target_url 跳转链接
   * @param string $pic_url 图片链接
   * @param string $robot_wxid
   * @param string $to_wxid
   * @return array
   */
  public function sendLinkMsg(string $title, string $text, string $target_url, string $pic_url, string $robot_wxid = "", string $to_wxid = "")
  {
    $link = array();
    $link['title'] = $title;
    $link['text'] = $text;
    $link['url'] = $target_url;
    $link['pic'] = $pic_url;

    $data = array();
    $data['type'] = 107;
    $data['msg'] = $link;
    $data['to_wxid'] = $to_wxid ?: $this->from_wxid;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 发送音乐分享
   * @param string $name 歌曲名字
   * @param string $robot_wxid
   * @param string $to_wxid
   * @return array
   */
  public function sendMusicMsg(string $name, string $robot_wxid = "", string $to_wxid = "")
  {
    $data = array();
    $data['type'] = 108;
    $data['msg'] = $name;
    $data['to_wxid'] = $to_wxid ?: $this->from_wxid;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 取指定登录账号的昵称
   * @param string $robot_wxid 账户id
   * @return array
   */
  public function getRobotName(string $robot_wxid = "")
  {
    $data = array();
    $data['type'] = 201;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 取指定登录账号的头像
   * @param string $robot_wxid 账户id
   * @return array
   */
  public function getRobotHeadImageUrl(string $robot_wxid = "")
  {
    $data = array();
    $data['type'] = 202;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 取登录账号列表
   * @return array
   */
  public function getLoggedAccountList()
  {
    $data = array();
    $data['type'] = 203;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 取好友列表
   * @param string $robot_wxid
   * @param int $is_refresh 是否刷新
   * @return array
   */
  public function getFriendList(string $robot_wxid = "", int $is_refresh = 0)
  {
    $data = array();
    $data['type'] = 204;
    $data['robot_wxid'] = $robot_wxid;
    $data['is_refresh'] = $is_refresh;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 取群聊列表
   * @param string $robot_wxid
   * @param int $is_refresh 是否刷新
   * @return array
   */
  public function getGroupList(string $robot_wxid = '', int $is_refresh = 0)
  {
    $data = array();
    $data['type'] = 205;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    $data['is_refresh'] = $is_refresh;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 取群成员列表
   * @param string $robot_wxid
   * @param string $group_wxid 群id
   * @param int $is_refresh 是否刷新
   * @return array
   */
  public function getGroupMemberList(string $robot_wxid, string $group_wxid, int $is_refresh = 0): array
  {
    $data = array();
    $data['type'] = 206;
    $data['robot_wxid'] = $robot_wxid;
    $data['group_wxid'] = $group_wxid;
    $data['is_refresh'] = $is_refresh;
    $result = $this->sendRequest($data, 'post');
    return json_decode($result, true);
  }


  /**
   * 取群成员资料
   * @param string $robot_wxid
   * @param string $group_wxid 群id
   * @param string $member_wxid 群成员id
   * @return array
   */
  public function getGroupMember(string $robot_wxid, string $group_wxid, string $member_wxid)
  {
    $data = array();
    $data['type'] = 207;
    $data['robot_wxid'] = $robot_wxid;
    $data['group_wxid'] = $group_wxid;
    $data['member_wxid'] = $member_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 接收好友转账
   * @param string $robot_wxid
   * @param string $friend_wxid 朋友id
   * @param string $json_string
   * @return array
   */
  public function acceptTransfer(string $robot_wxid = "", string $friend_wxid = '', string $json_string = '')
  {
    $data = array();
    $data['type'] = 301;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    $data['friend_wxid'] = $friend_wxid ?: $this->from_wxid;
    $data['msg'] = $json_string ?: $this->msg;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 同意群聊邀请
   * @param string $robot_wxid
   * @param string $json_string 同步消息事件中群聊邀请原消息
   * @return array
   */
  public function agreeGroupInvite(string $robot_wxid = "", string $json_string = '')
  {
    $data = array();
    $data['type'] = 302;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    $data['msg'] = $json_string ?: $this->msg;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 同意好友请求
   * @param string $robot_wxid 账户id
   * @param string $json_string 好友请求事件中原消息
   * @return array
   */
  public function agreeFriendVerify(string $robot_wxid = "", string $json_string = '')
  {
    $data = array();
    $data['type'] = 303;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    $data['msg'] = $json_string ?: $this->msg;
    return $this->sendRequest($data, 'post');
  }

  /**
   * 修改好友备注
   * @param string $robot_wxid
   * @param string $friend_wxid 好友id
   * @param string $note 新备注
   * @return array
   */
  public function modifyFriendNote(string $robot_wxid, string $friend_wxid, string $note)
  {
    $data = array();
    $data['type'] = 304;
    $data['robot_wxid'] = $robot_wxid;
    $data['friend_wxid'] = $friend_wxid;
    $data['note'] = $note;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 删除好友
   * @param string $robot_wxid
   * @param string $friend_wxid 好友id
   * @return array
   */
  public function deleteFriend(string $robot_wxid, string $friend_wxid)
  {
    $data = array();
    $data['type'] = 305;
    $data['robot_wxid'] = $robot_wxid;
    $data['friend_wxid'] = $friend_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 踢出群成员
   * @param string $member_wxid 群成员id
   * @param string $group_wxid 群id
   * @param string $robot_wxid
   * @return array
   */
  public function removeGroupMember(string $member_wxid, string $group_wxid = '', string $robot_wxid = "")
  {
    $data = array();
    $data['type'] = 306;
    $data['robot_wxid'] = $robot_wxid ?: $this->robot_wxid;
    $data['group_wxid'] = $group_wxid ?: $this->from_wxid;
    $data['member_wxid'] = $member_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 修改群名称
   * @param string $robot_wxid
   * @param string $group_wxid 群id
   * @param string $group_name 新群名
   * @return array
   */
  public function modifyGroupName(string $robot_wxid, string $group_wxid, string $group_name)
  {
    $data = array();
    $data['type'] = 307;
    $data['robot_wxid'] = $robot_wxid;
    $data['group_wxid'] = $group_wxid;
    $data['group_name'] = $group_name;
    return $this->sendRequest($data, 'post');
  }

  /**
   * 修改群公告
   * @param string $robot_wxid
   * @param string $group_wxid 群id
   * @param string $notice 新公告
   * @return array
   */
  public function modifyGroupNotice(string $robot_wxid, string $group_wxid, string $notice)
  {
    $data = array();
    $data['type'] = 308;
    $data['robot_wxid'] = $robot_wxid;
    $data['group_wxid'] = $group_wxid;
    $data['notice'] = $notice;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 建立新群
   * @param string $robot_wxid
   * @param array $friends 三个人及以上的好友id数组，['wxid_1xxx', 'wxid_2xxx', 'wxid_3xxx', 'wxid_4xxx']
   * @return array
   */
  public function buildingGroup(string $robot_wxid, array $friends)
  {
    $data = array();
    $data['type'] = 309;
    $data['robot_wxid'] = $robot_wxid;
    $data['friends'] = $friends;
    return $this->sendRequest($data, 'post');
  }


  /**
   * 退出群聊
   * @param string $robot_wxid 账户id
   * @param string $group_wxid 群id
   * @return array
   */
  public function quitGroup(string $robot_wxid, string $group_wxid)
  {
    $data = array();
    $data['type'] = 310;
    $data['robot_wxid'] = $robot_wxid;
    $data['group_wxid'] = $group_wxid;
    return $this->sendRequest($data, 'post');
  }

  /**
   * 邀请加入群聊
   * @param string $robot_wxid
   * @param string $group_wxid 群id
   * @param string $friend_wxid 好友id
   * @return array
   */
  public function inviteInGroup(string $robot_wxid, string $group_wxid, string $friend_wxid)
  {
    $data = array();
    $data['type'] = 311;
    $data['robot_wxid'] = $robot_wxid;
    $data['group_wxid'] = $group_wxid;
    $data['friend_wxid'] = $friend_wxid;
    return $this->sendRequest($data, 'post');
  }


  /**
   * HTTP请求
   * @param array $data
   * @param string $method 请求方法 post / get
   * @param int $timeout 超时时间
   * @return array|string
   */
  public function sendRequest(array $data, string $method = 'get', int $timeout = 3)
  {
    if ($this->key != "") {
      $data['key'] = $this->key;
    }
    $params = ['data' => json_encode($data)];
    $curl = curl_init();
    $is_https = stripos($this->url, 'https://') === 0;
    curl_setopt($curl, CURLOPT_URL, $this->url);
    if ('get' != $method) { //以POST方式发送请求
      curl_setopt($curl, CURLOPT_POST, 1); //post提交方式
      curl_setopt($curl, CURLOPT_POSTFIELDS, $params); //设置传送的参数
    }

    curl_setopt($curl, CURLOPT_HEADER, false); //设置header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout); //设置等待时间
    if ($is_https) {
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    }
    $res = curl_exec($curl); //运行curl
    $err = curl_error($curl);

    if (false === $res || !empty($err)) {
      return false;
    }
    curl_close($curl); //关闭curl
    $res = json_decode($res, true);
    $data = json_decode($res['data'], true);
    if ($data) {
      $res['data'] = $data;
    } else {
      $res['data'] = urldecode($res['data']);
    }
    return $res;
  }
}
