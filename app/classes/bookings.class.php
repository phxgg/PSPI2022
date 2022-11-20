<?php
class Bookings extends CMS
{
  /**
   * Checks for bookings that have finished and marks them as finished.
   */
  public static function Update()
  {
    global $mysqli;

    $bookings = self::GetAllBookings();
    if ($bookings == NULL)
      return;

    foreach ($bookings as $booking) {
      // if booking date & time is in the past, update `done` column to 1
      if (strtotime($booking->date . ' ' . $booking->time) < strtotime('+10 minutes')) { // time()
        $mysqli->query(sprintf('UPDATE `bookings` SET `done` = 1 WHERE `id` = %d', $booking->id));
      }

      // also free store reserved tables
      $mysqli->query(sprintf('UPDATE `stores` SET `reserved` = `reserved` - 1 WHERE `id` = %d', $booking->storeid));
    }
  }

  public static function GetAllBookings()
  {
    global $mysqli;

    $bookings = $mysqli->query(sprintf('SELECT * FROM `bookings` WHERE `done` = 0 ORDER BY `id` DESC'));
    
    if ($bookings->num_rows == 0)
      return NULL;
    
    $allBookings = [];
    while($booking = $bookings->fetch_object()) {
      $allBookings[] = $booking;
    }

    return $allBookings;
  }

  public static function GetStoreBookings($storeid)
  {
    global $mysqli;

    $storeid = intval($storeid);

    $bookings = $mysqli->query(sprintf('SELECT * FROM `bookings` WHERE `storeid` = %d WHERE `done` = 0 ORDER BY `id` DESC', $storeid));

    if ($bookings->num_rows == 0)
      return NULL;

    $storeBookings = [];
    while($booking = $bookings->fetch_object()) {
      $storeBookings[] = $booking;
    }
    
    return $storeBookings;
  }

  public static function GetNumOfNewBookingsForAllOwnedStores($profid)
  {
    global $mysqli;

    $profid = intval($profid);

    $numOfBookings = 0;

    $ownedStores = $mysqli->query(sprintf(
      'SELECT `id` FROM `stores` WHERE `added_by` = %d',
      $profid
    ));

    if ($ownedStores->num_rows == 0)
      return $numOfBookings;

    while($store = $ownedStores->fetch_object()) {
      $storeBookings = $mysqli->query(sprintf(
        'SELECT `id` FROM `bookings` WHERE `storeid` = %d AND `done` = 0',
        $store->id
      ));

      $numOfBookings += $storeBookings->num_rows;
    }

    return $numOfBookings;
  }

  public static function GetNewBookingsForAllOwnedStores($profid)
  {
    global $mysqli;

    $ownedStores = $mysqli->query(sprintf(
      'SELECT `id`, `title`, `image` FROM `stores` WHERE `added_by` = %d',
      $profid
    ));

    if ($ownedStores->num_rows == 0)
      return [NULL, 'You do not own any stores.'];

    $bookings = [];

    while ($store = $ownedStores->fetch_object()) {
      $storeBookings = $mysqli->query(sprintf(
        'SELECT * FROM `bookings` WHERE `storeid` = %d AND `done` = 0',
        $store->id
      ));

      while ($booking = $storeBookings->fetch_object()) {
        $bookings[] = ['store' => $store, 'booking' => $booking];
      }
    }

    if (count($bookings) == 0)
      return [NULL, 'You do not have any new bookings.'];

    return ['ok', $bookings];
  }

  public static function BookingExistsByNameAndDate($storeid, $name, $date)
  {
    global $mysqli;

    $storeid = intval($storeid);
    $name = $mysqli->real_escape_string($name);
    $date = $mysqli->real_escape_string($date);
    // $time = date('H:i', strtotime($mysqli->real_escape_string($time)));

    $bookings = $mysqli->query(sprintf(
      'SELECT `id` FROM `bookings` WHERE `storeid` = %d AND `name` = "%s" AND `date` = "%s"',
      $storeid,
      $name,
      $date
    ));

    if ($bookings->num_rows != 0)
      return true;

    return false;
  }

  public static function DeleteBooking($bookingid) {
    global $mysqli;

    $bookingid = intval($bookingid);

    $booking = $mysqli->query(sprintf(
      'SELECT * FROM `bookings` WHERE `id` = %d',
      $bookingid
    ));

    if ($booking->num_rows == 0)
      return [NULL, 'Η κράτηση δεν υπάρχει.'];

    $booking = $booking->fetch_object();

    if (!Users::OwnsStore($booking->storeid) && $booking->uid != Account::getAccount()->id)
      return [NULL, 'Αυτό το κατάστημα δεν σου ανοίκει.'];

    $query = $mysqli->query(sprintf(
      'DELETE FROM `bookings` WHERE `id` = %d',
      $bookingid
    ));

    // also free store reserved tables
    $freeReserved = $mysqli->query(sprintf('UPDATE `stores` SET `reserved` = `reserved` - 1 WHERE `id` = %d', $booking->storeid));

    if ($query && $freeReserved)
      return ['ok', 'Η κράτηση διαγράφηκε.'];

    return [NULL, 'Κάτι πήγε στραβά.'];
  }

  public static function GetContactDetails($bookingid) {
    global $mysqli;

    $bookingid = intval($bookingid);

    $booking = $mysqli->query(sprintf(
      'SELECT `id`, `storeid`, `message`, `name`, `email`, `phone` FROM `bookings` WHERE `id` = %d',
      $bookingid
    ));

    if ($booking->num_rows == 0)
      return [NULL, 'Η κράτηση δεν υπάρχει.'];

    $booking = $booking->fetch_object();

    if (!Users::OwnsStore($booking->storeid))
      return [NULL, 'Αυτό το κατάστημα δεν σου ανοίκει.'];

    $contactDetails = [
      'id' => $booking->id,
      'name' => $booking->name,
      'email' => $booking->email,
      'phone' => $booking->phone,
      'message' => $booking->message
    ];

    return ['ok', $contactDetails];
  }

  public static function GetMyBookings() {
    global $mysqli;

    $bookings = $mysqli->query(sprintf('SELECT * FROM `bookings` WHERE `uid` = %d AND `done` = 0', Account::getAccount()->id));

    if ($bookings->num_rows == 0)
      return [NULL, 'Δεν έχεις κάνει καμία κράτηση.'];

    $bookingsArr = [];

    while($booking = $bookings->fetch_object()) {
      $bookingsArr[] = ['store' => Stores::Fetch($booking->storeid), 'booking' => $booking];
    }

    return ['ok', $bookingsArr];
  }
}
