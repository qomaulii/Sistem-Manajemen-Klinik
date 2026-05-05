<a href="<?= base_url('account/signup') ?>" class="btn btn-primary btn-lg" role="button">
    <span class="glyphicon glyphicon-user"></span> <br/>Register User
</a>
<?php if(isset($bitauth) && $bitauth->logged_in()): ?>
    <a href="<?= base_url('account/users') ?>" class="btn btn-primary btn-lg" role="button">
        <span class="glyphicon glyphicon-user"></span> <br/>Users
    </a>
    <a href="<?= base_url('account/groups') ?>" class="btn btn-primary btn-lg" role="button">
        <span class="glyphicon glyphicon-user"></span> <br/>Groups
    </a>
    <a href="<?= base_url('account/add_group') ?>" class="btn btn-primary btn-lg" role="button">
        <span class="glyphicon glyphicon-user"></span> <br/>Create Group
    </a>
<?php endif; ?>