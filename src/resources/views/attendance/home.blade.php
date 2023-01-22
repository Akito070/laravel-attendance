@extends('layouts.app')
@section('content')
<div class="container">
    

    {{--　登録に成功したらメッセージを表示 --}}
    @if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('message')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    {{--　登録に失敗したらメッセージを表示 --}}
    @if (session('message-error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{session('message-error')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="body-clock">
        <div class="clock">
            <p class="clock-date"></p>
            <p class="clock-time"></p>
        </div>
    </div>
    <div class="container text-center">
        <form  action="{{route('attendance.timeStart')}}" method="POST">
            @csrf
            <button id="button" class="btn btn-dark w-100" type="submit"><h1 class="display-3">出勤</h1></button>
        </form>
        
        <form  action="{{route('attendance.timeEnd')}}" method="POST">
            @csrf
            <button id="button" class="btn btn-dark w-100" type="submit"><h1 class="display-3">退勤</h1></button>
        </form>
    </div>
    </div>
    <div class="calendar-time text-center">
        <br>
        {{-- 一時的非表示 --}}
        <a href="{{route('attendance.dailyAttendance')}}" style="display: none">
            <button class="btn btn-outline-info w-50" type="button"><h2 class="display-4"><i class="fas fa-calendar-check"></i>日次勤怠</h2></button>
        </a>
    </div>
</div>
@endsection