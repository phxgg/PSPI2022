<?php
if (!defined('ACCESS')) exit;

global $mysqli, $parser;

if (!isset($_GET['sid'])) {
  header('Location: ?page=categories');
  die();
}

$sid = intval($_GET['sid']);

$store = Stores::Fetch($sid);
if ($store == NULL || !$store->approved) {
  header('Location: ?page=categories');
  die();
}

$storeAddress = $store->city.', '.$store->address.', '.$store->zipcode;
$isFavorite = Stores::IsFavorite($store->id);
$availableTables = $store->capacity - $store->reserved;
$hasSetupOpHours = Stores::GetOperationalHours($store->id)[0] !== NULL ? true : false;

$badgeColor = 'primary';
if ($availableTables == 0)
  $badgeColor = 'danger';
else if ($availableTables > 0 && $availableTables <= 3)
  $badgeColor = 'warning';
else if ($availableTables > 3)
  $badgeColor = 'success';

$favoriteBtn = '';
$randomId = 'favorites_'.$store->id;
if ($isFavorite) {
  $favoriteBtn = '<button id="'.$randomId.'" class="btn btn-sm btn-danger" onclick="javascript:app.favorite(\''.$randomId.'\', '.$store->id.');" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove from favorites"><i class="bi bi-heart-half"></i></button>';
} else {
  $favoriteBtn = '<button id="'.$randomId.'" class="btn btn-sm btn-outline-danger" onclick="javascript:app.favorite(\''.$randomId.'\', '.$store->id.');" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to favorites"><i class="bi bi-heart"></i></button>';
}

$opHoursAlert = '';
if (!$hasSetupOpHours) {
  $opHoursAlert = '
  <span
    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning"
    data-bs-toggle="tooltip"
    data-bs-placement="right"
    title="Αυτό το κατάστημα δεν έχει καταχωρίσει ακόμη τις ώρες λειτουργίας του.">
    !
  </span>
  ';
}
?>

<div class="card text-dark mb-3">
  <div class="card-header" id="store-title"><?= $store->title; ?></div>
  <div class="card-body">
    
    <div id="loading" class="text-center" style="display:none;">
      <div class="spinner-border text-primary m-5" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div id="store-result"></div>

    <div class="row">
      <div class="col-md-8 offset-md-2 text-center">
        <h2 class="text-dark">
          <i class="<?= $iconClass; ?>"></i> <?= $store->title; ?>
          <br />
          <a
            href="?page=book&sid=<?= $store->id; ?>"
            class="btn btn-primary position-relative <?= ($availableTables == 0 || !$hasSetupOpHours) ? 'disabled' : ''; ?>">
            <i class="bi bi-bookmark-star"></i> Book a table
          </a>
        </h2>
      </div>
    </div>
    <br />

    <div class="card mb-3 position-relative" style="max-width: 540px; margin: 0 auto;">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="<?= $store->image; ?>" class="img-fluid rounded-start" alt="<?= $store->title; ?>">
          <?= $opHoursAlert; ?>
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">Περιγραφή</h5>
            <p class="card-text">
              <?php
              $parser->parse($store->description);
              echo $parser->getAsHTML();
              ?>
            </p>
            <p class="card-text">
              <small class="text-muted">
                <i class="bi bi-geo text-danger"></i>
                <a href="https://maps.google.com/?q=<?= $storeAddress; ?>" class="text-muted" target="_blank">
                  <?= $storeAddress; ?>
                </a>
              </small>
            </p>
          </div>
          <div class="card-footer">
            <p>
              <i class="bi bi-calendar4-week"></i> <span class="badge bg-<?= $badgeColor; ?>"><?= $availableTables; ?></span> tables available
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
// window.onload = () => {
//   app.LoadStore(<?= $sid; ?>);
// };
</script>
