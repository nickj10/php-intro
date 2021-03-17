<?php

declare(strict_types=1);

namespace SallePW\SlimApp\Service;

use Cloudinary\Cloudinary;
use Slim\Views\Twig;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Api\Upload\UploadApi;
use SallePW\SlimApp\Configuration\CloudinarySingleton;
use Psr\Http\Message\UploadedFileInterface;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class CloudinaryService
{
    private const UPLOADS_DIR = '../uploads/';
    private const UNEXPECTED_ERROR = "An unexpected error occurred uploading the file '%s'...";
    private const INVALID_EXTENSION_ERROR = "The received file extension '%s' is not valid (Only .png files)";
    private const INVALID_SIZE_ERROR = "Image size exceeds 1MB.";
    private const INVALID_DIMENSION_ERROR = "Image dimension should be within 500X500";
    private const ALLOWED_EXTENSION = 'png';
    private const ALLOWED_DIMENSION = '500';
    private const ALLOWED_SIZE = '1000000';

    private $errors = array();

    private CloudinarySingleton $cld;


    // We use this const to define the extensions that we are going to allow
    private const ALLOWED_EXTENSIONS = ['jpg', 'png', 'pdf'];

    public function __construct(CloudinarySingleton $cld)
    {
        $this->cld = $cld;
    }

    public function modifyPhoto($uploadedFile, $filename)
    {

        $file = $uploadedFile;
        $name = $file->getClientFilename();
        $fileInfo = pathinfo($name);
        $format = strtolower($fileInfo['extension']);
        $size = $file->getSize();
        $uploadResult = $this->cld->getCloudinary()->uploadApi()->upload(
            self::UPLOADS_DIR . $filename . "." . $format,
            ["public_id" => $filename, "transformation" => [
                ["width" => 500, "height" => 500, "crop" => "crop"]
            ]]
        );
        var_dump($uploadResult['width']);
        if ($uploadResult) {
            return $uploadResult['url'];
        }

        //list($width, $height) = getimagesize($this::UPLOADS_DIR . $filename . "." . $format);

        //Checks if extension is valid
        // if ($format != self::ALLOWED_EXTENSION) {
        //     $this->errors['extension'] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
        // } else {
        //     //Checks if the size is valid
        //     if ($size >= self::ALLOWED_SIZE) {
        //         $this->errors['size'] = self::INVALID_SIZE_ERROR;
        //     } else {
        //         $basename = bin2hex(random_bytes(8));
        //         $filename = sprintf('%s.%0.8s', $basename, $format);

        //         if ($format == self::ALLOWED_EXTENSION && $size <= self::ALLOWED_SIZE) {
        //             //If dimension is too big, we crop the image to 400x400
        //             if ($width > self::ALLOWED_DIMENSION || $height > self::ALLOWED_DIMENSION) {
        //                 $this->cld->getCloudinary()->uploadApi()->upload(
        //                     self::UPLOADS_DIR . $filename . "." . $format,
        //                     ["public_id" => $filename,"width" => 400, "height" => 400, "crop" => "limit"]
        //                 );
        //                 return $filename;
        //             } else {
        //                 //If dimension is fine we generate a unique name and store it to a folder
        //                 $file->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $filename);
        //                 return $filename;
        //             }
        //         }
        //     }
        // }
        return $this->errors['file_upload_error'] = $uploadResult;
    }
}
