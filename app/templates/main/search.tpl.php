<?php
$q = (isset($_GET['q'])) ? $_GET['q'] : '';
?>

<div class="card text-dark mb-3">
  <div class="card-header">
    <i class="<?= $iconClass; ?>"></i>
    <?= $title; ?>
  </div>
  <div class="card-body">
    
    <div id="loading" class="text-center">
      <div class="spinner-border text-primary m-5" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <p class="card-text">Αναζήτηση για: "<?= htmlentities($q); ?>"</p>
    <div id="searchpage-results"></div>
  </div>
</div>

<div id="init"></div>

<script type="text/javascript">
window.onload = () => {
  app.LoadSearch('<?= $q; ?>');
};
</script>
