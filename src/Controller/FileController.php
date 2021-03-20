<?php

declare(strict_types=1);

namespace SallePW\SlimApp\Controller;

use Psr\Http\Message\UploadedFileInterface;
use Slim\Views\Twig;
use Ramsey\Uuid\Uuid;
use Intervention\Image\ImageManagerStatic as Image;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\SlimApp\Service\CloudinaryService;

final class FileController
{
    private const UPLOADS_DIR = __DIR__ . '/../../uploads/';

    private const UNEXPECTED_ERROR = "An unexpected error occurred uploading the file '%s'...";

    private const INVALID_EXTENSION_ERROR = "The received file extension '%s' is not valid";

    // We use this const to define the extensions that we are going to allow
    private const ALLOWED_EXTENSIONS = ['jpg', 'png', 'pdf'];

    public function __construct(
        private Twig $twig,
        private CloudinaryService $cld
    ) {
        Image::configure(array('driver' => 'imagick'));
    }

    public function showFileFormAction(Request $request, Response $response): Response
    {
        return $this->twig->render(
            $response,
            'upload.twig',
            []
        );
    }

    public function uploadFileAction(Request $request, Response $response): Response
    {
        $uploadedFiles = $request->getUploadedFiles();

        $errors = [];

        /** @var UploadedFileInterface $uploadedFile */
        foreach ($uploadedFiles['files'] as $uploadedFile) {
            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(
                    self::UNEXPECTED_ERROR,
                    $uploadedFile->getClientFilename()
                );
                continue;
            }

            $name = $uploadedFile->getClientFilename();

            $fileInfo = pathinfo($name);

            $format = $fileInfo['extension'];

            if (!$this->isValidFormat($format)) {
                $errors[] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
                continue;
            }

            $myuuid = Uuid::uuid4();
            $newFilename = $myuuid->toString() . "." . $format;
            // We generate a custom name here instead of using the one coming from the form
            $uploadedFile->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $newFilename);

            // open an image file
            $img = Image::make(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $newFilename);

            // resize image instance
            $img->resize(500, 500);

            // save image in desired format
            $img->save(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $newFilename);
            // $photoUrl = $this->cld->modifyPhoto($uploadedFile, $myuuid);
            // $file_name = basename($photoUrl);
            // if (file_put_contents(self::UPLOADS_DIR . $file_name, file_get_contents($photoUrl))) {
            //     echo "File downloaded successfully";
            // } else {
            //     echo "File downloading failed.";
            // }
        }

        return $this->twig->render($response, 'upload.twig', [
            'errors' => $errors,
        ]);
    }

    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }
}
