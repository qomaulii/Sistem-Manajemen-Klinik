<legend><?= esc(@$title) ?></legend>

<?php if(!empty($patient->patient_id)): ?>
<div id="ticket" style="border: 1px solid #ddd; padding: 20px; background: #fff; width: 500px;">
  <div class="ticketHeader" style="text-align: center; font-weight: bold; margin-bottom: 15px;">CLINIC VISIT TICKET</div>
  <div class="row">
    <div class='col-xs-6'>
      <label>Name: </label> <?= esc($patient->first_name.' '.$patient->last_name) ?><br/>
      <label>Father Name: </label> <?= esc($patient->fname) ?><br/>
      <label>Gender: </label> <?= $patient->gender ? 'Male' : 'Female' ?><br/>
      <label>Phone: </label> <?= esc($patient->phone) ?><br/>
      <label>Age: </label> <?= (date('Y') - date('Y', $patient->birth_date)) ?><br/>
    </div>
    <div class="col-xs-6">
      <label>Date: </label> <?= date('M d, Y H:i', $patient->create_date) ?><br/>
      <label>Visit ID: </label> <?= $patient->patient_id ?><br/>
      <label><?= $patient->id_type ?>: </label> <?= esc($patient->social_id) ?><br/>
      <label>Doctor: </label> <?= esc(@$doc_info->first_name.' '.@$doc_info->last_name) ?><br/>
    </div>
  </div>
</div>
<div class="pull-right hidden-print" style="margin-top: 15px;">
  <a href="<?= base_url('patient') ?>" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span></a>
  <button onclick="window.print();" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Print</button>
</div>
<?php endif; ?>