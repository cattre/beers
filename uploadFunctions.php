<?php

// Check if image file is a actual image
function checkForImage(string $error) :string {
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if (!$error && $check === false) {
            $error = 'The file is not an image, please try again.';
        }
    return $error;
}

// Check if file already exists
function checkNewImage(string $error, string $targetFile) :string {
    if (!$error && file_exists($targetFile)) {
        $imageError = 'This filename already exists, please rename and try again.';
    }
    return $error;
}

// Check file size
function checkFileSize(string $error) :string {
    if (!$error && $_FILES['photo']['size'] > 500000) {
        $error = 'The image needs to be smaller than 500kb.';
    }
    return $error;
}

// Allow certain file formats
function checkFileType(string $error, string $fileType) :string {
    if (!$error && $fileType != 'jpg' && $fileType != 'png' && $fileType != 'jpeg'
        && $fileType != 'gif') {
        $imageError = 'Only JPG, JPEG, PNG & GIF iamge files are allowed.';
    }
    return $error;
}

// Check if $uploadOk is set to 0 by an error
function uploadFile(string $error, string $target) :string {
    if (!$error) {
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $error = 'Sorry, there was an error uploading your file, please try again.';
        }
    }
    return $error;
}