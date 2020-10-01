<?php

/**
 * Checks if file is an image
 *
 * @param string $error
 *                     Current error message
 * @return string
 *               New error message
 */
function checkForImage(string $error) :string {
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if (!$error && $check === false) {
            $error = 'The file is not an image, please try again.';
        }
    return $error;
}

/**
 * Checks if filename already exists
 *
 * @param string $error
 *                     Current error message
 * @param string $targetFile
 *                          Destination filename
 *
 * @return string
 *               New error message
 */
function checkNewImage(string $error, string $targetFile) :string {
    if (!$error && file_exists($targetFile)) {
        $error = 'This filename already exists, please rename and try again.';
    }
    return $error;
}

/**
 * Checks file size is within limits
 *
 * @param string $error
 *                     Current error message
 *
 * @return string
 *               New error message
 */
function checkFileSize(string $error) :string {
    if (!$error && $_FILES['photo']['size'] > 500000) {
        $error = 'The image needs to be smaller than 500kb.';
    }
    return $error;
}

/**
 * Checks format of file
 *
 * @param string $error
 *                     Current error message
 * @param string $fileType
 *                        Uploaded file type
 *
 * @return string
 *               New error message
 */
function checkFileType(string $error, string $fileType) :string {
    if (!$error && $fileType != 'jpg' && $fileType != 'png' && $fileType != 'jpeg'
        && $fileType != 'gif') {
        $error = 'Only JPG, JPEG, PNG & GIF iamge files are allowed.';
    }
    return $error;
}

/**
 * Upload file if no errors
 *
 * @param string $error
 *                     Current error message
 * @param string $target
 *                      Target file
 *
 * @return string
 *               New error message
 */
function uploadFile(string $error, string $target) :string {
    if (!$error) {
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $error = 'Sorry, there was an error uploading your file, please try again.';
        }
    }
    return $error;
}