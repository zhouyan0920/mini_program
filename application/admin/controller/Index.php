<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Controller
{
	//显示登录
    public function login(){
        return $this->fetch();
    }
	//显示主页
    public function index(){
        //获取公众号id
        $account_id = input( "account_id" );
        //查询游戏种类
        $games = db( "game_events" )->field( "id,name" )->where( "account_id" , $account_id )->select();
        $this->assign ( ["games" => $games] );
        return $this->fetch();
    }
    //欢迎界面
    public function welcome(){
        return $this->fetch();
    }
    //登录验证
    public function loginCheck(){
    	return success_msg();
    }
    //退出
    public function quit(){
        //清空管理员的session
    	session( "admin" , null );
        //重定向回登陆界面
        return $this->redirect( "login" );
    }
    //游戏详情信息
    public function gameinfo(){
        //接收游戏活动id
        $gameId = input( "id" );
        //查找当前游戏活动信息
        $gameinfo = db( "game_events" )->field( "id,name,description,type,eff_at,exp_at" )
                    ->where( ["id" => $gameId] )
                    ->find();
        $this->assign( ["gameinfo" => $gameinfo] );
        return $this->fetch();
    }
    //删除游戏活动
    public function delGame(){
        //取出游戏id
        $gameId = input( "id" );
        //删除
        $result = db( "game_events" )->where( "id" , $gameId )->delete();
        //结果等于1，表示删除了一条，则成功
        if($result == 1) return success_msg();
        //否则返回失败
        return error_msg("删除失败");
    }
    //保存游戏活动id到session
    public function saveId(){
        $gameId = input( "id" );
        session( "gameId" , $gameId );
    }
    //游戏编辑页面
    public function gameedit(){
        $gameId = session( "gameId" );
        //查看游戏信息
        $gameinfo = db( "game_events" )
                    ->field( "id,name,description,remark,type,integral,user_times,
                            share_times,need_subscribe,need_bind,share_image,share_title,
                            share_desc,theme_id,draw_id,eff_at,exp_at,eff_at,exp_at,ext_value" )
                    ->where( ["id" => $gameId] )
                    ->find();
        //所有模板
        $themes = db( "event_themes" )->field( "id" )->select();
        //所有抽奖活动
        $draws = db( "events" )->field( "id" )->select();
        $this->assign( ["gameinfo" => $gameinfo , "themes" => $themes , "draws" => $draws] );
        return $this->fetch();
    }
    //修改游戏信息
    public function changeGameInfo(){
        $id = session( "gameId" );
        //获取所有数据
        $data = [
            "name" => input( "name" ),
            "description" => input( "description" ),
            "remark" => input( "remark" ),
            "type" => input( "type" ),
            "integral" => input( "integral" ),
            "user_times" => input( "user_times" ),
            "share_times" => input( "share_times" ),
            "need_subscribe" => input( "need_subscribe" ),
            "need_bind" => input( "need_bind" ),
            "share_title" => input( "share_title" ),
            "theme_id" => input( "theme_id" ),
            "draw_id" => input( "draw_id" ),
            "eff_at" => input( "eff_at" ),
            "exp_at" => input( "exp_at" ),
            "ext_value" => input( "ext_value" )
        ];
        //更新
        $res = db( "game_events" )->where( "id" , $id )->update( $data );
        //判断更新的条数
        if($res == 1)return success_msg();
        if($res == 0)return json(["state"=>-1]);
        return error_msg("修改失败");
    }
}
