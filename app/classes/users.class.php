<?php
class Users extends CMS
{
  public static function GetAllUsers()
  {
    global $mysqli;

    $users = $mysqli->query(
      'SELECT
        `id`,
        `username`,
        `firstname`,
        `lastname`,
        `email`,
        `phone`,
        `creation_date`,
        `rank`,
        `favorites`
      FROM `users` ORDER BY `rank` DESC'
    );
    if ($users->num_rows == 0)
      return NULL;
    
    $usersArr = [];
    while ($user = $users->fetch_object()) {
      $usersArr[] = $user;
    }

    return $usersArr;
  }

  public static function Fetch($uid)
  {
    global $mysqli;

    $uid = intval($uid);

    $data = $mysqli->query(sprintf(
      'SELECT
        `id`,
        `username`,
        `firstname`,
        `lastname`,
        `email`,
        `phone`,
        `creation_date`,
        `rank`,
        `favorites`
      FROM `users`
      WHERE `id` = %d',
      $uid
    ));

    if (!$data || $data->num_rows == 0)
      return NULL;

    return $data->fetch_object();
  }

  // return array(data, message)
  public static function AddUser($user, $firstname, $lastname, $email, $phone, $pass, $confirmpass, $rank)
  {
    global $mysqli;

    $rank = intval($rank);
    // if rank intval is not 'user', 'professional' or 'admin', then automatically convert to 'user'
    if ($rank != 0 && $rank != 1 && $rank != 2) $rank = 0; 

    if (Misc::MultipleEmpty($user, $firstname, $lastname, $email, $phone, $pass, $confirmpass))
      return [NULL, 'All fields are required.'];

    if ($pass !== $confirmpass)
      return [NULL, 'Password confirmation failed.'];

    if (strlen($user) > 32)
      return [NULL, 'Maximum number of characters for username is 32.'];
    
    if (strlen($phone) > 20)
      return [NULL, 'Maximum characters for phone is 20.'];

    if (!Misc::IsValidEmail($email))
      return [NULL, 'Invalid email format.'];

    $user = $mysqli->real_escape_string($user);
    $email = $mysqli->real_escape_string($email);
    $phone = $mysqli->real_escape_string($phone);

    $account = $mysqli->query(sprintf('SELECT * FROM `users` WHERE `username` = "%s" OR `email` = "%s" OR `phone` = "%s"',
      $user,
      $email,
      $phone
    ));

    if ($account->num_rows != 0)
      return [NULL, 'This username, email or phone already exists. Choose something else.'];

    $account = $account->fetch_object();

    $firstname = ucfirst(strtolower($mysqli->real_escape_string($firstname)));
    $lastname = ucfirst(strtolower($mysqli->real_escape_string($lastname)));

    $salt = Misc::GenerateRandomString(32);
    $query = $mysqli->query(sprintf(
      'INSERT INTO `users` (`username`, `firstname`, `lastname`, `email`, `phone`, `password`, `salt`, `rank`) VALUES("%s", "%s", "%s", "%s", "%s", "%s", "%s", %d)',
      $user,
      $firstname,
      $lastname,
      $email,
      $phone,
      md5(md5($mysqli->real_escape_string($pass)) . $salt),
      $salt,
      $rank
    ));
    
    if ($query)
      return ['ok', 'User added successfully'];

    return [NULL, 'Something went wrong.'];
  }

  public static function UsernameExists($username)
  {
    global $mysqli;

    $username = $mysqli->real_escape_string($username);

    $exists = $mysqli->query(sprintf('SELECT `id` FROM `users` WHERE `username` = "%s"', $username));

    if ($exists->num_rows != 0)
      return true;
    return false;
  }

  public static function EmailExists($email)
  {
    global $mysqli;

    $email = $mysqli->real_escape_string($email);

    $exists = $mysqli->query(sprintf('SELECT `id` FROM `users` WHERE `email` = "%s"', $email));

    if ($exists->num_rows != 0)
      return true;
    return false;
  }

