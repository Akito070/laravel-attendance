<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();
Route::get('/home', [HomeController::class, 'index']);

/**
 * No 3
 * 勤怠管理システムのホームページ
 */
Route::group(['middleware' => 'auth'], function () {

    /**
     * 勤怠管理システムのホームページを表示
     */
    Route::get('/', [HomeController::class, 'index'])->name('home');

    /**
     * No 3-1
     * 出勤ボタン
     */
    Route::post('/attendance/time-start', [HomeController::class, 'timeStart'])->name('attendance.timeStart');
    /**
     * No 3-2
     * 退勤ボタン
     */
    Route::post('/attendance/time-end', [HomeController::class, 'timeEnd'])->name('attendance.timeEnd');
    /**
     * No 3-3
     * 日次勤怠画面を表示
     */
    Route::get('/attendance/dailyAttendance', [AttendanceController::class, 'dailyAttendance'])->name('attendance.dailyAttendance');
    /**
     * No 4-1
     * 日次勤怠画面の前ボタン
     */
    Route::post('/attendance/dailyAttendance/prevMonth', [AttendanceController::class, 'prevMonth'])->name('dailyAttendance.prevMonth');
    /**
     * No 4-3
     * 日次勤怠画面の次ボタン
     */
    Route::post('/attendance/dailyAttendance/nextMonth', [AttendanceController::class, 'nextMonth'])->name('dailyAttendance.nextMonth');

    /**
     * No 4-4
     * 勤怠検索
     */
    Route::post('/attendance/dailyAttendance/search', [AttendanceController::class, 'searchCalendar'])->name('dailyAttendance.search');

    /**
     * No 5
     * 勤怠の編集ボタン
     */
    Route::post('/attendance/dailyAttendance/edit/{id}', [AttendanceController::class, 'editAttendace'])->name('dailyAttendance.edit');

    /**
     * No 6
     * 勤怠の削除ボタン
     */
    Route::post('/attendance/dailyAttendance/delete/{id}', [AttendanceController::class, 'deleteAttendance'])->name('dailyAttendance.delete');

    /**
     * No 7-1
     * PDF
     */
    Route::post('/attendance/dailyAttendance/pdf', [ExportController::class, 'exportPDF'])->name('dailyAttendance.pdf');
});
