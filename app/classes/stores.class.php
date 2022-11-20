<?php
class Stores extends CMS
{
  public static function Exists($storeid)
  {
    global $mysqli;

    $storeid = intval($storeid);
    $exists = $mysqli->query(sprintf('SELECT `id` FROM `stores` WHERE `id` = %d', $storeid));

    if ($exists->num_rows != 0)
      return true;
    return false;
  }

  /**
   * This function checks whether the title, city, address and zipcode of the
   * provided store already exist for another store in the database.
   * 
   * This is used to prevent duplicate stores.
   */
  public static function PhysicalStoreExists($title, $city, $address, $zipcode)
  {
    global $mysqli;

    $title    = $mysqli->real_escape_string($title);
    $city     = $mysqli->real_escape_string($city);
    $address  = $mysqli->real_escape_string($address);
    $zipcode  = $mysqli->real_escape_string($zipcode);

    $fetch = $mysqli->query(sprintf(
      'SELECT `id` FROM `stores` WHERE
        `title` = "%s"
        AND `city` = "%s"
        AND `address` = "%s"
        AND `zipcode` = "%s"',
      $title,
      $city,
      $address,
      $zipcode
    ));
    if ($fetch->num_rows != 0)
      return true;
    return false;
  }

  public static function Fetch($storeid)
  {
    global $mysqli;
    
    $storeid = intval($storeid);

    $fetch = $mysqli->query(sprintf('SELECT * FROM `stores` WHERE `id` = %d', $storeid));
    if ($fetch->num_rows == 0)
      return NULL;

    // Return categories as an array, and not as a string.
    $fetch = $fetch->fetch_object();
    $catArr = explode(',', $fetch->categories);
    $finalCategories = [];
    foreach($catArr as $catId) {
      $finalCategories[] = Categories::Fetch($catId);
    }
    $fetch->categories = $finalCategories;

    return $fetch;
  }

  /**
   * Check if the provided store is the logged in user's favorite list.
   */
  public static function IsFavorite($storeid)
  {
    global $mysqli;

    $storeid = intval($storeid);

    $favorite = $mysqli->query(sprintf('SELECT `id` FROM `users` WHERE `id` = %d AND FIND_IN_SET(%d, `favorites`) > 0',
      Account::getAccount()->id,
      $storeid
    ));

    if ($favorite->num_rows != 0)
      return true;
    return false;
  }

  public static function IsApproved($storeid)
  {
    global $mysqli;

    $storeid = intval($storeid);

    $approved = $mysqli->query(sprintf('SELECT `id` FROM `stores` WHERE `id` = %d AND `approved` = 1',
      $storeid
    ));

    if ($approved->num_rows != 0)
      return true;
    return false;
  }

  public static function GetUnapprovedStores()
  {
    global $mysqli;
    
    $stores = $mysqli->query('SELECT * FROM `stores` WHERE `approved` = 0 ORDER BY `id` ASC');

    if ($stores->num_rows == 0)
      return NULL;

    $storesArr = [];
    while ($store = $stores->fetch_object()) {
      $storesArr[] = ['data' => $store];
    }
    
    return $storesArr;
  }

