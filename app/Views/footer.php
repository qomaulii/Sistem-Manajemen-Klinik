</div> <!-- Close content div -->
      <div id="tmpDiv"></div>
      
      <footer class="footer" style="margin-top: 30px;">
        <div class='alert alert-success hidden-print' style="padding: 10px;">
            <div class='text-right' style="font-size: 12px;">
                Clinic Management System | &copy; <?= date('Y') ?> Baratali Ghadamalizadeh. All Right Reserved.
            </div>
        </div>
      </footer>
    </div> <!-- Close container div -->
    <script src="<?= base_url('content/js/main.js') ?>"></script>
    <?php if(isset($script)) echo $script; ?>
  </body>
</html>