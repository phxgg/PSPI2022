<?php if (!defined('ACCESS')) exit; ?>

<div class="card text-dark mb-3">
  <div class="card-header"><i class="<?= $iconClass; ?>"></i> <?= $title; ?></div>
  <div class="card-body">
    <!-- <h5 class="card-title">Light card title</h5> -->
    <p class="card-text">
      <?php
      if (Account::IsLoggedIn()) {
        $user = Account::getAccount();
        echo sprintf(
          'Hello, %s!<br/>Your account was created on %s<hr />',
          $user->username,
          $user->creation_date
        );
      }
      ?>

      Μήνυμα καλοσορίσματος.
    </p>
  </div>
</div>

<script type="text/javascript">
window.location.replace('/?page=categories');
</script>
