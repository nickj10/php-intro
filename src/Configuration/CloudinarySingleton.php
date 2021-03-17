<?php
declare(strict_types=1);

namespace SallePW\SlimApp\Configuration;

use Cloudinary\Cloudinary;

final class CloudinarySingleton
{

    private static ?CloudinarySingleton $instance = null;

    private Cloudinary $cld;

    private function __construct(
        string $my_cloud_name,
        string $my_key,
        string $my_secret
    ) {
        $this->cld = new Cloudinary([
            'cloud' => [
                'cloud_name' => $my_cloud_name,
                'api_key' => $my_key,
                'api_secret' => $my_secret
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    public static function getInstance(
        string $my_cloud_name,
        string $my_key,
        string $my_secret
    ): CloudinarySingleton {
        if (self::$instance === null) {
            self::$instance = new self(
                $my_cloud_name,
                $my_key,
                $my_secret
            );
        }

        return self::$instance;
    }

    public function getCloudinary(): Cloudinary
    {
        return $this->cld;
    }
}
