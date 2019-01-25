<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\v1\ProductCollection;
use App\Product;
use App\Http\Controllers\Controller;

class ProductController extends Controller {


    public function index($type) {

        $products = Product::whereApproved(1)->where('type', $type)->latest()->paginate(6);
        return new ProductCollection($products);
    }
}
