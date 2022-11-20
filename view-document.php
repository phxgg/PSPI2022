<?php
require_once 'app/init.php';
error_reporting(E_ALL);

Account::ProfessionalRequired();

function ShowPDF($content) {
  $file = sprintf('%s.pdf', Misc::GenerateRandomString());
  // $content = base64_decode($content);
  header('Content-type: application/pdf');
  header('Content-Disposition: attachment; filename='.$file);
  echo $content;
}

$allowedTypes = [
  'identification',
  'license'
];

if (!isset($_GET['type'])
  || !in_array($_GET['type'], $allowedTypes)
  || !isset($_GET['id']))
  die('u wot m8?');

$id = $_GET['id'];
$type = $_GET['type'];

$data = Stores::GetDocuments($id);

if ($data[0] == NULL)
  die($data[1]);

switch ($type) {
  case 'identification':
    ShowPDF($data[1]->identification);
    break;
  case 'license':
    ShowPDF($data[1]->license);
    break;
}

