<?php
require_once 'app/init.php';

Account::AdminRequired();

if (!defined('ADMIN'))
  define('ADMIN', true);

CMS::AdminInitialize($_GET);
