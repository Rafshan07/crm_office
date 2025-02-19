<?php
session_start();
require_once 'lib/database.php';
require_once 'lib/user.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
  $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';

  if (empty($email) || empty($password)) {
    $error = "Email and Password are required!";
  } else {
    $user = new User();
    $role = $user->login($email, $password);

    if ($role) {
      // Reset login attempts and set session variables
      $_SESSION['login_attempts'] = 0;
      $_SESSION['role'] = $role;
      $_SESSION['userid'] = $user->getUserIDByEmail($email); // Fetch UserID for staff/admin

      // Redirect based on role
      switch ($role) {
        case "admin":
          $success = "Login successful! Redirecting to admin dashboard...";
          header("Location: main.php");
          exit();
        case "sales":
          $success = "Login successful! Redirecting to sales dashboard...";
          header("Location: sales.php");
          exit();
        case "staff":
          $success = "Login successful! Redirecting to staff dashboard...";
          header("Location: staff_dashboard.php");
          exit();
        default:
          $error = "Unknown role!";
          break;
      }
    } elseif ($user->isCustomer($email, $password)) {
      // Handle customer login
      $_SESSION['role'] = 'customer';
      $_SESSION['userid'] = $user->getCustomerID($email);

      $success = "Login successful! Redirecting to customer dashboard...";
      header("Location: customer.php");
      exit();
    } else {
      // Handle failed login attempts
      if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
      }
      $_SESSION['login_attempts']++;

      if ($_SESSION['login_attempts'] > 5) {
        $error = "Too many failed attempts! Please try again later.";
      } else {
        $error = "Invalid Email or Password!";
      }
    }
  }
}
?>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger" role="alert">
    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
  </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
  <div class="alert alert-success" role="alert">
    <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
  </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>apcorn crm</title>
</head>

<body>
  <section class="login-section d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
      <h2 class="text-center mb-4">
        <i class="fas fa-user-circle me-2"></i>Login
      </h2>
      <form action="" method="POST">
        <div class="mb-3 input-group">
          <span class="input-group-text">
            <i class="fas fa-envelope"></i>
          </span>
          <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email">
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text">
            <i class="fas fa-lock"></i>
          </span>
          <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password">
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-animated">
          <i class="fas fa-sign-in-alt me-2"></i>Login
        </button>
      </form>
    </div>
  </section>
  <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
</body>

</html>