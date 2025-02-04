<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class AdminController extends Controller
{
    public function index()
    {
        // 全ユーザーを取得
        $users = User::all()->groupBy('grade');

        // ビューにデータを渡す
        return view('users.index', compact('users'));
    }

}
