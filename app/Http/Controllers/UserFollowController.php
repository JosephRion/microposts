<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request; //これはコメントアウトするのか。
//Chapter 10.3 UserFollowController@store, destroy に掲載されいているコードのことですね。
//記述しても誤作動にはなりませんが、その use はこのクラスでは不要です。
//理由は、このクラスでは Illuminate\Http\Request というクラスを使っていないためです。
//mentor-sugimoto 2022.07.12..TKT09:02

class UserFollowController extends Controller
{
    //フォロー機能 開始タグ L15 C10.3
    /**
     * ユーザをフォローするアクション。
     *
     * @param  $id  相手ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        // 認証済みユーザ（閲覧者）が、 idのユーザをフォローする
        \Auth::user()->follow($id);
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * ユーザをアンフォローするアクション。
     *
     * @param  $id  相手ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 認証済みユーザ（閲覧者）が、 idのユーザをアンフォローする
        \Auth::user()->unfollow($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
    //フォロー機能 閉じタグ
}
