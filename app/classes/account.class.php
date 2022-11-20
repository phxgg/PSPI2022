<?php
class Account extends CMS
{
  public static function IsLoggedIn()
  {
    return (isset($_SESSION['loggedIn']));
  }

  /**
   * Do not allow to access this page if user is logged in.
   */
  public static function NoLogin()
  {
    if (self::IsLoggedIn()) {
      header('Location: /?page=index');
      die();
    }
  }

  public static function LoginRequired()
  {
    if (!self::IsLoggedIn()) {
      header('Location: /?page=login');
      die();
    }
  }

  public static function IsAdmin()
  {
    if (self::getAccount()->rank == 2)
      return true;
    return false;
  }

  public static function IsProfessional()
  {
    if (self::getAccount()->rank >= 1) // >=1 because admins can access too
      return true;
    return false;
  }

  public static function AdminRequired()
  {
    if (!self::IsLoggedIn() || !self::IsAdmin()) {
      header('Location: /?page=index');
      die();
    }
  }

  public static function ProfessionalRequired()
  {
    if (!self::IsLoggedIn() || !self::IsProfessional()) {
      header('Location: /?page=index');
      die();
    }
  }

  /**
   * Get the current logged in user.
   * If provided with an $id, then it will return the user with that id.
   */
  public static function getAccount($id = -1)
  {
    global $mysqli;

    $id = ($id == -1) ? intval($_SESSION['uid']) : intval($id);

    if (Account::IsLoggedIn()) {
      return $mysqli->query(sprintf('SELECT * FROM `users` WHERE `id` = %d', $id))->fetch_object();
    }
    return NULL;
  }

  public static function Login($user, $pass)
  {
    global $mysqli;

    if (Misc::MultipleEmpty($user, $pass))
      return Misc::Error('User and/or pass empty.');
    
    $account = $mysqli->query(sprintf('SELECT * FROM `users` WHERE `username` = "%s"', $mysqli->real_escape_string($user)))->fetch_object();
    if (empty($account))
      return Misc::Error('User not found.');
    
    if (md5(md5($pass) . $account->salt) != $account->password)
      return Misc::Error('Wrong password.');
    
    $_SESSION['loggedIn'] = true;
    $_SESSION['uid'] = $account->id;
    header('Location: ?page=index');
  }

  // return array(data, message)
  public static function Register($user, $firstname, $lastname, $email, $phone, $pass, $confirmpass, $professional)
  {
    global $mysqli;

    $professional = intval($professional);
    if ($professional != 0 && $professional != 1) $professional = 0;

    if (Misc::MultipleEmpty($user, $firstname, $lastname, $email, $phone, $pass, $confirmpass))
      return [NULL, 'All fields are required.'];
    
    if ($pass !== $confirmpass)
      return [NULL, 'Password confirmation failed.'];

    if (strlen($user) > 32)
      return [NULL, 'Maximum characters for username is 32.'];

    if (strlen($phone) > 20)
      return [NULL, 'Maximum characters for phone is 20.'];

    if (!Misc::IsValidEmail($email))
      return [NULL, 'Invalid email format.'];

    $user = $mysqli->real_escape_string($user);
    $email = $mysqli->real_escape_string($email);
    $phone = $mysqli->real_escape_string($phone);

    $exists = $mysqli->query(sprintf(
      'SELECT `id` FROM `users` WHERE `username` = "%s" OR `email` = "%s" OR `phone` = "%s"',
      $user,
      $email,
      $phone
    ))->fetch_object();

    if (!empty($exists))
      return [NULL, 'A user with this username, email or phone already exists.'];
    
    $salt = Misc::GenerateRandomString(32);
    $password = md5(md5($mysqli->real_escape_string($pass)) . $salt);

    $firstname = ucfirst(strtolower($mysqli->real_escape_string($firstname)));
    $lastname = ucfirst(strtolower($mysqli->real_escape_string($lastname)));

    $query = $mysqli->query(sprintf(
      'INSERT INTO `users` (`username`, `firstname`, `lastname`, `email`, `phone`, `password`, `salt`, `rank`) VALUES("%s", "%s", "%s", "%s", "%s", "%s", "%s", %d)',
      $user,
      $firstname,
      $lastname,
      $email,
      $phone,
      $password,
      $salt,
      $professional
    ));

    if ($query)
      return ['ok', 'User registered successfully.'];
    
    return [NULL, 'Something went wrong.'];
  }

  // return array(data, message)
  public static function ChangePassword($oldpass, $newpass, $confirm)
  {
    global $mysqli;

    if (Misc::MultipleEmpty($oldpass, $newpass, $confirm))
      return [NULL, 'All fields are required.'];
    
    if ($newpass !== $confirm)
      return [NULL, 'Password confirmation failed.'];
    
    if (md5(md5($oldpass) . self::getAccount()->salt) != self::getAccount()->password)
      return [NULL, 'Old password is not correct.'];
    
    $salt = Misc::GenerateRandomString(32);
    $query = $mysqli->query(sprintf('UPDATE `users` SET `password` = "%s", `salt` = "%s" WHERE `id` = %d',
      $mysqli->real_escape_string(md5(md5($newpass) . $salt)),
      $salt,
      self::getAccount()->id
    ));

    if ($query)
      return ['ok', 'Password changed!'];

    return [NULL, 'Something went wrong.'];
  }

  // return array(data, message)
  public static function ChangeEmail($currpass, $newemail)
  {
    global $mysqli;

    if (Misc::MultipleEmpty($currpass, $newemail))
      return [NULL, 'All fields are required.'];
    
    if (!Misc::IsValidEmail($newemail))
      return [NULL, 'Invalid email.'];
    
    if (md5(md5($currpass) . self::getAccount()->salt) != self::getAccount()->password)
      return [NULL, 'Your password is not correct'];

    $newemail = $mysqli->real_escape_string($newemail);

    // Check for existing accounts with that email
    $check = $mysqli->query(sprintf(
      'SELECT `id` FROM `users` WHERE `email` = "%s"',
      $newemail
    ));

    if ($check->num_rows != 0)
      return [NULL, 'This email is used by another user. Choose something else.'];

    $query = $mysqli->query(sprintf(
      'UPDATE `users` SET `email` = "%s" WHERE `id` = %d',
      $newemail,
      self::getAccount()->id
    ));

    if ($query)
      return ['ok', 'Email changed!'];

    return [NULL, 'Something went wrong.'];
  }

  // return array(data, message)
  public static function ChangePhone($currpass, $newphone)
  {
    global $mysqli;

    if (Misc::MultipleEmpty($currpass, $newphone))
      return [NULL, 'All fields are required.'];
    
    if (!is_numeric($newphone) || strlen($newphone) > 20)
      return [NULL, 'Invalid phone.'];
    
    if (md5(md5($currpass) . self::getAccount()->salt) != self::getAccount()->password)
      return [NULL, 'Your password is not correct'];

    $newphone = $mysqli->real_escape_string($newphone);

    // Check for existing accounts with that email
    $check = $mysqli->query(sprintf(
      'SELECT `id` FROM `users` WHERE `phone` = "%s"',
      $newphone
    ));

    if ($check->num_rows != 0)
      return [NULL, 'This phone is used by another user. Choose something else.'];

    $query = $mysqli->query(sprintf(
      'UPDATE `users` SET `phone` = "%s" WHERE `id` = %d',
      $newphone,
      self::getAccount()->id
    ));

    if ($query)
      return ['ok', 'Phone changed!'];

    return [NULL, 'Something went wrong.'];
  }
}
