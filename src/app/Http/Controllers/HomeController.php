<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Carbon;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * No 3
     *勤怠管理システムのホーム画面を表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('attendance.home');
    }

    /**
     * No 3-1
     * 勤怠管理システム画面の出勤登録処理
     */
    public function timeStart()
    {
        // 現在認証しているユーザーを取得
        $user = auth()->user();
        //現在ログインしている人のレコードを最新順に並び替えて、最新レコードを取得
        $oldTimestamp = Attendance::where('user_id', $user->id)->latest()->first();

        //デフォルトで出勤時間が9:00
        $workTimeStart = Carbon::today();
        //出勤時間を登録
        $timeStart = Carbon::today(); //例：2022/12/20 00:00:00
        $dayOfWeek = Carbon::now()->isoFormat('MM/DD(ddd)');

        //現在の時間を取得
        $timeStartHour = Carbon::now()->hour;
        //現在の分を取得
        $timeStartMinute = Carbon::now()->minute;
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

        $timeStart->addHour($timeStartHour)->addMinute($timeStartMinute);

        if ($oldTimestamp) {
            //登録した出勤日時を取得　例:2022-10-28 11:45
            $oldTimestampPunchIn = new Carbon($oldTimestamp->attendance_start_time);

            //1日の中の一番初めの時刻（00:00:00） 例:2022-10-28 00:00:00を取得
            $oldTimestampDay = $oldTimestampPunchIn->startOfDay();
        } else {

            //デフォルト設定勤務開始時間 9:00
            if ($timeStart > $workTimeStart->addHour(9)) {
                $remarks = '遅刻';
            } else {
                $remarks = '';
            }

            //出勤時間を登録
            $attendanceTimeStart = Attendance::create([
                'user_id' => $user->id,
                'attendance_day_of_week' => $dayOfWeek,
                'attendance_start_time' => $timeStart,
                'attendance_break_time' => '1:00',
                'attendance_remarks' => $remarks
            ]);

            //ßdd($attendanceTimeStart->attendance_start_time);
            return redirect()->back()->with('message', '出勤打刻が完了しました');
        }

        //出勤ボタンを押下で、日付を取得
        $newTimestampDay = Carbon::today();

        /**
         * 日付を比較する。同日付の出勤打刻で、かつ直前のTimestampの退勤打刻がされていない場合エラーを吐き出す。
         */
        if (($oldTimestampDay == $newTimestampDay) && (!empty($oldTimestamp->attendance_start_time))) {
            return redirect()->back()->with('message-error', 'すでに出勤打刻がされています');
        }

        /**
         * 本日に一回も出勤時間登録していない場合は、登録する。
         */
        if ($timeStart > $workTimeStart->addHour(9)) {
            $remarks = '遅刻';
        } else {
            $remarks = '';
        }
        $attendanceTimeStart = Attendance::create([
            'user_id' => $user->id,
            'attendance_day_of_week' => $dayOfWeek,
            'attendance_start_time' => $timeStart,
            'attendance_break_time' => '1:00',
            'attendance_remarks' => $remarks
        ]);
        return redirect()->back()->with('message', '出勤打刻が完了しました');
    }


    /**
     * No 3-2
     * 勤怠管理システム画面の退勤登録処理
     */
    public function timeEnd()
    {
        // 現在認証しているユーザーを取得
        $user = auth()->user();

        //現在ログインしている人のレコードを最新順に並び替えて、最新レコードを取得
        $timestamp = Attendance::where('user_id', $user->id)->latest()->first();


        //退勤時間のデータがあるか、確認
        if (!empty($timestamp->attendance_end_time)) {
            return redirect()->back()->with('message-error', '既に退勤の打刻がされているか、出勤打刻されていません');
        }
        //Attendance_idを取得
        $attendanceId = $timestamp->attendance_id;

        //出勤時間
        $timeStart = new carbon($timestamp->attendance_start_time);

        // 勤務時間
        $timeEnd = Carbon::now();
        $work_time_sec = $timeStart->diffInSeconds($timeEnd);
        $work_time_hour = floor($work_time_sec / 3600);
        $work_time_min  = floor(($work_time_sec - ($work_time_hour * 3600)) / 60);
        $work_time_s  = $work_time_sec - ($work_time_hour * 3600 + $work_time_min * 60);

        //勤務時間が3時間以下なら休憩なし
        if ($work_time_sec <= 3600) {
            $break_time = '0:00';
        } else {
            //勤務時間が3時間以降なら休憩あり
            $break_time = '1:00';
        }

        //退勤時間を登録
        $timeEnd = Carbon::today(); //例：2022/12/20 00:00:00
        //現在の時間を取得
        $timeEndHour = Carbon::now()->hour;
        //現在の分を取得
        $timeEndMinute = Carbon::now()->minute;
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

        $timeEnd->addHour($timeEndHour)->addMinute($timeEndMinute);

        Attendance::where('attendance_id', '=', $attendanceId)->update([
            'attendance_end_time' => $timeEnd,
            'attendance_break_time' => $break_time,
            'attendance_actualWork_time' => $work_time_hour . ':' . $work_time_min
        ]);

        return redirect()->back()->with('message', '退勤打刻が完了しました');
    }
}
