@extends('adminlte::page') 

@section('content')
<div class="container">
    <h1>勤怠管理</h1>

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('attendance.request') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="date">日付:</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required readonly>
        </div>

        @if ($pendingRequest && $pendingRequest->status == 'pending')
            <button type="button" class="btn btn-secondary" disabled>申請中</button>
        @elseif ($pendingRequest && $pendingRequest->status == 'approved')
            <button type="button" class="btn btn-success" disabled>出勤承認済み</button>
        @else
            <button type="submit" class="btn btn-primary">出勤申請</button>
        @endif
    </form>

    <table class="table mt-4">
        <thead>
        <tr>
            <th colspan="4">今日の記録</th>
        </tr>
            <tr>
                <th>日付</th>
                <th>出勤時間</th>
                <th>退勤時間</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody>
        <!-- 今日の出勤・退勤 -->
            @if ($todayAttendance)
                <tr>
                    <td>{{ $todayAttendance->date }}</td>
                    <td>{{ $todayAttendance->check_in ?? '未登録' }}</td>
                    <td>{{ $todayAttendance->check_out ?? '未登録' }}</td>
                    <td>
                        @if (!$todayAttendance->check_out)
                            <form action="{{ route('attendance.update', $todayAttendance->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger">退勤記録</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="4">今日の出勤記録はありません。</td>
                </tr>
            @endif
        </tbody>
    </table>

        <form action="{{ route('attendance.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="month">月を選択:</label>
                    <select name="month" id="month" class="form-control">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ $m }}月
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="year">年を選択:</label>
                    <select name="year" id="year" class="form-control">
                        @for ($y = Carbon\Carbon::now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                {{ $y }}年
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">表示</button>
                </div>
            </div>
        </form>

        <!-- 過去の記録 -->
        <table class="table mt-4">
            <thead>
                <tr>
                    <th colspan="4">{{ $year }}年{{ $month }}月の記録</th>
                </tr>
                <tr>
                    <th>日付</th>
                    <th>出勤時間</th>
                    <th>退勤時間</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->date }}</td>
                        <td>{{ $attendance->check_in ?? '未登録' }}</td>
                        <td>{{ $attendance->check_out ?? '未登録' }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
@endsection

@section('css')
@stop

@section('js')
@stop