  public static function DeleteStore($storeid)
  {
    global $mysqli;

    if (!self::Exists($storeid))
      return [NULL, 'Αυτό το κατάστημα δεν υπάρχει.'];
    
    $storeid = intval($storeid);

    $query = $mysqli->query(sprintf('DELETE FROM `stores` WHERE `id` = %d', $storeid));

    // Also delete all bookings related to that store
    $deleteBookings = $mysqli->query(sprintf('DELETE FROM `bookings` WHERE `storeid` = %d', $storeid));

    if ($query && $deleteBookings)
      return ['ok', 'Το κατάστημα διαγράφηκε.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function AddStore($title, $description, $city, $address, $zipcode, $image, $categories, $maxpersonpertable, $capacity)
  {
    global $mysqli;

    if (strlen($title) > 50)
      return [NULL, 'Ο μέγιστος αριθμός χαρακτήρων για τον τίτλο είναι 50.'];

    if (strlen($description) > 2000)
      return [NULL, 'Ο μέγιστος αριθμός χαρακτήρων για την περιγραφή είναι 2000.'];

    if (strlen($zipcode) > 5)
      return [NULL, 'Ο μέγιστος αριθμός χαρακτήρων για τον ΤΚ είναι 5.'];

    if (self::PhysicalStoreExists($title, $city, $address, $zipcode))
      return [NULL, 'Αυτό το κατάστημα υπάρχει ήδη.'];

    $title              = $mysqli->real_escape_string($title);
    $description        = $mysqli->real_escape_string($description);
    $city               = $mysqli->real_escape_string($city);
    $address            = $mysqli->real_escape_string($address);
    $zipcode            = $mysqli->real_escape_string($zipcode);
    $image              = $mysqli->real_escape_string($image);
    $categories         = $mysqli->real_escape_string($categories);
    $maxpersonpertable  = intval($maxpersonpertable);
    $capacity           = intval($capacity);

    $catArr = explode(',', $categories);
    if (empty($catArr[0])) // using $catArr[0] because the explode function will auto create one index even though $categories might be empty
      return [NULL, 'Πρέπει να επιλέξετε τουλάχιστον μία κατηγορία.'];

    $finalCategories = [];
    foreach ($catArr as $cat) {
      if (Categories::Exists($cat))
        $finalCategories[] = $cat;
    }

    // stringify $finalCategories
    $finalCategories = implode(',', $finalCategories);

    $query = $mysqli->query(sprintf(
      'INSERT INTO `stores`
        (`added_by`, `title`, `description`, `city`, `address`, `zipcode`, `categories`, `image`, `capacity`, `maxpersonpertable`)
        VALUES(%d, "%s", "%s", "%s", "%s", "%s", "%s", "%s", %d, %d)',
      Account::getAccount()->id,
      $title,
      $description,
      $city,
      $address,
      $zipcode,
      $finalCategories,
      $image,
      $capacity,
      $maxpersonpertable
    ));

    if ($query)
      return ['ok', 'Το κατάστημα προστέθηκε.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function EditStoreDescription($storeid, $description)
  {
    global $mysqli, $parser;

    if (!self::Exists($storeid))
      return [NULL, 'Αυτό το κατάστημα δεν υπάρχει.'];

    if (!Users::OwnsStore($storeid))
      return [NULL, 'Αυτό το κατάστημα δεν σου ανοίκει.'];

    if (strlen($description) > 2000)
      return [NULL, 'Ο μέγιστος αριθμός χαρακτήρων για την περιγραφή είναι 2000.'];

    $storeid = intval($storeid);

    $description = $mysqli->real_escape_string($description);
    $parser->parse(htmlentities($description));

    $edit = $mysqli->query(sprintf(
      'UPDATE `stores` SET `description` = "%s" WHERE `id` = %d',
      $parser->getAsBBCode(),
      $storeid
    ));

    if (!$edit)
      return [NULL, 'Κάτι πήγε στραβά.'];
    
    return ['ok', 'Επιτυχής επεξεργασία περιγραφής.'];
  }

  public static function EditStore($storeid, $title, $description, $city, $address, $zipcode, $image, $categories, $maxpersonpertable, $capacity)
  {
    global $mysqli, $parser;

    if (!self::Exists($storeid))
      return [NULL, 'Αυτό το κατάστημα δεν υπάρχει.'];

    $fetch = self::Fetch($storeid);

    if (!Users::OwnsStore($storeid))
      return [NULL, 'Αυτό το κατάστημα δεν σου ανοίκει.'];

    if (strlen($title) > 50)
      return [NULL, 'Ο μέγιστος αριθμός χαρακτήρων για τον τίτλο είναι 50.'];

    if (strlen($description) > 2000)
      return [NULL, 'Ο μέγιστος αριθμός χαρακτήρων για την περιγραφή είναι 2000.'];

    if (strlen($zipcode) > 5)
      return [NULL, 'Ο μέγιστος αριθμός χαρακτήρων για τον ΤΚ είναι 5.'];

    /* Check whether title, city and address are the same with another store. */
    if ($title != $fetch->title
      && $city != $fetch->city
      && $address != $fetch->address
      && $zipcode != $fetch->zipcode)
      if (self::PhysicalStoreExists($title, $city, $address, $zipcode))
        return [NULL, 'Αυτό το κατάστημα υπάρχει ήδη.'];

    $title              = $mysqli->real_escape_string($title);
    $description        = $mysqli->real_escape_string($description);
    $city               = $mysqli->real_escape_string($city);
    $address            = $mysqli->real_escape_string($address);
    $zipcode            = $mysqli->real_escape_string($zipcode);
    $image              = $mysqli->real_escape_string($image);
    $categories         = $mysqli->real_escape_string($categories);
    $maxpersonpertable  = intval($maxpersonpertable);
    $capacity           = intval($capacity);

    $description = $mysqli->real_escape_string($description);
    $parser->parse(htmlentities($description));

    /**
     * explode categories
     * check for each category if it exists
     */

    $catArr = explode(',', $categories);
    if (empty($catArr[0])) // using $catArr[0] because the explode function will auto create one index even though $categories might be empty
      return [NULL, 'Πρέπει να επιλέξετε τουλάχιστον μία κατηγορία.'];

    $finalCategories = [];
    foreach ($catArr as $cat) {
      if (Categories::Exists($cat))
        $finalCategories[] = $cat;
    }

    // stringify $finalCategories
    $finalCategories = implode(',', $finalCategories);

    $storeid = intval($storeid);

    $query = $mysqli->query(sprintf(
      'UPDATE `stores` SET
        `title` = "%s",
        `description` = "%s",
        `city` = "%s",
        `address` = "%s",
        `zipcode` = "%s",
        `categories` = "%s",
        `image` = "%s",
        `capacity` = %d,
        `maxpersonpertable` = %d
      WHERE `id` = %d',
      $title,
      $parser->getAsBBCode(),
      $city,
      $address,
      $zipcode,
      $finalCategories,
      $image,
      $capacity,
      $maxpersonpertable,
      $storeid
    ));

    if ($query)
      return ['ok', 'Επιτυχής επεξεργασία καταστήματος.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function ApproveStore($storeid)
  {
    global $mysqli;

    if (!self::Exists($storeid))
      return 0; // error

    $approved = self::IsApproved($storeid);

    $storeid = intval($storeid);

    if ($approved) {
      $query = $mysqli->query(sprintf('UPDATE `stores` SET `approved` = 0 WHERE `id` = %d', $storeid));
      if ($query) return -1; // unapproved
      else return 0; // error
    }
    else {
      $query = $mysqli->query(sprintf('UPDATE `stores` SET `approved` = 1 WHERE `id` = %d', $storeid));
      if ($query) return 1; // approved
      else return 0; // error
    }
  }

  public static function GetAllStores()
  {
    global $mysqli;
    
    $stores = $mysqli->query('SELECT * FROM `stores` ORDER BY `id` DESC');

    if ($stores->num_rows == 0)
      return NULL;

    $storesArr = [];
    while ($store = $stores->fetch_object()) {
      $storesArr[] = ['data' => $store];
    }
    
    return $storesArr;
  }

  /**
   * Return the logged in user's owned stores
   */
  public static function GetOwnedStores()
  {
    global $mysqli;

    $stores = $mysqli->query(sprintf('SELECT * FROM `stores` WHERE `added_by` = %d ORDER BY `id` DESC', Account::getAccount()->id));

    if ($stores->num_rows == 0)
      return NULL;

    $storesArr = [];
    while ($store = $stores->fetch_object()) {
      $storesArr[] = $store;
    }

    return $storesArr;
  }

  public static function GetOwnedStoresByUserId($userid) {
    global $mysqli;

    $userid = intval($userid);

    $stores = $mysqli->query(sprintf('SELECT * FROM `stores` WHERE `added_by` = %d ORDER BY `id` DESC', $userid));

    if ($stores->num_rows == 0)
      return NULL;

    $storesArr = [];
    while ($store = $stores->fetch_object()) {
      $storesArr[] = $store;
    }

    return $storesArr;
  }

  /**
   * This function will return the operational hours of a store
   */
  public static function GetOperationalHours($storeid)
  {
    global $mysqli;

    if (!Users::OwnsStore($storeid))
      return [NULL, 'Αυτό το κατάστημα δεν σου ανοίκει.'];

    $storeid = intval($storeid);

    $opHours = $mysqli->query(sprintf(
      'SELECT
        `storeid`,
        `week_day`,
        `opens_at`,
        `closes_at`
      FROM `store_operational_hours` WHERE `storeid` = %d',
      $storeid
    ));

    if ($opHours->num_rows == 0)
      return [NULL, 'Δεν έχουν οριστεί ώρα λειτουργίας.']; // NULL or 'info'?

    $opHoursArr = [];
    while ($opHour = $opHours->fetch_object()) {
      $opHoursArr[] = $opHour;
    }

    return ['ok', $opHoursArr];
  }

  public static function SaveOperationalHours($storeid, $POST)
  {
    global $mysqli;

    if (!Users::OwnsStore($storeid))
      return [NULL, 'Αυτό το κατάστημα δεν σου ανοίκει.'];

    $storeid = intval($storeid);

    $opHoursArr = [
      'monday' => [
        'week_day' => 0,
        'check' => isset($POST['mondayCheck']) ? $POST['mondayCheck'] : 0,
        'opens_at' => isset($POST['mondayOpensAt']) ? $POST['mondayOpensAt'] : '',
        'closes_at' => isset($POST['mondayClosesAt']) ? $POST['mondayClosesAt'] : ''
      ],
      'tuesday' => [
        'week_day' => 1,
        'check' => isset($POST['tuesdayCheck']) ? $POST['tuesdayCheck'] : 0,
        'opens_at' => isset($POST['tuesdayOpensAt']) ? $POST['tuesdayOpensAt'] : '',
        'closes_at' => isset($POST['tuesdayClosesAt']) ? $POST['tuesdayClosesAt'] : ''
      ],
      'wednesday' => [
        'week_day' => 2,
        'check' => isset($POST['wednesdayCheck']) ? $POST['wednesdayCheck'] : 0,
        'opens_at' => isset($POST['wednesdayOpensAt']) ? $POST['wednesdayOpensAt'] : '',
        'closes_at' => isset($POST['wednesdayClosesAt']) ? $POST['wednesdayClosesAt'] : ''
      ],
      'thursday' => [
        'week_day' => 3,
        'check' => isset($POST['thursdayCheck']) ? $POST['thursdayCheck'] : 0,
        'opens_at' => isset($POST['thursdayOpensAt']) ? $POST['thursdayOpensAt'] : '',
        'closes_at' => isset($POST['thursdayClosesAt']) ? $POST['thursdayClosesAt'] : ''
      ],
      'friday' => [
        'week_day' => 4,
        'check' => isset($POST['fridayCheck']) ? $POST['fridayCheck'] : 0,
        'opens_at' => isset($POST['fridayOpensAt']) ? $POST['fridayOpensAt'] : '',
        'closes_at' => isset($POST['fridayClosesAt']) ? $POST['fridayClosesAt'] : ''
      ],
      'saturday' => [
        'week_day' => 5,
        'check' => isset($POST['saturdayCheck']) ? $POST['saturdayCheck'] : 0,
        'opens_at' => isset($POST['saturdayOpensAt']) ? $POST['saturdayOpensAt'] : '',
        'closes_at' => isset($POST['saturdayClosesAt']) ? $POST['saturdayClosesAt'] : ''
      ],
      'sunday' => [
        'week_day' => 6,
        'check' => isset($POST['sundayCheck']) ? $POST['sundayCheck'] : 0,
        'opens_at' => isset($POST['sundayOpensAt']) ? $POST['sundayOpensAt'] : '',
        'closes_at' => isset($POST['sundayClosesAt']) ? $POST['sundayClosesAt'] : ''
      ]
    ];

    foreach ($opHoursArr as $key=>$value) {
      if ($value['check'] != 1 || $value['opens_at'] == '' || $value['closes_at'] == '') {
        unset($opHoursArr[$key]);
      }
    }

    // Delete existing operational hours
    $delete = $mysqli->query(sprintf(
      'DELETE FROM `store_operational_hours` WHERE `storeid` = %d',
      $storeid
    ));

    if (!$delete)
      return [NULL, 'Κάτι πήγε στραβά. (1)'];

    // Insert new operational hours
    foreach ($opHoursArr as &$value) {
      $insert = $mysqli->query(sprintf(
        'INSERT INTO `store_operational_hours`
          (`storeid`, `week_day`, `opens_at`, `closes_at`)
        VALUES
          (%d, %d, "%s", "%s")',
        $storeid,
        $value['week_day'],
        $value['opens_at'],
        $value['closes_at']
      ));

      if (!$insert)
        return [NULL, 'Κάτι πήγε στραβά. (2)'];
    }

    return ['ok', 'Οι ώρες λειτουργίας καταχωρήθηκαν με επιτυχία.'];
  }

  public static function ViewDocuments($storeid)
  {
    global $mysqli;

    if (!Users::OwnsStore($storeid))
      return [NULL, 'Αυτό το κατάστημα δεν σου ανοίκει.'];

    $store = self::Fetch($storeid);
    if ($store == NULL)
      return [NULL, 'Αυτό το κατάστημα δεν υπάρχει.'];

    $storeid = intval($storeid);

    $documents = $mysqli->query(sprintf(
      'SELECT * FROM `documents` WHERE `storeid` = %d',
      $storeid
    ));

    $storeData = ['id' => $store->id, 'title' => $store->title];

    if ($documents->num_rows == 0)
      return [
        'info',
        [
          'store' => $storeData,
          'msg' => 'Δεν έχετε αποστείλει κανένα έγγραφο.'
        ]
      ];
    
    $documents = $documents->fetch_object();
    $docs = [
      'id' => $documents->id,
      // 'identification' => base64_encode($documents->identification),
      // 'license' => base64_encode($documents->license)
    ];

    return [
      'ok',
      [
        'store' => $storeData,
        'documents' => $docs
      ]
    ];
  }

  public static function GetDocuments($id)
  {
    global $mysqli;

    $id = intval($id);

    $fetch = $mysqli->query(sprintf('SELECT * FROM documents WHERE `id` = %d', $id));
    if ($fetch->num_rows == 0)
      return [NULL, 'Δεν υπάρχουν έγγραφα.'];

    $fetch = $fetch->fetch_object();

    if (!Users::OwnsStore($fetch->storeid))
      return [NULL, 'Δεν έχεις πρόσβαση.'];

    return ['ok', $fetch];
  }

  public static function DeleteDocuments($storeid)
  {
    global $mysqli;

    $storeid = intval($storeid);

    $documents = $mysqli->query(sprintf(
      'SELECT `id` FROM `documents` WHERE `storeid` = %d',
      $storeid
    ));

    if ($documents->num_rows == 0)
      return [NULL, 'Δεν υπάρχουν έγγραφα.'];

    if (!Users::OwnsStore($storeid))
      return [NULL, 'Δεν έχεις πρόσβαση.'];

    $delete = $mysqli->query(sprintf('DELETE FROM `documents` WHERE `storeid` = %d', $storeid));
    if ($delete)
      return ['ok', 'Τα έγγραφα διαγράφηκαν επιτυχώς.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function UploadDocuments($storeid, $identification, $license)
  {
    global $mysqli;

    $storeid = intval($storeid);

    // Check if store has already uploaded documents
    $documents = $mysqli->query(sprintf(
      'SELECT `id` FROM `documents` WHERE `storeid` = %d',
      $storeid
    ));

    if ($documents->num_rows != 0)
      return [NULL, 'Έχετε ήδη αποστείλει έγγραφα.'];
    
    if (!Users::OwnsStore($storeid))
      return [NULL, 'No access.'];

    // Check for file extensions
    $identificationExt = end(explode('.', $identification['name']));
    $licenseExt = end(explode('.', $license['name']));

    if ($identificationExt !== 'pdf' || $licenseExt !== 'pdf')
      return [NULL, 'Το αρχείο δεν είναι έγκυρο.'];

    // Check for file size
    $max_file_size = 1048576;
    if ($identification['size'] > $max_file_size
      || $license['size'] > $max_file_size) // 1mb = 1048576 bytes
      return [NULL, 'Το αρχείο είναι πολύ μεγάλο.'];

    // Insert file into database
    $identification = $mysqli->real_escape_string(file_get_contents($identification['tmp_name']));
    $license = $mysqli->real_escape_string(file_get_contents($license['tmp_name']));

    $insert = $mysqli->query(sprintf(
      'INSERT INTO `documents`
        (`storeid`, `identification`, `license`)
      VALUES
        (%d, "%s", "%s")',
      $storeid,
      $identification,
      $license
    ));

    if ($insert)
      return ['ok', 'Τα έγγραφα αποστάλθηκαν επιτυχώς.'];
      
    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function GetNumberOfUnapprovedStores()
  {
    global $mysqli;
    
    $stores = $mysqli->query('SELECT * FROM `stores` WHERE `approved` = 0 ORDER BY `id` ASC');

    return $stores->num_rows;
  }

  public static function Search($query, $categoryid = null)
  {
    global $mysqli;

    if ($query == null) return NULL;
    $query = $mysqli->real_escape_string($query);

    if ($categoryid !== null) $categoryid = intval($categoryid);

    $search = $mysqli->query(sprintf(
      'SELECT * FROM `stores` WHERE
        `approved` = 1
      AND
        (`title` LIKE "%%%s%%" OR
        `description` LIKE "%%%s%%" OR
        `city` LIKE "%%%s%%" OR
        `address` LIKE "%%%s%%" OR
        `zipcode` LIKE "%%%s%%")
      LIMIT 5',
      $query, $query, $query, $query, $query
      ));

    if ($search->num_rows == 0)
      return NULL;

    $searchArr = [];
    while ($row = $search->fetch_object()) {
      $searchArr[] = ['data' => $row, 'isFavorite' => (self::IsFavorite($row->id))];
    }

    return $searchArr;
  }

  public static function GetFavorites()
  {
    global $mysqli;

    $favorites = $mysqli->query(sprintf('SELECT `favorites` FROM `users` WHERE `id` = %d', Account::getAccount()->id));
    if ($favorites->num_rows == 0)
      return [NULL, 'Δεν βρέθηκαν αγαπημένα καταστήματα.'];

    $favorites = $favorites->fetch_object();
    $favArr = explode(',', $favorites->favorites);

    $storesArr = [];
    foreach ($favArr as $key => $value) {
      $store = $mysqli->query(sprintf('SELECT * FROM `stores` WHERE `id` = %d AND `approved` = 1', $value))->fetch_object();

      if (!empty($store)) {
        // Get store operational hours info
        $hasSetupOpHours = false;
        $opHours = Stores::GetOperationalHours($store->id);
        if ($opHours[0] !== NULL) $hasSetupOpHours = true;
        else $opHours[1] = NULL;
        
        $storesArr[] = [
          'data' => $store,
          'hasSetupOpHours' => $hasSetupOpHours,
          'opHours' => $opHours[1]
        ];
      }
    }

    return ['ok', $storesArr];
  }

  public static function Favorite($storeid)
  {
    global $mysqli;

    $storeid = intval($storeid);

    $currentFavorites = Account::getAccount()->favorites;

    if (self::IsFavorite($storeid)) {
      // remove from favorites

      $favArr = [];

      if (!empty($currentFavorites))
        $favArr = explode(',', $currentFavorites);

      if (($key = array_search(strval($storeid), $favArr)) !== false) {
        unset($favArr[$key]);
      }

      $newFav = implode(',', $favArr);

      $remove = $mysqli->query(sprintf('UPDATE `users` SET `favorites` = "%s" WHERE `id` = %d', $newFav, Account::getAccount()->id));
      
      if ($remove) return -1;
      else return 0;
    } else {
      // add into favorites

      if (empty($currentFavorites))
        $add = $mysqli->query(sprintf('UPDATE `users` SET `favorites` = CONCAT(\'%s\') WHERE `id` = %d', strval($storeid), Account::getAccount()->id));
      else
        $add = $mysqli->query(sprintf('UPDATE `users` SET `favorites` = CONCAT(favorites,\',%s\') WHERE `id` = %d', strval($storeid), Account::getAccount()->id));

      if ($add) return 1;
      else return 0;
    }
  }

  public static function isOpenOnDate($storeid, $dayName, $time)
  {
    global $mysqli;

    $storeid = intval($storeid);
    $dayName = $mysqli->real_escape_string($dayName);
    $time = $mysqli->real_escape_string($time);

    $dayIndex = array_search($dayName, Misc::$weekDays);

    $opHours = self::GetOperationalHours($storeid);

    if ($opHours[0] == NULL)
      return false;

    $isOpen = false;
    $time = strtotime($time);

    foreach ($opHours[1] as $row) {
      if ($row->week_day == $dayIndex) {
        $v_time = date('H:i', $time);
        $v_start = date('H:i', strtotime($row->opens_at));
        $v_end = date('H:i', strtotime($row->closes_at));

        if ($v_time >= $v_start && $v_time <= $v_end) {
          $isOpen = true;
          break;
        }
      }
    }

    return $isOpen;
  }

  public static function BookStore($storeid, $name, $email, $phone, $people, $date, $time, $message) {
    global $mysqli;

    if (Misc::MultipleEmpty($name, $email, $phone, $date, $time))
      return [NULL, 'All fields are required.'];

    if (strlen($phone) > 20)
      return [NULL, 'Maximum characters for phone is 20.'];

    if (!Misc::IsValidEmail($email))
      return [NULL, 'Invalid email format.'];

    if (!self::Exists($storeid))
      return [NULL, 'Αυτό το κατάστημα δεν υπάρχει.'];

    if (Bookings::BookingExistsByNameAndDate($storeid, $name, $date))
      return [NULL, 'Υπάρχει ήδη κράτηση σε αυτό το όνομα, την συγκεκριμένη ημέρα.'];

    $hasSetupOpHours = (Stores::GetOperationalHours($storeid)[0] !== NULL) ? true : false;
    if (!$hasSetupOpHours)
      return [NULL, 'Αυτό το κατάστημα δεν έχει καταχωρίσει ακόμη τις ώρες λειτουργίας του.'];
    
    $storeid = intval($storeid);

    $name = $mysqli->real_escape_string($name);
    $email = $mysqli->real_escape_string($email);
    $phone = $mysqli->real_escape_string($phone);
    $people = intval($people);
    $date = $mysqli->real_escape_string($date);
    $time = date('H:i', strtotime($mysqli->real_escape_string($time)));
    $message = $mysqli->real_escape_string($message);

    if ($people <= 0)
      return [NULL, 'Ο αριθμός ατόμων πρέπει να είναι μεγαλύτερος από 0.'];

    // Fetch store
    $store = self::Fetch($storeid);

    if ($store->reserved == $store->capacity)
      return [NULL, 'Το κατάστημα αυτή τη στιγμή είναι γεμάτο από κρατήσεις.'];

    if ($store->maxpersonpertable < $people)
      return [NULL, 'Ο μέγιστος αριθμός ατόμων για κάθε τραπέζι είναι ' . $store->maxpersonpertable . '.'];

    // Check if store is open at the current time
    $dayName = strtolower(date('l', strtotime($date)));
    $isOpen = self::isOpenOnDate($storeid, $dayName, $time);

    if (!$isOpen)
      return [NULL, 'Το κατάστημα δεν είναι ανοιχτό στις συγκεκριμένες ώρες.'];

    $query = $mysqli->query(sprintf(
      'INSERT INTO `bookings`
        (`uid`, `storeid`, `name`, `email`, `phone`, `date`, `time`, `persons`, `message`)
      VALUES
        (%d, %d, "%s", "%s", "%s", "%s", "%s", %d, "%s")',
      Account::getAccount()->id,
      $storeid,
      $name,
      $email,
      $phone,
      $date,
      $time,
      $people,
      $message
    ));

    $updateReserved = $mysqli->query(sprintf('UPDATE `stores` SET `reserved` = `reserved` + 1 WHERE `id` = %d', $storeid));

    // $query = $mysqli->query(sprintf('DELETE FROM `stores` WHERE `id` = %d', $storeid));

    if ($query && $updateReserved)
      return ['ok', 'Η κράτηση πραγματοποιήθηκε με επιτυχία.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }
}
