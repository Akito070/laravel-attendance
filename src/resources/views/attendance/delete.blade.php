{{-- 削除用モーダル --}}
<div class="modal" tabindex="-1" id="delete{{$attendance->attendance_id}}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">削除</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>本当に削除をしますか。</p>
        </div>
        <div class="modal-footer">
            <form action="{{ route('dailyAttendance.delete', ['id'=>$attendance->attendance_id]) }}" method="POST">
            @csrf
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                <input type="submit" class="btn btn-danger" class="btn btn-danger" value="削除">
            </form>
          
        </div>
      </div>
    </div>
  </div>