<?php if (!defined('ACCESS')) exit; ?>

<?= Account::LoginRequired(); ?>

<div class="card text-dark mb-3">
  <div class="card-header" id="favorites-title"><?= $title; ?></div>
  <div class="card-body">

    <h5 class="text-center">
      <i class="<?= $iconClass; ?>"></i> <?= $subtitle; ?>
    </h5>
    <hr class="mydivider-center text-muted" />

    <div class="input-group mb-3">
      <span class="input-group-text bi bi-search"></span>
      <input type="text" class="form-control me-2" id="filterInput" onkeyup="filter();" placeholder="Filter results..."><br />
    </div>

    <div id="loading" class="text-center">
      <div class="spinner-border text-primary m-5" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div id="favorites-result"></div>

  </div>
</div>

<div id="init"></div>

<script type="text/javascript">
window.onload = () => {
  app.LoadFavorites();
};

function filter() {
  $("#filterInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#favorites-result .store").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}
</script>
