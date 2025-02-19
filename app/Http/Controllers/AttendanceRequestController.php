<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRequest;
use App\Models\User;
use App\Models\Attendance;

class AttendanceRequestController extends Controller
{

    public function store(Request $request)
    {
        // 既に申請があるかチェック
        $existingRequest = AttendanceRequest::where('user_id', Auth::id())
            ->where('date', now()->toDateString())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', '既に出勤申請中です。');
        }

        // 出勤申請を作成
        AttendanceRequest::create([
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'status' => 'pending',
        ]);

        // 管理者に通知を送る（仮）
        $admin = User::where('auth', 1)->first();
        if ($admin) {
            // 通知を送る処理（実装する場合はメール or リアルタイム通知）
        }

        return redirect()->back()->with('success', '出勤申請を送信しました。');
    }

    public function showRequests()
    {
        // 申請中の出勤リクエストを取得
        $requests = AttendanceRequest::where('status', 'pending')->get();
        return view('users.attendanceRequests', compact('requests'));
    }

    public function updateStatus($id, $status)
    {
        $request = AttendanceRequest::findOrFail($id);

        if (!in_array($status, ['approved', 'rejected'])) {
            return redirect()->back()->with('error', '無効な操作です。');
        }

        // ステータスを更新
        $request->update(['status' => $status]);

        if ($status == 'approved') {
            // 出勤情報を記録
            Attendance::create([
                'user_id' => $request->user_id,
                'date' => $request->date,
                'check_in' => now(), // 承認時の時間を出勤時間として登録
            ]);
        }

        return redirect()->back()->with('success', '申請を' . ($status == 'approved' ? '承認' : '拒否') . 'しました。');
    }
}
