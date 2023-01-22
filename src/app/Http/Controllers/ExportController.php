<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    /**
     * No 7-1
     * PDF
     */
    public function exportPDF(Request $request)
    {
        // 現在認証しているユーザーを取得
        $user = auth()->user();
        //表示している日付を検索
        $attendances = Attendance::where("attendance_start_time", "LIKE", "$request->getMonth%")->get();

        //月の日付を取得
        $getMonth = Carbon::create($request->getMonth)->isoFormat('Y年M月');
        //勤務時間の合計
        $work_time = Attendance::where('user_id', $user->id)
            ->where('attendance_start_time', 'LIKE', "$request->getMonth%")->sum('attendance_actualWork_time');

        //秒を時間に返す（時：分）
        $work_time_hour = mb_substr($work_time, 0, 2);
        $work_time_min  = mb_substr($work_time, 2, 2);

        $sumActualWorkTime = $work_time_hour . ':' . $work_time_min;

        $pdf = PDF::loadView('export.pdf', ['attendances' => $attendances], compact('user', 'getMonth', 'sumActualWorkTime'));
        // PDFをダウンロード
        $now = Carbon::now();
        return $pdf->download('勤務報告書_' . $now . 'pdf');
    }
}
