<?php

namespace Model;

use Binemmanuel\ServeMyPhp\BaseModel;

class Product extends BaseModel
{
    public ?String $id;
    public ?String $productId;
    public ?String $name;
    public ?float $price;
    public ?String $currency;
    public ?String $updatedOn;
    public ?String $createdOn;

    private static array $rules;

    protected function __setTable(): string
    {
        return 'products';
    }

    public function makeRules(array $rules): void
    {
        self::$rules = $rules;
    }

    protected function rules(): array
    {
        return self::$rules;
    }
}
