<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\v1\SubProductCollection;
use App\SubProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubProductController extends Controller {


    public function index($productId) {

        $subProducts = SubProduct::where('product_id', $productId)->whereApproved(1)->where('parent_id', null)->latest()->paginate(6);

        return new SubProductCollection($subProducts);
    }

    public function show($subProductId) {

        $subProduct = SubProduct::where('id', $subProductId)->whereApproved(1)->where('parent_id', null)->first();

        return new \App\Http\Resources\v1\SubProduct($subProduct);
    }
}
