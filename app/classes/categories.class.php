<?php
class Categories extends CMS
{
  public static function GetAllCategories()
  {
    global $mysqli;

    $categories = $mysqli->query('SELECT * FROM `categories` ORDER BY `id`');
    if ($categories->num_rows == 0)
      return NULL;
    
    $catArr = [];
    while ($category = $categories->fetch_object()) {
      $catArr[] = $category;
    }

    return $catArr;
  }

  /**
   * The $list is used for pagination.
   * Depending on that variable, the function will limit the number of results.
   */
  public static function GetStoresFromCategoryId($list, $categoryid)
  {
    global $mysqli;

    $list = intval($list);
    if ($list < 0) $list = 0;

    if($list == null) {
			$list = 0;
			$index = 0;
		}

    $index = ceil($list * STORES_DISPLAY);
    
    // Get category info
    $categoryid = intval($categoryid);
    $category = $mysqli->query(sprintf('SELECT * FROM `categories` WHERE `id` = %d', $categoryid));
    if ($category->num_rows == 0)
      return NULL;
    $category = $category->fetch_object();
    
    // Get store info
    $stores = $mysqli->query(sprintf(
      'SELECT * FROM `stores` WHERE FIND_IN_SET(%d, `categories`) > 0 AND `approved` = 1 ORDER BY `id` ASC LIMIT %d,%d',
      $categoryid,
      $index,
      STORES_DISPLAY
    ));

    if ($stores->num_rows == 0)
      return NULL;

    $storesArr = [];
    while ($store = $stores->fetch_object()) {
      // Get store operational hours info
      $hasSetupOpHours = false;
      $opHours = Stores::GetOperationalHours($store->id);
      if ($opHours[0] !== NULL) $hasSetupOpHours = true;
      else $opHours[1] = NULL;
      
      // Data to send
      $storesArr[] = [
        'data' => $store,
        'isFavorite' => (Stores::IsFavorite($store->id)),
        'hasSetupOpHours' => $hasSetupOpHours,
        'opHours' => $opHours[1]
      ];
    }

    $data = [$category, $storesArr];
    
    return $data;
  }

  public static function AddCategory($name, $image)
  {
    global $mysqli;

    if (!(!filter_var($image, FILTER_VALIDATE_URL) === false))
      return [NULL, 'Το URL εικόνας δεν είναι έγκυρο.'];

    if (strlen($name) > 50)
      return [NULL, 'Το όνομα κατηγορίας δεν μπορεί να είναι μεγαλύτερο από 50 χαρακτήρες.'];

    $name = $mysqli->real_escape_string($name);
    $image = $mysqli->real_escape_string($image);

    // Check if category name already exists
    $exists = $mysqli->query(sprintf(
      'SELECT `id` FROM `categories` WHERE `name` = "%s"',
      $name
    ));

    if ($exists->num_rows != 0)
      return [NULL, 'Υπάρχει ήδη κατηγορία με αυτό το όνομα.'];

    // Else move on
    $query = $mysqli->query(sprintf(
      'INSERT INTO `categories` (`name`, `image`) VALUES("%s", "%s")',
      $name,
      $image
    ));

    if ($query)
      return ['ok', 'Η κατηγορία δημιουργήθηκε με επιτυχία.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function Exists($categoryid)
  {
    global $mysqli;

    $categoryid = intval($categoryid);
    $exists = $mysqli->query(sprintf('SELECT `id` FROM `categories` WHERE `id` = %d', $categoryid));

    if ($exists->num_rows != 0)
      return true;
    return false;
  }

  public static function Fetch($categoryid)
  {
    global $mysqli;

    $categoryid = intval($categoryid);

    $fetch = $mysqli->query(sprintf('SELECT * FROM `categories` WHERE `id` = %d', $categoryid));
    if ($fetch->num_rows == 0)
      return NULL;
      
    return $fetch->fetch_object();
  }

  public static function NameExists($name)
  {
    global $mysqli;

    $name = $mysqli->real_escape_string($name);

    $fetch = $mysqli->query(sprintf('SELECT `id` FROM `categories` WHERE `name` = "%s"', $name));
    if ($fetch->num_rows != 0)
      return true;
    return false;
  }

  public static function EditCategory($categoryid, $name, $image)
  {
    global $mysqli;

    if (!self::Exists($categoryid))
      return [NULL, 'Η κατηγορία δεν υπάρχει.'];

    $categoryid = intval($categoryid);

    $fetch = self::Fetch($categoryid);

    if ($name != $fetch->name)
      if (self::NameExists($name))
        return [NULL, 'Υπάρχει ήδη κατηγορία με αυτό το όνομα.'];

    $name = $mysqli->real_escape_string($name);
    $image = $mysqli->real_escape_string($image);

    $query = $mysqli->query(sprintf(
      'UPDATE `categories` SET
        `name` = "%s",
        `image` = "%s"
      WHERE `id` = %d',
      $name,
      $image,
      $categoryid
    ));

    if ($query)
      return ['ok', 'Η κατηγορία ενημερώθηκε με επιτυχία.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function DeleteCategory($categoryid)
  {
    global $mysqli;

    if (!self::Exists($categoryid))
      return [NULL, 'Η κατηγορία δεν υπάρχει.'];
    
    $categoryid = intval($categoryid);

    // Remove category id from all stores
    $stores = Stores::GetAllStores();

    foreach ($stores as $store) {
      $store = $store['data'];
      $catArr = explode(',', $store->categories);

      if (in_array($categoryid, $catArr)) {
        // remove cat id from array
        $key = array_search($categoryid, $catArr);
        unset($catArr[$key]);
      }

      $catArr = implode(',', $catArr);

      $updateCategories = $mysqli->query(sprintf(
        'UPDATE `stores` SET
          `categories` = "%s"
        WHERE `id` = %d',
        $catArr,
        $store->id
      ));

      if (!$updateCategories)
        return [NULL, 'Κάτι πήγε στραβά.'];
    }

    $deleteCategory = $mysqli->query(sprintf('DELETE FROM `categories` WHERE `id` = %d', $categoryid));

    if ($deleteCategory)
      return ['ok', 'Η κατηγορία διαγράφηκε με επιτυχία.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }
}