  public static function PhoneExists($phone)
  {
    global $mysqli;

    $phone = $mysqli->real_escape_string($phone);

    $exists = $mysqli->query(sprintf('SELECT `id` FROM `users` WHERE `phone` = "%s"', $phone));

    if ($exists->num_rows != 0)
      return true;
    return false;
  }

  public static function EditUser($uid, $user, $firstname, $lastname, $email, $phone, $rank)
  {
    global $mysqli;

    $uid = intval($uid);
    $rank = intval($rank);
    // if rank intval is not 'user', 'professional' or 'admin', then automatically convert to 'user'
    if ($rank != 0 && $rank != 1 && $rank != 2) $rank = 0; 

    if (Misc::MultipleEmpty($user, $firstname, $lastname, $email, $phone))
      return [NULL, 'All fields are required.'];

    if (strlen($user) > 32)
      return [NULL, 'Maximum number of characters for username is 32.'];
    
    if (strlen($phone) > 20)
      return [NULL, 'Maximum characters for phone is 20.'];

    if (!Misc::IsValidEmail($email))
      return [NULL, 'Invalid email format.'];

    // Get current user info
    $fetch = Users::Fetch($uid);
    if (empty($fetch))
      return [NULL, 'User not found.'];

    // If username, email or phone have been changed then perform a check so we don't update the user with a value that is already used
    if ($user != $fetch->username)  
      if (self::UsernameExists($user))
        return [NULL, 'This username already exists.'];

    if ($email != $fetch->email)
      if (self::EmailExists($email))
        return [NULL, 'This email already exists.'];

    if ($phone != $fetch->phone)
      if (self::PhoneExists($phone))
        return [NULL, 'This phone already exists.'];

    $user = $mysqli->real_escape_string($user);
    $email = $mysqli->real_escape_string($email);
    $phone = $mysqli->real_escape_string($phone);

    $firstname = ucfirst(strtolower($mysqli->real_escape_string($firstname)));
    $lastname = ucfirst(strtolower($mysqli->real_escape_string($lastname)));

    $query = $mysqli->query(sprintf(
      'UPDATE `users`
      SET
        `username` = "%s",
        `firstname` = "%s",
        `lastname` = "%s",
        `email` = "%s",
        `phone` = "%s",
        `rank` = %d
      WHERE `id` = %d',
      $user,
      $firstname,
      $lastname,
      $email,
      $phone,
      $rank,
      $uid
    ));
    
    if ($query)
      return ['ok', 'User edited successfully'];

    return [NULL, 'Something went wrong.'];
  }

  public static function DeleteUser($uid)
  {
    global $mysqli;

    $fetch = Users::Fetch($uid);
    if (empty($fetch))
      return [NULL, 'This user does not exist.'];
    
    $uid = intval($uid);

    $query = $mysqli->query(sprintf('DELETE FROM `users` WHERE `id` = %d', $uid));

    // Also delete all bookings and stores associated with this user
    $ownedStores = Stores::GetOwnedStoresByUserId($uid);

    foreach($ownedStores as $store) {
      // delete store. This function also deletes all bookings associated with this store
      Stores::DeleteStore($store->id);
    }

    $deleteUserBookings = $mysqli->query(sprintf('DELETE FROM `bookings` WHERE `uid` = %d', $uid));

    if ($query && $deleteUserBookings)
      return ['ok', 'Deleted user!'];

    return [NULL, 'Something went wrong.'];
  }

  public static function OwnsStore($storeid, $uid = -1)
  {
    global $mysqli;

    $uid = ($uid == -1) ? intval($_SESSION['uid']) : intval($uid);

    $storeid = intval($storeid);

    $owns = $mysqli->query(sprintf(
      'SELECT `id` FROM `stores` WHERE `id` = %d AND `added_by` = %d',
      $storeid,
      $uid
    ));

    if ($owns->num_rows != 0 || Account::IsAdmin())
      return true;
    return false;
  }
}
