<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" id="patientSearch" class="form-control" placeholder="Cari nama pasien (A-Z)..." onkeyup="filterPatients()">
            <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="list-group" id="patientList">
            <?php foreach ($patients as $p) : ?>
                <a href="<?= base_url('doctor/medical_history_detail/' . $p->user_id) ?>" class="list-group-item">
                    <span class="glyphicon glyphicon-folder-open" style="margin-right: 10px; color: #3498db;"></span>
                    <?= esc($p->first_name . ' ' . $p->last_name) ?>
                    <small class="pull-right" style="color: #999;">Lihat Riwayat</small>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
function filterPatients() {
    let input = document.getElementById("patientSearch").value.toUpperCase();
    let list = document.getElementById("patientList");
    let items = list.getElementsByTagName("a");
    for (let i = 0; i < items.length; i++) {
        if (items[i].innerText.toUpperCase().indexOf(input) > -1) items[i].style.display = "";
        else items[i].style.display = "none";
    }
}
</script>