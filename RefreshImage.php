<?php
require(dirname(__FILE__) . "/Common/ClassesAndFunctions.php");
require(dirname(__FILE__) . "/Common/ConstantsAndSettings.php");

session_start();
$user = $_SESSION['user'];

if (isset($_POST["picsID"])) {
    $pic_id = $_POST["picsID"];
    if (isset($_SESSION['chosenAlbumid'])) {
        $album = Album::getAlbumById($user->getId(), $_SESSION['chosenAlbumid']);
    } else {
        $album = Album::getAlbumById($user->getId(), '1');
    }
    $pictures = Picture::getPictures($album->getId());


    $selectedPicture = Picture::getPictureById($pictures, $pic_id);
    if ($selectedPicture != null) {
        $_SESSION['selectedpic'] = $selectedPicture;
        $comments = Comment::getComments($selectedPicture->getID());
    }

}


if (isset($submit) && isset($_POST['Comments'])) {
    $text = $_POST['Comments'];
    Comment::addComment($user->getID(), $selectedPicture->getID(), $text);
}
?>
<h2> <?php echo $_SESSION['selectedpic']->getTitle() ?></h2>
<div id="imagecontainer">
    <p style="float: left;"><img style="width:500px;height:400px" src="<?php echo $_SESSION['selectedpic']->getOriginalFilePath($user->getId()); ?>" ></p>
    <h4>Description:</h4>
    <p> <?php echo $_SESSION['selectedpic']->getDescription() ?></p>
    <h4>Comments:</h4>
    <?php
    if ($comments != null) {
        foreach ($comments as $comment) {
            $show .= $comment->getText();
            echo "$show </br>";
        }
    }
    ?>
    <div class="form-group">
        <label class="col-md-4 control-label" for="Comments">Add Comment:</label>  
        <div class="col-md-5">
            <input id="Comments" name="Comments" type="text" value="" placeholder="Comment" class="form-control input-lg"> 
            </br>
            <input type='submit' class="btn btn-primary"  class='button' name='submit' value='submit'/>
        </div>

    </div>


</div>




