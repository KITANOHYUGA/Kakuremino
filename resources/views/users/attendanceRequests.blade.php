@extends('adminlte::page') 

@section('content')
<div class="container">
    <h1>出勤申請一覧</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ユーザー名</th>
                <th>日付</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->date }}</td>
                    <td>{{ $request->status == 'pending' ? '申請中' : ($request->status == 'approved' ? '承認済み' : '拒否') }}</td>
                    <td>
                        <a href="{{ route('attendance.request.update', ['id' => $request->id, 'status' => 'approved']) }}" class="btn btn-success">承認</a>
                        <a href="{{ route('attendance.request.update', ['id' => $request->id, 'status' => 'rejected']) }}" class="btn btn-danger">拒否</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
