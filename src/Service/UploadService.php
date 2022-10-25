<?php

class UploadService {

    public function upload($file, $directory)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->getParameter("updload_directory") . $directory, $fileName);
        return $fileName;
    }

    public function removeFile(string $file)
    {
        $file_path = $this->getParameter('images_directory') . '/' . $file;
        if (file_exists($file_path)) unlink($file_path);
    }
}