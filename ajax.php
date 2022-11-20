<?php

header('Content-Type: application/json; charset=utf-8');

require_once 'app/init.php';

function reply($status, $data) {
  die(json_encode(['status' => $status, 'data' => $data]));
}

// Die if the request method is not 'POST'
if (@$_SERVER['REQUEST_METHOD'] !== 'POST') die();

// if (!Account::IsLoggedIn())
//   reply(StatusCodes::Error, 'Not logged in.');

// List of allowed actions
$allowed_actions = [
  'search',
  'load-store',
  'load-user',
  'load-category',
  'load-categories',
  'load-users',
  'load-favorites',
  'load-my-bookings',
  'load-stores',
  'load-unapproved-stores',
  'load-manage-stores-tab',
  'load-manage-bookings-tab',
  'load-store-operational-hours',
  'save-store-operational-hours',
  'load-booking-contact-details',
  'favorite',
  'approve-store',
  'change-email',
  'change-password',
  'change-phone',
  'register',
  'add-user',
  'add-category',
  'edit-user',
  'edit-category',
  'edit-store',
  'edit-store-description',
  'add-store',
  'view-store-documents',
  'upload-store-documents',
  'delete-store-documents',
  'delete-booking',
  'delete-user',
  'delete-category',
  'delete-store',
  'fetch-category',
  'book-store'
];

// Get current action
$action = $_POST['action'];

if (!isset($action))
  reply(StatusCodes::Error, 'No action.');

if (!in_array($action, $allowed_actions))
  reply(StatusCodes::Error, 'Invalid action.');

