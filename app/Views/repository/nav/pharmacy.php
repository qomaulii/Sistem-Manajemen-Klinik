<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="glyphicon glyphicon-leaf"></span> Pharmacy Dep. <b class="caret"></b>
  </a>
  <ul class="dropdown-menu" style="min-width: 220px;">
    <li style="padding: 5px 20px;"><strong>- Inventory</strong></li>
    <li><a href="<?= base_url('drug') ?>"><span class="glyphicon glyphicon-list"></span> All Drugs Database</a></li>
    <li><a href="<?= base_url('drug/new_drug') ?>"><span class="glyphicon glyphicon-file"></span> Register New Item</a></li>
    <li class="divider"></li>
    <li style="padding: 5px 20px;"><strong>- Transactions</strong></li>
    <li><a href="<?= base_url('drug/add_drug') ?>"><span class="glyphicon glyphicon-import"></span> Add Purchased Stocks</a></li>
    <li><a href="<?= base_url('drug/return_drug') ?>"><span class="glyphicon glyphicon-export"></span> Return/Expired Items</a></li>
  </ul>
</li>