<?php

require(dirname(__FILE__) . "/Common/ClassesAndFunctions.php");
require(dirname(__FILE__) . "/Common/ConstantsAndSettings.php");

// Start Session
session_start();

// User
$user = $_SESSION['user'];
if (!isset($user)) {
    $_SESSION["from"] = "AddAlbum.php";
    header("Location: Login.php");
}

$accessibilities = Accessibility::getAccessibility();

// User clicked Sign in
if (isset($_POST['addAlbum'])) {
    
    // Get inputs
    $title = trim(filter_input(INPUT_POST, 'title'));
    $accessibility = trim(filter_input(INPUT_POST, 'accessibility'));
    $description = trim(filter_input(INPUT_POST, 'description'));
    
    try {
        $album = Album::addAlbum($title, $accessibility, $description, $user->getId());
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
} else if (isset($_POST['clear'])) {
    header("Location: AddAlbum.php");
}

// Header
include(dirname(__FILE__) . "/Common/Header.php"); ?>

<h2>Create New Album</h2>
<p>Welcome <?php echo $user->getName(); ?> (not you? change user <a href="Login.php">here</a>)</p><br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!-- Student not found -->
    <?php if ($error != null) { ?>
        <p><small class="text-danger"><?php echo $error; ?></small></p>
    <?php } ?>
        
    <!-- Title -->
    <div class="form-group row">
        <label for="title" class="col-sm-2 col-form-label">Title: </label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
        </div>
    </div>
    <!-- Accessibility -->
    <div class="form-group row">
        <label for="accessibility" class="col-sm-2 col-form-label">Accessibility: </label>
        <div class="col-sm-4">
            <select name="accessibility">
                <?php foreach ($accessibilities as $accessibility) { ?>
                    <option value="<?php echo $accessibility->getAccessibilityCode(); ?>"><?php echo $accessibility->getDescription(); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <!-- Description -->
    <div class="form-group row">
        <label for="description" class="col-sm-2 col-form-label">Description: </label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="description" value="<?php echo $description; ?>">
        </div>
    </div>
    <button type="submit" name="addAlbum" class="btn btn-primary align-right">Submit</button>
    <button type="submit" name="clear" class="btn btn-primary align-right">Clear</button>
</form>
<?php include(dirname(__FILE__) . "/Common/Footer.php"); ?>