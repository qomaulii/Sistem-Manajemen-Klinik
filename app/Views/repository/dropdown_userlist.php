<div class="well col col-md-10 col-md-offset-1" style="padding: 20px; background: #fff;">
    <legend>- Clinic Staff List</legend>
    
    <!-- User Row 1 -->
    <div class="row-fluid user-row" style="border-bottom: 1px solid #eee; padding: 10px 0;">
        <div class="col col-md-1">
            <img class="img-circle" src="<?= base_url('photo.jpg') ?>" alt="User Pic" style="width: 45px; height: 45px;">
        </div>
        <div class="col col-md-10">
            <strong><?= session()->get('ba_username') ?></strong><br>
            <span class="text-muted" style="font-size: 12px;">Role: <?= session()->get('ba_position') ?></span>
        </div>
        <div class="col col-md-1 dropdown-user" data-for=".user-detail-1" style="cursor: pointer;">
            <i class="glyphicon glyphicon-chevron-down text-muted"></i>
        </div>
    </div>
    
    <div style="display: none; padding: 15px; background: #f9f9f9;" class="user-infos user-detail-1">
        <div class="panel panel-primary" style="border-radius: 0px;">
            <div class="panel-heading">Detailed Information</div>
            <div class="panel-body">
                <table class="table table-condensed" style="margin-bottom: 0px;">
                    <tbody>
                        <tr><td>Full Name:</td><td><?= session()->get('ba_first_name').' '.session()->get('ba_last_name') ?></td></tr>
                        <tr><td>Email:</td><td><?= session()->get('ba_email') ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.dropdown-user').click(function() {
        var dataFor = $(this).attr('data-for');
        var idFor = $(dataFor);
        var currentButton = $(this);
        
        idFor.slideToggle(400, function() {
            if(idFor.is(':visible')) {
                currentButton.html('<i class="glyphicon glyphicon-chevron-up text-muted"></i>');
            } else {
                currentButton.html('<i class="glyphicon glyphicon-chevron-down text-muted"></i>');
            }
        });
    });
});
</script>