<?php

require(dirname(__FILE__) . "/Common/ClassesAndFunctions.php");

// User clicked Sign up
if (isset($_POST['signup'])) {
    
    // Get inputs
    $userID = trim(filter_input(INPUT_POST, 'userID'));
    $name = trim(filter_input(INPUT_POST, 'name'));
    $phone = trim(filter_input(INPUT_POST, 'phone'));
    $password = trim(filter_input(INPUT_POST, 'password'));
    $confirmPassword = trim(filter_input(INPUT_POST, 'passwordAgain'));
    
    try {
        $user = User::createUser($userID, $name, $phone, $password, $confirmPassword);
        if (is_a($user, 'User')) {
            header("Location: Login.php");
        }
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
} else if (isset($_POST['clear'])) {
    header("location: NewUser.php");
}

// Header
include(dirname(__FILE__) . "/Common/Header.php"); ?>
<h2>Sign Up</h2>
<p>All fields are required.</p><br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <?php if ($error != null) { ?>
        <p><small class="text-danger"><?php echo $error; ?></small></p>
    <?php } ?>
    
    <!-- Student Id -->
    <div class="form-group row">
        <label for="userID" class="col-sm-2 col-form-label">User ID: </label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="userID" value="<?php echo $userID; ?>">
        </div>
    </div>
    
    <!-- Name -->
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Name: </label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
        </div>
    </div>
    
    <!-- Phone -->
    <div class="form-group row">
        <label for="phone" class="col-sm-2 col-form-label">Phone number: </label>
        <div class="col-sm-4">
            <input type="tel" class="form-control" name="phone" value="<?php echo $phone; ?>">
        </div>
    </div>
    
    <!-- Password -->
    <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label">Password: </label>
        <div class="col-sm-4">

            <input type="password" class="form-control" name="password" value="<?php echo $passwordAgain; ?>">
        </div>
    </div>
    
    <!-- Confirm password -->
    <div class="form-group row">
        <label for="passwordAgain" class="col-sm-2 col-form-label">Password Again: </label>
        <div class="col-sm-4">
            <input type="password" class="form-control" name="passwordAgain" value="<?php echo $passwordAgain; ?>">
        </div>
    </div>
    
    <button type="submit" name="signup" class="btn btn-primary align-right">Submit</button>
    <button type="submit" name="clear" class="btn btn-primary align-right">Clear</button>
            
</form>
<?php include(dirname(__FILE__) . "/Common/Footer.php"); ?>