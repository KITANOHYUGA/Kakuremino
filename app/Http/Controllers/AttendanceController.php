<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // $today = date('Y-m-d');
        $userId = Auth::id();
        $today = Carbon::today()->format('Y-m-d');

        // 🔹 今日の出勤記録を取得（固定表示）
        $todayAttendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        // 現在の年と月を取得（リクエストがない場合は今月をデフォルト）
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // 指定された年と月の出勤記録を取得
        $attendances = Attendance::where('user_id', $userId, Auth::id())
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        // 🔹 今日の日付の出勤申請を取得（過去のデータを取得しないように修正）
        $pendingRequest = AttendanceRequest::where('user_id', $userId, Auth::id())
            ->where('date', $today) // ✅ 今日の申請のみ取得
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
            // dd($year, $month, Attendance::pluck('date'));
        return view('attendance.index', compact('todayAttendance','attendances','pendingRequest', 'year', 'month', 'today'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        if ($attendance->user_id != Auth::id()) {
            abort(403);
        }

        $attendance->update([
            'check_out' => now(),
        ]);

        return redirect()->back()->with('success', '退勤時間を記録しました。');
    }
}
