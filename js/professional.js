var prof = {
  _infoModal: function (storeid) {
    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-store',
        'sid': storeid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#info-title').html('-');
            $('#info-description').html('<div class="alert alert-danger">Nothing to show.</div>');
            break;
          case 1:
            break;
          case 2:
            var store = res.data;
            var storeAddress = `${store.city}, ${store.address}, ${store.zipcode}`;
            var approval = (store.approved != 0)
              ? '<span class="badge bg-primary"><i class="bi bi-check-circle"></i> Approved</span>'
              : '<span class="badge bg-warning"><i class="bi bi-hourglass-split"></i> Pending</span>';

            $('#info-title').html(`${store.title} ${approval}`);
            $('#info-description').html(store.description);
            $('#info-address').html(`
              <small>
                <a href="https://maps.google.com/?q=${storeAddress}" class="text-muted" target="_blank">
                  <i class="bi bi-geo text-danger"></i>
                  ${storeAddress}
                </a>
              </small>
            `);
            break;
        }
      }
    });
  },

  _documentsModal: function (storeid) {
    $('#delete-documents-result').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'view-store-documents',
        'sid': storeid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#documents-title').html('-');
            $('#documents-body').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            // Upload documents
            var store = res.data.store;

            var result = '';

            $('#documents-title').html(`<i class="bi bi-file-earmark-pdf"></i> Αποστολή εγγράφων: ${store.title}`);

            result += `<div class="alert alert-info" id="upload-documents-result">${res.data.msg}</div>`;

            result += `
              <form id="form-upload-documents" enctype="multipart/form-data">
                <input type="hidden" id="upload-documents-id" value="${store.id}">
                <div class="mb-3">
                  <label for="upload-identification" class="form-label">
                    <i class="bi bi-person-badge"></i>
                    Ταυτότητα
                  </label>
                  <input class="form-control" type="file" id="upload-identification" accept="application/pdf">
                </div>
                <div class="mb-3">
                  <label for="upload-license" class="form-label">
                    <i class="bi bi-file-earmark-richtext"></i>
                    Άδεια καταστήματος
                  </label>
                  <input class="form-control" type="file" id="upload-license" accept="application/pdf">
                </div>
              </form>
            `;

            $('#documents-body').html(result);

            $('#documents-footer').html(`
            <button type="button" class="btn btn-primary btn-sm" id="upload-documents-btn">
              <i class="bi bi-send"></i>
              Υποβολή
            </button>
            `);

            $('#init').html(`
            <script type="text/javascript">
              $('#upload-documents-btn').click(function() {
                prof.UploadDocuments();
              });
            </script>
            `);
            break;
          case 2:
            // View documents
            var store = res.data.store;
            var documents = res.data.documents;

            var result = '';

            $('#documents-title').html(`<i class="bi bi-file-earmark-pdf"></i> Προβολή εγγράφων: ${store.title}`);

            result += `
            <input type="hidden" id="upload-documents-id" value="${store.id}">
            
            <a href="view-document.php?id=${documents.id}&type=identification" target="_blank" class="text-decoration-none">
              <div class="card border-info mb-3" style="width: 18rem;">
                <div class="card-body">
                  <h5 class="card-title text-dark">
                    <i class="bi bi-person-badge"></i>
                    Ταυτότητα
                  </h5>
                  <h6 class="card-subtitle mb-2 text-muted">Προβολή</h6>
                </div>
              </div>
            </a>

            <a href="view-document.php?id=${documents.id}&type=license" target="_blank" class="text-decoration-none">
              <div class="card border-info" style="width: 18rem;">
                <div class="card-body">
                  <h5 class="card-title text-dark">
                    <i class="bi bi-file-earmark-richtext"></i>
                    Άδεια καταστήματος
                  </h5>
                  <h6 class="card-subtitle mb-2 text-muted">Προβολή</h6>
                </div>
              </div>
            </a>
            `;

            $('#documents-body').html(result);

            $('#documents-footer').html(`
            <span id="delete-documents-result" class="text-muted"></span>
            <button type="button" class="btn btn-outline-danger btn-sm" id="delete-documents-btn">
              <i class="bi bi-trash3"></i>
              Διαγραφή
            </button>
            `);

            $('#init').html(`
            <script type="text/javascript">
              $('#delete-documents-btn').click(function() {
                if (confirm('Είσαι σίγουρος;') == true)
                  prof.DeleteDocuments();
              });
            </script>
            `);
            break;
        }
      }
    });
  },

  _operationalHoursModal: function (storeid) {
    $('#op-hours-result').html('');
    $('#op-hours-id').val(storeid);

    weekDays.forEach(day => {
      $(`#${day}-check`).prop('checked', false);
      $(`#${day}-opens-at`).val('').prop('disabled', true);
      $(`#${day}-closes-at`).val('').prop('disabled', true);
    });

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-store-operational-hours',
        'sid': storeid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#op-hours-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            var opHours = res.data;

            $('#op-hours-id').val(`${opHours[0].storeid}`);

            opHours.forEach(el => {
              var day = weekDays[el.week_day];
              $(`#${day}-check`).prop('checked', true);
              $(`#${day}-opens-at`).val(el.opens_at).prop('disabled', false);
              $(`#${day}-closes-at`).val(el.closes_at).prop('disabled', false);
            });
            break;
        }
      }
    });
  },

  _viewContactDetailsModal: function (bookingid) {
    $('#contact-details-title').html('-');
    $('#contact-details-result').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-booking-contact-details',
        'bid': bookingid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#contact-details-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            console.log(res.data);
            var contactDetails = res.data;

            $('#contact-details-title').html(`Contact Details for Booking #${contactDetails.id}`);
            $('#contact-details-result').html(`
              <div class="row mt-2">
                <div class="col">
                  <label>Όνομα</label>
                </div>
                <div class="col">
                  <input class="form-control" type="text" value="${contactDetails.name}" disabled>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col">
                  <label>Email</label>
                </div>
                <div class="col">
                  <input class="form-control" type="text" value="${contactDetails.email}" disabled>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col">
                  <label>Τηλέφωνο</label>
                </div>
                <div class="col">
                  <input class="form-control" type="text" value="${contactDetails.phone}" disabled>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col">
                  <label>Μήνυμα</label>
                </div>
                <div class="col">
                  <textarea class="form-control" disabled>${contactDetails.message}</textarea>
                </div>
              </div>
            `);
            break;
        }
      }
    });
  },

  _addStoreModal: function () {
    addStoreCategories = [];

    $('#add-store-result').html('');

    $('#description-chars').html(300);
    $('#add-categories-list').val('');

    $('#add-title').val('');
    $('#add-city').val('');
    $('#add-address').val('');
    $('#add-zipcode').val('');
    $('#add-image').val('');
    $('#add-capacity').val('');
    $('#add-maxpersonpertable').val('');
    $('#add-description').val('');
    $('#add-categories').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-categories'
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            break;
          case 1:
            break;
          case 2:
            res.data.forEach((el, index) => {
              $('#add-categories').append(`<option value="${el.id}">${el.name}</option>`);
            });

            $('#add-categories').selectpicker('refresh');
            break;
        }
      }
    });
  },

  _editStoreModal: function (storeid) {
    editStoreCategories = [];

    $('#edit-store-result').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-store',
        'sid': storeid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-store-result').html('<div class="alert alert-danger">Something went wrong.</div>');
            break;
          case 1:
            break;
          case 2:
            var store = res.data;

            if (store.approved == 1) {
              $('#approve-store-span').removeClass('bg-warning');
              $('#approve-store-span').addClass('bg-primary');
              $('#approve-store-span').html(`
                <i class="bi bi-check-circle"></i>
                Approved
              `);
            } else {
              $('#approve-store-span').removeClass('bg-primary');
              $('#approve-store-span').addClass('bg-warning');
              $('#approve-store-span').html(`
                <i class="bi bi-hourglass-split"></i>
                Pending
              `);
            }

            // add all categories in edit-categories as options
            // show 'selected' if store includes this category

            // select edit-categories
            $('#edit-categories').html('');
            $.ajax({
              type: 'post',
              url: 'ajax.php',
              data: {
                'action': 'load-categories'
              },
              success: function (catRes) {
                switch (catRes.status) {
                  case 0:
                    //
                    break;
                  case 1:
                    //
                    break;
                  case 2:
                    catRes.data.forEach(c => {
                      var currCategories = $('#edit-categories').html();
                      var storeIncludesCategory = (app._storeIncludesCategory(store.categories, c.id)) ? ' selected="selected"' : '';

                      if (storeIncludesCategory != '')
                        editStoreCategories.push(c.id);

                      $('#edit-categories').html(currCategories + `<option value='${c.id}'${storeIncludesCategory}>${c.name}</option>`);
                    });

                    $('#edit-categories').selectpicker('refresh');
                    break;
                }
              }
            });

            // fill in inputs
            $('#edit-id').val(store.id);
            $('#edit-title').val(store.title);
            $('#edit-description').val(store.description);
            $('#edit-city').val(store.city);
            $('#edit-address').val(store.address);
            $('#edit-zipcode').val(store.zipcode);
            $('#edit-image').val(store.image);
            $('#edit-maxpersonpertable').val(store.maxpersonpertable);
            $('#edit-approved').val(store.approved);
            $('#edit-capacity').val(store.capacity);
            $('#edit-expand-description').attr('href', `?page=edit-description&sid=${store.id}`);

            // input edit-categories-list
            var editCategoriesList = '';
            store.categories.forEach((c, index) => {
              if (index === store.categories.length - 1) editCategoriesList += c.id;
              else editCategoriesList += c.id + ',';
            });

            $('#edit-categories-list').val(editCategoriesList);
            $('#edit-description-chars').html(300 - store.description.length);

            break;
        }
      }
    });
  },

  AddStore: function () {
    const resetBtn = () => {
      $('#add-store-btn').removeClass('disabled');
      $('#add-store-btn').html(`
        <i class="bi bi-plus"></i>
        Προσθήκη
      `);
    }

    $('#add-store-btn').addClass('disabled');
    $('#add-store-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var title = $('#add-title').val();
    var city = $('#add-city').val();
    var address = $('#add-address').val();
    var zipcode = $('#add-zipcode').val();
    var image = $('#add-image').val();
    var capacity = $('#add-capacity').val();
    var maxpersonpertable = $('#add-maxpersonpertable').val();
    var description = $('#add-description').val();
    var categories = $('#add-categories-list').val();

    if (!title
      || !city
      || !address
      || !zipcode
      || !image
      || !description) {
      $('#add-store-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
      resetBtn();
      return;
    }

    if (title.length > 50 || description.length > 300 || zipcode.length > 5) {
      $('#add-store-result').html(`
        Max chars are: title 50, description 300, zipcode 5
      `);
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'add-store',
        'title': title,
        'city': city,
        'address': address,
        'zipcode': zipcode,
        'image': image,
        'capacity': capacity,
        'maxpersonpertable': maxpersonpertable,
        'description': description,
        'categories': categories
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#add-store-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#add-store-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }

        resetBtn();
      }
    });
  },

  EditStore: function () {
    const resetBtn = () => {
      $('#edit-store-btn').removeClass('disabled');
      $('#edit-store-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αποθήκευση
      `);
    }

    $('#edit-store-btn').addClass('disabled');
    $('#edit-store-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var sid = $('#edit-id').val();
    var title = $('#edit-title').val();
    var city = $('#edit-city').val();
    var address = $('#edit-address').val();
    var zipcode = $('#edit-zipcode').val();
    var image = $('#edit-image').val();
    var capacity = $('#edit-capacity').val();
    var maxpersonpertable = $('#edit-maxpersonpertable').val();
    var description = $('#edit-description').val();
    var categories = $('#edit-categories-list').val();

    if (sid === '') {
      resetBtn();
      return;
    }

    if (!title
      || !city
      || !address
      || !zipcode
      || !image
      || !description) {
      $('#edit-store-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
      resetBtn();
      return;
    }

    if (title.length > 50 || description.length > 300 || zipcode.length > 5) {
      $('#edit-store-result').html(`
        Max chars are: title 50, description 300, zipcode 5
      `);
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'edit-store',
        'sid': sid,
        'title': title,
        'city': city,
        'address': address,
        'zipcode': zipcode,
        'image': image,
        'capacity': capacity,
        'maxpersonpertable': maxpersonpertable,
        'description': description,
        'categories': categories
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-store-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#edit-store-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }

        resetBtn();
      }
    });
  },

  DeleteDocuments: function () {
    const resetBtn = () => {
      $('#delete-documents-btn').removeClass('disabled');
      $('#delete-documents-btn').html(`
        <i class="bi bi-trash3"></i>
        Διαγραφή
      `);
    }

    $('#delete-documents-btn').addClass('disabled');
    $('#delete-documents-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var sid = $('#upload-documents-id').val();

    if (sid === '') {
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'delete-store-documents',
        'sid': sid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#delete-documents-result').html(res.data);
            break;
          case 1:
            break;
          case 2:
            // toggle documents modal again
            $('#documentsModal').hide();
            prof._documentsModal(sid);
            $('#documentsModal').show();
            break;
        }

        resetBtn();
      }
    });
  },

  UploadDocuments: function () {
    const resetBtn = () => {
      $('#upload-documents-btn').removeClass('disabled');
      $('#upload-documents-btn').html(`
        <i class="bi bi-send"></i>
        Υποβολή
      `);
    }

    $('#upload-documents-btn').addClass('disabled');
    $('#upload-documents-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var sid = $('#upload-documents-id').val();
    var identification = $('#upload-identification').prop('files')[0];
    var license = $('#upload-license').prop('files')[0];

    if (sid === '') {
      resetBtn();
      return;
    }

    var formData = new FormData();
    formData.append('action', 'upload-store-documents');
    formData.append('sid', sid);
    formData.append('identification', identification);
    formData.append('license', license);

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#upload-documents-result').removeClass('alert-info');
            $('#upload-documents-result').removeClass('alert-success');
            $('#upload-documents-result').addClass('alert-danger');
            $('#upload-documents-result').html(res.data);
            break;
          case 1:
            break;
          case 2:
            $('#upload-documents-result').removeClass('alert-info');
            $('#upload-documents-result').removeClass('alert-danger');
            $('#upload-documents-result').addClass('alert-success');
            $('#upload-documents-result').html(res.data);

            // toggle documents modal again
            $('#documentsModal').hide();
            prof._documentsModal(sid);
            $('#documentsModal').show();
            break;
        }

        resetBtn();
      }
    });
  },

  SaveOperationalHours: function() {
    const resetBtn = () => {
      $('#save-op-hours-btn').removeClass('disabled');
      $('#save-op-hours-btn').html(`
        <i class="bi bi-pencil-square btn-sm"></i>
        Αποθήκευση
      `);
    }

    $('#save-op-hours-btn').addClass('disabled');
    $('#save-op-hours-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var sid = $('#op-hours-id').val();

    var mondayCheck = $('#monday-check').prop('checked') ? 1 : 0;
    var tuesdayCheck = $('#tuesday-check').prop('checked') ? 1 : 0;
    var wednesdayCheck = $('#wednesday-check').prop('checked') ? 1 : 0;
    var thursdayCheck = $('#thursday-check').prop('checked') ? 1 : 0;
    var fridayCheck = $('#friday-check').prop('checked') ? 1 : 0;
    var saturdayCheck = $('#saturday-check').prop('checked') ? 1 : 0;
    var sundayCheck = $('#sunday-check').prop('checked') ? 1 : 0;

    var mondayOpensAt = $('#monday-opens-at').val();
    var tuesdayOpensAt = $('#tuesday-opens-at').val();
    var wednesdayOpensAt = $('#wednesday-opens-at').val();
    var thursdayOpensAt = $('#thursday-opens-at').val();
    var fridayOpensAt = $('#friday-opens-at').val();
    var saturdayOpensAt = $('#saturday-opens-at').val();
    var sundayOpensAt = $('#sunday-opens-at').val();

    var mondayClosesAt = $('#monday-closes-at').val();
    var tuesdayClosesAt = $('#tuesday-closes-at').val();
    var wednesdayClosesAt = $('#wednesday-closes-at').val();
    var thursdayClosesAt = $('#thursday-closes-at').val();
    var fridayClosesAt = $('#friday-closes-at').val();
    var saturdayClosesAt = $('#saturday-closes-at').val();
    var sundayClosesAt = $('#sunday-closes-at').val();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'save-store-operational-hours',
        'sid': sid,

        'mondayCheck': mondayCheck,
        'tuesdayCheck': tuesdayCheck,
        'wednesdayCheck': wednesdayCheck,
        'thursdayCheck': thursdayCheck,
        'fridayCheck': fridayCheck,
        'saturdayCheck': saturdayCheck,
        'sundayCheck': sundayCheck,

        'mondayOpensAt': mondayOpensAt,
        'tuesdayOpensAt': tuesdayOpensAt,
        'wednesdayOpensAt': wednesdayOpensAt,
        'thursdayOpensAt': thursdayOpensAt,
        'fridayOpensAt': fridayOpensAt,
        'saturdayOpensAt': saturdayOpensAt,
        'sundayOpensAt': sundayOpensAt,

        'mondayClosesAt': mondayClosesAt,
        'tuesdayClosesAt': tuesdayClosesAt,
        'wednesdayClosesAt': wednesdayClosesAt,
        'thursdayClosesAt': thursdayClosesAt,
        'fridayClosesAt': fridayClosesAt,
        'saturdayClosesAt': saturdayClosesAt,
        'sundayClosesAt': sundayClosesAt
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#op-hours-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#op-hours-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }

        resetBtn();
      }
    });
  },

  LoadManageStoresTab: function () {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-manage-stores-tab'
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            // error
            break;
          case 1:
            break;
          case 2:
            var result = '';
            res.data.forEach(store => {
              var storeAddress = `${store.address}, ${store.city}, ${store.zipcode}`;
              var availableTables = store.capacity - store.reserved;

              var approval = '';
              if (store.approved != 0) approval = '<div class="badge bg-primary">Approved</div>';
              else approval = '<span class="badge bg-warning">Pending</span>';

              result += `
              <tr>
                <td><img src="${store.image}" class="rounded" width="40px" height="40px" /></td>
                <td>
                
                  <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#infoModal" onclick="javascript:prof._infoModal(${store.id});">
                    ${store.title}
                  </button>
                </td>
                <td>
                  <a class="btn btn-danger btn-sm" href="https://maps.google.com/?q=${storeAddress}" target="_blank">
                    <i class="bi bi-geo"></i>
                  </a>
                  ${storeAddress}
                </td>
                <td>${availableTables}</td>
                <td>${approval}</td>
                <td>
                  <span data-bs-toggle="modal" data-bs-target="#editStoreModal">
                    <a href="#${store.id}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" onclick="javascript:prof._editStoreModal(${store.id});">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                  </span>
                  <span data-bs-toggle="modal" data-bs-target="#documentsModal">
                    <a href="#${store.id}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Documents" onclick="javascript:prof._documentsModal(${store.id});">
                      <i class="bi bi-file-earmark-pdf"></i>
                    </a>
                  </span>
                  <span data-bs-toggle="modal" data-bs-target="#operationalHoursModal">
                    <a href="#${store.id}" class="btn btn-purple btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Operational Hours" onclick="javascript:prof._operationalHoursModal(${store.id});">
                      <i class="bi bi-calendar-week"></i>
                    </a>
                  </span>
                </td>
              </tr>
              `;
            });

            $('#manage-stores-result').html(result);

            $('#manage-stores-table').DataTable({
              'order': [[4, 'desc']]
            });

            app._initTooltips();

            break;
        }

        $('#loading').hide();
      }
    });
  },

  LoadManageBookingsTab: function () {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-manage-bookings-tab'
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            // error
            break;
          case 1:
            break;
          case 2:
            var result = '';
            res.data.forEach(bookingDetails => {
              var store = bookingDetails.store;
              var booking = bookingDetails.booking;

              result += `
              <tr>
                <td><img src="${store.image}" class="rounded" width="40px" height="40px" /></td>
                <td>
                
                  <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#infoModal" onclick="javascript:prof._infoModal(${store.id});">
                    ${store.title}
                  </button>
                </td>
                <td>
                  ${booking.name}
                </td>
                <td>
                  ${booking.persons}
                </td>
                <td>
                  ${booking.date} ${booking.time}
                </td>
                <td>
                  <span data-bs-toggle="modal" data-bs-target="#viewContactDetailsModal">
                    <a href="#${booking.id}" class="btn btn-purple btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Contact Details" onclick="javascript:prof._viewContactDetailsModal(${booking.id});">
                      <i class="bi bi-person-rolodex"></i>
                    </a>
                  </span>
                  <a href="#${store.id}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="javascript:prof.DeleteBooking(${booking.id});">
                    <i class="bi bi-trash3"></i>
                  </a>
                </td>
              </tr>
              `;
            });

            $('#manage-bookings-result').html(result);

            $('#manage-bookings-table').DataTable({
              'order': [[3, 'desc']]
            });

            app._initTooltips();

            break;
        }

        $('#loading').hide();
      }
    });
  },

  DeleteBooking: function (bookingid) {
    if (confirm('Είσαι σίγουρος;') != true)
      return;

    if (bookingid === '') {
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'delete-booking',
        'bid': bookingid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            alert(res.data);
            break;
          case 1:
            break;
          case 2:
            location.reload();
            break;
        }
      }
    });
  },

  EditDescription: function () {
    const resetBtn = () => {
      $('#save-btn').removeClass('disabled');
      $('#save-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αποθήκευση
      `);
    }

    $('#save-btn').addClass('disabled');
    $('#save-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var sid = $('#sid').val();
    var description = $('#description').val();

    if (sid === '' || description.length > 2000) {
      resetBtn();
      return;
    }
    
    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'edit-store-description',
        'sid': sid,
        'description': description
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#description-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#description-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }

        resetBtn();
      }
    });
  },
};
