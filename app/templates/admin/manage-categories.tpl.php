<?php if (!defined('ADMIN')) exit; ?>

<?= Account::AdminRequired(); ?>

<div class="card text-dark mb-3">
  <div class="card-header">
    <i class="<?= $iconClass; ?>"></i>
    <?= $title; ?>
  </div>
  <div class="card-body">

    <button type="button" class="btn btn-sm btn-purple" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
      <i class="bi bi-tags"></i>
      Προσθήκη κατηγορίας
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
          <th>Ενδεικτική εικόνα</th>
          <th>Όνομα</th>
          <th>Επιλογές</th>
        </tr>
      </thead>
      <tbody id="categories-result">
      </tbody>
    </table>

  </div>
</div>

<div id="init"></div>

<!-- Add Category modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">
          <i class="bi bi-tags"></i>
          Προσθήκη κατηγορίας
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="add-category-result"></div>

          <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" id="name" placeholder="Όνομα κατηγορίας" aria-label="Name" aria-describedby="name-addon">
            <label for="name" class="form-label">
              Όνομα κατηγορίας
              <span class="text-danger">*</span>
            </label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" id="image" placeholder="Ενδεικτική εικόνα (LINK)" aria-label="Image" aria-describedby="image-addon">
            <label for="image" class="form-label">
              Ενδεικτική εικόνα (LINK)
              <span class="text-danger">*</span>
            </label>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="add-category-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-tags"></i>
          Προσθήκη
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Category modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">
          <i class="bi bi-pencil-square"></i>
          Επεξεργασία κατηγορίας
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div id="edit-category-result"></div>

          <input type="hidden" id="edit-id" value="">

          <div class="form-floating mb-3">
            <input type="text" name="edit-name" class="form-control" id="edit-name" placeholder="Όνομα" aria-label="Name" aria-describedby="name-edit-addon">
            <label for="edit-name" class="form-label">
              Όνομα
              <span class="text-danger">*</span>
            </label>
          </div>
          
          <div class="form-floating mb-3">
            <input type="text" name="edit-image" class="form-control" id="edit-image" placeholder="Ενδεικτική εικόνα (LINK)" aria-label="Image" aria-describedby="image-edit-addon">
            <label for="edit-image" class="form-label">
              Ενδεικτική εικόνα (LINK)
              <span class="text-danger">*</span>
            </label>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="delete-category-btn" class="btn btn-danger btn-sm">
          <i class="bi bi-trash3"></i>
          Διαγραφή
        </button>
        <button type="button" id="edit-category-btn" class="btn btn-purple btn-sm">
          <i class="bi bi-pencil-square"></i>
          Αποθήκευση
        </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  admin.LoadCategories();

  $('#add-category-btn').click(function() {
    admin.AddCategory();
  });

  $('#edit-category-btn').click(function() {
    admin.EditCategory();
  });

  $('#delete-category-btn').click(function() {
    admin.DeleteCategory();
  });
});
</script>
