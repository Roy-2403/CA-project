<?php
include 'inc/header.php';
Session::CheckLogin();
?>

<style>
  .login-background {
    background-image: url('images/login.jpg');
    background-size: cover;
    background-position: center;
  }
  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.2);
  }
  .content-container {
    position: relative;
    z-index: 2;
  }
</style>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
   $userLog = $users->userLoginAuthotication($_POST);
}
if (isset($userLog)) {
  echo $userLog;
}

$logout = Session::get('logout');
if (isset($logout)) {
  echo $logout;
}
?>

<div class="container mt-5 login-background">
  <div class="overlay"></div>
  <div class="row justify-content-center content-container">
    <div class="col-md-6">
      <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
          <h3 class='text-center'><i class="fas fa-sign-in-alt mr-2"></i> User Login</h3>
        </div>
        <div class="card-body">
          <form action="" method="post">
            <div class="form-group">
              <label for="email">Email Address:</label>
              <input type="email" name="email" class="form-control" placeholder="Enter email">
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" name="password" class="form-control" placeholder="Enter password">
            </div>
            <div class="text-center">
              <button type="submit" name="login" class="btn btn-success btn-block">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include 'inc/footer.php';
?>
