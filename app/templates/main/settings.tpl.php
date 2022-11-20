<?php if (!defined('ACCESS')) exit; ?>

<?= Account::LoginRequired(); ?>

<div class="card text-dark mb-3">
  <div class="card-header">
    <i class="<?= $iconClass; ?>"></i>
    <?= $title; ?>
  </div>
  <div class="card-body">

    <h5 class="text-center">
      <i class="<?= $iconClass; ?>"></i> <?= $subtitle; ?>
    </h5>
    <hr class="mydivider-center text-muted" />

    <div style="margin: auto; max-width: 500px;">

      <!-- My profile -->
      <h5 class="card-title">
        <i class="bi bi-info-circle"></i>
        Στοιχεία
      </h5>
      <form>
        <div class="mb-3 row">
          <div class="row g-2 align-items-center">
            <label class="col col-form-label">Ονοματεπώνυμο</label>
            <div class="col">
              <input type="text" class="form-control" value="<?= Account::getAccount()->firstname.' '.Account::getAccount()->lastname; ?>" readonly>
            </div>
          </div>
          <div class="row g-2 align-items-center">
            <label class="col col-form-label">Email</label>
            <div class="col">
              <input type="text" class="form-control" value="<?= Account::getAccount()->email; ?>" readonly>
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#emailModal">
                <i class="bi bi-pencil-square"></i>
              </button>
            </div>
          </div>
          <div class="row g-2 align-items-center">
            <label class="col col-form-label">Τηλέφωνο</label>
            <div class="col">
              <input type="phone" class="form-control" value="<?= Account::getAccount()->phone; ?>" readonly>
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#phoneModal">
                <i class="bi bi-pencil-square"></i>
              </button>
            </div>
          </div>
          <div class="row g-2 align-items-center">
            <label class="col col-form-label">Κωδικός</label>
            <div class="col">
              <input type="password" class="form-control" value="********" readonly>
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#passwordModal">
                <i class="bi bi-pencil-square"></i>
              </button>
            </div>
          </div>
        </div>
      </form>

    </div>

  </div>
</div>

<!-- Email modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="emailModalLabel">
          <i class="bi bi-envelope"></i>
          Αλλαγή email
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="change-email-result"></div>

          <div class="form-floating mb-3">
            <input type="password" name="email-currpass" class="form-control" id="email-currpass" placeholder="Τωρινός κωδικός">
            <label for="email-currpass" class="form-label">Τωρινός κωδικός</label>
          </div>
          <div class="form-floating mb-3">
            <input type="email" name="email-newemail" class="form-control" id="email-newemail" placeholder="Νέο email">
            <label for="email-newemail" class="form-label">Νέο email</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" id="change-email-btn" class="btn btn-primary">
          <i class="bi bi-pencil-square"></i>
          Αλλαγή
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Phone modal -->
<div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="phoneModalLabel">
          <i class="bi bi-telephone"></i>
          Αλλαγή τηλεφώνου
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="change-phone-result"></div>

          <div class="form-floating mb-3">
            <input type="password" name="phone-currpass" class="form-control" id="phone-currpass" placeholder="Τωρινός κωδικός">
            <label for="phone-currpass" class="form-label">Τωρινός κωδικός</label>
          </div>
          <div class="form-floating mb-3">
            <input type="phone" name="phone-newphone" class="form-control" id="phone-newphone" placeholder="Νέο τηλέφωνο">
            <label for="phone-newphone" class="form-label">Νέο τηλέφωνο</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="change-phone-btn" class="btn btn-primary">
          <i class="bi bi-pencil-square"></i>
          Αλλαγή
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Password modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordModalLabel">
          <i class="bi bi-lock"></i>
          Αλλαγή κωδικού
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="change-password-result"></div>

          <div class="form-floating mb-3">
            <input type="password" name="pass-currpass" class="form-control" id="pass-currpass" placeholder="Τωρινός κωδικός">
            <label for="password" class="form-label">Τωρινός κωδικός</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" name="pass-newpass" class="form-control" id="pass-newpass" placeholder="Νέος κωδικός">
            <label for="pass-newpass" class="form-label">Νέος κωδικός</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" name="pass-confirmpass" class="form-control" id="pass-confirmpass" placeholder="Επιβεβαίωση νέου κωδικού">
            <label for="pass-confirmpass" class="form-label">Επιβεβαίωση νέου κωδικού</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="change-password-btn" class="btn btn-primary">
          <i class="bi bi-pencil-square"></i>
          Αλλαγή
        </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $('#change-email-btn').click(function() {
    app.ChangeEmail();
  });

  $('#change-phone-btn').click(function() {
    app.ChangePhone();
  });

  $('#change-password-btn').click(function() {
    app.ChangePassword();
  });
});
</script>
