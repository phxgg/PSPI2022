<?php
class CMS
{
  /**
   * Initialize CMS.
   */
  public static function Initialize($GET)
  {
    $tpl = (isset($GET['page']) ? $GET['page'] : '');

    if (strlen($tpl) == 1 || strlen($tpl) == 0) {
      self::LoadTemplate('index');
    } else {
      self::LoadTemplate($tpl);
    }
  }

  /**
   * Initalize CMS only for admins.
   */
  public static function AdminInitialize($GET)
  {
    $tpl = (isset($GET['page']) ? $GET['page'] : '');

    if (strlen($tpl) == 1 || strlen($tpl) == 0) {
      self::LoadAdminTemplate('dashboard');
    } else {
      self::LoadAdminTemplate($tpl);
    }
  }

  public static function LoadTemplate($tpl)
  {
    if (!file_exists(sprintf('app/templates/main/%s.tpl.php', $tpl)))
      header('Location: /?page=index');

    // Templates
    $data = Pages::$main;

    if (isset($data[$tpl]['title'])) {
      $title = $data[$tpl]['title'];
      $subtitle = $data[$tpl]['subtitle'];
      $iconClass = $data[$tpl]['iconClass'];
    }

    ob_start();
    include 'app/templates/main/header.tpl.php';
    include sprintf('app/templates/main/%s.tpl.php', $tpl);
    include 'app/templates/main/footer.tpl.php';

    $temporary = ob_get_contents();
    ob_end_clean();

    $temporary = CMS::sanitize_output($temporary);
    //$temporary .= "\r\n <!-- NO CACHE --> ";

    echo $temporary;

    unset($temporary);
  }

  public static function LoadAdminTemplate($tpl)
  {
    if (!file_exists(sprintf('app/templates/admin/%s.tpl.php', $tpl)))
      header('Location: admin.php?page=dashboard');

    // Admin Templates
    $data = Pages::$admin;

    if (isset($data[$tpl]['title'])) {
      $title = $data[$tpl]['title'];
      $subtitle = $data[$tpl]['subtitle'];
      $iconClass = $data[$tpl]['iconClass'];
    }

    ob_start();
    include 'app/templates/admin/header.tpl.php';
    include sprintf('app/templates/admin/%s.tpl.php', $tpl);
    include 'app/templates/admin/footer.tpl.php';

    $temporary = ob_get_contents();
    ob_end_clean();

    $temporary = CMS::sanitize_output($temporary);
    //$temporary .= "\r\n <!-- NO CACHE --> ";

    echo $temporary;

    unset($temporary);
  }

  /**
   * minimise html output
   */
  public static function sanitize_output($buffer)
  {
    // Commented for debugging purposes
    
    /*
    $search = [
      '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
      '/[^\S ]+\</s',  // strip whitespaces before tags, except space
      '/(\s)+/s'       // shorten multiple whitespace sequences
    ];

    $replace = [
      '>',
      '<',
      '\\1'
    ];

    $buffer = preg_replace($search, $replace, $buffer);

    $buffer = str_replace("\t", "", $buffer);
    */

    return $buffer;
  }
}
