<?php

namespace controllers;

require_once('../autoloaders.php');


use Models\movies as ModelsMovies;
use validators\movies;


const video_folder = "videos/";
const img_folder = "cover/";


unset($_SESSION['msg']);


try {

/**
 * @param admin
 * check if admin is logged in before accessing the routes
 */
    if (!isset($_SESSION['admin'])) {

        header('location: ../admin/login.php');
    }


    /**
     * @param upload
     * Route for upload of movies
     */
    if (isset($_POST['upload'])) {

        unset($_POST['upload']);  //unset form key

        $validate = movies::validate($_POST); //Validate Input

        //Check if validation fails
        if (count($validate) > 0) {


            $_SESSION['msg'] = ['code' => 400, "message" => array_values($validate)[0][0]];
             
            header('location: ../admin/upload.php');

            return;
        }

        /**
         * Extract FILES image and video
         */
        $video = $_FILES['video'];
        $cover = $_FILES['cover'];

        //split the name of the videos 
        $split_video_name = explode('.', $video['name']);
        //merge folder and movie name to get file path
        $video_location = video_folder . $_POST['name'] . '.' . $split_video_name[count($split_video_name) - 1];

        //splitting file name to get ext and merging with folder to get cover path
        $split_image_name = explode('.', $cover['name']);
        $img_location = img_folder . $_POST['name'] . "." . $split_image_name[count($split_image_name) - 1];

        //create video folder if it is not existing
        if (!is_dir("../" . video_folder)) {
            mkdir("../" . video_folder);
        }

        //create cover folder if it is not existing
        if (!is_dir("../" . img_folder)) {
            mkdir("../" . img_folder);
        }
       
        //uploding both image and video
        $video_upload_progress = move_uploaded_file($video['tmp_name'], "../" . $video_location);
        $img_upload_progress = move_uploaded_file($cover['tmp_name'], "../" . $img_location);


        //check if upload was successfuly
        if ($video_upload_progress && $img_upload_progress) {
            
            //setting up  input values
            $post = $_POST;
            $post['image_location'] = $img_location;
            $post['file_location'] = $video_location;
            $post['size'] = $video['size'];

            //Creating a new movie using upload function
            if ((new ModelsMovies())->upload($post)) {
                $_SESSION['msg'] = ['code' => 201, "message" => "Created"];
            }
        }else{
            $_SESSION['msg'] = ['code' => 500, "message" => "Could not upload files"];
        }

      
        header('location: ../admin/upload.php');
        return;
    }






   /**
     * @param id
     *  To access the routes below movie id should be part of te request
     */

     
    if (!isset($_POST['id'])) {
        $_SESSION['msg'] = ['code' => 422, 'message' => 'Id is required'];
        header("location:  " . $_SERVER['HTTP_REFERER']);
        return;
    }

    $movies = new ModelsMovies();  //create a movie object

    //checking if id is a valid movie
    if (!$movies->find($_POST['id'])) {
        $_SESSION['msg'] = ['code' => 404, 'message' => 'Movie not found'];
        header("location:  " . $_SERVER['HTTP_REFERER']);
        return;
    }


    /**
     * @param delete
     * Route for deleting a film
     */

    if (isset($_POST['delete'])) { //Case Login Controller

        unset($_POST['delete']);


         //calling the delete movie function, if false return message
        if (!$movies->delete()) {
            $_SESSION['msg'] = ['code' => 404, 'message' => 'Could not delete movie'];
            header("location:  ../admin/movies.php");
            return;
        }


        $_SESSION['msg'] = ['code' => 200, "message" => "deleted"]; //Returning to view
        header("location:  ../admin/movies.php");
        return;
    }


    /**
     * @param edit
     * Route for editing movie
     */


    if (isset($_POST['edit'])) {

        unset($_POST['edit']);



        $post = $_POST;
        $validate = movies::edit($post); //Validate Input

        //Check if validation fails
        if (count($validate) > 0) {

            $_SESSION['msg'] = ['code' => 400, "message" => array_values($validate)[0][0]];
             
            header('location: ../admin/upload.php');

            return;
        }

         $movies->get(); //getting details of the movie ! function must be called after find() function
       
         /**
          * Checking if the movie name was changed in other to change the file names for image and viseo
          */
        if (isset($post['name']) &&  ($post['name'] != $movies->result[0]['name'])) {

            $img_ext = explode(".", $movies->result[0]['image_location'])[1];
            $file_ext = explode(".", $movies->result[0]['file_location'])[1];

            $new_image_location = img_folder . $post['name'] . "." . $img_ext;
            $new_file_location = video_folder . $post['name'] . "." . $file_ext;

            rename("../" . $movies->result[0]['image_location'], "../" . $new_image_location);
            rename("../" . $movies->result[0]['file_location'], "../" . $new_file_location);

            $post['file_location'] = $new_file_location;
            $post['image_location'] = $new_image_location;
        }


        //checking if video is part of the request to upload
        if (isset($_FILES['video']) ) {
            $video = $_FILES['video'];
            $split_video_name = explode('.', $video['name']);
            $video_location = video_folder . $_POST['name'] . '.' . $split_video_name[count($split_video_name) - 1];
            $video_upload_progress = move_uploaded_file($video['tmp_name'], "../" . $video_location);
            if (!$video_upload_progress) {
                $_SESSION['msg'] = ['code' => 204, "message" => "could not upload video"];
                header("location:  " . $_SERVER['HTTP_REFERER']);
                return;
            }
            $post['file_location'] = $video_location;
            $post['size'] = $video['size'];
        }
      
         //checking if cover is part of the request to upload
        if (isset($_FILES['cover'])) {
            $cover = $_FILES['cover'];
            $split_image_name = explode('.', $cover['name']);
            $img_location = img_folder . $_POST['name'] . "." . $split_image_name[count($split_image_name) - 1];

            $img_upload_progress = move_uploaded_file($cover['tmp_name'], "../" . $img_location);
            if (!$img_upload_progress) {
                $_SESSION['msg'] = ['code' => 204, "message" => "could not upload cover"];
                header("location:  " . $_SERVER['HTTP_REFERER']);
                return;
            }
            $post['image_location'] = $img_location;
        }

      //calling the editupload function
        if ($movies->editUpload($post)) {
            $_SESSION['msg'] = ['code' => 204, "message" => "Updated"];
        }
        header("location:  " . $_SERVER['HTTP_REFERER']); //returning to view
        return;
    }



    echo  "Invalid Request";
} catch (\Exception $e) {
    echo  $e->getMessage();
}
