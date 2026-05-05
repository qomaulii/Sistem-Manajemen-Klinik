<div class="tab-pane active" id="comments">
  <script>
    $(document).ready(function(){
      $('#comment').keypress(function(e){
        if(e.which == 13) { // Enter key
          $.post($('#commentBox').attr('action'), $('#commentBox').serialize(), function(data){
              $('#commentGroup').prepend(data);
              $('#comment').val('');
          });
          return false;
        }
      });
    });
  </script>

  <?php if(session()->get('ba_user_id') == $doctor->user_id && $status_code > 1): ?>
    <?= form_open('comment/add/'.$doctor->patient_doctor_id, ['id' => 'commentBox']) ?>
      <?= form_hidden('patient_doctor_id', $doctor->patient_doctor_id) ?>
      <input type="text" name="comment" id="comment" class="form-control" style="height: 40px;" placeholder="Write your comment about this patient..." required>
    <?= form_close() ?>
    <p></p>
  <?php endif; ?>

  <div id="commentGroup">
    <?php if(@$comments != 'unauthorized' && !empty($comments)): ?>
      <?php foreach ($comments as $comment): ?>
        <div id="comment<?= $comment->comment_id ?>" class="well well-md" style="padding: 10px; margin-bottom: 10px;">
          <div class="commentBody" style="font-size: 14px;"><?= esc($comment->comment) ?></div>
          <div class="text-right" style="font-size: 11px; color: #888;">
            Date: <?= date('M d, Y H:i', $comment->create_date) ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-warning">No comments available or unauthorized access.</div>
    <?php endif; ?>
  </div>
</div>