<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('VKauth.php');
$vk = new VKauth();
$vk->authorize();
$status = $vk->getStatus();
if($status == 1 || $status == 2)  // [0 => 'NotAuthorized', 1=> 'JustAuthorized', 2=> 'Authorized']
{
    $request_params = array(
                    'user_id' => $vk->get_user_id(),
                    'v' => $vk->get_vkAPIversion(),
                    'access_token' => $vk->get_access_token()
                    );
    $result = $vk->apiRequest('users.get', $request_params);
    $user_first_name = $result['response'][0]['first_name'];
    $user_last_name = $result['response'][0]['last_name'];
    $user_name = $user_first_name.' '.$user_last_name;
    $request_params = array(
                            'user_id' => $vk->get_user_id(),
                            'fields' => 'domain',
                            'v' => $vk->get_vkAPIversion(),
                            'order' => 'random',
                            'count' => 5,
                            'access_token' => $vk->get_access_token()
                            );
    $random_five_friends = $vk->apiRequest('friends.get', $request_params);
    $random_five_friends = $random_five_friends['response']['items'];
    $html = $user_name."</br>";
    $html.= "5 random friends: </br>";
    foreach($random_five_friends as $friend)
    {
        $html.= "<a href='https://vk.com/{$friend['domain']}'>{$friend['first_name']} {$friend['last_name']}</a></br>";
    }
    echo $html;
}
elseif($status == 0)
{
    $vk->echo_auth_button();
}

?>





