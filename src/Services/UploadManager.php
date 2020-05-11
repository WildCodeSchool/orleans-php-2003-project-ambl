<?php


namespace App\Services;

class UploadManager
{

    const AUTHORIZED_FILE = ['image/jpeg', 'image/png'];

    /**
     * @var array
     */
    private $errors;

    /**
     * @var array
     */
    private $file;

    /**
     * @var int
     */
    private $fileMaxSize;

    /**
     * @var string
     */
    private $uploadDir;

    public function __construct(array $file, int $fileMaxSize, string $uploadDir)
    {
        $this->file = $file;
        $this->uploadDir = $uploadDir;
        $this->fileMaxSize = $fileMaxSize;
    }

    /**
     * Returns errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors ?? [];
    }

    /**
     * Check if the file is valid
     *
     */
    public function isValidate()
    {

        $mime = mime_content_type($this->file['tmp_name']);

        if (!in_array($mime, self::AUTHORIZED_FILE)) {
            $this->errors[] = "Le fichier doit être une image";
        }

        if ($this->file['size'] > $this->fileMaxSize) {
            $this->errors[] = "Le fichier doit être inférieur à " . ($this->fileMaxSize / 1000000) . ' Mo';
        }
    }

    /**
     * Upload the file and return the file name
     *
     * @return string
     */
    public function upload() : string
    {

        if (empty($this->errors)) {
            $tmpFilePath = $this->file['tmp_name'];
            $fileExtension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
            $fileName = uniqid() . '.' .$fileExtension;
            $filePath = $this->uploadDir . "/" . $fileName;

            if (!move_uploaded_file($tmpFilePath, $filePath)) {
                $fileName = '';
            }
        } else {
            $fileName = '';
        }

        return $fileName;
    }
}
