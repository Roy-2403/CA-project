<?php
include 'inc/header.php';
header("Content-Security-Policy: frame-ancestors 'self'"); //prevent clickjacking

Session::CheckSession();

$logMsg = Session::get('logMsg');
if (isset($logMsg)) {
  echo "<div class='alert alert-info text-center'>$logMsg</div>";
}
$msg = Session::get('msg');
if (isset($msg)) {
  echo "<div class='alert alert-success text-center'>$msg</div>";
}
Session::set("msg", NULL);
Session::set("logMsg", NULL);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['remove'])) {
    $remove = preg_replace('/[^0-9]/', '', $_GET['remove']); // Ensuring only numbers are retained
    $removeUser = $users->deleteUserById($remove);
    if (isset($removeUser)) {
      echo "<div class='alert alert-danger'>$removeUser</div>";
    }
  }

  if (isset($_GET['deactive'])) {
    $deactive = preg_replace('/[^0-9]/', '', $_GET['deactive']);
    $deactiveId = $users->userDeactiveByAdmin($deactive);
    if (isset($deactiveId)) {
      echo "<div class='alert alert-warning'>$deactiveId</div>";
    }
  }

  if (isset($_GET['active'])) {
    $active = preg_replace('/[^0-9]/', '', $_GET['active']);
    $activeId = $users->userActiveByAdmin($active);
    if (isset($activeId)) {
      echo "<div class='alert alert-success'>$activeId</div>";
    }
  }
}
?>

<div class="container mt-5">
  <div class="card ">
  <div class="card-header custom-light-header">
  <h3><i class="fas fa-users mr-2"></i>User List <span class="float-right">Welcome! <strong><?php echo Session::get('username'); ?></strong></span></h3>
</div>
    </div>
    <div class="card-body pr-2 pl-2">
      <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <tr>
            <th class="text-center">SL</th>
            <th class="text-center">Name</th>
            <th class="text-center">Username</th>
            <th class="text-center">Email Address</th>
            <th class="text-center">Mobile</th>
            <th class="text-center">Status</th>
            <th class="text-center">Created</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $allUser = $users->selectAllUserData();
          if ($allUser) {
            $i = 0;
            foreach ($allUser as $value) {
              $i++;
              ?>
              <tr class="text-center" <?php if (Session::get("id") == $value->id) echo 'style="background:#d9edf7"'; ?>>
                <td><?php echo $i; ?></td>
                <td><?php echo htmlspecialchars($value->name); ?></td>
                <td><?php echo htmlspecialchars($value->username); ?></td>
                <td><?php echo htmlspecialchars($value->email); ?></td>
                <td><?php echo htmlspecialchars($value->mobile); ?></td>
                <td><?php echo ($value->isActive == '0') ? '<span class="badge badge-info">Active</span>' : '<span class="badge badge-danger">Deactive</span>'; ?></td>
                <td><?php echo $users->formatDate($value->created_at); ?></td>
                <td>
                  <a class="btn btn-success btn-sm" href="profile.php?id=<?php echo $value->id; ?>">View</a>
                  <a class="btn btn-info btn-sm" href="profile.php?id=<?php echo $value->id; ?>">Edit</a>
                  <a onclick="return confirm('Are you sure to delete?')" class="btn btn-danger btn-sm" href="?remove=<?php echo $value->id; ?>">Remove</a>
                  <a onclick="return confirm('Are you sure to change status?')" class="btn btn-warning btn-sm" href="?deactive=<?php echo $value->id; ?>">Toggle Status</a>
                </td>
              </tr>
              <?php
            }
          } else {
            echo '<tr class="text-center"><td colspan="8">No user available now!</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include 'inc/footer.php'; ?>
