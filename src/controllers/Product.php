<?php

namespace Controller;

use Binemmanuel\ServeMyPhp\{
    BaseController,
    Request,
    Response,
    Rule,
};

use Model\{
    Product as ProductModel,
    CoverPhoto,
    File,
};

class Product extends BaseController
{
    public function getAll(Request $request, Response $response)
    {
        $products = (new ProductModel($this->db))->fetchAll();

        return $response::sendJson(
            $products,
            allowMethods: 'GET',
            allowHeader: 'Authorization, Content-Type, x-auth-token',
        );
    }

    public function get(Request $request, Response $response)
    {
        $product = (new ProductModel($this->db))->loadData($request->jsonBody());

        $product->makeRules([
            'productId' => [Rule::REQUIRED],
        ]);

        if ($product->hasError()) {
            return $response::sendJson([
                'error' => true,
                'errors' => $product->errors(),
            ], statusCode: 400);
        }

        $products = $product->findAll(['productId' => $product->productId]);

        return $response::sendJson($products);
    }

    public function add(Request $request, Response $response)
    {


        // return $response::sendJson($request->jsonBody());
        $product = (new ProductModel($this->db))->loadData($request->jsonBody());

        $product->makeRules([
            'name' => [Rule::REQUIRED],
            'price' => [Rule::REQUIRED, Rule::NUMBER],
            'image' => [Rule::REQUIRED],
            'description' => [Rule::REQUIRED, [Rule::MIN_LENGTH, 5]],
        ]);

        if ($product->hasError()) {
            return $response::sendJson([
                'error' => true,
                'errors' => $product->errors(),
            ], statusCode: 400);
        }

        $product = $product->save();

        return $response::sendJson($product);
    }

    public function addProductImage(Request $request, Response $response)
    {
        $file = $request->file[0] ?? [];
        $uploadedBy = $request->uploadedBy ?? '';

        if (empty($file)) {
            return $response->sendJson([
                'error' => true,
                'message' => 'No file selected for upload',
            ], statusCode: 400);
        }

        if (empty($uploadedBy)) {
            return $response->sendJson([
                'error' => true,
                'type' => 'uploadedBy',
                'message' => 'This is required field',
            ], statusCode: 400);
        }

        // Check if the file a valid one
        if (!File::isValid(pathinfo($file['name'], PATHINFO_EXTENSION))) {
            return $response->sendJson([
                'error' => true,
                'message' => 'Invalid file type',
                'file' => $file,
            ], statusCode: 400);

            // Check if the file has a valid size
        } else if ($file['size'] === 0) {
            return $response->sendJson([
                'error' => true,
                'message' => 'File too large or not valid',
                'file' => $file,
            ], statusCode: 400);
        }

        $file = (new File($this->db))->uploadFile($file, uploadedBy: $uploadedBy);

        $coverPhoto = (new CoverPhoto($this->db))->loadData([
            'url' => $file['url'] ?? '',
            'uploadedBy' => $uploadedBy,
        ]);

        $hasCover = $coverPhoto->find(['uploadedBy' => $uploadedBy]);

        if (!empty($hasCover)) {
            $coverPhoto->update(['uploadedBy' => $uploadedBy]);

            return $response->sendJson([
                'error' => false,
                'file' => $file,
                'message' => 'Updated successfully',
            ]);
        }

        $coverPhoto->save();

        return $response->sendJson([
            'error' => false,
            'file' => $file,
            'message' => 'Uploaded successfully',
        ]);
    }
}
