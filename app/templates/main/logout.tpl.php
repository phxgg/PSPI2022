<?php
if (!defined('ACCESS')) exit;
session_destroy();
header('Location: /?page=login');
