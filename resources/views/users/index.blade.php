@extends('adminlte::page') 

@section('content')
<div class="container">
    <h1>ユーザー一覧</h1>

    <!-- 学年ごとのボタン -->
    <div class="mb-3">
        <p>学年を選択してください:</p>
        @foreach ($users as $grade => $students)
            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalGrade{{ $grade }}">
                {{ $grade == 'other' ? 'その他' : $grade . '年' }}
            </button>
        @endforeach
    </div>

    <!-- 学年ごとのモーダル -->
    @foreach ($users as $grade => $students)
        <div class="modal fade" id="modalGrade{{ $grade }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $grade }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel{{ $grade }}">{{ $grade == 'other' ? 'その他' : $grade . '年' }} のユーザー</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @if (count($students) > 0)
                                @foreach ($students as $user)
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><strong>名前:</strong> {{ $user->name }}</h5>
                                                <p class="card-text"><strong>学年:</strong> {{ $user->grade == 'other' ? 'その他' :  'その他' }}</p>
                                                <p class="card-text"><strong>メール:</strong> {{ $user->email }}</p>
                                                <p class="card-text"><strong>登録日:</strong> {{ $user->created_at->format('Y-m-d') }}</p>
                                                <p class="card-text"><strong>役割:</strong> {{ $user->role ?? '未設定' }}</p>
                                                <a href="#" class="btn btn-primary">詳細を見る</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>この学年には登録されたユーザーがいません。</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@stop
