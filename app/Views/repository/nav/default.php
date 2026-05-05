<!-- Menu Utama Dashboard -->
<li class="active">
    <a href="<?= base_url() ?>">
        <span class="glyphicon glyphicon-home"></span> Dashboard Home
    </a>
</li>

<!-- Menu Bantuan & Informasi -->
<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="glyphicon glyphicon-info-sign"></span> Help Center <b class="caret"></b>
  </a>
  <ul class="dropdown-menu" style="min-width: 180px;">
    <li><a href="#"><span class="glyphicon glyphicon-question-sign"></span> FAQ</a></li>
    <li><a href="#"><span class="glyphicon glyphicon-phone-alt"></span> Support Contact</a></li>
    <li class="divider"></li>
    <li><a href="<?= base_url('report_bug/add') ?>" onclick="$.get($(this).attr('href'),'',function(data){$('#tmpDiv').html(data);});return false;">
        <span class="glyphicon glyphicon-warning-sign"></span> Report Technical Issue
    </a></li>
  </ul>
</li>

<!-- Link ke Website Utama atau Portal Lain -->
<li>
    <a href="#">
        <span class="glyphicon glyphicon-globe"></span> Clinic Website
    </a>
</li>