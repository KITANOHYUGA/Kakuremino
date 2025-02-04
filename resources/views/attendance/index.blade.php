@extends('adminlte::page') 

@section('content')
<div class="container">
    <h1>勤怠管理</h1>

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="date">日付:</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required readonly>
        </div>
        <button type="submit" class="btn btn-primary">出勤</button>
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
        @php
            $today = \Carbon\Carbon::today()->format('Y-m-d');
        @endphp
        @foreach ($attendances as $attendance)
            @if ($attendance->date === $today)
                <tr>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->check_in }}</td>
                    <td>{{ $attendance->check_out }}</td>
                    <td>
                        @if (!$attendance->check_out)
                            <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger" >退勤記録</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        </table>

        <!-- 過去の記録 -->
        <table class="table mt-4">
        <thead>
        <tr>
            <th colspan="4">過去の記録</th>
        </tr>
            <tr>
                <th>日付</th>
                <th>出勤時間</th>
                <th>退勤時間</th>
                <th>操作</th>
            </tr>
        </thead>
        @foreach ($attendances as $attendance)
            @if ($attendance->date !== $today)
                <tr>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->check_in }}</td>
                    <td>{{ $attendance->check_out }}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
    </tbody>
    </table>
</div>
@endsection

@section('css')
@stop

@section('js')
@stop
