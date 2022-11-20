<?php if (!defined('ACCESS')) exit; ?>

<?= Account::ProfessionalRequired(); ?>

<div class="card text-dark mb-3">
  <div class="card-header">
    <i class="<?= $iconClass; ?>"></i>
    <?= $title; ?>
  </div>
  <div class="card-body">

    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">
          <i class="bi bi-grid"></i>
        </button>
      </li>

      <li class="nav-item" role="presentation">
        <button class="nav-link" id="manage-stores-tab" data-bs-toggle="tab" data-bs-target="#manage-stores" type="button" role="tab" aria-controls="manage-stores" aria-selected="false">
          <i class="bi bi-shop"></i>
          Καταστήματα
        </button>
      </li>

      <li class="nav-item" role="presentation">
        <button class="nav-link" id="manage-bookings-tab" data-bs-toggle="tab" data-bs-target="#manage-bookings" type="button" role="tab" aria-controls="manage-bookings" aria-selected="false">
          <i class="bi bi-bookmark-star"></i>
          Κρατήσεις
          <span class="badge bg-danger" id="new-bookings" data-bs-toggle="tooltip" data-bs-placement="right" title="Νέες κρατήσεις">
            <?= Bookings::GetNumOfNewBookingsForAllOwnedStores(Account::getAccount()->id); ?>
          </span>
        </button>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">

      <!-- Dashboard tab -->
      <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
        <div class="alert alert-info">
          Αρχική σελίδα επαγγελματία.<br />
          Εδώ μπορείτε να διαχειριστείτε τα καταστήματά σας, καθώς και τις κρατήσεις που έχουν γίνει σε αυτά.
        </div>
      </div>

      <!-- Manage Stores tab -->
      <div class="tab-pane fade" id="manage-stores" role="tabpanel" aria-labelledby="manage-stores-tab">
        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addStoreModal" onclick="javascript:prof._addStoreModal();">
          <i class="bi bi-plus"></i>
          Προσθήκη καταστήματος
        </button>
        <hr class="mydivider text-muted" />

        <div id="loading" class="text-center">
          <div class="spinner-border text-primary m-5" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>

        <table class="table display dt-responsive nowrap" style="width: 100%;" id="manage-stores-table">
          <thead>
            <tr>
              <th>Logo</th>
              <th>Τίτλος</th>
              <th>Διεύθυνση</th>
              <th>Ελεύθερα τραπέζια</th>
              <th>Status</th>
              <th>Επιλογές</th>
            </tr>
          </thead>
          <tbody id="manage-stores-result">
          </tbody>
        </table>

      </div>

      <!-- Manage Bookings tab -->
      <div class="tab-pane fade" id="manage-bookings" role="tabpanel" aria-labelledby="manage-bookings-tab">
        <table class="table display dt-responsive nowrap" style="width: 100%;" id="manage-bookings-table">
          <thead>
            <tr>
              <th>Logo</th>
              <th>Κατάστημα</th>
              <th>Όνομα</th>
              <th>Άτομα</th>
              <th>Ημερομηνία</th>
              <th>Επιλογές</th>
            </tr>
          </thead>
          <tbody id="manage-bookings-result">
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<!-- Store Info modal -->
<div class="modal fade" id="infoModal" aria-labelledby="info-title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="info-title">Store title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="word-break: break-all;">
        <p id="info-description"></p>
      </div>
      <div class="modal-footer">
        <div id="info-address"></div>
      </div>
    </div>
  </div>
</div>

