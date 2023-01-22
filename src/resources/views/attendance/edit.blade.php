{{-- 編集用のモーダル --}}
<div class="modal fade" id="edit{{$attendance->attendance_id}}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalLabel">編集</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('dailyAttendance.edit', ['id'=>$attendance->attendance_id]) }}">
                @csrf
                {{-- {{method_field('PATCH')}}        --}}
                    <div class="mb-3">
                        <label for="start_time" class="col-form-label">開始時間</label>
                        <input type="datetime-local" class="form-control" id="start_time" name="start" value="{{$attendance->attendance_start_time}}">
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="col-form-label">終了時間</label>
                        <input type="datetime-local" class="form-control" id="end_time" name="end" value="{{$attendance->attendance_end_time}}">
                    </div>
                    <div class="mb-3">
                        <label for="break" class="col-form-label">休憩時間</label>
                        <input type="time" class="form-control" name="break" id="break" value="{{$attendance->attendance_break_time}}">
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="col-form-label">備考</label>
                        <textarea class="form-control" name="remarks" id="remarks">{{$attendance->attendance_remarks}}</textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                
                        <input type="submit" class="btn btn-primary" value="更新">
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

