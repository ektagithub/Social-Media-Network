<?php

require(dirname(__FILE__) . "/Common/ClassesAndFunctions.php");

// Start Session
session_start();

// User
$user = $_SESSION['user'];
if (!isset($user)) {
    $_SESSION["from"] = "MyFriends.php";
    header("Location: Login.php");
}

// Defriend Selected
if (isset($_POST["defriendSelected"])) {
    $defriends = $_POST["defriend"];
    $friends = Friendship::getFriends($user->getId());
    $i = 0;
    try {
        foreach ($defriends as $defriend) {
            if ($defriend == $friends[$i]->getId($user->getId())) {
                Friendship::deleteFriend($user->getId(), $defriend);
            }
            $i++;
        }
        header("Location: MyFriends.php");
    } catch (Exception $e) {

    }
    for ($i = 0; $i < count($friends); $i++) {
        if ($defriend[$i] == $friends[$i]->getId()) {
            Friendship::deleteFriend($user->getId(), $defriend[i]);
        }
    }
    header("Location: MyFriends.php");
}

// Accept Selected
if (isset($_POST["acceptSelected"])) {
    $requests = $_POST["requests"];
    $i = 0;
    try {
        $friendRequests = Friendship::getFriendRequests($user->getId());
        foreach ($requests as $request) {
            if ($request == $friendRequests[$i]->getId($user->getId())) {
                Friendship::approveFriend($user->getId(), $request);
            }
            $i++;
        }
        header("Location: MyFriends.php");
    } catch (Exception $e) {

    }
}

// Deny Selected
if (isset($_POST["denySelected"])) {
    $requests = $_POST["requests"];
    $i = 0;
    try {
        $friendRequests = Friendship::getFriendRequests($user->getId());
        foreach ($requests as $request) {
            if ($request == $friendRequests[$i]->getId($user->getId())) {
                Friendship::deleteFriend($user->getId(), $request);
            }
            $i++;
        }
        header("Location: MyFriends.php");
    } catch (Exception $e) {
        
    }
}

// Header
include(dirname(__FILE__) . "/Common/Header.php"); ?>

<h2>My Friends</h2>
<p>Welcome <?php echo $user->getName(); ?>(not you? change user <a href="Login.php">here</a>)</p>

<div class="">
    <div class="row">
        <p class="col-sm-10">Friends:</p>
        <a class="col-sm-2" href="AddFriend.php">Add Friends</a>
    </div>
    <form action="MyFriends.php" method="post">
        <?php try {
            $friends = Friendship::getFriends($user->getId()); ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Shared Albums</th>
                        <th scope="col">Defriend</th>
                    </tr>
                </thead>
                <tbody id="defriends">
                    <?php foreach ($friends as $friend) { 
                        $friendUser = User::getUserById($friend->getId($user->getId())); ?>
                        <tr>
                            <td><a href="FriendPictures.php?"><?php echo $friendUser->getName(); ?></a></td>
                            <td><?php echo Album::countFriendAlbums($friendUser->getId()); ?></td>
                            <td><input type="checkbox" name="defriend[]" value="<?php echo $friendUser->getId(); ?>"></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div id="defriendError"></div>
            <button type="submit" name="defriendSelected" id="defriendSelected" class="btn btn-primary">Defriend Selected</button>
        <?php } catch (Exception $e) { ?>
            <p><small class="text-danger"><?php echo $e->getMessage(); ?></small></p>
        <?php } ?>
            
        <p>Friend Requests</p>
        <?php try {
            $friendRequests = Friendship::getFriendRequests($user->getId()); ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Accept or Deny</th>
                    </tr>
                </thead>
                <tbody id="deniesSelected">
                    <?php foreach ($friendRequests as $friendRequest) { 
                        $friendRequestUser = User::getUserById($friendRequest->getId($user->getId())); ?>
                        <tr>
                            <td><?php echo $friendRequestUser->getName(); ?></td>
                            <td><input type="checkbox" name="requests[]" value="<?php echo $friendRequestUser->getId(); ?>"></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div id="deniesError"></div>
            <button type="submit" name="acceptSelected" id="defriendSelected" class="btn btn-primary">Accept Selected</button>
            <button type="submit" name="denySelected" id="denySelected" class="btn btn-primary">Deny Selected</button>
        <?php } catch (Exception $e) { ?>
            <p><small class="text-danger"><?php echo $e->getMessage(); ?></small></p>
        <?php } ?>
    </form>
</div>
<script>
    document.getElementById('defriendSelected').onclick = function(e) {
        var numOfDefriends = document.querySelectorAll("#defriends input:checked").length;
        if (numOfDefriends == 0) {
            document.getElementById('defriendError').innerHTML = "<p><small class='text-danger'>No box was checked!</small></p>";
            e.preventDefault();
        } else {
            if (!confirm('The selected friends will be defriended!')) {
                e.preventDefault();
            }
        }
    };
    document.getElementById('denySelected').onclick = function(e) {
        var numOfDenies = document.querySelectorAll("#deniesSelected input:checked").length;
        if (numOfDenies == 0) {
            document.getElementById('deniesError').innerHTML = "<p><small class='text-danger'>No box was checked!</small></p>";
            e.preventDefault();
        } else {
            if (!confirm('The selected users will be denied!')) {
                e.preventDefault();
            }
        }
    };
</script>
<?php include(dirname(__FILE__) . "/Common/Footer.php"); ?>