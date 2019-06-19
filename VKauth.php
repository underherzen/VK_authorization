<?php
require_once('functions.php');
require_once('VKauthSettings.php');
class VKauth
{
    private $client_secret = '';
    private $client_id = '';
    private $redirect_uri = '';
    private $scope = '';
    private $display = '';
    private $response_type = '';
    private $vkAPIversion = '';
    private $status = 0;  // [0 => 'NotAuthorized', 1=> 'JustAuthorized', 2=> 'Authorized']
    private $user_id = '';
    private $access_token = '';
    public
    function __construct()
    {
        $this->client_secret = VKauthSettings::get_client_secret();
        $this->client_id = VKauthSettings::get_client_id();
        $this->redirect_uri = VKauthSettings::get_redirect_uri();
        $this->scope = VKauthSettings::get_scope();
        $this->display = VKauthSettings::get_display();
        $this->response_type = VKauthSettings::get_response_type();
        $this->vkAPIversion = VKauthSettings::get_vkAPIversion();
    }
    function get_user_id()
    {
        return $this->user_id;
    }
    function get_access_token()
    {
        return $this->access_token;
    }
    function get_vkAPIversion()
    {
        return $this->vkAPIversion;
    }
    function apiRequest($method, $request_params)
    {
        $get_params = http_build_query($request_params);
        $result = json_decode(file_get_contents("https://api.vk.com/method/{$method}?{$get_params}"), TRUE);
        return $result;
    }
    function set_Cookie($user_token, $access_token, $user_id)
    {
        setcookie("user_token", $user_token, time()+3600*24);   //one day
        setcookie("access_token", $access_token, time()+3600*24);
        setcookie("user_id", $user_id, time()+3600*24);
        
    }
    function echo_auth_button()
    {
        echo "<a href='https://oauth.vk.com/authorize?client_id={$this->client_id}&display={$this->display}&redirect_uri={$this->redirect_uri}&scope={$this->scope}&response_type={$this->response_type}&v={$this->vkAPIversion}'><button onlick='return false;'>Авторизация</button></a>";
    }
    function getUserInfo($code) //access_token, user_id
    {
        $c = json_decode(file_get_contents("https://oauth.vk.com/access_token?client_id={$this->client_id}&client_secret={$this->client_secret}&redirect_uri={$this->redirect_uri}&code={$code}"), TRUE);
        return $c;
    }
    function setStatus($state)
    {
        if($state>2 or $state<0 or !is_int($state))
        {
            $this->status = 0;
            $this->set_Cookie('','','');
        }
        else
        {
          $this->status = intval($state); 
        }
        
    }
    function getStatus()
    {
        return $this->status;
    }
    function authorize()
    {
        if(isset($_GET['code']) && (!isset($_COOKIE['access_token']) || $_COOKIE['access_token'] == ''))
        {
            
            $this->setStatus(1);
            $userInfo = $this->getUserInfo($_GET['code']);
            $user_id = $userInfo['user_id'];
            $access_token = $userInfo['access_token'];
            $user_token=generateRandomString(80);
            $this->set_Cookie($user_token, $access_token, $user_id);
            $this->user_id = $user_id;
            $this->access_token = $access_token;
            
        }
        elseif(isset($_COOKIE['user_token']) && $_COOKIE['user_token'] != '' && $_COOKIE['access_token'] != '')
        {
            $this->setStatus(2);
            $this->user_id = $_COOKIE['user_id'];
            $this->access_token = $_COOKIE['access_token'];
        }
        elseif(!isset($_GET['code']))
        {
            $this->setStatus(0);
        }
        
    }
    
}
?>