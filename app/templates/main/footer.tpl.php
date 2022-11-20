<?php if (!defined('ACCESS')) exit; ?>

  <footer class="footer text-center">
    <hr class="mydivider-center text-muted" />
    <small class="text-muted py-3">
      <?= SITE_NAME; ?> &copy; 2022<br />
      <a href="/?page=contact" class="text-decoration-none">
        <i class="bi bi-chat-dots"></i>
        Επικοινωνία
      </a>
    </small>
  </footer>
  
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.5/r-2.2.9/datatables.min.js"></script>

<!-- Custom JS -->
<script type="text/javascript" src="js/main.js"></script>
<?= (Account::IsLoggedIn() && Account::IsProfessional()) ? '<script type="text/javascript" src="js/professional.js"></script>' : ''; ?>
<script type="text/javascript" src="js/voice-search.js"></script>

<script type="text/javascript">
$.fn.selectpicker.Constructor.BootstrapVersion = '5';

$(document).ready(function() {
  // enable tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>

</body>
</html>