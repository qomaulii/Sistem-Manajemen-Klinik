<div class="row">

    <aside class="col-sm-3">
        <?php
        if (isset($title) && strtolower($title) !== 'login') {
            if (isset($is_logged_in) && $is_logged_in === false) {
                echo view('account/login');
            } else {
                echo view('repository/sidebar');
            }
        }
        ?>
    </aside>

    <article class="col-sm-9" id="mainContent"> 
        <?php 
        if (isset($view_content)) {
            echo view($view_content);
        } else {
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Dashboard Klinik</h3>
                </div>
                <div class="panel-body">
                    <h4>Selamat Datang di Sistem Manajemen Klinik!</h4>
                    <p>Pilih menu di sebelah kiri untuk mulai mengelola operasional klinik.</p>
                </div>
            </div>
            <?php
        }
        if (isset($includes) && is_array($includes)) {
            foreach ($includes as $include) {
                echo view($include);
            }
        }
        ?>
    </article>

</div>