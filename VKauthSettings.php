<?php
class VKauthSettings
{
    static protected $client_secret = 'cG4g5cQvF3Lwc6L0yoJg';
    static protected $client_id = '7012192';
    static protected $redirect_uri = 'https://msimoneyduck.xyz/';
    static protected $scope = '2';
    static protected $display = 'page';
    static protected $response_type = 'code';
    static protected $vkAPIversion = '5.95';
    public
    static function get_client_secret()
    {
        return self::$client_secret;
    }
    public
    static function get_client_id()
    {
        return self::$client_id;
    }
    public
    static function get_redirect_uri()
    {
        return self::$redirect_uri;
    }
    public
    static function get_scope()
    {
        return self::$scope;
    }
    public
    static function get_display()
    {
        return self::$display;
    }
    public
    static function get_response_type()
    {
        return self::$response_type;
    }
    public
    static function get_vkAPIversion()
    {
        return self::$vkAPIversion;
    }
}

?>