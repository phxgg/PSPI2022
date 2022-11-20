<?php
if (!defined('ACCESS')) exit;

global $mysqli;

if (!isset($_GET['cid'])) {
  header('Location: ?page=categories');
  die();
}

$list = (isset($_GET['l'])) ? intval($_GET['l']) : 0;
if ($list < 0) $list = 0;

$cid = intval($_GET['cid']);
?>

<div class="card text-dark mb-3">
  <div class="card-header" id="category-title"></div>
  <div class="card-body">

    <h5 class="text-center" id="title"></h5>
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

    <div id="category-result"></div>

    <ul class="pagination">
      <li class="page-item"><a class="page-link" href="?page=view-category&cid=<?= $cid; ?>&l=<?= $list-1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
      <?= ($list != 0) ? '<li class="page-item"><a class="page-link" href="?page=view-category&cid='.$cid.'&l='.($list-1).'">'.($list-1).'</a></li>' : null; ?>
      <li class="page-item active"><a class="page-link" href="?page=view-category&cid=<?= $cid; ?>&l=<?= $list; ?>"><?= $list; ?></a><li>
      <li class="page-item"><a class="page-link" href="?page=view-category&cid=<?= $cid; ?>&l=<?= $list+1; ?>"><?= $list+1; ?></a></li>
      <li class="page-item"><a class="page-link" href="?page=view-category&cid=<?= $cid; ?>&l=<?= $list+1; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
    </ul>

  </div>
</div>

<div id="init"></div>

<script type="text/javascript">
window.onload = () => {
  app.LoadCategory(<?= $list; ?>, <?= $cid; ?>);
};

function filter() {
  $("#filterInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#category-result .store").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}
</script>