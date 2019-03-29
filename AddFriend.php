<?php

require(dirname(__FILE__) . "/Common/ClassesAndFunctions.php");

// Start session
session_start();

// User
$user = $_SESSION['user'];
if (!isset($user)) {
    $_SESSION["from"] = "AddFriend.php";
    header("Location: Login.php");
}

// Send Friend Request
if (isset($_POST["sendFriendRequest"])) {
    $friendId = $_POST["friendId"];
    try {
        Friendship::sendFriendRequest($user->getId(), $friendId);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Header
include(dirname(__FILE__) . "/Common/Header.php"); ?>

<h2>Add Friend</h2>
<p>Welcome <?php echo $user->getName(); ?> (not you? change user <a href="Login.php">here</a>)</p>
<p>Enter the ID of the user you want to be friends with.</p>
<form action="AddFriend.php" method="post" class="row col-lg-10">
    <?php if ($error != null) { ?>
        <p><small class="text-danger"><?php echo $error; ?></small></p>
    <?php } ?>
    <div class="form-group col-sm-1">
        <label for="friendId" class="form-control-static">ID:</label>
    </div>
    <div class="form-group col-sm-3">
        <input type="text" class="form-control" name="friendId" value="<?php echo $friendId; ?>">
    </div>
    <button type="submit" class="btn btn-primary" name="sendFriendRequest">Send Friend Request</button>
</form>

<?php include(dirname(__FILE__) . "/Common/footer.php"); ?>