<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class UploadService {
    private ContainerBagInterface $container;
    private Filesystem $fileSystem;

    public function __construct(ContainerBagInterface $container, Filesystem $fileSystem)
    {
        $this->container = $container;
        $this->fileSystem = $fileSystem;
    }

    public function upload($file, $directory)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->container->get("updload_directory") . $directory, $fileName);
        return $fileName;
    }

    public function removeFile(string $file)
    {
        $file_path = $this->container->get('images_directory') . '/' . $file;
        if (file_exists($file_path)) unlink($file_path);
    }
}