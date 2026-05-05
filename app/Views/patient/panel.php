<?php 
  $status_code = $doctor->status;
  $status_list = [0 => 'Waiting', 1 => 'Finished', 2 => 'Treatment'];
  $status = isset($status_list[$status_code]) ? $status_list[$status_code] : 'Unknown';
?>

<?php if(!empty($patient->patient_id)): ?>
<div class="panel panel-default" id="pInfo" style="margin-bottom: 20px;">
    <div class="panel-heading" style="background-color: #f5f5f5;">
      <h4 class="panel-title">
        <?= $patient->patient_id .' - '. esc($patient->first_name.' '.$patient->last_name) ?> 
        (<span id="status" class="label label-info"><?= $status ?></span>)
        
        <?php if($status_code != 1): ?>
          <div class="pull-right">
            <?= form_open('patient/status/'.$doctor->patient_doctor_id, ['id' => 'statusForm', 'style' => 'display:inline;']) ?>
              <?= form_hidden('patient_doctor_id', $doctor->patient_doctor_id) ?>
              <?= form_hidden('patient_id', $patient->patient_id) ?>
              <?= form_hidden('user_id', session()->get('ba_user_id')) ?>
              <?= form_hidden('status', $status_code ? 1 : 2) ?>
              <?= form_hidden('url', current_url()) ?>
              <input type="submit" value="<?= $status_code ? 'Finish' : 'Accept' ?>" class="btn btn-xs btn-primary">
            <?= form_close() ?>
          </div>
        <?php endif; ?>
      </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class='col-xs-6'>
              <label>Father Name: </label> <?= esc($patient->fname) ?><br/>
              <label>Gender: </label> <?= $patient->gender ? 'Male' : 'Female' ?><br/>
              <label>Age: </label> <?= (date('Y') - date('Y', $patient->birth_date)) ?><br/>
            </div>
            <div class="col-xs-6">
              <label>Registration: </label> <?= date('M d, Y', $patient->create_date) ?><br/>
              <label>Doctor: </label> <?= esc(@$doc_info->first_name.' '.@$doc_info->last_name) ?><br/>
              <label>ID Type: </label> <?= esc($patient->social_id) ?> (<?= $patient->id_type ?>)<br/>
            </div>
        </div>
    </div>
</div>

<div>
  <ul class="nav nav-tabs" id="panelTab">
    <li class="active"><a href="#comments" data-toggle="tab">Comments</a></li>
    <li><a href="#drugs" data-toggle="tab">Drugs</a></li>
    <li><a href="#xrays" data-toggle="tab">X-Rays</a></li>
    <li><a href="#labs" data-toggle="tab">Laboratory</a></li>
  </ul>
  
  <div class="tab-content" style="padding: 15px; border: 1px solid #ddd; border-top: none;">
    <div class="tab-pane active" id="comments"><?= view('patient/panel/comments') ?></div>
    <div class="tab-pane" id="drugs"><?= view('patient/panel/drugs') ?></div>
    <div class="tab-pane" id="xrays"><?= view('patient/panel/xrays') ?></div>
    <div class="tab-pane" id="labs"><?= view('patient/panel/labs') ?></div>
  </div>
</div>
<?php endif; ?>