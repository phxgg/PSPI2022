<?php
if (!defined('ACCESS')) exit;

class Misc extends CMS
{
  public static $weekDays = [
    'monday',
    'tuesday',
    'wednesday',
    'thursday',
    'friday',
    'saturday',
    'sunday'
  ];

  public static function GenerateRandomString($length = 8)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }

  /*
  public static function MultipleEmpty()
  {
    foreach (func_get_args() as $arg)
      if (empty($arg))
        continue;
      else
        return false;
    return true;
  }
  */

  /**
   * Function to check whether a variable is empty or not.
   * We can pass as many variables, that we would like to check, as we want.
   */
  public static function MultipleEmpty()
  {
    foreach (func_get_args() as $arg) {
      if (empty($arg)) {
        return true;
      }
    }
    return false;
  }

  public static function IsValidEmail($email)
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      return false;
    return true;
  }

  public static function Contact($email, $message)
  {
    if (self::MultipleEmpty($email, $message))
      return self::Error('Παρακαλώ συμπληρώστε όλα τα πεδία.');

    if (!self::IsValidEmail($email))
      return self::Error('Το email που δώσατε δεν είναι έγκυρο.');

    $message = wordwrap($message, 300); // use wordwrap() if lines are longer than 300 characters

    mail('thesskratisi@gmail.com', 'Contact Page', $message, "From: $email");
    return self::Success('Το μήνυμα σας στάλθηκε επιτυχώς! Θα επικοινωνήσουμε μαζί σας το συντομότερο.');
    
    // return self::Info('Under construction.');
  }

  public static function Error($msg, $style = null)
  {
    echo sprintf('<div class="alert alert-danger"%s>%s</div>', ($style != null) ? sprintf(' style="%s"', $style) : '', $msg);
  }

  public static function Success($msg, $style = null)
  {
    echo sprintf('<div class="alert alert-success"%s>%s</div>', ($style != null) ? sprintf(' style="%s"', $style) : '', $msg);
  }

  public static function Warning($msg, $style = null)
  {
    echo sprintf('<div class="alert alert-warning"%s>%s</div>', ($style != null) ? sprintf(' style="%s"', $style) : '', $msg);
  }

  public static function Info($msg, $style = null)
  {
    echo sprintf('<div class="alert alert-info"%s>%s</div>', ($style != null) ? sprintf(' style="%s"', $style) : '', $msg);
  }
}
