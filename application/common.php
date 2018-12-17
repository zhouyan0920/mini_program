<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//返回失败信息，json格式
function error_msg($msg){
	return json(["state"=>0,"err_msg"=>$msg]);
}
//返回成功信息
function success_msg($key=null,$val=null){
	$arr=["state"=>1];
	if($key && $val) $arr[$key]=$val;
	return json($arr);
}

function curl($url,$post = null,$header = null){
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL,$url );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER,true );
    if(strpos( $url,"https") === 0 ){
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,false );
    }
    if($post){
        curl_setopt( $ch,CURLOPT_POST,true );
        curl_setopt( $ch,CURLOPT_POSTFIELDS,$post );
    }
    if($header) curl_setopt( $url, CURLOPT_HTTPHEADER, $header );
    $res = curl_exec($ch);
    if( curl_errno($ch) ){
        curl_close($ch);
        return curl_error($ch);
    }
    curl_close($ch);
    return $res;
}

//获取小程序access_token
function get_access_token($app_accounts_id){
    //DO 1.连接本地的 Redis 服务
    $redis=new Redis();
    $redis->connect('127.0.0.1',6379);

    //DO 2.从数据库中找到$app_id、$app_secret
    $program = db('xcx') -> where('id',$app_accounts_id) -> find();
    $app_id = $program['app_id'];
    $app_secret = $program['app_secret'];
    $exitTime=7100; //有效期为2个小时7200
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$app_id.'&secret='.$app_secret;

    //DO 3.判断redis长度是否>0？
    if($redis->strlen($app_id) > 0){
        $token_str = json_decode( get($app_id),true );
        $access_token = $token_str[0];
        $expires_in = $token_str[1];
    }

    //DO 4.判断access_token是否在有效期内
    if(isset( $access_token) && ($expires_in + $exitTime) > time() ) {
        return $access_token;
    }else{
        //DO 5.得到access_token
        $res = curl($url);
        $res = json_decode($res,true);//把json格式的字符串转为数组
        $access_token = $res['access_token'];
        $expires_in = $res['expires_in'];//7200有效期
        //DO 6.把$access_token存入redis,key为$app_id，保证token的唯一性
        $redis -> set($app_id,json_encode([$access_token,$expires_in]));
        return $access_token;
    }



}

//获取openid、session_key
function get_openid($app_accounts_id,$code){
    //DO 1.从数据库中找到$app_id、$app_secret
    $program = db('xcx') -> where('id',$app_accounts_id) -> find();
    $app_id = $program['app_id'];
    $app_secret = $program['app_secret'];
    //DO 2.请求地址
    $url='https://api.weixin.qq.com/sns/jscode2session?appid='.$app_id.'&secret='.$app_secret.'&js_code='.$code.'&grant_type=authorization_code';
    $res = curl($url);
    //DO 3.执行请求地址返回结果
    $resp_json = [
        'openid' => $res['openid'],
        'session_key' => $res['session_key'],
        'unionid' => $res['unionid'],
        'errcode' => $res['errcode'],
        'errmsg' => $res['errmsg']
    ];
    return json_encode($resp_json);
}
