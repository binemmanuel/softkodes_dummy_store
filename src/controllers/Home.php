<?php

namespace Controller;

use Binemmanuel\ServeMyPhp\BaseController;
use Binemmanuel\ServeMyPhp\Request;
use Binemmanuel\ServeMyPhp\Response;

class Home extends BaseController
{
    public function index(Request $request, Response $response)
    {
        return $response::sendJson(['message' => 'Welcome']);
    }
}
