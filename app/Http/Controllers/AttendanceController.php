<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::where('user_id', Auth::id())->get();
        return view('attendance.index', compact('attendances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        // 今日の出勤データが既に存在するか確認
        $existingAttendance = Attendance::where('user_id', Auth::id())
        ->where('date', $request->date)
        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', '既に出勤記録があります。');
        }

        // 出勤データの作成
        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'check_in' => now(),
        ]);

        return redirect()->back()->with('success', '出勤時間を記録しました。');
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
