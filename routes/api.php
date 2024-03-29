<?php

use Illuminate\Http\Request;

Route::prefix('v1')->namespace('Api\v1')->group(function() {

    $this->post('start', 'AuthController@start');
    $this->post('verify/{phoneNumber}', 'AuthController@verify');
    $this->get('recode/{phoneNumber}', 'AuthController@recode');
    $this->get('products/{type}', 'ProductController@index');
    $this->get('sub-products/{productId}', 'SubProductController@index');
    $this->get('sub-products/{subProductId}/show', 'SubProductController@show');

    $this->middleware('jwt.auth')->group(function() {

        $this->post('check-password', 'AuthController@checkPassword');
        $this->post('register', 'AuthController@register');
        $this->get('user', 'AuthController@user');
    });
});