<?php if (!defined('ACCESS')) exit; ?>

<div class="card text-dark mb-3">
  <div class="card-header"><i class="<?= $iconClass; ?>"></i> <?= $title; ?></div>
  <div class="card-body">
    
    <div class="text-center">
      <?php if (Account::IsLoggedIn()): ?>
      <h4>
        <span>&#128516;</span>
        Καλώς όρισες, <?= Account::getAccount()->firstname; ?>
      </h4>
      <?php else: ?>
      <h6>
        <span>&#128527;</span> Είσαι καινούριος; Κάνε <a href="?page=register" class="btn btn-outline-primary btn-sm">εγγραφή</a> για να κάνεις μια κράτηση!
      </h6>
      <?php endif; ?>
    </div>
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

    <div id="categories-result"></div>
  </div>
</div>

<div id="init"></div>

<script type="text/javascript">
window.onload = () => {
  app.LoadCategories();
};

function filter() {
  $("#filterInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#categories-result .category").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}
</script>

