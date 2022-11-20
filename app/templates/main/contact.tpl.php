<?php if (!defined('ACCESS')) exit; ?>

<div class="card text-dark mb-3">
  <div class="card-header">
    <i class="<?= $iconClass; ?>"></i>
    <?= $title; ?>
  </div>
  <div class="card-body">

    <h5 class="text-center">
      <i class="<?= $iconClass; ?>"></i>
      <?= $subtitle; ?>
    </h5>
    <hr class="mydivider-center text-muted" />

    <form action="" method="post" style="margin: auto; max-width: 500px;">
      <?php
      if (isset($_POST['send'])) {
        echo Misc::Contact($_POST['email'], $_POST['message']);
      }
      ?>
      
      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" value="<?= (Account::IsLoggedIn()) ? Account::getAccount()->email : ''; ?>">
        <label for="email" class="form-label">Email</label>
      </div>
      <div class="form-floating mb-3">
        <textarea class="form-control position-relative" name="message" id="message" placeholder="Θα χαρούμε να σε ακούσουμε! &#128522;" maxlength="300" style="min-height:200px;"></textarea>
        <label for="message">Θα χαρούμε να σε ακούσουμε! &#128522;</label>
        <span id="message-chars" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">300</span>
      </div>
      <button type="submit" name="send" class="btn btn-primary float-end">
        <i class="bi bi-envelope"></i>
        Αποστολή
      </button>
    </form>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $('#message').keyup(function() {
    $('#message-chars').html(300-$('#message').val().length)
  });

  $('#message').keydown(function() {
    $('#message-chars').html(300-$('#message').val().length)
  });
});
</script>
