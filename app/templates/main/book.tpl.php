<?php
if (!defined('ACCESS')) exit;

global $mysqli;

if (!isset($_GET['sid'])) {
  header('Location: ?page=categories');
  die();
}

$sid = intval($_GET['sid']);
$store = Stores::Fetch($sid);
$hasSetupOpHours = (Stores::GetOperationalHours($store->id)[0] !== NULL) ? true : false;
if ($store == NULL || !$store->approved || !$hasSetupOpHours) {
  header('Location: ?page=categories');
  die();
}

// $storeData = json_encode(['data' => $store]);
?>

<?= Account::LoginRequired(); ?>

<?php $account = Account::getAccount(); ?>

<!-- <div id="store-details"></div> -->

<div id="book-a-table" class="card book-a-table">
  <div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2 text-center">
        <h2 class="text-dark"><i class="<?= $iconClass; ?>"></i> <?= $subtitle; ?></h2>
        <p class="card-body text-dark"><?= $store->title; ?></p>
      </div>
    </div>

    <form style="margin: auto; max-width:500px;">
      <div id="book-result"></div>

      <input type="hidden" name="sid" id="sid" value="<?= $store->id; ?>">

      <div class="row g-1">
        <div class="col-md-4 form-floating mb-3">
          <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="<?= $account->firstname.' '.$account->lastname; ?>">
          <label for="name">Name</label>
        </div>

        <div class="col form-floating mb-3">
          <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?= $account->email; ?>">
          <label for="email">Email</label>
        </div>
      </div>
      
      <div class="row g-1">
        <div class="col form-floating mb-3">
          <input type="number" class="form-control" name="phone" id="phone" placeholder="Phone number" value="<?= $account->phone; ?>">
          <label for="phone">Phone number</label>
        </div>

        <div class="col form-floating mb-3">
          <input type="number" class="form-control" name="people" id="people" min="1" max="<?= $store->maxpersonpertable; ?>" placeholder="No. of people">
          <label for="people">No. of people</label>
        </div>
      </div>

      <div class="row g-1">
        <div class="col form-floating mb-3">
          <input type="date" name="date" class="form-control" id="date" placeholder="Date">
          <label for="date">Date</label>
        </div>

        <div class="col form-floating mb-3">
          <input type="time" class="form-control" name="time" id="time" placeholder="Time">
          <label for="time">Time</label>
        </div>
      </div>

      <div class="form-floating mb-3">
        <textarea class="form-control position-relative" name="message" id="message" placeholder="Θα ήθελες να προσθέσεις κάτι; &#129300;" maxlength="300"></textarea>
        <label for="message">Θα ήθελες να προσθέσεις κάτι; &#129300;</label>
        <span id="message-chars" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">300</span>
      </div>

      <button type="submit" id="book-btn" class="btn btn-primary float-end">
        <i class="<?= $iconClass; ?>"></i>
        Κάνε κράτηση
      </button>
    </form>
  </div>
</div>

<script type="text/javascript">
Date.prototype.toDateInputValue = (function() {
  var local = new Date(this);
  local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
  return local.toJSON().slice(0,10);
});

$(document).ready(function() {
  $('#message').keyup(function() {
    $('#message-chars').html(300-$('#message').val().length)
  });

  $('#message').keydown(function() {
    $('#message-chars').html(300-$('#message').val().length)
  });

  $('#book-btn').click(function(e) {
    e.preventDefault();
    app.BookStore();
  });

  // console.log(new Date().toDateInputValue());
  $('#date').val(new Date().toDateInputValue());
});
</script>
