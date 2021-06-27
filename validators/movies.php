<?php

namespace validators;

use Models\movies as ModelsMovies;
use validators\Validators;

class movies implements Validators
{


    function __construct($post)
    {
        $this->post = $post;
    }

 /**
     * @param post contains values from $_POST
     */
    static function  validate($post)
    {


        $errors = [];
        if (!isset($post['name'])) {

            $errors['name'][] = 'Name is Required';
        }
        if (!isset($post['description'])) {

            $errors['description'][] = 'Description is Required';
        }

        if (!isset($post['genre'])) {
            $errors['genre'][] = 'Genre is Required';
        }

        if (!isset($_FILES['cover'])) {
            $errors['cover'][] = 'Image Cover is Required';
        }

        if (!isset($_FILES['video'])) {
            $errors['video'][] = 'Video  is Required';
        }
        if (count($errors) > 0) {
            return $errors;
        }


        if (empty($post['name'])) {

            $errors['name'][] = 'Name is Required';
        }

        if (empty($post['description'])) {

            $errors['description'][] = 'Description is Required';
        }


        $genre = $post['genre'];



        if (!is_array($genre)) {
            $errors['genre'][] = ' Invalid Genre. Must be an Array ';
            return $errors;
        }


        foreach ($genre as $value) {
            if (!(new ModelsMovies())->findGenre($value)) {
                $errors['genre'][] = 'Invalid Genre. Does  not exist ';
            }
        }
        $cover = $_FILES['cover'];
        $video = $_FILES['video'];




        if (!str_contains($cover['type'], "image/")) {
            $errors['cover'][] = 'Image Cover must be an Image File';
        }

        if (!str_contains($video['type'], "video/")) {
            $errors['video'][] = 'Video must be a Video File';
        }
        return $errors;
    }




    static function  edit($post)
    {


        $errors = [];
        if (isset($post['name'])) {

            if (empty($post['name'])) {

                $errors['name'][] = 'Name is Required';
            }
        }
        if (isset($post['description'])) {

            if (empty($post['description'])) {

                $errors['description'][] = 'Description is Required';
            }
        }

        if (isset($post['genre'])) {
            $genre = $post['genre'];



            if (!is_array($genre)) {
                $errors['genre'][] = ' Invalid Genre. Must be an Array ';
                return $errors;
            }


            foreach ($genre as $value) {
                if (!(new ModelsMovies())->findGenre($value)) {
                    $errors['genre'][] = 'Invalid Genre. Does  not exist ';
                }
            }
        }

  
        if (isset($_FILES['cover']) && !empty($_FILES['cover']['name'])) {
            $cover = $_FILES['cover'];
            if (!str_contains($cover['type'], "image/")) {
                $errors['cover'][] = 'Image Cover must be an Image File';
            }
        } else {
            unset($_FILES['cover']);
        }
      
        if (!isset($_FILES['video']) && !empty($_FILES['video']['name'])) {
            $video = $_FILES['video'];
            if (!str_contains($video['type'], "video/")) {
                $errors['video'][] = 'Video must be a Video File';
            }
        } else {
            unset($_FILES['video']);
        }











        return $errors;
    }
}