<!-- Add Store modal -->
<div class="modal fade" id="addStoreModal" tabindex="-1" aria-labelledby="addStoreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addStoreModalLabel">
          <i class="bi bi-plus"></i>
          Προσθήκη καταστήματος
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="add-store-result"></div>

          <input type="hidden" id="add-categories-list" value="">

          <div class="form-floating mb-3">
            <input type="text" name="add-title" class="form-control" id="add-title" placeholder="Τίτλος" aria-label="Title" aria-describedby="title-add-addon">
            <label for="add-title" class="form-label">
              Τίτλος
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="add-city" class="form-control" id="add-city" placeholder="Πόλη" aria-label="City" aria-describedby="city-add-addon">
            <label for="add-city" class="form-label">
              Πόλη
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="add-address" class="form-control" id="add-address" placeholder="Διεύθυνση" aria-label="Address" aria-describedby="address-add-addon">
            <label for="add-address" class="form-label">
              Διεύθυνση
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="add-zipcode" class="form-control" id="add-zipcode" placeholder="T.K." aria-label="Zipcode" aria-describedby="zipcode-add-addon">
            <label for="add-zipcode" class="form-label">
              Τ.Κ.
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="mb-3">
            <label for="add-categories">Κατηγορίες</label>
            <select id="add-categories" class="selectpicker" multiple></select>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="add-image" class="form-control" id="add-image" placeholder="Ενδεικτική εικόνα (LINK)" aria-label="Image" aria-describedby="image-add-addon">
            <label for="add-image" class="form-label">
              Ενδεικτική εικόνα (LINK)
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="number" name="add-capacity" class="form-control" id="add-capacity" placeholder="Χωρητικότητα (τραπέζια)" aria-label="Χωρητικότητα" aria-describedby="capacity-edit-addon">
            <label for="add-capacity" class="form-label">
              Χωρητικότητα (τραπέζια)
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="number" name="add-maxpersonpertable" class="form-control" id="add-maxpersonpertable" placeholder="Μέγιστος αριθμός ατόμων ανά τραπέζι" aria-label="Max person per talbe" aria-describedby="maxpersonpertable-edit-addon">
            <label for="add-maxpersonpertable" class="form-label">
              Μέγιστος αριθμός ατόμων ανά τραπέζι
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <textarea class="form-control position-relative" name="add-description" id="add-description" placeholder="Περιγραφή" maxlength="300"></textarea>
            <label for="add-description">Περιγραφή</label>
            <span id="description-chars" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">300</span>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="add-store-btn" class="btn btn-success">
          <i class="bi bi-plus"></i>
          Προσθήκη
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Store modal -->
<div class="modal fade" id="editStoreModal" tabindex="-1" aria-labelledby="editStoreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editStoreModalLabel">
          <i class="bi bi-pencil-square"></i>
          Επεξεργασία καταστήματος
          <span id="approve-store-span" class="badge bg-primary">
            <i class="bi bi-check-circle"></i>
            Approved
          </span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="edit-store-result"></div>

          <input type="hidden" id="edit-id" value="">
          <input type="hidden" id="edit-categories-list" value="">

          <div class="form-floating mb-3">
            <input type="text" name="edit-title" class="form-control" id="edit-title" placeholder="Τίτλος" aria-label="Title" aria-describedby="title-edit-addon">
            <label for="edit-title" class="form-label">
              Τίτλος
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="edit-city" class="form-control" id="edit-city" placeholder="Πόλη" aria-label="City" aria-describedby="city-edit-addon">
            <label for="edit-city" class="form-label">
              Πόλη
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="edit-address" class="form-control" id="edit-address" placeholder="Διεύθυνση" aria-label="Address" aria-describedby="address-edit-addon">
            <label for="edit-address" class="form-label">
              Διεύθυνση
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="edit-zipcode" class="form-control" id="edit-zipcode" placeholder="T.K." aria-label="Zipcode" aria-describedby="zipcode-edit-addon">
            <label for="edit-zipcode" class="form-label">
              Τ.Κ.
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="mb-3">
            <label for="edit-categories">Κατηγορίες</label>
            <select id="edit-categories" class="selectpicker" multiple></select>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="edit-image" class="form-control" id="edit-image" placeholder="Ενδεικτική εικόνα (LINK)" aria-label="Image" aria-describedby="image-edit-addon">
            <label for="edit-image" class="form-label">
              Ενδεικτική εικόνα (LINK)
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="number" name="edit-capacity" class="form-control" id="edit-capacity" placeholder="Χωρητικότητα (τραπέζια)" aria-label="Χωρητικότητα" aria-describedby="capacity-edit-addon">
            <label for="edit-capacity" class="form-label">
              Χωρητικότητα (τραπέζια)
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="number" name="edit-maxpersonpertable" class="form-control" id="edit-maxpersonpertable" placeholder="Μέγιστος αριθμός ατόμων ανά τραπέζι" aria-label="Max person per talbe" aria-describedby="maxpersonpertable-edit-addon">
            <label for="edit-maxpersonpertable" class="form-label">
              Μέγιστος αριθμός ατόμων ανά τραπέζι
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <textarea class="form-control position-relative" name="edit-description" id="edit-description" placeholder="Περιγραφή" maxlength="300"></textarea>
            <label for="edit-description">Περιγραφή</label>
            <span id="edit-description-chars" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">300</span>
          </div>
          <a class="btn btn-outline-primary btn-sm" id="edit-expand-description" href="#">
            <i class="bi bi-card-text"></i>
            Θέλω να επεκτείνω την περιγραφή
          </a>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="edit-store-btn" class="btn btn-success">
          <i class="bi bi-pencil-square"></i>
          Αποθήκευση
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Documents modal -->
<div class="modal fade" id="documentsModal" aria-labelledby="documents-title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="documents-title">Store title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="word-break: break-all;">
        <div id="documents-body"></div>
      </div>
      <div class="modal-footer">
        <div id="documents-footer"></div>
      </div>
    </div>
  </div>
