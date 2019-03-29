<?php

require(dirname(__FILE__) . "/Common/ClassesAndFunctions.php");

// Start Session
session_start();

// User clicked Sign in
if (isset($_POST['login'])) {
    
    // Get inputs
    $userId = trim(filter_input(INPUT_POST, 'userId'));
    $password = trim(filter_input(INPUT_POST, 'password'));
    
    try {
        $user = User::validateUserLogin($userId, $password);
        
        // If login is valid
        if (is_a($user, 'User')) {
            $_SESSION['user'] = $user;
                  echo '<script type="text/javascript">',
     'success();',
     '</script>';
            if ($_SESSION["from"] != null) {
                header("Location: " . $_SESSION["from"]);
            }else{
                header("Location: AddAlbum.php");
            }
      
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    
} else if (isset($_POST['clear'])) {
    header("location: Login.php");
}

// Header
include(dirname(__FILE__) . "/Common/Header.php"); ?>

<h2>Sign In</h2>
<p>You need to <a href="NewUser.php">sign up</a> if you are a new user.</p><br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!-- Student not found -->
    <?php if ($error != null) { ?>
        <p><small class="text-danger"><?php echo $error; ?></small></p>
    <?php } ?>
        
    <!-- Student Id -->
    <div class="form-group row">
        <label for="userId" class="col-sm-2 col-form-label">User ID: </label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="userId" value="<?php echo $userId; ?>">
        </div>
    </div>
    
<script>
function success() {
    alert("You are now logged in");
}
</script>
    <!-- Password -->
    <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label">Password: </label>
        <div class="col-sm-4">
            <input type="password" class="form-control" name="password" value="">
        </div>
    </div>
    <button type="submit" name="login" class="btn btn-primary align-right">Submit</button>
    <button type="submit" name="clear" class="btn btn-primary align-right">Clear</button>
</form>
<?php include(dirname(__FILE__) . "/Common/Footer.php"); ?>