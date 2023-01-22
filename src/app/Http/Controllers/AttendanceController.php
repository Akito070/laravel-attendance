<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Carbon;


class AttendanceController extends Controller
{
    /**
     * No 3-3
     * 日次勤怠画面を表示
     */
    public function dailyAttendance()
    {
        //ユーザ情報を取得
        $user = auth()->user();

        //現在の日時を取得
        $now = Carbon::now();
        //今月の日付を取得
        //$getMonth = $now->format('Y年m月');
        $getMonth = $now->format('Y-m');
        //月初の日時を取得
        $startMonth = $now->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
        //月末の日時を取得
        $endMonth = $now->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
        //今月の出勤時間を表示
        $attendances = Attendance::whereBetween('attendance_start_time', [$startMonth, $endMonth])
            ->where('user_id', $user->id)->get();

        return view('attendance.dailyAttendance', compact('getMonth', 'attendances'));
    }

    /**
     * No 4-1
     * 前の月の日付を表示
     */
    public function prevMonth(Request $request)
    {

        //表示している日付
        $getDate = $request->getMonth;
        //表示している年を取得
        $year = mb_substr($getDate, 0, 4);
        //表示している月を取得
        $month = mb_substr($getDate, 5);
        //日付をcarbonに代入
        $dateMonth = new Carbon($year . $month . '01');
        //一月前
        $dateMonth->subMonth();
        //月の日付を取得
        $getMonth = $dateMonth->format('Y-m');

        //月初の日時を取得
        $startMonth = $dateMonth->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');

        //月末の日時を取得
        $endMonth = $dateMonth->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');

        //月の出勤時間を表示
        $attendances = Attendance::whereBetween('attendance_start_time', [$startMonth, $endMonth])->get();

        return view('attendance.dailyAttendance', compact('getMonth', 'attendances'));
    }

    /**
     * No 4-3
     * 次の月の日付を表示
     */
    public function nextMonth(Request $request)
    {
        //表示している日付
        $getDate = $request->getMonth;
        //表示している年を取得
        $year = mb_substr($getDate, 0, 4);
        //表示している月を取得
        $month = mb_substr($getDate, 5);
        //日付をcarbonに代入
        $dateMonth = new Carbon($year . $month . '01');
        //一月前
        $dateMonth->addMonth();
        //月の日付を取得
        $getMonth = $dateMonth->format('Y-m');
        //月初の日時を取得
        $startMonth = $dateMonth->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');

        //月末の日時を取得
        $endMonth = $dateMonth->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');

        //月の出勤時間を表示
        $attendances = Attendance::whereBetween('attendance_start_time', [$startMonth, $endMonth])->get();

        return view('attendance.dailyAttendance', compact('getMonth', 'attendances'));
    }



    /**
     * No 4-4
     * 勤怠検索
     */
    public function searchCalendar(Request $request)
    {
        //表示している日付
        $getMonth = $request->getMonth;
        $attendances = Attendance::where("attendance_start_time", "LIKE", "$request->getMonth%")->get();
        return view('attendance.dailyAttendance', compact('attendances', 'getMonth'));
    }

    /**
     * No 5
     * 勤怠編集処理
     */
    public function editAttendace(Request $request, $id)
    {
        //開始時間に入力した値を取得
        $attendanceStart = $request->input('start');

        //勤務開始の日付を取得
        $dateStart = mb_substr($attendanceStart, 0, 10);

        //開始時間に入力した値を取得
        $attendanceEnd = $request->input('end');

        //勤務終了の日付を取得
        $dateEnd = mb_substr($attendanceEnd, 0, 10);

        //曜日を取得
        $dayOfWeek = Carbon::create($attendanceStart)->isoFormat('MM/DD(ddd)');

        //勤務時間の更新では、出勤時間と退勤時間は同じ日付ではないといけない
        if ($dateStart != $dateEnd) {
            return redirect()->route('attendance.dailyAttendance')->with('message-error', '勤務時間を確認してください。');
        }

        //出勤時間
        $attendanceStart = $request->input('start');

        $timeStart = new carbon(mb_substr($attendanceStart, 11, 5));

        //退勤時間
        $attendanceEnd = $request->input('end');
        $timeEnd = new carbon(mb_substr($attendanceEnd, 11, 5));


        //出勤時間
        $timeStartReset = new carbon($dateStart . '00:00:00');
        //現在の時間を取得
        $timeStartHour = $timeStart->hour;
        //現在の分を取得
        $timeStartMinute = $timeStart->minute;
        //出勤時間の分は00、15、30、45
        if ($timeStartMinute < 15) {
            $timeStartMinute = 00;
        } else if ($timeStartMinute < 30) {
            $timeStartMinute = 15;
        } else if ($timeStart->minute < 45) {
            $timeStartMinute = 30;
        } else {
            $timeStartMinute = 45;
        }
        $timeStartReset->addHour($timeStartHour)->addMinute($timeStartMinute);

        //退勤時間
        $timeEndReset = new carbon($dateEnd . '00:00:00');

        //退勤の時間を取得
        $timeEndHour = $timeEnd->hour;
        //退勤の分を取得
        $timeEndMinute = $timeEnd->minute;
        //退勤時間の分は00、15、30、45
        if ($timeEndMinute < 15) {
            $timeEndMinute = 00;
        } else if ($timeEndMinute < 30) {
            $timeEndMinute = 15;
        } else if ($timeEnd->minute < 45) {
            $timeEndMinute = 30;
        } else {
            $timeEndMinute = 45;
        }

        $timeEndReset->addHour($timeEndHour)->addMinute($timeEndMinute);

        // 勤務時間
        $work_time_sec = $timeStart->diffInSeconds($timeEnd);
        $work_time_hour = floor($work_time_sec / 3600);
        $work_time_min  = floor(($work_time_sec - ($work_time_hour * 3600)) / 60);

        //勤務時間が3時間以下なら休憩なし
        if ($work_time_sec <= 3600) {
            $break_time = '0:00';
        } else {
            //勤務時間が3時間以降なら休憩あり
            $break_time = '1:00';
        }

        Attendance::where('attendance_id', '=', $id)->update([
            'attendance_day_of_week' => $dayOfWeek,
            'attendance_start_time' => $timeStartReset,
            'attendance_end_time' => $timeEndReset,
            'attendance_break_time' => $break_time,
            'attendance_actualWork_time' => $work_time_hour . ':' . $work_time_min,
            'attendance_remarks' => $request->input('remarks')
        ]);


        return redirect()->route('attendance.dailyAttendance')->with('message', '更新が完了しました');
    }


    /**
     * No 6
     * 削除処理
     */
    public function deleteAttendance($id)
    {
        Attendance::where('attendance_id', $id)->delete();

        return redirect()->route('attendance.dailyAttendance')->with('message', '削除ができました。');
    }
}
