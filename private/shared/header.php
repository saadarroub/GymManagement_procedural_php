<?php
//to do : desing footer

  if(!isset($page_title)) { $page_title = 'SM_Program'; }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= url_for('stylesheets/staff.css'); ?>">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <title><?php echo h($page_title); ?></title>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand mr-5" href="<?php echo url_for('staff/index.php'); ?>">Home</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span><i class="fa fa-align-right"></i></span>
      </button>
      <!-- menu -->
      <?php if(is_logged_in()) { ?>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-user"></i> Welcome <?php echo h($_SESSION['username']); ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo url_for('staff/utilisateur/profil/edit_mdp.php?IdUser=' . h(u($_SESSION['user_id']))); ?>">Profil</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo url_for('staff/logout.php'); ?>">Dexonnexion</a>
        </div>
      </li>
          <li class="nav-item">
            <a class="nav-link link-hover" href="<?php echo url_for('staff/salle/index.php'); ?>">Salle</a>
          </li>
          <li class="nav-item">
            <a class="nav-link link-hover" href="<?php echo url_for('staff/sport/index.php'); ?>">Sport</a>
          </li>
          <li class="nav-item">
            <a class="nav-link link-hover" href="<?php echo url_for('staff/utilisateur/index.php'); ?>">Utilusateur</a>
          </li>
          <li class="nav-item">
            <a class="nav-link link-hover" href="<?php echo url_for('staff/client/index.php'); ?>">Clients</a>
          </li>
        </ul>
      </div>
    </div>
<?php } ?>
    <!-- /menu -->
    </div>
  </nav>
  <!-- /Navbar -->