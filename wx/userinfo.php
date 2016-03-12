<?php

require_once "token.php";
	
class WxUser
{
	/**
	 * 
	 * 网页授权接口微信服务器返回的数据，返回样例如下
	 *{
	 *	"openid":" OPENID",		
	 *	"nickname": NICKNAME,	用户昵称
	 *	"sex":"1",				用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
	 *	"province":"PROVINCE"	用户个人资料填写的省份
	 *	"city":"CITY",			普通用户个人资料填写的城市
	 *	"country":"COUNTRY",	国家，如中国为CN
	 *	"headimgurl":"http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46", 用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
	 *	"privilege":[	用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）
	 *	"PRIVILEGE1"
	 *	"PRIVILEGE2"
	 *	],
	 *	"unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"	只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。
	 *}
	 */
	 
	public $openid=WxToken::GetOpenid();
	$data=WxToken::$data;
	public $token=$data["access_token"];
	
	/**
	 * 
	 * 通过openid从工作平台获取用户资料
	 * 
	 * @return u_data	用户资料的json格式数据
	 */
	public function GetUserInfo()
	{
		
		$url = $this->__CreateUserUrlForOpenid();
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if(WxConfig::CURL_PROXY_HOST != "0.0.0.0" && WxConfig::CURL_PROXY_PORT != 0){
			curl_setopt($ch,CURLOPT_PROXY, WxConfig::CURL_PROXY_HOST);
			curl_setopt($ch,CURLOPT_PROXYPORT, WxConfig::CURL_PROXY_PORT);
		}
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$u_data = json_decode($res,true);
		return $u_data;
	}
	
	/**
	 * 
	 * 构造获取用户信息的url地址
	 * @param string $openid，微信跳转带回的openid
	 * 
	 * @return 请求的url
	 */
	private function __CreateUserUrlForOpenid()
	{		
		return "https://api.weixin.qq.com/sns/userinfo?access_token=".$this->token."&openid=".$this->openid."&lang=zh_CN";
	}
}

?>
