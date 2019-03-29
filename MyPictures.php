<?php
require(dirname(__FILE__) . "/Common/ClassesAndFunctions.php");
require(dirname(__FILE__) . "/Common/ConstantsAndSettings.php");
include 'Common/Header.php"';
#include 'Common/Footer.php"';
// Start Session
session_start();

// It was set as name in 1-5. session should be set to the USER class. 
$user = $_SESSION['user'];
//$albums = Album::getAlbums($user->getId());

if (!isset($user)) {
    $_SESSION["from"] = "MyPictures.php";
    header("Location:Login.php");
}

if (isset($_POST["albumsel"])) {
    $album_id = $_POST["albumsel"];
    $album = Album::getAlbumById($user->getId(), $album_id);
} else {
    $album = Album::getAlbumById($user->getId(), '7');
}
$pictures = Picture::getPictures($album->getId());
$selectedPicture = $pictures[0];
$comments = Comment::getComments($selectedPicture->getID());


if (isset($submit) && isset($_POST['Comments'])) {
    $text = $_POST['Comments'];
    Comment::addComment($user->getID(), $selectedPicture->getID(), $text);
}

if (isset($_POST['right'])) {
    rotateImage($selectedPicture->getOriginalFilePath($user->getId()), 90);
    header("Location:Mypictures.php");
    exit();
}

if (isset($_POST['left'])) {
    rotateImage($selectedPicture->getOriginalFilePath($user->getId()), -90);
    header("Location:Mypictures.php");
    exit();
}

if (isset($_POST['deleteLink'])) {
    deletePic($selectedPicture->getOriginalFilePath($user->getId()));
    header("Location:Mypictures.php");
    exit();
}


if (isset($_POST['download'])) {
    downloadFile($selectedPicture->getOriginalFilePath($user->getId()));
}

function fill_table() {
    $output = '';
    $user = $_SESSION['user'];
    //$albums = Album::getAlbums($user->getId());
    $album = Album::getAlbumById($user->getId(), '7');
    $pictures = Picture::getPictures($album->getId());
    foreach ($pictures as $pic) {
        $output.= "<a data-refresh-img href='" . $pic->getOriginalFilePath($user->getId()) . "'> <img style='width:120px;height:120px' "
                . "src='" . $pic->getThumbnailFilePath($user->getId()) . "'"
                . "name='" . $pic->getFileName() . "'"
                . "id='" . $pic->getID() . "'></a>";
    }

    return $output;
}
?>


<html>
    <head>
        <title>Online Course Registration</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <form action="MyPictures.php" method="post">
            <br/>
            <div class="col-md-3">
                <select class="form-control" id="albumsel" name="albumsel">
                    <?php
                    echo Picture::fill_drp($user);
                    ?>
                </select> 
            </div> 
            <div class="container">
                <h1 id="title"></h1>
                <div class="album">
                    <img id="image" src="" name="albumImage" class="normal">
                    <button id="right" name="right" onclick="" type="submit" class="btn btn-info btn-lg"><span id="glyphicon-right" class="glyphicon glyphicon-repeat gly-flip-horizontal "></span></button>   
                    <button id="left" name="left" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-repeat gly-flip-horizontal-left"></span></button>                   
                    <button  id="download" name="download" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-download-alt"></span></button>
                    <button  id="deleteLink " name="deleteLink" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-trash"></span></button>

                </div>
            </div>

            <div id="contentcontainer" class="contentcontainer">
                <h2> <?php echo $selectedPicture->getTitle() ?></h2>
                <div id="imagecontainer">
                    <p style="float: left;"><img style="width:500px;height:400px" src="<?php echo $selectedPicture->getOriginalFilePath($user->getId()); ?>" ></p>
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
                <div style="clear: left" id="tablecontainer">
                    <table border="1" data-toggle="table" class="table" id="table">
                        <thead class="thead-light">
                            <?php
                            echo fill_table();
                            ?>
                    </table>
                </div>
            </div>


            <script>
                $(document).ready(function () {
                    $('#albumsel').change(function () {
                        var chosenAlbid = $(this).val();
                        $.ajax({
                            url: "Refresh.php",
                            method: "POST",
                            data: {chosenAlbid: chosenAlbid},
                            success: function (data) {
                                $('#contentcontainer').html(data);
                            }
                        });
                    });
                });
            </script> 


            <script>
                $(document).ready(function () {

                    $('img').click(function () {
                        var picsID = $(this).attr("id");
                        console.log(picsID)
                        $.ajax({
                            url: "RefreshImage.php",
                            method: "POST",
                            data: {picsID: picsID},
                            success: function (data) {
                                $('#imagecontainer').html(data);
                            }
                        });
                    });
                    $('a[data-refresh-img]').click(function (e) {
                        e.preventDefault(); // block href redirect
                        $('#imagecontainer img').attr('src', $(this).attr('href'));
                    });
                });
            </script>
            <br/>
        </form>

    </body>

</html>