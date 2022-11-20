<?php if (!defined('ADMIN')) exit; ?>

<?= Account::AdminRequired(); ?>

<div class="card text-dark mb-3">
  <div class="card-header">
    <i class="<?= $iconClass; ?>"></i>
    <?= $title; ?>
  </div>
  <div class="card-body">

    <div id="loading" class="text-center">
      <div class="spinner-border text-primary m-5" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <table class="table display dt-responsive nowrap" style="width: 100%;" id="myTable">
      <thead>
        <tr>
          <th>Logo</th>
          <th>Τίτλος</th>
          <th>Διεύθυνση</th>
          <th>Status</th>
          <th>Επιλογές</th>
        </tr>
      </thead>
      <tbody id="stores-result">
      </tbody>
    </table>

  </div>
</div>

<div id="init"></div>

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

<!-- Edit Store modal -->
<div class="modal fade" id="editStoreModal" tabindex="-1" aria-labelledby="editStoreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editStoreModalLabel">
          <i class="bi bi-pencil-square"></i>
          Επεξεργασία καταστήματος
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
            <span id="description-chars" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">300</span>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="approve-store-btn" class="btn btn-outline-primary btn-sm">
          <i class="bi bi-check-circle"></i>
          Approve
        </button>
        <button type="button" id="delete-store-btn" class="btn btn-danger btn-sm">
          <i class="bi bi-trash3"></i>
          Διαγραφή
        </button>
        <button type="button" id="edit-store-btn" class="btn btn-purple btn-sm">
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
    </div>
  </div>
</div>

<script type="text/javascript">  
var storeCategories = [];

$(document).ready(function() {
  admin.LoadStores();

  $('#edit-description').keyup(function() {
    $('#description-chars').html(300-$('#edit-description').val().length)
  });

  $('#edit-description').keydown(function() {
    $('#description-chars').html(300-$('#edit-description').val().length)
  });

  $('#edit-store-btn').click(function() {
    admin.EditStore();
  });

  $('#delete-store-btn').click(function() {
    admin.DeleteStore();
  });

  $('#approve-store-btn').click(function() {
    admin.ApproveStore();
  });

  $('#edit-categories').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    var opt = e.target.options[clickedIndex];

    if (opt.selected) storeCategories.push(opt.value);
    else storeCategories = removeFromArray(storeCategories, opt.value);

    var editCategoriesList = '';
    storeCategories.forEach((el, index) => {
      if (index === storeCategories.length-1) editCategoriesList += el;
      else editCategoriesList += el + ',';
    });

    $('#edit-categories-list').val(editCategoriesList);
  });
});
</script>