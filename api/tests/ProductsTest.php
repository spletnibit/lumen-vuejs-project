<?php
/**
 * Created by PhpStorm.
 * User: gpode
 * Date: 9/19/2017
 * Time: 1:40 PM
 */


class ProductsTest extends TestCase {

    public function test () {

        // get user with id 1
        $user = \App\User::findOrFail(1);
        //make factory product
        $productFactory = factory(App\Product::class)->make();
        //make factory productCategory
        $categoryFactory = factory(App\ProductCategory::class)->make();


        $product = \App\Product::where('user_id',"=","1")->inRandomOrder()->first();
        $userUnAuth = \App\User::findOrFail(2);


        $this->addProduct($user,$productFactory);
        $this->searchProducts($user);
        $this->getCategories($user);
        $this->addCategories($user,$categoryFactory);
        $this->editProduct($user,$product);
        $this->editProductUnAuth($userUnAuth,$product);
        $this->updateProduct($user,$product);
        $this->updateProductUnAuth($userUnAuth,$product);

    }

    private function addProduct($user, $product)
    {
        $response = $this
            ->actingAs($user)
            ->json('post','products/add',$product->toArray())
            ->seeStatusCode(201) // checks if request was fulfilled
            ->response
            ->getContent();

        //dd(json_decode($response));
    }

    private function searchProducts($user)
    {
        $response = $this
            ->actingAs($user)
            ->json('get','products/search',array())
            ->seeStatusCode(200) // checks if request was successful
            ->response
            ->getContent();

       // dd(json_decode($response));

    }

    private function getCategories($user)
    {
        $response =$this
            ->actingAs($user)
            ->json('get','products/categories',array())
            ->seeStatusCode(200) // checks if request was successful
            ->response
            ->getContent();

        //dd(json_decode($response));
    }

    private function addCategories($user, $category)
    {
        $response = $this
            ->actingAs($user)
            ->json('post','products/categories/add',$category->toArray())
            ->seeStatusCode(201) // checks if request was fulfilled
            ->response
            ->getContent();

        //dd(json_decode($response));
    }



    private function editProduct($user,$product)
    {
        $response = $this
            ->actingAs($user)
            ->json('get','products/'.$product->id)
            ->seeStatusCode(200)
            ->response
            ->getContent();

        //dd(json_decode($response));
    }


    private function editProductUnAuth($user,$product)
    {
        $response = $this
            ->actingAs($user)
            ->json('get','products/'.$product->id)
            ->seeStatusCode(403)
            ->response
            ->getContent();
    }


    private function updateProduct($user,$product)
    {
        $response = $this
            ->actingAs($user)
            ->json('put','products/'.$product->id)
            ->seeStatusCode(200)
            ->response
            ->getContent();
        //dd(json_decode($response));
    }

    private function updateProductUnAuth($user,$product)
    {
        $response = $this
            ->actingAs($user)
            ->json('put','products/'.$product->id)
            ->seeStatusCode(403)
            ->response
            ->getContent();
    }



}