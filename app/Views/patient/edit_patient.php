<?php if(!empty($patient->patient_id)): ?>
<link rel='stylesheet' href='<?= base_url('content/css/bootstrap-fileupload.min.css') ?>' media='screen'/>
<script src='<?= base_url('content/js/bootstrap-fileupload.js') ?>'></script>

<div class="col col-md-8 well well-md" style="padding: 20px;">
  <?= form_open_multipart('patient/edit_patient/'.$patient->patient_id, ["id" => "editpatientForm", "role" => "form"]) ?>
    <?= !empty($error) ? $error : '' ?>
    <fieldset>
      <legend>- Personal Information:</legend>
      <div class="form-group" style="margin-bottom: 15px;">
          <div class="col-md-9">
            <div style="margin-bottom: 10px;"><input type="text" name='first_name' value="<?= set_value('first_name', $patient->first_name) ?>" class='form-control' placeholder='First Name' required /></div>
            <div style="margin-bottom: 10px;"><input type="text" name='last_name' value="<?= set_value('last_name', $patient->last_name) ?>" class='form-control' placeholder='Last Name' /></div>
            <div style="margin-bottom: 10px;"><input type="text" name='fname' value="<?= set_value('fname', $patient->fname) ?>" class='form-control' placeholder='Father Name' /></div>
            <div class="col-md-12">
              <label class="radio-inline"><input type="radio" name='gender' value="1" <?= set_value('gender', $patient->gender) == '1' ? 'checked' : '' ?> />Male</label>
              <label class="radio-inline"><input type="radio" name='gender' value="0" <?= set_value('gender', $patient->gender) == '0' ? 'checked' : '' ?> />Female</label>
            </div>
          </div>
          <div class="col-md-3">
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="fileupload-preview thumbnail" style="width: 120px; height: 140px;">
                  <img src="<?= base_url($patient->picture) ?>" alt="Patient" style="max-width: 100px;"/>
              </div>
              <div class="text-center">
                <span class="btn btn-file btn-default" style="font-size: 11px;"><span class="fileupload-new">Select</span><span class="fileupload-exists">Change</span>
                <input type="file" name="picture" /></span>
              </div>
            </div>
          </div>
      </div>
    </fieldset>

    <fieldset>
      <legend>- Additional Information:</legend>
      <div class="form-group" style="margin-bottom: 15px;">
        <div class="col-md-6"><input type='email' name='email' value="<?= set_value('email', $patient->email) ?>" class='form-control' placeholder='Email' /></div>
        <div class="col-md-6"><input type='text' name='phone' value="<?= set_value('phone', $patient->phone) ?>" class='form-control' placeholder='Phone' required/></div>
      </div>
      <div class="form-group" style="margin-bottom: 15px;">
        <div class="col-md-12"><input type="text" name='address' value="<?= set_value('address', $patient->address) ?>" class='form-control' placeholder='Address'/></div>
      </div>
      <div class="form-group" style="margin-bottom: 15px;">
        <div class="col-md-6"><input type="text" name='social_id' value="<?= set_value('social_id', $patient->social_id) ?>" class='form-control' placeholder='Social ID'/></div>
        <div class="col-md-6"><?= form_dropdown('id_type', $id_type_options, set_value('id_type', $patient->id_type), "class='form-control'") ?></div>
      </div>
      <div class="form-group" style="margin-bottom: 15px;">
        <div class="col-md-6"><?= form_dropdown('doctor', $doctor_list, set_value('doctor', $doctor), "class='form-control'") ?></div>
        <div class="col-md-6"><input type="date" name='birth_date' value="<?= date('Y-m-d', $patient->birth_date) ?>" class='form-control'/></div>
      </div>
    </fieldset>

    <div class="form-group" style="margin-top: 20px;">
      <div class="col-md-6"><input type="submit" value='Update' class="form-control btn btn-info" /></div>
      <div class="col-md-6"><a href="<?= base_url('patient') ?>" class="form-control btn btn-info text-center" style="line-height: 26px;">Cancel</a></div>
    </div>
  <?= form_close() ?>
</div>
<?php else: ?>
  <div class="alert alert-danger text-center"><h1>Patient Not Found</h1></div>
<?php endif; ?>