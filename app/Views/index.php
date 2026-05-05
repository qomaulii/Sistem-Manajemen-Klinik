<!-- ==========================================
     KERANGKA TENGAH SISTEM MANAJEMEN KLINIK
     ========================================== -->
<section class="row mt-4">
    
    <!-- 1. BAGIAN KIRI: Sidebar Menu (Menu Navigasi) -->
    <aside class="col-md-3 col-sm-12 mb-4">
        <?php
        // Cek apakah halaman saat ini BUKAN halaman login
        if (isset($title) && strtolower($title) !== 'login') {
            
            // Cek status login (Sesuaikan variabel ini dengan library auth kamu nantinya)
            // Di CI3 kamu pakai $this->bitauth->logged_in(), di CI4 kita pakai variabel dari controller
            if (isset($is_logged_in) && $is_logged_in === false) {
                // Tampilkan form login jika belum masuk
                echo view('account/login');
            } else {
                // Tampilkan menu navigasi klinik jika sudah masuk
                echo view('repository/sidebar');
            }
        }
        ?>
    </aside>

    <!-- 2. BAGIAN KANAN: Konten Utama (Data Pasien, Obat, Rontgen) -->
    <article class="col-md-9 col-sm-12" id="mainContent"> 
        <?php 
        // A. Merender halaman spesifik yang dikirim dari Controller
        if (isset($view_content)) {
            echo view($view_content);
        } else {
            // B. TAMPILAN DASHBOARD DEFAULT KLINIK
            // Muncul otomatis kalau Controller tidak ngirim $view_content
            ?>
            <div class="panel panel-primary" style="border: 1px solid #0a78b4; border-radius: 5px;">
                <div class="panel-heading" style="background-color: #0a78b4; color: white; padding: 10px 15px;">
                    <h3 class="panel-title" style="margin: 0; font-size: 18px;">Dashboard Klinik</h3>
                </div>
                <div class="panel-body" style="padding: 20px;">
                    <h4>Selamat Datang di Sistem Manajemen Klinik!</h4>
                    <p>Pilih menu di sebelah kiri untuk mulai mengelola operasional klinik:</p>
                    <ul style="line-height: 1.8;">
                        <li><strong>Data Pasien:</strong> Pendaftaran dan rekam medis.</li>
                        <li><strong>Data Rontgen / X-Ray:</strong> Hasil periksa laboratorium.</li>
                        <li><strong>Jadwal Dokter:</strong> Pengaturan jam praktik.</li>
                    </ul>
                    <hr>
                    <p class="text-muted" style="font-size: 12px;"><em>Silakan lengkapi file di folder app/Views untuk menampilkan data spesifik.</em></p>
                </div>
            </div>
            <?php
        }
        
        // C. Merender file sisipan tambahan (Misal: popup modal, atau tabel pendukung)
        if (isset($includes) && is_array($includes)) {
            foreach ($includes as $include) {
                echo view($include);
            }
        }
        ?>
    </article>

</section>