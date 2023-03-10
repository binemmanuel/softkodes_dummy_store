<?php

namespace Model;

use Binemmanuel\ServeMyPhp\BaseModel;

enum FileType
{
    case jpeg;
    case jpg;
    case png;
    case gif;
    case mov;
    case mkv;
    case mp4;
}

class File extends BaseModel
{
    public ?String $name;
    public ?String $url;
    public ?String $caption;
    public ?String $altText;
    public ?String $description;
    public ?String $type;
    public ?String $uploadedBy;
    public ?String $uploadedOn;

    private static array $rules;

    protected function __setTable(): string
    {
        return 'library';
    }

    protected function rules(): array
    {
        return self::$rules;
    }

    public function makeRules(array $rules): void
    {
        self::$rules = $rules;
    }

    /**
     * Check if a file is valid
     * 
     * @param String $fileType The file type
     * @return Bool false | true if the file is a valid one.
     */
    public static function isValid(string $fileType): bool
    {
        return in_array(
            strtolower($fileType),
            [
                FileType::jpeg->name,
                FileType::jpg->name,
                FileType::png->name,
                FileType::gif->name,
            ]
        );
    }

    private static function getFileType(String $fileType)
    {
        return explode('/', $fileType)[0];
    }

    public function uploadFile(array $file, string $uploadedBy): array
    {
        $targetDir = UPLOADS_DIR;
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $targetFile = 'dev_test' . bin2hex(random_bytes(8))  . ".$fileExtension";
        $fileType = self::getFileType($file['type']);

        switch ($fileType) {
            case 'video':
                // Do video processing here
                $targetDir .= 'videos/';

                break;

            case 'image':
                // Do image processing here
                $targetDir .= 'images/';

                break;

            case 'application':
                // Do image processing here
                $targetDir .= 'applications/';

                break;
        }

        $targetDir = "$targetDir$targetFile";

        if (!move_uploaded_file($file['tmp_name'], $targetDir)) return [];

        return ($this->loadData([
            'url' => "/uploads/${fileType}s/$targetFile",
            'type' => $file['type'],
            'uploadedBy' => $uploadedBy,
        ]))->save();
    }
}
