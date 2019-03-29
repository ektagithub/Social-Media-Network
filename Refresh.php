<?php
require(dirname(__FILE__) . "/Common/ClassesAndFunctions.php");
require(dirname(__FILE__) . "/Common/ConstantsAndSettings.php");

session_start();
$user = $_SESSION['user'];

if (isset($_POST["chosenAlbid"])) {
    $user = $_SESSION['user'];
    $album_id = $_POST["chosenAlbid"];
    $_SESSION['chosenAlbumid'] = $album_id;
    $album = Album::getAlbumById($user->getId(), $album_id);
    $pictures = Picture::getPictures($album->getId());
    $selectedPicture = $pictures[0];
    $_SESSION['selectedpic'] = $selectedPicture;
    $comments = Comment::getComments($selectedPicture->getID());

    foreach ($pictures as $pic) {
        $output.= "<a data-refresh-img href='" . $pic->getOriginalFilePath($user->getId()) . "'> <img style='width:120px;height:120px' "
                . "src='" . $pic->getThumbnailFilePath($user->getId()) . "'"
                . "name='" . $pic->getFileName() . "' "
                . "id='" . $pic->getID() . "'></a>";
    }
}

if (isset($submit) && isset($_POST['Comments'])) {
    $text = $_POST['Comments'];
    var_dump($text);
    Comment::addComment($user->getID(), $selectedPicture->getID(), $text);
}
?>
<div id="contentcontainer" class="contentcontainer">
    <h2> <?php echo $selectedPicture->getTitle() ?></h2>
    <div id="imagecontainer">
        <p style="float: left;"><img style="width:500px;height:400px" src="<?php echo $_SESSION['selectedpic']->getOriginalFilePath($user->getId()); ?>" ></p>
        <h4>Description:</h4>
        <p> <?php echo $selectedPicture->getDescription() ?></p>
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
</div>


<div style="clear: left" id="tablecontainer">
    <table border="1" data-toggle="table" class="table" id="table">
        <thead class="thead-light">
            <?php
            echo $output;
            ?>
    </table>
    <script>
        $('a[data-refresh-img]').click(function (e) {
            e.preventDefault(); // block ahref redirect
            $('#imagecontainer img').attr('src', $(this).attr('href')); // replace image
            $('img').click(function () {
                var picsID = $(this).attr("id");
                console.log(picsID);
                $.ajax({
                    url: "RefreshImage.php",
                    method: "POST",
                    data: {picsID: picsID},
                    success: function (data) {
                        $('#imagecontainer').html(data);
                    }
                });
            });
        });
    </script>
</div>
</div>





