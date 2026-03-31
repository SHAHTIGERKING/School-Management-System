<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-graduation-cap me-2"></i>SchoolSys</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="../actions/logout.php">Logout</a>
            </li>
            <li class="nav-item">
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a class="btn btn-light text-primary ms-2" href="../admin/index.php">Dashboard</a>
                <?php else: ?>
                    <a class="btn btn-light text-primary ms-2" href="#">My Profile</a>
                <?php endif; ?>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-light text-primary ms-2" href="register.php">Register</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="main-content flex-grow-1">
    <?php 
    if(function_exists('displayFlashMessage')){
        displayFlashMessage();
    }
    ?>