</div>

<!-- Operational Hours modal -->
<div class="modal fade" id="operationalHoursModal" tabindex="-1" aria-labelledby="operationalHoursModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="operationalHoursModalLabel">
          <i class="bi bi-calendar-week"></i>
          Ώρες λειτουργίας
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="op-hours-result"></div>

          <input type="hidden" id="op-hours-id" value="">

          <div class="row g-3 ms-2">
            <div class="col">
              <h6>Ημέρα</h6>
            </div>
            <div class="col">
              <span class="badge bg-success">Ανοίγει στις</span>
            </div>
            <div class="col">
              <span class="badge bg-danger">Κλείνει στις</span>
            </div>
          </div>

          <hr />

          <div class="row g-3 ms-2">
            <div class="col form-check form-switch">
              <input class="form-check-input" type="checkbox" id="monday-check">
              <label class="form-check-label" for="monday-check">Δευτέρα</label>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="monday-opens-at" disabled>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="monday-closes-at" disabled>
            </div>
          </div>
          <hr class="text-muted" />

          <div class="row g-3 ms-2">
            <div class="col form-check form-switch">
              <input class="form-check-input" type="checkbox" id="tuesday-check">
              <label class="form-check-label" for="tuesday-check">Τρίτη</label>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="tuesday-opens-at" disabled>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="tuesday-closes-at" disabled>
            </div>
          </div>
          <hr class="text-muted" />

          <div class="row g-3 ms-2">
            <div class="col form-check form-switch">
              <input class="form-check-input" type="checkbox" id="wednesday-check">
              <label class="form-check-label" for="wednesday-check">Τετάρτη</label>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="wednesday-opens-at" disabled>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="wednesday-closes-at" disabled>
            </div>
          </div>
          <hr class="text-muted" />

          <div class="row g-3 ms-2">
            <div class="col form-check form-switch">
              <input class="form-check-input" type="checkbox" id="thursday-check">
              <label class="form-check-label" for="thursday-check">Πέμπτη</label>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="thursday-opens-at" disabled>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="thursday-closes-at" disabled>
            </div>
          </div>
          <hr class="text-muted" />

          <div class="row g-3 ms-2">
            <div class="col form-check form-switch">
              <input class="form-check-input" type="checkbox" id="friday-check">
              <label class="form-check-label" for="friday-check">Παρασκευή</label>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="friday-opens-at" disabled>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="friday-closes-at" disabled>
            </div>
          </div>
          <hr class="text-muted" />

          <div class="row g-3 ms-2">
            <div class="col form-check form-switch">
              <input class="form-check-input" type="checkbox" id="saturday-check">
              <label class="form-check-label" for="saturday-check">Σάββατο</label>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="saturday-opens-at" disabled>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="saturday-closes-at" disabled>
            </div>
          </div>
          <hr class="text-muted" />

          <div class="row g-3 ms-2">
            <div class="col form-check form-switch">
              <input class="form-check-input" type="checkbox" id="sunday-check">
              <label class="form-check-label" for="sunday-check">Κυριακή</label>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="sunday-opens-at" disabled>
            </div>
            <div class="col">
              <input type="time" class="form-control" id="sunday-closes-at" disabled>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="save-op-hours-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-pencil-square"></i>
          Αποθήκευση
        </button>
      </div>
    </div>
  </div>
</div>

<!-- View Contact Details modal -->
<div class="modal fade" id="viewContactDetailsModal" aria-labelledby="viewContactDetailsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contact-details-title">Contact details title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="word-break: break-all;">
        <p id="contact-details-result"></p>
      </div>
    </div>
  </div>
</div>

<div id="init"></div>

