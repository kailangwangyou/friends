<?php

class WxOauth  {

    private $AppId;
    private $AppSecret;

    public function __construct($appId,$appSecret){
        $this->AppId = $appId;
        $this->AppSecret = $appSecret;

    }

    /**
     * 获取用户openid (静默授权)
     * @param $code
     * @return mixed array('token'=>'','openid'=>'')
     */
    public function getTokenOpenid($code){
        $url_code = $this->__CreateOauthUrlForOpenid($code);
        return $this->get_curl($url_code);

    }

    /**
     * 获取用户信息 (用户授权)
     * @param $code
     * @return mixed array('token'=>'','openid'=>'')
     */
    public function getUserInfo($code){
        $url_code = $this->__CreateOauthUrlForOpenid($code);
        $openData = $this->get_curl($url_code);
        $url_userInfo = $this->__CreateUserInfoUrlForOpenid($openData);
        return  $this->get_curl($url_userInfo);
    }

    /**
     * 获取code
     *
     */
    public function getCode($type="snsapi_base"){
        //触发微信返回code码
        //$baseUrl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . $_SERVER['QUERY_STRING']);
        $baseUrl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] );
        $url = $this->__CreateOauthUrlForCode($baseUrl,$type);
        Header("Location: $url");
        exit();
    }

    /**
     * 创建获取用户信息的url
     * @param $data
     * @return string
     */
    private function __CreateUserInfoUrlForOpenid($data){
        return "https://api.weixin.qq.com/sns/userinfo?access_token={$data['access_token']}&openid={$data['openid']}&lang=zh_CN";
    }


    /**
     * 创建获取code的链接
     * @param $redirectUrl
     * @return string
     */
    private function __CreateOauthUrlForCode($redirectUrl,$type = "snsapi_base")
    {
        $urlObj["appid"] = $this->AppId;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "{$type}";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }

    /**
     * 创建获取openid的链接
     * @param $code
     * @return string
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = $this->AppId;
        $urlObj["secret"] = $this->AppSecret;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }

    /**
     * 获取用户信息
     * @param $code
     * @return mixed
     */
    private function get_curl($url)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //运行curl，结果以json形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($res,true);
        return $data;
    }

    /**
     * @param $urlObj
     * @return string
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }




}