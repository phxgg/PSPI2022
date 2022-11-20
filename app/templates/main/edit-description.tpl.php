<?php
if (!defined('ACCESS')) exit;

global $mysqli, $parser;

if (!isset($_GET['sid'])) {
  header('Location: ?page=professional');
  die();
}

$sid = intval($_GET['sid']);
$store = Stores::Fetch($sid);
if ($store == NULL) {
  header('Location: ?page=professional');
  die();
}

$parser->parse($store->description);
?>

<?= Account::LoginRequired(); ?>

<?php $account = Account::getAccount(); ?>

<div class="card text-dark mb-3">
  <div class="card-header">
    <i class="<?= $iconClass; ?>"></i>
    <?= $store->title; ?>
  </div>
  <div class="card-body">

    <h5 class="text-center">
      <i class="<?= $iconClass; ?>"></i>
      <?= $subtitle; ?>
    </h5>
    <p class="text-muted text-center">
      Το <a href="https://www.bbcode.org/reference.php" target="_blank">BBCode</a> επιτρέπεται.
    </p>
    <hr class="mydivider-center text-muted" />

    <form style="margin: auto; max-width: 500px;">
      <div id="description-result"></div>

      <input type="hidden" id="sid" value="<?= $store->id; ?>">
      <div class="form-floating mb-3">
        <textarea class="form-control position-relative" name="description" id="description" placeholder="Περιγραφή καταστήματος" maxlength="2000" style="min-height:500px;"><?= $parser->getAsBBCode(); ?></textarea>
        <label for="description">Περιγραφή καταστήματος</label>
        <span id="description-chars" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= 2000-strlen($store->description); ?></span>
      </div>
      <button type="button" id="save-btn" class="btn btn-primary float-end">
        <i class="bi bi-pencil-square"></i>
        Αποθήκευση
      </button>
    </form>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $('#description').keyup(function() {
    $('#description-chars').html(2000-$('#description').val().length)
  });

  $('#description').keydown(function() {
    $('#description-chars').html(2000-$('#description').val().length)
  });

  $('#save-btn').click(function() {
    prof.EditDescription();
  });

});
</script>

