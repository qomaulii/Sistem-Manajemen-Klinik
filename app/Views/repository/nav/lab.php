<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="glyphicon glyphicon-filter"></span> Laboratory <b class="caret"></b>
  </a>
  <ul class="dropdown-menu" style="min-width: 220px; padding: 10px 0px;">
    <li style="padding: 5px 20px;"><strong>- Test Management</strong></li>
    <li><a href="<?= base_url('test') ?>"><span class="glyphicon glyphicon-list"></span> List All Lab Tests</a></li>
    <li><a href="<?= base_url('test/new_test') ?>"><span class="glyphicon glyphicon-plus-sign"></span> Register New Test</a></li>
    <li class="divider"></li>
    <li style="padding: 5px 20px;"><strong>- Search & Reports</strong></li>
    <li><a href="<?= base_url('test/search') ?>" onclick="$.get($(this).attr('href'),'',function(data){$('#tmpDiv').html(data);});return false;">
        <span class="glyphicon glyphicon-search"></span> Quick Search Test
    </a></li>
  </ul>
</li>