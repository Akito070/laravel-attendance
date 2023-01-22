<!doctype html>
<html lang="en">

<head>
    <title>Laravel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Styles -->
    <link href="{{ public_path('css/export/export.css') }}" rel="stylesheet">

    <title>勤務実績報告書</title>
    
    @if (request()->is('*/pdf'))
    <style type="text/css">
    @font-face {
        font-family: ipaexg;
        font-style: normal;
        font-weight: normal;
        src: url('{{ storage_path('fonts/ipaexg.ttf') }}') format('truetype');
    }
    @font-face {
        font-family: ipaexg;
        font-style: bold;
        font-weight: bold;
        src: url('{{ storage_path('fonts/ipaexg.ttf') }}') format('truetype');
    }
    body {
        font-family: ipaexg !important;
    }
   
    </style>

    <!-- PDFの場合はフルパスでCSSを指定する -->
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
    @else
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif
</head>

<body>
    <div class="container">
        <div class="title">勤務実績報告書</div>
        <div class="date">{{$getMonth}}</div>
        <p>氏名 {{$user->name}}</p>
        <table class="table">
            <thead>
                <tr>
                  <th scope="col">日</th>
                  <th scope="col">曜日</th>
                  <th scope="col">出勤時間</th>
                  <th scope="col">退勤時間</th>
                  <th scope="col">休憩時間</th>
                  <th scope="col">勤務時間</th>
                  <th scope="col">備考</th>
                </tr>
            </thead>
            @foreach($attendances as $attendance)
            <tbody>
                <tr>
                  <td>{{mb_substr($attendance->attendance_day_of_week,3,2)}}</td>
                  <td>{{mb_substr($attendance->attendance_day_of_week,6,1)}}</td>
                  <td>{{mb_substr($attendance->attendance_start_time, 11, 5)}}</td>
                  <td>{{mb_substr($attendance->attendance_end_time, 11, 5)}}</td>
                  <td>{{mb_substr($attendance->attendance_break_time,0,5)}}</td>
                  <td>{{mb_substr($attendance->attendance_actualWork_time,0,5)}}</td>
                  <td>{{$attendance->attendance_remarks}}</td>
                </tr>
            </tbody>
            @endforeach
            <th scope="row">合計</th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$sumActualWorkTime}}</td>
            <td></td>
          </table>
    </div>
</body>

</html>
