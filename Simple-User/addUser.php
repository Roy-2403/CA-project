<?php
include 'inc/header.php';
Session::CheckSession();
$sId = Session::get('roleid');

if ($sId === '1') { // Only allow access if user is an admin
    session_start();
    
    // CSRF token generation and validation
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addUser'])) {
        // CSRF token check
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF token validation failed.');
        }

        // Sanitize and validate input data
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $roleid = filter_input(INPUT_POST, 'roleid', FILTER_SANITIZE_NUMBER_INT);

        // Ensure all inputs are clean and valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<div class='alert alert-danger'>Invalid email format.</div>";
        } else {
            $postData = [
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'mobile' => $mobile,
                'password' => $password, // Consider hashing the password before sending to the method
                'roleid' => $roleid
            ];
            $userAdd = $users->addNewUserByAdmin($postData);
            if (isset($userAdd)) {
                echo $userAdd;
            }
        }
    }
?>

<div class="card ">
    <div class="card-header">
        <h3 class='text-center'>Add New User</h3>
    </div>
    <div class="card-body" style="width:600px; margin:0px auto">
        <form action="" method="post">
            <div class="form-group pt-3">
                <label for="name">Your name</label>
                <input type="text" name="name" class="form-control">
            </div>
            <div class="form-group">
                <label for="username">Your username</label>
                <input type="text" name="username" class="form-control">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="text" name="mobile" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="roleid">Select user Role</label>
                <select class="form-control" name="roleid" id="roleid">
                    <option value="1">Admin</option>
                    <option value="2">Editor</option>
                    <option value="3">User only</option>
                </select>
            </div>
            <div class="form-group">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" name="addUser" class="btn btn-success">Register</button>
            </div>
        </form>
    </div>
</div>

<?php
} else {
    header('Location: index.php');
    exit; // Ensure no further script execution
}
include 'inc/footer.php';
?>
