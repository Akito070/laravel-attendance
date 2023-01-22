<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     */
    protected $table = 'attendance';

    protected $fillable = [
        'user_id', 'attendance_day_of_week', 'attendance_start_time', 'attendance_break_time',
        'attendance_actualWork_time', 'attendance_end_time', 'attendance_remarks'
    ];

    /**
     * ユーザ関連付け
     * 1対多
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
