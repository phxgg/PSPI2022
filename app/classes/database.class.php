<?php
// Set error reporting to 0.
// Need to hide sensitive data in case mysql reports an error.

error_reporting(0);

$mysqli = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);

// Re enable error reporting for debugging, or keep disabled for production.
// error_reporting(E_ALL);
