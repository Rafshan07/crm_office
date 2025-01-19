<?php require 'include/database.php'; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <title>apcorn crm</title>
  </head>
  <body>
    <section
      class="login-section d-flex justify-content-center align-items-center vh-100"
    >
      <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%">
        <h2 class="text-center mb-4">
          <i class="fas fa-user-circle me-2"></i>Login
        </h2>
        <form>
          <div class="mb-3 input-group">
            <span class="input-group-text">
              <i class="fas fa-envelope"></i>
            </span>
            <input
              type="email"
              class="form-control"
              id="email"
              placeholder="Enter your email"
            />
          </div>
          <div class="mb-3 input-group">
            <span class="input-group-text">
              <i class="fas fa-lock"></i>
            </span>
            <input
              type="password"
              class="form-control"
              id="password"
              placeholder="Enter your password"
            />
          </div>
          <button
            type="button"
            class="btn btn-primary w-100 btn-animated"
            onclick="window.location.href='main.php';"
          >
            <i class="fas fa-sign-in-alt me-2"></i>Login
          </button>
        </form>
        <p class="text-center mt-3">
          <a href="#" class="text-decoration-none">Forgot password?</a>
        </p>
      </div>
    </section>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
  </body>
</html>