switch ($action) {
  case 'search':
    if (!isset($_POST['q']))
      reply(StatusCodes::Error, NULL);

    $data = Stores::Search($_POST['q']);

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);
    
    reply(StatusCodes::OK, $data);
    break;
  case 'load-store':
    if (!isset($_POST['sid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::Fetch($_POST['sid']);

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-user':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin');

    if (!isset($_POST['uid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Users::Fetch($_POST['uid']);

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-category':
    if (!isset($_POST['cid']))
      reply(StatusCodes::Error, 'Invalid category id.');
      
    $list = (isset($_POST['list'])) ? intval($_POST['list']) : 0;
    $data = Categories::GetStoresFromCategoryId($list, $_POST['cid']);

    if ($data == NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-categories':
    $data = Categories::GetAllCategories();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-users':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    $data = Users::GetAllUsers();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-favorites':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'Not logged in.');

    $data = Stores::GetFavorites();

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'load-my-bookings':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'Not logged in.');

    $data = Bookings::GetMyBookings();

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'load-stores':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    $data = Stores::GetAllStores();

    if ($data == NULL)
      reply(StatusCodes::Error, NULL);
    
    reply(StatusCodes::OK, $data);
    break;
  case 'load-unapproved-stores':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    $data = Stores::GetUnapprovedStores();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);
    
    reply(StatusCodes::OK, $data);
    break;
  case 'load-manage-stores-tab':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'No access.');

    $data = Stores::GetOwnedStores();

    if ($data === NULL)
      reply(StatusCodes::Error, NULL);

    reply(StatusCodes::OK, $data);
    break;
  case 'load-manage-bookings-tab':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'No access.');

    $data = Bookings::GetNewBookingsForAllOwnedStores(Account::getAccount()->id);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'load-store-operational-hours':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'No access.');

    if (!isset($_POST['sid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::GetOperationalHours($_POST['sid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'save-store-operational-hours':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'No access.');

    if (!isset($_POST['sid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::SaveOperationalHours($_POST['sid'], $_POST);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'load-booking-contact-details':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'No access.');

    if (!isset($_POST['bid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Bookings::GetContactDetails($_POST['bid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'favorite':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'Not logged in.');
    
    if (!isset($_POST['sid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::Favorite($_POST['sid']);

    if ($data == 0)
      reply(StatusCodes::Error, 'Something went wrong.');
    
    reply(StatusCodes::OK, $data);
    break;
  case 'approve-store':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    if (!isset($_POST['sid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::ApproveStore($_POST['sid']);

    if ($data == 0)
      reply(StatusCodes::Error, 'Something went wrong.');

    reply(StatusCodes::OK, $data);
    break;
  case 'change-email':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'Not logged in.');
    
    if (!isset($_POST['currentpassword']) || !isset($_POST['newemail']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Account::ChangeEmail($_POST['currentpassword'], $_POST['newemail']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'change-phone':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'Not logged in.');
    
    if (!isset($_POST['currentpassword']) || !isset($_POST['newphone']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Account::ChangePhone($_POST['currentpassword'], $_POST['newphone']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'change-password':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'Not logged in.');

    if (!isset($_POST['currentpassword']) || !isset($_POST['newpassword']) || !isset($_POST['confirmpassword']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Account::ChangePassword($_POST['currentpassword'], $_POST['newpassword'], $_POST['confirmpassword']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'register':
    if (Account::IsLoggedIn())
      reply(StatusCodes::Error, 'You are already logged in.');

    $professional = 0;
    if (isset($_POST['professional'])) $professional = $_POST['professional'];

    if (
      !isset($_POST['username'])
      || !isset($_POST['firstname'])
      || !isset($_POST['lastname'])
      || !isset($_POST['email'])
      || !isset($_POST['phone'])
      || !isset($_POST['password'])
      || !isset($_POST['confirmpassword'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');
    
    $data = Account::Register(
      $_POST['username'],
      $_POST['firstname'],
      $_POST['lastname'],
      $_POST['email'],
      $_POST['phone'],
      $_POST['password'],
      $_POST['confirmpassword'],
      $_POST['professional']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'add-user':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    $rank = 0;
    if (isset($_POST['rank'])) $rank = $_POST['rank'];

    if (
      !isset($_POST['username'])
      || !isset($_POST['firstname'])
      || !isset($_POST['lastname'])
      || !isset($_POST['email'])
      || !isset($_POST['phone'])
      || !isset($_POST['password'])
      || !isset($_POST['confirmpassword'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');
    
    $data = Users::AddUser(
      $_POST['username'],
      $_POST['firstname'],
      $_POST['lastname'],
      $_POST['email'],
      $_POST['phone'],
      $_POST['password'],
      $_POST['confirmpassword'],
      $_POST['rank']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'add-category':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    if (!isset($_POST['name']) || !isset($_POST['image']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Categories::AddCategory($_POST['name'], $_POST['image']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-user':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    $rank = 0;
    if (isset($_POST['rank'])) $rank = $_POST['rank'];

    if (!isset($_POST['uid'])
      || !isset($_POST['username'])
      || !isset($_POST['firstname'])
      || !isset($_POST['lastname'])
      || !isset($_POST['email'])
      || !isset($_POST['phone'])
    )
      reply(StatusCodes::Error, 'Invalid parameters.');
    
    $data = Users::EditUser(
      $_POST['uid'],
      $_POST['username'],
      $_POST['firstname'],
      $_POST['lastname'],
      $_POST['email'],
      $_POST['phone'],
      $_POST['rank']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-category':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    if (!isset($_POST['cid'])
      || !isset($_POST['name'])
      || !isset($_POST['image']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Categories::EditCategory($_POST['cid'], $_POST['name'], $_POST['image']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-store':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['sid'])
      || !isset($_POST['title'])
      || !isset($_POST['description'])
      || !isset($_POST['city'])
      || !isset($_POST['address'])
      || !isset($_POST['zipcode'])
      || !isset($_POST['image'])
      || !isset($_POST['categories'])
      || !isset($_POST['maxpersonpertable'])
      || !isset($_POST['capacity']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::EditStore(
      $_POST['sid'],
      $_POST['title'],
      $_POST['description'],
      $_POST['city'],
      $_POST['address'],
      $_POST['zipcode'],
      $_POST['image'],
      $_POST['categories'],
      $_POST['maxpersonpertable'],
      $_POST['capacity']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'edit-store-description':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['sid'])
      || !isset($_POST['description']))
      reply(StatusCodes::Error, 'Invalid parameters.');
    
    $data = Stores::EditStoreDescription($_POST['sid'], $_POST['description']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'add-store':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'You cannot access this.');

      if (!isset($_POST['title'])
        || !isset($_POST['description'])
        || !isset($_POST['city'])
        || !isset($_POST['address'])
        || !isset($_POST['zipcode'])
        || !isset($_POST['image'])
        || !isset($_POST['categories'])
        || !isset($_POST['maxpersonpertable'])
        || !isset($_POST['capacity']))
        reply(StatusCodes::Error, 'Invalid parameters.');

      $data = Stores::AddStore(
        $_POST['title'],
        $_POST['description'],
        $_POST['city'],
        $_POST['address'],
        $_POST['zipcode'],
        $_POST['image'],
        $_POST['categories'],
        $_POST['maxpersonpertable'],
        $_POST['capacity']
      );

      if ($data[0] === NULL)
        reply(StatusCodes::Error, $data[1]);

      reply(StatusCodes::OK, $data[1]);
    break;
  case 'view-store-documents':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['sid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::ViewDocuments($_POST['sid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);
    elseif ($data[0] == 'info')
      reply(StatusCodes::Info, $data[1]);
      
    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-store-documents':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['sid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::DeleteDocuments($_POST['sid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-booking':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['bid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Bookings::DeleteBooking($_POST['bid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'upload-store-documents':
    if (!Account::IsLoggedIn() || !Account::IsProfessional())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['sid'])
      || !isset($_FILES['identification'])
      || !isset($_FILES['license']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::UploadDocuments($_POST['sid'], $_FILES['identification'], $_FILES['license']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-user':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    if (!isset($_POST['uid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Users::DeleteUser($_POST['uid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-category':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    if (!isset($_POST['cid']))
      reply(StatusCodes::Error, 'Invalid parameters');

    $data = Categories::DeleteCategory($_POST['cid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'delete-store':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    if (!isset($_POST['sid']))
      reply(StatusCodes::Error, 'Invalid parameters.');
    
    $data = Stores::DeleteStore($_POST['sid']);

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
  case 'fetch-category':
    if (!Account::IsLoggedIn() || !Account::IsAdmin())
      reply(StatusCodes::Error, 'You are not an admin.');

    if (!isset($_POST['cid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Categories::Fetch($_POST['cid']);

    if ($data === NULL)
      reply(StatusCodes::Error, 'Something went wrong.');

    reply(StatusCodes::OK, $data);
    break;
  case 'book-store':
    if (!Account::IsLoggedIn())
      reply(StatusCodes::Error, 'You cannot access this.');

    if (!isset($_POST['sid']))
      reply(StatusCodes::Error, 'Invalid parameters.');

    $data = Stores::BookStore(
      $_POST['sid'],
      $_POST['name'],
      $_POST['email'],
      $_POST['phone'],
      $_POST['people'],
      $_POST['date'],
      $_POST['time'],
      $_POST['message']
    );

    if ($data[0] === NULL)
      reply(StatusCodes::Error, $data[1]);

    reply(StatusCodes::OK, $data[1]);
    break;
}
