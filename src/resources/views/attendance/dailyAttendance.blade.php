@extends('layouts.app')
@section('content')
<div class="container">

  {{-- カレンダー --}}
  <div class="top">
    <div class="calendarButton">
      <form  action="{{route('dailyAttendance.prevMonth')}}" method="POST">
        @csrf
        <input id="prevMonth" type="hidden" name="getMonth"  value="{{$getMonth}}">
        <button type="submit" class="btn btn-dark-left"><i class="fa fa-chevron-left"></i></button>
      </form>

      <input class="form-control" type="month" name="calendar" value="{{$getMonth}}" oninput="changeValue()">    
      
      <form  action="{{route('dailyAttendance.nextMonth')}}" method="POST">
        @csrf
        <input id="nextMonth" type="hidden" name="getMonth" value="{{$getMonth}}">
        <button class="btn btn-dark-right"><i class="fa fa-chevron-right"></i></button>
     </form>  
    </div>

    {{-- 検索 --}}
    <div class="search">
      <form  action="{{route('dailyAttendance.search')}}" method="POST">
        @csrf
        <input id="searchCalendar" type="hidden" name="getMonth" value="{{$getMonth}}">
        <button type="submit" class="btn btn-dark"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
     </form>
    </div>
  </div>
  
    {{-- エクスポート --}}
    <div class="export">
      {{-- CSV --}}
      
     {{-- PDF --}}
        <form  action="{{route('dailyAttendance.pdf')}}" method="POST">
          @csrf
          <input type="hidden" name="getMonth" value="{{$getMonth}}">
          {{-- <a href="{{route('dailyAttendance.pdf')}}" class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i> PDF</a> --}}
          <button type="submit" class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i> PDF</button>
       </form>
      
    </div>
    <br>
    {{--　更新に成功したらメッセージを表示 --}}
    @if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('message')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    {{--　更新に失敗したらメッセージを表示 --}}
    @if (session('message-error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{session('message-error')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <table class="table">
        <thead class="table-dark">
            <tr>
              <th scope="col"></th>
              <th scope="col"></th>
              <th scope="col">日付</th>
              <th scope="col">出勤時間</th>
              <th scope="col">退勤時間</th>
              <th scope="col">休憩時間</th>
              <th scope="col">勤務時間</th>
              <th scope="col">備考</th>
              <th scope="col"></th>
            </tr>
        </thead>
        @foreach($attendances as $attendance)
        <tbody>
            <tr>
              <td></td>
              <td>
                <a href="#edit{{$attendance->attendance_id}}" data-bs-toggle="modal">
                  <i type="button" class="fa-solid fa-pen-to-square fa-xl"></i>
                </a>   
                {{-- 編集用のモーダル --}}
                @include('attendance.edit')
              </td>
              <td>{{$attendance->attendance_day_of_week}}</td>
              <td>{{mb_substr($attendance->attendance_start_time, 11, 5)}}</td>
              <td>{{mb_substr($attendance->attendance_end_time, 11, 5)}}</td>
              <td>{{mb_substr($attendance->attendance_break_time,0,5)}}</td>
              <td>{{mb_substr($attendance->attendance_actualWork_time,0,5)}}</td>
              <td>{{$attendance->attendance_remarks}}</td>
              <td>
                <a href="#delete{{$attendance->attendance_id}}" data-bs-toggle="modal">
                  <i type="button" class="fa-solid fa-trash fa-xl" style="color:red"></i>
                </a>
                    
                {{-- 削除用のモーダル --}}
                @include('attendance.delete')
              </td>
            </tr>
        </tbody>
        @endforeach
      </table>

</div>
<script>
  
    function changeValue() {
      
      //現在の日付を取得
      let date = document.getElementsByName('calendar');
      
      //prevMonth、nextMonth、searchCalendarの値を変更
      document.getElementById('prevMonth').value = date[0].value;
      document.getElementById('nextMonth').value = date[0].value;
      document.getElementById('searchCalendar').value = date[0].value;

    }
</script>
@endsection