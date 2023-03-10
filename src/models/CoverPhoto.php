<?php

namespace Model;

use Binemmanuel\ServeMyPhp\BaseModel;

class CoverPhoto extends File
{
    public ?String $coverPhotoId;
    public ?String $url;
    public ?String $uploadedBy;
    public ?String $madeCoverPhotoOn;

    private static array $rules;

    protected function __setTable(): string
    {
        return 'cover_photos';
    }

    protected function rules(): array
    {
        return self::$rules;
    }

    public function makeRules(array $rules): void
    {
        self::$rules = $rules;
    }

    public function setCoverPhoto(String $uploadedBy, array $file): array
    {
        $this->coverPhotoId = $this->_id;

        $file = $this->loadData(['url' => strtolower($file['url']), 'uploadedBy' => $uploadedBy])->save();
        $file = (new File(self::$database))->find(['url' => $file['url']]);

        return  $file;
    }

    public function save(): array
    {
        $this->coverPhotoId = $this->_id;

        return parent::save();
    }
}
