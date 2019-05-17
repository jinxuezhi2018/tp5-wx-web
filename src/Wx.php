<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 2018/12/28
 * Time: 14:01
 */

namespace xuezhitech\wx;

class Wx
{
    /**
     * 获得Code
     */
    public function getCode($redirect_uri,$scope='snsapi_base'){
        $app_id = Config::get('config.wx.app_id');
        $state = md5(uniqid());
        $redirect_uri = urlencode($redirect_uri);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$app_id}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
        return $url;
    }
    /**
     * 获得AccessToken
     */
    public function getAccessToken($code){
        $app_id = Config::get('config.wx.app_id');
        $secret = Config::get('config.wx.secret');
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$app_id}&secret={$secret}&code={$code}&grant_type=authorization_code";
        return $this->getCurlInfo($url);
    }

    public function getUserInfo($access_token,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        return $this->getCurlInfo($url);
    }

    private function getCurlInfo($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response,true);
    }
}
