<?php if (!defined('ACCESS')) exit; ?>

<?= Account::NoLogin(); ?>

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

    <?php
    if (isset($_POST['register'])) {
      $prof = (isset($_POST['professional'])) ? 1 : 0;

      echo Account::Register(
        $_POST['user'],
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['pass'],
        $_POST['confirmpass'],
        $prof
      );
    }
    ?>

    <form style="margin: auto; max-width: 500px;">
      <div id="register-result"></div>

      <div class="form-floating mb-3">
        <input
          type="text"
          name="user"
          class="form-control"
          id="username"
          placeholder="Όνομα χρήστη"
          aria-label="Username"
          aria-describedby="username-reg-addon">
        <label for="username" class="form-label">Όνομα χρήστη</label>
      </div>
      <div class="form-floating mb-3">
        <input
          type="text"
          name="firstname"
          class="form-control"
          id="firstname"
          placeholder="Όνομα"
          aria-label="First Name"
          aria-describedby="firstname-reg-addon">
        <label for="firstname" class="form-label">Όνομα</label>
      </div>
      <div class="form-floating mb-3">
        <input
          type="text"
          name="lastname"
          class="form-control"
          id="lastname"
          placeholder="Επίθετο"
          aria-label="Last Name"
          aria-describedby="lastname-reg-addon">
        <label for="lastname" class="form-label">Επίθετο</label>
      </div>
      <div class="form-floating mb-3">
        <input
          type="email"
          name="email"
          class="form-control"
          id="email"
          placeholder="Email"
          aria-label="Email"
          aria-describedby="email-reg-addon">
        <label for="email" class="form-label">Email</label>
        <i class="form-text">Δεν φαίνεται πουθενά, και δε θα το μοιραστούμε με κανέναν.</i>
      </div>
      <div class="form-floating mb-3">
        <input
          type="phone"
          name="phone"
          class="form-control"
          id="phone"
          placeholder="Τηλέφωνο"
          aria-label="Phone"
          aria-describedby="phone-reg-addon">
        <label for="phone" class="form-label">Τηλέφωνο</label>
        <i class="form-text">Δεν φαίνεται πουθενά, και δε θα το μοιραστούμε με κανέναν.</i>
      </div>
      <div class="form-floating mb-3">
        <input
          type="password"
          name="pass"
          class="form-control"
          id="password"
          placeholder="Κωδικός"
          aria-label="Password"
          aria-describedby="password-reg-addon">
        <label for="password" class="form-label">Κωδικός</label>
      </div>
      <div class="form-floating mb-3">
        <input
          type="password"
          name="confirmpass"
          class="form-control"
          id="confirmpassword"
          placeholder="Επιβεβαίωση κωδικού"
          aria-label="Password confirm"
          aria-describedby="password-confirm-reg-addon">
        <label for="confirmpassword" class="form-label">Επιβεβαίωση κωδικού</label>
      </div>
      <div class="form-check form-switch mb-3">
        <input class="form-check-input" name="professional" id="professional" type="checkbox" value="" id="flexCheckProfessional">
        <label class="form-check-label" for="flexCheckProfessional">
          Κάνω εγγραφή ως επαγγελματίας.
        </label>
      </div>
      <button type="button" name="register" id="register-btn" class="btn btn-primary float-end">
        <i class="bi bi-person-plus"></i>
        Εγγραφή
      </button>
    </form>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $('#register-btn').click(function() {
    app.Register();
  });
});
</script>
