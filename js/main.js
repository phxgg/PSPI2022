var app = {
  /**
   * 
   * @param {*} storeData Store data
   * @param {*} displayingFavorites Whether we are currently displaying the my-favorites page.
   * @returns 
   */
  _displayStore: function(storeData, displayingFavorites = false) {
    var result = '';

    // var currentWeekday = new Date().toLocaleString('default', { weekday: 'long' }).toLowerCase();

    // var store = (!displayingFavorites) ? storeData['data'] : storeData;
    var store = storeData['data'];
    var isFavorite = (!displayingFavorites) ? storeData['isFavorite'] : true;
    var hasSetupOpHours = storeData['hasSetupOpHours'];
    // var opHours = storeData['opHours'];
    var storeImage = (!store.image) ? 'img/cafe.jpeg' : store.image;
    var availableTables = store.capacity - store.reserved;
    var storeDescription = (store.description.length > 150) ? store.description.substring(0, 150) + '...' : store.description;
    var storeAddress = `${store.city}, ${store.address}, ${store.zipcode}`;
    var storeAddressDisplay = (storeAddress.length > 25) ? storeAddress.substring(0, 25) + '...' : storeAddress;

    // var todayOpHours = (hasSetupOpHours) ? opHours[weekDays.indexOf(currentWeekday)] : null;

    // Alert user if the store has not setup its operational hours
    var opHoursAlert = '';
    if (!hasSetupOpHours) {
      opHoursAlert = `
      <span
        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning"
        data-bs-toggle="tooltip"
        data-bs-placement="right"
        title="Αυτό το κατάστημα δεν έχει καταχωρίσει ακόμη τις ώρες λειτουργίας του.">
        !
      </span>
      `;
    }

    var badgeColor = 'primary';
    if (availableTables == 0)
      badgeColor = 'danger';
    else if (availableTables > 0 && availableTables <= 3)
      badgeColor = 'warning';
    else if (availableTables > 3)
      badgeColor = 'success';

    var favoriteBtn = '';
    var randomId = `favorites_${makeid(8)}`;
    if (isFavorite) {
      favoriteBtn = `<button id="${randomId}" class="btn btn-sm btn-danger" onclick="javascript:app.favorite('${randomId}', ${store.id});" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove from favorites"><i class="bi bi-heart-half"></i></button>`;
    } else {
      favoriteBtn = `<button id="${randomId}" class="btn btn-sm btn-outline-danger" onclick="javascript:app.favorite('${randomId}', ${store.id});" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to favorites"><i class="bi bi-heart"></i></button>`;
    }

    result += `
      <div class="store col-sm-3">
        <div class="card mb-3" style="max-width: 18rem;">
          <a href="?page=view-store&sid=${store.id}">
            <img class="card-img-top" src="${storeImage}" alt="${store.title}">
            ${opHoursAlert}
          </a>
          <div class="card-body">
            <h5 class="card-title">${store.title} <a href="?page=view-store&sid=${store.id}" class="btn btn-light btn-sm"><i class="bi bi-link"></i></a></h5>
            <div class="float-end">${favoriteBtn}</div>
            <p class="card-text">
              <small>
                <i class="bi bi-geo text-danger"></i>
                <a href="https://maps.google.com/?q=${storeAddress}" class="text-muted" target="_blank">
                  ${storeAddressDisplay}
                </a>
              </small><hr />
              ${storeDescription}
            </p>
          </div>
          <div class="card-footer">
            <p>
              <i class="bi bi-calendar4-week"></i> <span class="badge bg-${badgeColor}">${availableTables}</span> tables available
            </p>
            <a
              href="?page=book&sid=${store.id}"
              class="btn btn-primary position-relative ${(availableTables == 0 || !hasSetupOpHours) ? 'disabled' : ''}">
              <i class="bi bi-bookmark-star"></i> Book a table
            </a>
          </div>
        </div>
      </div>
    `;

    return result;
  },

  _displayBooking: function(bookingData) {
    var result = '';
    
    var store = bookingData['store'];
    var booking = bookingData['booking'];

    var storeImage = (!store.image) ? 'img/cafe.jpeg' : store.image;
    var availableTables = store.capacity - store.reserved;
    var storeAddress = `${store.city}, ${store.address}, ${store.zipcode}`;
    var storeAddressDisplay = (storeAddress.length > 25) ? storeAddress.substring(0, 25) + '...' : storeAddress;

    var badgeColor = 'primary';
    if (availableTables == 0)
      badgeColor = 'danger';
    else if (availableTables > 0 && availableTables <= 3)
      badgeColor = 'warning';
    else if (availableTables > 3)
      badgeColor = 'success';

    result += `
      <div class="booking col-sm-3">
        <div class="card mb-3" style="max-width: 18rem;">
          <a href="?page=view-store&sid=${store.id}">
            <img class="card-img-top" src="${storeImage}" alt="${store.title}">
            <a
              href="#${booking.id}"
              onclick="javascript:app.DeleteBooking(${booking.id});"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-decoration-none text-white"
              data-bs-toggle="tooltip"
              data-bs-placement="right"
              title="Ακυρώστε αυτή τη κράτηση.">
              X
            </a>
          </a>
          <div class="card-body">
            <h5 class="card-title">${store.title} <a href="?page=view-store&sid=${store.id}" class="btn btn-light btn-sm"><i class="bi bi-link"></i></a></h5>
            <p class="card-text">
              <small>
                <i class="bi bi-geo text-danger"></i>
                <a href="https://maps.google.com/?q=${storeAddress}" class="text-muted" target="_blank">
                  ${storeAddressDisplay}
                </a>
              </small><hr />
              <b>Στοιχεία:</b><br />
              ${booking.name}<br />
              ${booking.persons} άτομα<br />
              ${booking.date} ${booking.time}<br />
              ${booking.phone}<br />
              ${booking.email}
              ${(booking.message != '') ? '<br /><b>Μήνυμα:</b><br />' + booking.message : '' } 
            </p>
          </div>
          <div class="card-footer">
            <p>
              <i class="bi bi-calendar4-week"></i> <span class="badge bg-${badgeColor}">${availableTables}</span> tables available
            </p>
          </div>
        </div>
      </div>
    `;

    return result;
  },

  _initTooltips: function() {
    $('#init').html(`
      <script type="text/javascript">
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        });
      </script>
    `);
  },

  _storeIncludesCategory(_storeCategories, _categoryId) {
    if (_storeCategories.some(category => category.id == _categoryId)) return true;
    return false;
  },

  LoadStore: function (storeid) {
    $('#loading').show();

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
            $('#store-title').html('-');
            $('#store-result').html('<div class="alert alert-danger">No access.</div>');
            break;
          case 1:
            $('#store-result').html('<div class="alert alert-info">Info.</div>');
            break;
          case 2:
            var store = res.data;
            var storeAddress = `${store.city}, ${store.address}, ${store.zipcode}`;

            $('#store-title').html(store.title)
            $('#page-title').html(`${store.title}`);

            $('#store-result').html(`
              <small class="text-muted">${storeAddress}</small>
              <hr />
              ${store.description}
            `);
            break;
        }

        $('#loading').hide();
      }
    });
  },

  LoadCategory: function (list, categoryid) {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-category',
        'list': list,
        'cid': categoryid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#category-title').html('-');
            $('#category-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            // todo
            break;
          case 2:
            var category = res.data[0];
            var stores = res.data[1];

            $('#category-title').html(category.name);
            $('#title').html(`<i class="bi bi-shop text-primary"></i> ${category.name}`);
            $('#page-title').html(`${category.name}`);

            var result = '<div class="row justify-content-center">';

            var x = 0;
            for (var i = 0; i < stores.length; i++) {
              x++;
              result += app._displayStore(stores[i]);
              
              if (x == 3) {
                result += '</div><div class="row justify-content-center">';
              }

              if (i == stores.length - 1) {
                result += '</div>';
              }
            }

            $('#category-result').html(result);

            app._initTooltips();

            break;
        }

        $('#loading').hide();
      }
    });
  },

  LoadCategories: function () {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-categories'
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#categories-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            var categories = res.data;

            result = '<div class="row justify-content-center">';

            for (var i = 0; i < categories.length; i++) {
              var category = categories[i];

              result += `
                <div class="category col-sm-3">
                  <div class="card mb-3" style="max-width: 18rem;">
                    <a href="?page=view-category&cid=${category.id}">
                      <img class="card-img-top" src="${category.image}" alt="${category.name}">
                    </a>
                    <div class="card-body">
                      <h5 class="card-title">${category.name} <a href="?page=view-category&cid=${category.id}" class="btn btn-light"><i class="bi bi-link"></i></a></h5>
                      <!-- <p class="card-text"></p> -->
                    </div>
                  </div>
                </div>
              `;
            }

            result += '</div>';

            $('#categories-result').html(result);

            break;
        }
      }
    });

    $('#loading').hide();
  },

  LoadFavorites: function() {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-favorites'
      },
      success: function (res) {
        var result = '';

        switch (res.status) {
          case 0:
            $('#favorites-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            result += '<div class="row">';

            for (var i = 0; i < res.data.length; i++) {
              result += app._displayStore(res.data[i], true);
            }

            result += '</div>';

            $('#favorites-result').html(result);

            app._initTooltips();
            
            break;
        }
      }
    });

    $('#loading').hide();
  },

  LoadMyBookings: function() {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'load-my-bookings'
      },
      success: function (res) {
        var result = '';

        switch (res.status) {
          case 0:
            $('#bookings-result').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
            break;
          case 1:
            break;
          case 2:
            result += '<div class="row">';

            for (var i = 0; i < res.data.length; i++) {
              result += app._displayBooking(res.data[i], true);
            }

            result += '</div>';

            $('#bookings-result').html(result);

            app._initTooltips();
            
            break;
        }
      }
    });

    $('#loading').hide();
  },

  LoadSearch: function (q) {
    $('#loading').show();

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'search',
        'q': q
      },
      success: function (res) {
        // switch (res.status) {
        //   case 0:
        //     break;
        //   case 1:
        //     break;
        //   case 2:
        //     break;
        // }

        var result = '';

        if (res.data && res.data.length > 0) {

          result += '<div class="row">';

          for (var i = 0; i < res.data.length; i++) {
            result += app._displayStore(res.data[i]);
          }

          result += '</div>';

          $('#searchpage-results').html(result);

          app._initTooltips();
        } else {
          $('#searchpage-results').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
        }
      }
    });

    $('#loading').hide();
  },

  doSearch: function (q) {
    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'search',
        'q': q
      },
      success: function (res) {
        var result = `<h6 class="dropdown-header">Αποτελέσματα</h6>`;

        if (res.data && res.data.length > 0) {
          for (var i = 0; i < res.data.length; i++) {
            var store = res.data[i]['data'];
            var storeAddress = `${store.city}, ${store.address}, ${store.zipcode}`;
            var storeDescription = (store.description.length > 50) ? store.description.substring(0, 50) + '...' : store.description;
            
            result += '<div class="card mb-3" style="max-width: 540px;"><div class="row g-0">';
            result += `
              <div class="col-md-4">
                <a href="?page=view-store&sid=${store.id}">
                  <img src="${store.image}" class="img-fluid rounded-start" style="max-height:200px;max-width:180px;" alt="${store.title}">
                </a>
              </div>`;
            result += `
              <div class="col-md-8">
                <div class="card-body">
                  <a href="?page=view-store&sid=${store.id}" class="text-dark" style="text-decoration: none;">
                    <h5 class="card-title">
                      ${store.title}
                    </h5>
                  </a>
                  <p class="card-text">${storeDescription}</p>
                  <p class="card-text">
                    <i class="bi bi-geo text-danger"></i>
                    <small class="text-muted">${storeAddress}</small>
                  </p>
                </div>
              </div>`;
            result += '</div></div>';
          }


          // console.log(suggestions);
          $('#search-results').html(result);
        } else {
          $('#search-results').html('<div class="alert alert-danger">Δεν υπάρχουν αποτελέσματα.</div>');
        }
      }
    });
  },

  favorite: function (randomid, sid) {
    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'favorite',
        'sid': sid
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            alert(res.data);
            break;
          case 1:
            // todo
            break;
          case 2:
            switch (res.data) {
              case -1:
                // just removed from favorites
                  $(`#${randomid}`).attr('title', 'Add into favorites');
                  $(`#${randomid}`).attr('data-bs-original-title', 'Add to favorites');
                  $(`#${randomid}`).attr('area-label', 'Add to favorites');

                  $(`#${randomid}`).removeClass('btn-danger');
                  $(`#${randomid}`).addClass('btn-outline-danger');
                  $(`#${randomid}`).html(`<i class="bi bi-heart"></i>`);
                break;
              case 1:
                // just added into favorites
                $(`#${randomid}`).attr('title', 'Remove from favorites');
                $(`#${randomid}`).attr('data-bs-original-title', 'Remove from favorites');
                $(`#${randomid}`).attr('area-label', 'Remove from favorites');

                $(`#${randomid}`).removeClass('btn-outline-danger');
                $(`#${randomid}`).addClass('btn-danger');
                $(`#${randomid}`).html(`<i class="bi bi-heart-half"></i>`);
                break;
            }
            break;
        }
      }
    });
  },

  ChangeEmail: function () {
    const resetBtn = () => {
      $('#change-email-btn').removeClass('disabled');
      $('#change-email-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αλλαγή
      `);
    }

    $('#change-email-btn').addClass('disabled');
    $('#change-email-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var currentpassword = $('#email-currpass').val();
    var newemail = $('#email-newemail').val();
    
    if (!currentpassword || !newemail) {
      $('#change-email-result').html('<div class="alert alert-danger">All fields are required.</div>');
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'change-email',
        'currentpassword': currentpassword,
        'newemail': newemail
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#change-email-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#change-email-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  ChangePhone: function () {
    const resetBtn = () => {
      $('#change-phone-btn').removeClass('disabled');
      $('#change-phone-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αλλαγή
      `);
    }

    $('#change-phone-btn').addClass('disabled');
    $('#change-phone-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var currentpassword = $('#phone-currpass').val();
    var newphone = $('#phone-newphone').val();
    
    if (!currentpassword || !newphone) {
      $('#change-phone-result').html('<div class="alert alert-danger">All fields are required.</div>');
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'change-phone',
        'currentpassword': currentpassword,
        'newphone': newphone
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#change-phone-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#change-phone-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  ChangePassword: function () {
    const resetBtn = () => {
      $('#change-password-btn').removeClass('disabled');
      $('#change-password-btn').html(`
        <i class="bi bi-pencil-square"></i>
        Αλλαγή
      `);
    }

    $('#change-password-btn').addClass('disabled');
    $('#change-password-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var currentpassword = $('#pass-currpass').val();
    var newpassword = $('#pass-newpass').val();
    var confirmpassword = $('#pass-confirmpass').val();
    
    if (!currentpassword || !newpassword || !confirmpassword) {
      $('#change-password-result').html('<div class="alert alert-danger">All fields are required.</div>');
      resetBtn();
      return;
    }

    if (newpassword !== confirmpassword) {
      $('#change-password-result').html('<div class="alert alert-danger">Password confirmation failed.</div>');
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'change-password',
        'currentpassword': currentpassword,
        'newpassword': newpassword,
        'confirmpassword': confirmpassword
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#change-password-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#change-password-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  Register: function() {
    const resetBtn = () => {
      $('#register-btn').removeClass('disabled');
      $('#register-btn').html(`
        <i class="bi bi-person-plus"></i>
        Εγγραφή
      `);
    }
    
    $('#register-btn').addClass('disabled');
    $('#register-btn').html(`
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
    var professional = ($('#professional').is(':checked')) ? 1 : 0;

    if (!username
      || !firstname
      || !lastname
      || !email
      || !phone
      || !password
      || !confirmpassword) {
        $('#register-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
        resetBtn();
        return;
      }

    if (password !== confirmpassword) {
      $('#register-result').html(`<div class="alert alert-danger">Password confirmation failed.</div>`);
      resetBtn();
      return;
    }

    if (username.length > 32 || phone.length > 20) {
      $('#register-result').html(`<div class="alert alert-danger">Username max chars is 32, phone max chars is 20.</div>`);
      resetBtn();
      return;
    }

    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'register',
        'username': username,
        'firstname': firstname,
        'lastname': lastname,
        'email': email,
        'phone': phone,
        'password': password,
        'confirmpassword': confirmpassword,
        'professional': professional
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#register-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#register-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
  },

  BookStore: function() {
    const resetBtn = () => {
      $('#book-btn').removeClass('disabled');
      $('#book-btn').html(`
        <i class="bi bi-bookmark-star"></i>
        Κάνε κράτηση
      `);
    }
    
    $('#book-btn').addClass('disabled');
    $('#book-btn').html(`
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Working...
    `);

    var sid = $('#sid').val();
    var name = $('#name').val();
    var email = $('#email').val();
    var phone = $('#phone').val();
    var people = $('#people').val();
    var date = $('#date').val();
    var time = $('#time').val();
    var message = $('#message').val();

    if (sid === '') {
      $('#book-result').html(`<div class="alert alert-danger">Invalid sid.</div>`);
      resetBtn();
      return;
    }

    if (!name
      || !email
      || !phone
      || !people
      || !date
      || !time) {
        $('#book-result').html(`<div class="alert alert-danger">All fields are required.</div>`);
        resetBtn();
        return;
      }
    
    $.ajax({
      type: 'post',
      url: 'ajax.php',
      data: {
        'action': 'book-store',
        'sid': sid,
        'name': name,
        'email': email,
        'phone': phone,
        'people': people,
        'date': date,
        'time': time,
        'message': message
      },
      success: function (res) {
        switch (res.status) {
          case 0:
            $('#book-result').html(`<div class="alert alert-danger">${res.data}</div>`);
            break;
          case 1:
            break;
          case 2:
            $('#book-result').html(`<div class="alert alert-success">${res.data}</div>`);
            break;
        }
      }
    });

    resetBtn();
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
};

// Generate random string
function makeid(length) {
  var result = '';
  var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  var charactersLength = characters.length;
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}

// Remove element from array
function removeFromArray(arr, el) {
  arr.splice(arr.indexOf(el), 1);
  return arr;
}

// Week days to string
const weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

// Search bar
$(document).ready(function () {
  let timeoutID = null;

  $('#search-bar').keyup(function (e) {
    const q = e.target.value;

    if (e.keyCode == 13) {
      window.location = '?page=search&q=' + q;
    } else {
      clearTimeout(timeoutID);
      timeoutID = setTimeout(() => app.doSearch(q), 500);
    }
  });

  $('#search-bar').focus(function () {
    $('#search-results').show('blind');
  });

  $('#search-bar').focusout(function () {
    $('#search-results').hide('blind');
  });
});
