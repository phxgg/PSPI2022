<?php if (!defined('ACCESS')) exit; ?>

<!doctype html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title id="page-title"><?= $title; ?> / <?= SITE_NAME;; ?></title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.5/r-2.2.9/datatables.min.css"/>

  <!-- Custom Styles -->
  <link rel="stylesheet" href="css/styles.css">

  <!-- jQuery -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<main class="container">
  <noscript>
    <br />
    <div class="alert alert-danger">
      <h3>Προσοχή!</h3>
      Η ιστοσελίδα μας βασίζεται κυρίως σε JavaScript.<br>
      Παρακαλούμε ενεργοποιήστε την JavaScript για την ομαλή λειτουργία του site.
    </div>
  </noscript>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="?page=index">
        <img src="img/logo.png" width="90" height="80" class="d-inline-block align-text-top" />
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="?page=categories">
              <i class="bi bi-tags"></i>
              Κατηγορίες
            </a>
          </li>
        </ul>

        <div class="search-bar flex-grow-1 row height d-flex justify-content-center align-items-center">
          <div class="col-md-8">
            <div class="form" id="search-form">
                <i class="bi bi-search"></i> <input type="search" id="search-bar" class="form-control form-input" placeholder="Βρες ένα κατάστημα...">
                <span class="left-pan" id="mic-wrapper">
                  <button type="button" id="search-voice" class="btn btn-sm">
                    <i class="bi bi-mic-mute"></i>
                  </button>
              </span>
            </div>
            
            <div class="dropdown-menu" id="search-results" style="margin-top:10px;min-width:500px;min-height:200px;padding:10px;"></div>
            
          </div>
        </div>

        <div class="d-flex p-1">
          <?php if (Account::IsLoggedIn()) { ?>

            <div class="btn-group">
              <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownAccountButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i> <?= Account::getAccount()->username; ?>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="?page=my-favorites">
                  <i class="bi bi-heart-fill text-danger"></i>
                  Αγαπημένα
                </a>
                <a class="dropdown-item" href="?page=my-bookings">
                  <i class="bi bi-bookmark-star text-success"></i>
                  Κρατήσεις
                </a>
                <a class="dropdown-item" href="?page=settings">
                  <i class="bi bi-gear-wide-connected"></i>
                  Ρυθμίσεις
                </a>
                <a class="dropdown-item" href="?page=logout" style="color: #a94442;">
                  <i class="bi bi-box-arrow-left"></i>
                  Αποσύνδεση
                </a>
                <?php if (Account::IsProfessional()) { ?>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="?page=professional">
                    <i class="bi bi-patch-check text-primary"></i>
                    Επαγγελματίας
                  </a>
                <?php } ?>
                <?php if (Account::IsAdmin()) { ?>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="admin.php?page=dashboard">
                    <i class="bi bi-kanban text-deep-purple"></i>
                    Διαχειριστής
                  </a>
                <?php } ?>
              </div>
            </div>

          <?php } else { ?>

            <div class="btn-group">
              <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownLoginButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-box-arrow-in-right"></i>
                Σύνδεση
              </button>
              <div class="dropdown-menu">
                <form action="" method="post" class="px-4 py-3">
                  <div class="input-group mb-3">
                    <span class="input-group-text bi bi-person" id="username-addon"></span>
                    <input type="text" name="user" class="form-control" id="exampleDropdownFormUsername1" placeholder="Όνομα χρήστη" aria-label="Username" aria-describedby="username-addon">
                  </div>
                  <div class="input-group mb-3">
                    <span class="input-group-text bi bi-lock" id="password-addon"></span>
                    <input type="password" name="pass" class="form-control" id="exampleDropdownFormPassword1" placeholder="Κωδικός" aria-label="Password" aria-describedby="password-addon">
                  </div>
                  <button type="submit" name="sign-in" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Σύνδεση
                  </button>
                </form>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="?page=register">Είσαι καινούριος; Κάνε εγγραφή!</a>
                <!-- <a class="dropdown-item" href="#">Ξέχασες τον κωδικό σου;</a> -->
              </div>
            </div>
          <?php } ?>

        </div>
      </div>
  </nav>

  <br />

  <?php
  if (!Account::IsLoggedIn()) {
    if (isset($_POST['sign-in'])) {
      echo Account::Login($_POST['user'], $_POST['pass']);
    }
  }
  ?>