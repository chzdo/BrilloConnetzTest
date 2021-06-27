<?php

use Models\movies; //movies movies

include('header.php'); //including header file


$movies = new movies(); //class movies

$genre = $movies->getAllGenre(); //function to get all genres

$movies_array = [];

/**
 * @param q 
 * 
 * this params is for checking if the page will process editing of movies or new upload
 * if set then the page will process edit
 */


if (isset($_GET['q']) && !empty($_GET['q'])) {

    if (!$movies->find(base64_decode($_GET['q']))) { //check if movie exist

        $_SESSION['msg'] = ['code' => 400, 'message' => "Movie not found"];
    } else {

        $movies_info = $movies->get();          // function to get movies
        $temp = [];


        foreach ($movies_info as  $value) 
        {
            $temp[] = $value['genre_id'];   // arranging genres of the movie
        }

       
        $movies_array = $movies_info[0];
        $movies_array['genre'] = $temp;
    }
}





?>


<!--
Menu section of the page
-->

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>
<!--
End Menu section of the page
-->
<hr />


<?php

include('../alert.php');


//check if param q exist  and movie exist
if (isset($_GET['q']) && count(@$movies_array) == 0) {
    return;
}
?>

<!--
form  for upload  of movies
-->
<form class="form-signin" action="../controllers/movies.php" method="post" enctype="multipart/form-data">

    <h1 class="h3 mb-3 font-weight-normal">Upload Movie</h1>
    <label  class="sr-only">Movie Title</label>

    <input type="text" class="form-control" name="name" value='<?= @$movies_array['name']    ?>' placeholder="Movie Title" required>
    <label class="sr-only">Price</label>

    <input type="number"  class="form-control" name="amount" value='<?= @$movies_array['amount']    ?>' placeholder="Price" required autofocus>
    <label class="sr-only">Description</label>
    <textarea  class="form-control" placeholder="Description" name="description" required><?= @$movies_array['description'] ?></textarea>
    <label  class="sr-only">Cover Image</label>
    <input type="file"  class="form-control" name="cover" placeholder="Price">
    <label  class="sr-only">Video</label>
    <input type="file" id="inputEmail" class="form-control" name="video" placeholder="Price">

    <?php
    $type = count(@$movies_array) > 0 ? 'edit' : 'upload';
    if ($type == 'edit') { ?>
        <input type="hidden" name="id" value="<?= @$movies_array['id']     ?>" />

        <div class="row">
            <div class="col-6">
                <img src="../<?= @$movies_array['image_location']   ?>" width="75" height="75" />
            </div>
            <div class="col-6">
                <video controls src="../<?= @$movies_array['file_location']   ?>" width="75" height="75">
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="<?= $type  ?>" />
    <?php

    foreach ($genre as $g) {
        $checked = count(@$movies_array) > 0 ? @in_array($g['id'], @$movies_array['genre']) : false;
    ?>

        <?= $g['name']  ?>: <input type="checkbox" name="genre[]" value="<?= $g['id'] ?>" <?= $checked ? 'checked' : "" ?> />
    <?php
    }
    ?>
    <br>
    <button class="btn  btn-primary " type="submit">save</button>

</form>


<!-- 

end of form for upload of movies

-->








<?php include('footer.php')  ?>