<?php if (!defined('ADMIN')) exit; ?>

<?= Account::AdminRequired(); ?>

<div class="card text-dark mb-3">
  <div class="card-header">
    <i class="<?= $iconClass; ?>"></i>
    <?= $title; ?>
  </div>
  <div class="card-body">

    <button type="button" class="btn btn-sm btn-purple" data-bs-toggle="modal" data-bs-target="#addUserModal">
      <i class="bi bi-person-plus"></i>
      Προσθήκη χρήστη
    </button>
    <hr class="mydivider text-muted" />

    <div id="loading" class="text-center">
      <div class="spinner-border text-primary m-5" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <table class="table display dt-responsive nowrap" style="width: 100%;" id="myTable">
      <thead>
        <tr>
          <th>Username</th>
          <th>Όνομα</th>
          <th>Email</th>
          <th>Rank</th>
          <th>Επιλογές</th>
        </tr>
      </thead>
      <tbody id="users-result">
      </tbody>
    </table>

  </div>
</div>

<div id="init"></div>

<!-- Add User modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">
          <i class="bi bi-person-plus"></i>
          Προσθήκη χρήστη
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="add-user-result"></div>

          <div class="form-floating mb-3">
            <input type="text" name="user" class="form-control" id="username" placeholder="Όνομα χρήστη" aria-label="Username" aria-describedby="username-reg-addon">
            <label for="username" class="form-label">
              Όνομα χρήστη
              <span class="text-danger">*</span>
            </label>
          </div>
          
          <div class="form-floating mb-3">
            <input type="text" name="firstname" class="form-control" id="firstname" placeholder="Όνομα" aria-label="First Name" aria-describedby="firstname-reg-addon">
            <label for="firstname" class="form-label">
              Όνομα
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Επίθετο" aria-label="Last Name" aria-describedby="lastname-reg-addon">
            <label for="lastname" class="form-label">
              Επίθετο
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="email" name="email" class="form-control" id="email" placeholder="Email" aria-label="Email" aria-describedby="email-reg-addon">
            <label for="email" class="form-label">
              Email
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="phone" name="phone" class="form-control" id="phone" placeholder="Τηλέφωνο" aria-label="Phone" aria-describedby="phone-reg-addon">
            <label for="phone" class="form-label">
              Τηλέφωνο
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="password" name="pass" class="form-control" id="password" placeholder="Κωδικός" aria-label="Password" aria-describedby="password-reg-addon">
            <label for="password" class="form-label">
              Κωδικός
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="password" name="confirmpass" class="form-control" id="confirmpassword" placeholder="Επιβεβαίωση κωδικού" aria-label="Password confirm" aria-describedby="password-confirm-reg-addon">
            <label for="confirmpassword" class="form-label">
              Επιβεβαίωση κωδικού
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="input-group mb-3">
            <i class="input-group-text bi bi-bar-chart-line"></i>
            <select name="rank" id="rank" class="form-select uneditable-input">
              <label class="form-label">Rank</label>
              <option value="0" selected>Χρήστης</option>
              <option value="1">Επαγγελματίας</option>
              <option value="2">Διαχειριστής</option>
            </select>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" id="add-user-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-person-plus"></i>
          Προσθήκη
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Edit User modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">
          <i class="bi bi-pencil-square"></i>
          Επεξεργασία χρήστη
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="edit-user-result"></div>

          <input type="hidden" id="edit-id" value="">

          <div class="form-floating mb-3">
            <input type="text" name="edit-user" class="form-control" id="edit-username" placeholder="Όνομα χρήστη" aria-label="Username" aria-describedby="username-edit-addon">
            <label for="edit-username" class="form-label">
              Όνομα χρήστη
              <span class="text-danger">*</span>
            </label>
          </div>
          
          <div class="form-floating mb-3">
            <input type="text" name="edit-firstname" class="form-control" id="edit-firstname" placeholder="Όνομα" aria-label="First Name" aria-describedby="firstname-edit-addon">
            <label for="edit-firstname" class="form-label">
              Όνομα
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="edit-lastname" class="form-control" id="edit-lastname" placeholder="Επίθετο" aria-label="Last Name" aria-describedby="lastname-edit-addon">
            <label for="lastname" class="form-label">
              Επίθετο
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="email" name="edit-email" class="form-control" id="edit-email" placeholder="Email" aria-label="Email" aria-describedby="email-edit-addon">
            <label for="edit-email" class="form-label">
              Email
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="phone" name="edit-phone" class="form-control" id="edit-phone" placeholder="Τηλέφωνο" aria-label="Phone" aria-describedby="phone-edit-addon">
            <label for="edit-phone" class="form-label">
              Τηλέφωνο
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="input-group mb-3">
            <i class="input-group-text bi bi-bar-chart-line"></i>
            <select name="edit-rank" id="edit-rank" class="form-select uneditable-input">
              <label class="form-label">Rank</label>
              <option value="0" selected>Χρήστης</option>
              <option value="1">Επαγγελματίας</option>
              <option value="2">Διαχειριστής</option>
            </select>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" id="delete-user-btn" class="btn btn-danger btn-sm">
          <i class="bi bi-trash3"></i>
          Διαγραφή
        </button>
        <button type="button" id="edit-user-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-pencil-square"></i>
          Αποθήκευση
        </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  admin.LoadUsers();

  $('#add-user-btn').click(function() {
    admin.AddUser();
  });

  $('#edit-user-btn').click(function() {
    admin.EditUser();
  });

  $('#delete-user-btn').click(function () {
    admin.DeleteUser();
  });
});
</script>