<script type="text/javascript">
  var addStoreCategories = [];
  var editStoreCategories = [];

  $(document).ready(function() {
    prof.LoadManageStoresTab();
    prof.LoadManageBookingsTab();

    // Add Description characters left
    $('#add-description').keyup(function() {
      $('#description-chars').html(300 - $('#add-description').val().length)
    });

    $('#add-description').keydown(function() {
      $('#description-chars').html(300 - $('#add-description').val().length)
    });

    // Edit Description characters left
    $('#edit-description').keyup(function() {
      $('#edit-description-chars').html(300 - $('#edit-description').val().length)
    });

    $('#edit-description').keydown(function() {
      $('#edit-description-chars').html(300 - $('#edit-description').val().length)
    });

    // Modal Buttons
    $('#add-store-btn').click(function() {
      prof.AddStore();
    });

    $('#edit-store-btn').click(function() {
      prof.EditStore();
    });

    $('#save-op-hours-btn').click(function() {
      prof.SaveOperationalHours();
    });

    // Operational Hours checkboxes
    $('#monday-check').change(function() {
      if ($(this).is(':checked')) {
        $('#monday-opens-at').prop('disabled', false);
        $('#monday-closes-at').prop('disabled', false);
      } else {
        $('#monday-opens-at').prop('disabled', true);
        $('#monday-closes-at').prop('disabled', true);
      }
    });

    $('#tuesday-check').change(function() {
      if ($(this).is(':checked')) {
        $('#tuesday-opens-at').prop('disabled', false);
        $('#tuesday-closes-at').prop('disabled', false);
      } else {
        $('#tuesday-opens-at').prop('disabled', true);
        $('#tuesday-closes-at').prop('disabled', true);
      }
    });

    $('#wednesday-check').change(function() {
      if ($(this).is(':checked')) {
        $('#wednesday-opens-at').prop('disabled', false);
        $('#wednesday-closes-at').prop('disabled', false);
      } else {
        $('#wednesday-opens-at').prop('disabled', true);
        $('#wednesday-closes-at').prop('disabled', true);
      }
    });

    $('#thursday-check').change(function() {
      if ($(this).is(':checked')) {
        $('#thursday-opens-at').prop('disabled', false);
        $('#thursday-closes-at').prop('disabled', false);
      } else {
        $('#thursday-opens-at').prop('disabled', true);
        $('#thursday-closes-at').prop('disabled', true);
      }
    });

    $('#friday-check').change(function() {
      if ($(this).is(':checked')) {
        $('#friday-opens-at').prop('disabled', false);
        $('#friday-closes-at').prop('disabled', false);
      } else {
        $('#friday-opens-at').prop('disabled', true);
        $('#friday-closes-at').prop('disabled', true);
      }
    });

    $('#saturday-check').change(function() {
      if ($(this).is(':checked')) {
        $('#saturday-opens-at').prop('disabled', false);
        $('#saturday-closes-at').prop('disabled', false);
      } else {
        $('#saturday-opens-at').prop('disabled', true);
        $('#saturday-closes-at').prop('disabled', true);
      }
    });

    $('#sunday-check').change(function() {
      if ($(this).is(':checked')) {
        $('#sunday-opens-at').prop('disabled', false);
        $('#sunday-closes-at').prop('disabled', false);
      } else {
        $('#sunday-opens-at').prop('disabled', true);
        $('#sunday-closes-at').prop('disabled', true);
      }
    });

    // Edit Categories select picker
    $('#edit-categories').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
      var opt = e.target.options[clickedIndex];

      if (opt.selected) editStoreCategories.push(opt.value);
      else editStoreCategories = removeFromArray(editStoreCategories, opt.value);

      var editCategoriesList = '';
      editStoreCategories.forEach((el, index) => {
        if (index === editStoreCategories.length - 1) editCategoriesList += el;
        else editCategoriesList += el + ',';
      });

      $('#edit-categories-list').val(editCategoriesList);
    });

    // Add Categories select picker
    $('#add-categories').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
      var opt = e.target.options[clickedIndex];

      if (opt.selected) addStoreCategories.push(opt.value);
      else addStoreCategories = removeFromArray(addStoreCategories, opt.value);

      var addCategoriesList = '';
      addStoreCategories.forEach((el, index) => {
        if (index === addStoreCategories.length - 1) addCategoriesList += el;
        else addCategoriesList += el + ',';
      });

      $('#add-categories-list').val(addCategoriesList);
    });
  });
</script>