<?php
require(dirname(__FILE__) . "/Common/ClassesAndFunctions.php");

session_start();

//user
$user = $_SESSION["user"];

//check if logged in
if (!isset($user)) {
    $_SESSION["from"] = "MyAlbums.php";
    header("Location:Login.php");
}

$albums = Album::getAlbums($user->getId());
$accessibilities = Accessibility::getAccessibility();
$albumCount = Album::getAlbumCount($user->getId());

if (isset($_POST["Submit"])) {
    $accesses = $_POST["accessibility"];
    $i = 0;
    foreach ($albums as $album) {
        try {
            if ($accesses[$i] != $album->getAccessibility()) {
                Album::updateAlbum($accesses[$i], $album->getId());
            }
        } catch (Exception $ex) {
            
        }
        $i++;
    }
    header("Location:MyAlbums.php");
}

if (isset($_POST["deleteSelected"])) {
    $deleteAlbums = $_POST["deleteSelected"];
    $i = 0;
    foreach ($deleteAlbums as $deleteAlbum) {
        if ($deleteAlbum == $albums[$i]->getId()) {
            try {
                Album::deleteAlbum($deleteAlbum);
                header("Location: MyAlbums.php");
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        $i++;
    }
}

// Header
include(dirname(__FILE__) . "/Common/Header.php");
?>

<!-- Page Title -->
<h1 style="text-align:center;">My Albums</h1>

<!-- Welcome Message -->
<p>Welcome <strong><?php echo $user->getName(); ?></strong>! (not you? change user <a href="Logout.php">here</a>)</p>

<!-- Create New Album -->
<div class="text-right">
    <a href="AddAlbum.php">Create a new album</a>
</div>

<?php if ($albums == null) { ?>
    <p><small class="text-danger">No current albums.</small></p>
<?php } else { ?>
    <!-- Table -->
    <form method="post" action="MyAlbums.php">
        <table width="100%">
            <tr>
                <th>Title</th>
                <th>Date Updated</th>
                <th>Number of Pictures</th>
                <th>Accessibility</th>
                <th></th>
            </tr>
            <?php foreach ($albums as $album) {
                $picCount = Album::getPicCount($album->getId());
                ?>
                <tr>
                    <td><a href="MyPictures.php?albumId=<?php echo $album->getId(); ?>"><?php echo $album->getTitle(); ?></a></td>
                    <td><?php echo $album->getDateUpdated(); ?></td>
                    <td><?php echo $picCount; ?></td>
                    <td><select name="accessibility[]">
                                <?php foreach ($accessibilities as $accessibility) { ?>
                                <option value="<?php echo $accessibility->getAccessibilityCode(); ?>"<?php if ($album->getAccessibility() === $accessibility->getAccessibilityCode()) { ?> selected<?php } ?>>
                                <?php echo $accessibility->getDescription(); ?>
                                </option>
        <?php } ?>
                        </select></td>
                    <td><button type="submit" value="'.$album->getId().'" name="deleteSelected[]">Delete</button></td>
                </tr>
    <?php } ?>
        </table>
        <div class="text-right">
            <input type="Submit" name="Submit" class="btn btn-primary" value="Save Changes">
        </div>
    </form>
<?php } ?>

<!-- Javascript -->
<script>
    var button = Array.prototype.slice.call(document.getElementsByTagName("button"));
    button.forEach(function (element, index) {
        element.addEventListener("click", function () {
            if (!confirm('The selected album and all pictures will be deleted!')) {
                e.preventDefault();
            }
        });
    });
</script>
<?php
// Footer
require(dirname(__FILE__) . "/Common/Footer.php");
