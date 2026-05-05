<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="glyphicon glyphicon-picture"></span> X-Ray Dep. <b class="caret"></b>
  </a>
  <ul class="dropdown-menu" style="min-width: 220px; padding: 10px 0px;">
    <li style="padding: 5px 20px;"><strong>- Radiology Services</strong></li>
    <li><a href="<?= base_url('xray') ?>"><span class="glyphicon glyphicon-th"></span> View X-Ray List</a></li>
    <li><a href="<?= base_url('xray/new_xray') ?>"><span class="glyphicon glyphicon-file"></span> Add New X-Ray Type</a></li>
    <li class="divider"></li>
    <li style="padding: 5px 20px;"><strong>- Imaging Search</strong></li>
    <li><a href="<?= base_url('xray/search') ?>" onclick="$.get($(this).attr('href'),'',function(data){$('#tmpDiv').html(data);});return false;">
        <span class="glyphicon glyphicon-zoom-in"></span> Search Imaging
    </a></li>
  </ul>
</li>