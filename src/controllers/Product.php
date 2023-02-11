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

        return $response::sendJson(
            $products,
            allowMethods: 'GET',
            allowHeader: 'Authorization, Content-Type, x-auth-token',
        );
    }
}
