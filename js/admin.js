var admin = {
  _infoModal: function(storeid) {
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

  _documentsModal: function(storeid) {
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
            // No documents uploaded
            var store = res.data.store;

            $('#documents-title').html(`<i class="bi bi-file-earmark-pdf"></i> Προβολή εγγράφων: ${store.title}`);

            result = `<div class="alert alert-warning">Δεν υπάρχουν έγγραφα.</div>`;
            $('#documents-body').html(result);
            break;
          case 2:
            // View documents
            var store = res.data.store;
            var documents = res.data.documents;

            var result = '';

            $('#documents-title').html(`<i class="bi bi-file-earmark-pdf"></i> Προβολή εγγράφων: ${store.title}`);

            result += `            
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
            break;
        }
      }
    });
  },

  _editUserModal: function(uid) {
    $('#edit-user-result').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-user',
        'uid': uid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-user-result').html('<div class="alert alert-danger">Something went wrong.</div>');
            break;
          case 1:
            break;
          case 2:
            var user = res.data;
            
            $('#edit-id').val(user.id);
            $('#edit-username').val(user.username);
            $('#edit-firstname').val(user.firstname);
            $('#edit-lastname').val(user.lastname);
            $('#edit-email').val(user.email);
            $('#edit-phone').val(user.phone);
            $('#edit-rank').val(user.rank);

            break;
        }
      }
    });
  },

  _editStoreModal: function(storeid) {
    storeCategories = [];
    
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
              $('#approve-store-btn').removeClass('btn-outline-primary');
              $('#approve-store-btn').addClass('btn-warning');
              $('#approve-store-btn').html(`
                <i class="bi bi-check-circle"></i>
                Unapprove
              `);
            } else {
              $('#approve-store-btn').removeClass('btn-warning');
              $('#approve-store-btn').addClass('btn-outline-primary');
              $('#approve-store-btn').html(`
                <i class="bi bi-check-circle"></i>
                Approve
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
                        storeCategories.push(c.id);

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

            // input edit-categories-list
            var editCategoriesList = '';
            store.categories.forEach((c, index) => {
              if (index === store.categories.length-1) editCategoriesList += c.id;
              else editCategoriesList += c.id + ',';
            });

            $('#edit-categories-list').val(editCategoriesList);
            $('#description-chars').html(300-store.description.length);

            break;
        }
      }
    });
  },

  _editCategoryModal: function(cid) {
    $('#edit-category-result').html('');

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'fetch-category',
        'cid': cid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-category-result').html('<div class="alert alert-danger">Something went wrong.</div>');
            break;
          case 1:
            break;
          case 2:
            var category = res.data;
            
            $('#edit-id').val(category.id);
            $('#edit-name').val(category.name);
            $('#edit-image').val(category.image);

            break;
        }
      }
    });
  },

  LoadCategories: function() {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-categories'
      },
      success: function (res) {
        // console.log(res);

        switch (res.status) {
          case 0:
            // $('#categories-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            result = '';

            res.data.forEach(category => {
              result += `
              <tr>
                <td><img src="${category.image}" width="40px" height="40px" class="rounded" /></td>
                <td>${category.name}</td>
                <td>
                  <a href="#${category.id}" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal" onclick="javascript:admin._editCategoryModal(${category.id});">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                </td>
              </tr>
              `;
            });

            $('#categories-result').html(result);

            $('#myTable').DataTable();

            break;
        }
      }
    });

    $('#loading').hide();
  },

  LoadStores: function() {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-stores'
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            // $('#categories-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            result = '';

            for (var i = 0; i < res.data.length; i++) {
              var store = res.data[i]['data'];
              var storeAddress = `${store.address}, ${store.city}, ${store.zipcode}`;

              var approval = '';
              if (store.approved != 0) approval = '<div class="badge bg-primary">Approved</div>';
              else approval = '<span class="badge bg-warning">Pending</span>';

              result += `
              <tr>
                <td><img src="${store.image}" class="rounded" width="40px" height="40px" /></td>
                <td>
                
                  <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#infoModal" onclick="javascript:admin._infoModal(${store.id});">
                    ${store.title}
                  </button>
                </td>
                <td>
                  <a class="btn btn-danger btn-sm" href="https://maps.google.com/?q=${storeAddress}" target="_blank">
                    <i class="bi bi-geo"></i>
                  </a>
                  ${storeAddress}
                </td>
                <td>${approval}</td>
                <td>
                  <a href="#${store.id}" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editStoreModal" onclick="javascript:admin._editStoreModal(${store.id});">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="#${store.id}" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#documentsModal" onclick="javascript:admin._documentsModal(${store.id});">
                    <i class="bi bi-file-earmark-pdf"></i>
                  </a>
                </td>
              </tr>
              `;
            }

            $('#stores-result').html(result);

            $('#myTable').DataTable({
              'order': [[3, 'desc']]
            });

            break;
        }
      }
    });

    $('#loading').hide();
  },

  LoadUsers: function() {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-users'
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            // $('#users-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            result = '';

            res.data.forEach(user => {
              var rankToStr = '';
              switch (user.rank) {
                case '0':
                  rankToStr = '<span class="badge bg-dark">Χρήστης</span>';
                  break;
                case '1':
                  rankToStr = '<span class="badge bg-primary">Επαγγελματίας</span>'
                  break;
                case '2':
                  rankToStr = '<span class="badge bg-purple">Διαχειριστής</span>';
                  break;
              }

              result += `
              <tr>
                <td>${user.username}</td>
                <td>${user.firstname} ${user.lastname}</td>
                <td>${user.email}</td>
                <td>${rankToStr}</td>
                <td>
                  <a href="#${user.id}" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal" onclick="javascript:admin._editUserModal(${user.id});">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                </td>
              </tr>
              `;
            });

            $('#users-result').html(result);

            $('#myTable').DataTable();

            break;
        }
      }
    });

    $('#loading').hide();
  },
  
  DeleteUser: function() {
    if (confirm('Είσαι σίγουρος;') == true) {
      var uid = $('#edit-id').val();
      
      $.ajax({
          type: 'post',
        url: 'ajax.php',
        data: {
          'action': 'delete-user',
          'uid': uid
        },
        success: function (res) {
          switch (res.status) {
            case 0:
              $('#edit-user-result').html(`<div class="alert alert-danger">${res.data}</div>`);
              break;
            case 1:
              break;
            case 2:
              location.reload();
              break;
          }
        }
      });
    } else {
      return;
    }
  },

  DeleteCategory: function() {
    if (confirm('Είσαι σίγουρος;') == true) {
      var cid = $('#edit-id').val();
      
      $.ajax({
          type: 'post',
        url: 'ajax.php',
        data: {
          'action': 'delete-category',
          'cid': cid
        },
        success: function (res) {
          switch (res.status) {
            case 0:
              $('#edit-category-result').html(`<div class="alert alert-danger">${res.data}</div>`);
              break;
            case 1:
              break;
            case 2:
              location.reload();
              break;
          }
        }
      });
    } else {
      return;
    }
  },

  DeleteStore: function() {
    if (confirm('Είσαι σίγουρος;') == true) {
      var sid = $('#edit-id').val();
      
      $.ajax({
          type: 'post',
        url: 'ajax.php',
        data: {
          'action': 'delete-store',
          'sid': sid
        },
        success: function (res) {
          switch (res.status) {
            case 0:
              $('#edit-store-result').html(`<div class="alert alert-danger">${res.data}</div>`);
              break;
            case 1:
              break;
            case 2:
              location.reload();
              break;
          }
        }
      });
    } else {
      return;
    }
  },

  AddUser: function() {
    const resetBtn = () => {
      $('#add-user-btn').removeClass('disabled');
      $('#add-user-btn').html(`
        <i class="bi bi-person-plus"></i>
        Προσθήκη
      `);
    }

    $('#add-user-btn').addClass('disabled');
    $('#add-user-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var username = $('#username').val();
    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();
    var email = $('#email').val();
    var phone = $('#phone').val();
    var password = $('#password').val();
    var confirmpassword = $('#confirmpassword').val();
    var rank = $('#rank').find(':selected').val();

    if (!username
      || !firstname
      || !lastname
      || !email
      || !phone
      || !password
      || !confirmpassword) {
        $('#add-user-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
        resetBtn();
        return;
      }

    if (password !== confirmpassword) {
      $('#add-user-result').html('<div class="alert alert-danger">Password confirmation failed.</div>')
      resetBtn();
      return;
    }

    if (phone.length > 20 || username.length > 32) {
      $('#add-user-result').html('Max chars for username is 32, and for phone is 20.');
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'add-user',
        'username': username,
        'firstname': firstname,
        'lastname': lastname,
        'email': email,
        'phone': phone,
        'password': password,
        'confirmpassword': confirmpassword,
        'rank': rank
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#add-user-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#add-user-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  AddCategory: function() {
    const resetBtn = () => {
      $('#add-category-btn').removeClass('disabled');
      $('#add-category-btn').html(`
        <i class="bi bi-tags"></i>
        Προσθήκη
      `);
    }

    $('#add-category-btn').addClass('disabled');
    $('#add-category-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var name = $('#name').val();
    var image = $('#image').val();

    if (!name) {
      $('#add-category-result').html(`<div class="alert alert-danger">Category name cannot be empty.</div>`);
      resetBtn();
      return;
    }

    if (name.length > 50) {
      $('#add-category-result').html('Max chars for category name is 50.');
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'add-category',
        'name': name,
        'image': image,
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#add-category-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#add-category-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  EditUser: function() {
    const resetBtn = () => {
      $('#edit-user-btn').removeClass('disabled');
      $('#edit-user-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αποθήκευση
      `);
    }

    $('#edit-user-btn').addClass('disabled');
    $('#edit-user-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var uid = $('#edit-id').val();
    var username = $('#edit-username').val();
    var firstname = $('#edit-firstname').val();
    var lastname = $('#edit-lastname').val();
    var email = $('#edit-email').val();
    var phone = $('#edit-phone').val();
    var rank = $('#edit-rank').find(':selected').val();

    if (!username
      || !firstname
      || !lastname
      || !email
      || !phone) {
        $('#edit-user-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
        resetBtn();
        return;
      }

    if (phone.length > 20 || username.length > 32) {
      $('#edit-user-result').html('Max chars for username is 32, and for phone is 20.');
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'edit-user',
        'uid': uid,
        'username': username,
        'firstname': firstname,
        'lastname': lastname,
        'email': email,
        'phone': phone,
        'rank': rank
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-user-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#edit-user-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  EditCategory: function() {
    const resetBtn = () => {
      $('#edit-category-btn').removeClass('disabled');
      $('#edit-category-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αποθήκευση
      `);
    }

    $('#edit-category-btn').addClass('disabled');
    $('#edit-category-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var cid = $('#edit-id').val();
    var name = $('#edit-name').val();
    var image = $('#edit-image').val();

    if (!name
      || !image) {
        $('#edit-category-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
        resetBtn();
        return;
      }

    if (name.length > 50) {
      $('#edit-category-result').html('Max chars for name is 50.');
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'edit-category',
        'cid': cid,
        'name': name,
        'image': image
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-category-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#edit-category-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  EditStore: function() {
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

    // console.log(title, city, address, zipcode, image, description);

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
      }
    });

    resetBtn();
  },

  ApproveStore: function() {
    var sid = $('#edit-id').val();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'approve-store',
        'sid': sid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#edit-store-result').html('<div class="alert alert-danger">Something went wrong.</div>');
            break;
          case 1:
            break;
          case 2:
            switch (res.data) {
              case -1:
                // unapproved store
                $('#approve-store-btn').removeClass('btn-warning');
                $('#approve-store-btn').addClass('btn-outline-primary');
                $('#approve-store-btn').html(`
                  <i class="bi bi-check-circle"></i>
                  Approve
                `);
                break;
              case 1:
                // approved store
                $('#approve-store-btn').removeClass('btn-outline-primary');
                $('#approve-store-btn').addClass('btn-warning');
                $('#approve-store-btn').html(`
                  <i class="bi bi-check-circle"></i>
                  Unapprove
                `);
                break;
            }
            break;
        }
      }
    });
  },
};
