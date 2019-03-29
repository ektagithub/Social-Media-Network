<?php
require_once("Common/ClassesAndFunctions.php");
require_once("Common/ConstantsAndSettings.php");

session_start();

// User
$user = $_SESSION["user"];

//check if logged in
if (!isset($user)) {
    $_SESSION["from"] = "UploadPicture.php";
    header("Location:Login.php");
}
$albums = Album::getAlbums($user->getId());
$albumId = $_POST["album"];
$title = $_POST["txtTitle"];
$description = $_POST["description"];

$originalPath = "Pictures/" . $user->getId() . "/" . $albumId . "/" . ORIGINAL_PICTURES_DIR;
$albumPath = "Pictures/" . $user->getId() . "/" . $albumId . "/" . ALBUM_PICTURES_DIR;
$thumbPath = "Pictures/" . $user->getId() . "/" . $albumId . "/" . ALBUM_THUMBNAILS_DIR;

if (isset($_POST['btnUpload'])) {
    $total = count($_FILES['txtUpload']['name']);
    for ($i = 0; $i < $total; $i++) {
        if ($_FILES['txtUpload']['error'][$i] == 0) {
            $tempFilePath = $_FILES['txtUpload']['tmp_name'][$i];
            $filePath = $originalPath . "/" . $_FILES['txtUpload']['name'][$i];
            $pathInfo = pathinfo($filePath);
            $dir = $pathInfo['dirname'];
            $fileName = $pathInfo['filename'];
            $ext = $pathInfo['extension'];
            if (!file_exists($originalPath)) {
                mkdir($originalPath, 0755, true);
            }
            if (!file_exists($albumPath)) {
                mkdir($albumPath, 0755, true);
            }
            if (!file_exists($thumbPath)) {
                mkdir($thumbPath, 0755, true);
            }
            //make sure not to overwrite existing files 
            $j = "";
            while (file_exists($filePath)) {
                $j++;
                $filePath = $dir . "/" . $fileName . "_" . $j . "." . $ext;
            }
            move_uploaded_file($tempFilePath, $filePath);

            $imageDetails = getimagesize($filePath);

            if ($imageDetails && in_array($imageDetails[2], $supportedImageTypes)) {
                Picture::upload($filePath, $albumPath, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT);
                Picture::upload($filePath, $thumbPath, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT);
                $fileName = $fileName . "." . $ext;
                Picture::insertIntoDatabase($albumId, $fileName, $title, $description, date("Y-m-d"));
                $success = "Image(s) uploaded successfully";
            } else {
                $error = "Uploaded file is not a supported type";
                unlink($filePath);
            }
        } elseif ($_FILES['txtUpload']['error'][$i] == 1) {
            $error = "Upload file is too large";
        } elseif ($_FILES['txtUpload']['error'][$i] == 4) {
            $error = "No upload file specified";
        } else {
            $error = "Error happened while uploading the file. Try again later";
        }
    }
}

// Header
require_once("Common/header.php"); ?>
<!-- Title -->
<h1 style="text-align:center;">Upload Pictures</h1>
<?php if ($albums == null) { ?>
    <p><small class="text-danger">Please create an album before uploading pictures.</small></p>
<?php } else { ?>
    <p>Accepted picture types: JPG (JPEG), GIF, and PNG</p>
    <p>You can upload multiple pictures by pressing the Shift key while selecting pictures.</p>
    <p>When uploading multiple pictures, the Title and Description fields will be applied to all pictures.</p>
    <span style="color:blue; font-size:15px;"><?php echo $success; ?></span>
    <?php echo $error; ?>
    <form method="POST" action="UploadPicture.php" enctype="multipart/form-data">
        <table>
            <tr>
                <td>
                    <strong>Upload to album:</strong> 
                </td>
                <td><select name="album" style="width:400px;">
                    <?php foreach ($albums as $album) { ?>
                        <option value="<?php echo $album->getId() ?>"><?php echo $album->getTitle() ?></option>
                    <?php } ?>
                </select></td>
            </tr>
            <tr>
                <td>
                    <strong>File(s) to Upload:</strong>
                </td>
                <td>
                    <input type="file" name="txtUpload[]" style="width:400px;" multiple="multiple">
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Title</strong>
                </td>
                <td>
                    <input type="text" name="txtTitle" style="width:400px;" />
                </td>
            </tr>
            <tr>
                <td><strong>Description</strong></td>
                <td><textarea style="width:400px;" rows="10" name="description"></textarea>
            </tr>
            <tr>
                <td><input type="submit" name="btnUpload" value="Upload" class="button"/></td>
                <td><input type="reset" name="btnReset" value="Reset" class="button"/></td>
            </tr>
        </table>
    </form>
<?php }

// Footer
require_once("Common/Footer.php");
