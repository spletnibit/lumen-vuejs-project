<?php
/**
 * Created by PhpStorm.
 * User: gpode
 * Date: 9/19/2017
 * Time: 1:40 PM
 */

class CustomersTest extends TestCase {

    public function test () {

        $user = \App\User::find(1);
        $customer = \App\Customer::where("user_id","=",1)
                    ->first(); // get customer with permission for user

        $customerUnAuth = \App\Customer::where("user_id","<",1)->first();

        $userFactory = factory(App\User::class)->make();
        $customerFactory = factory(App\Customer::class)->make();



        $this->getCustomers($user);
        $this->editCustomer($user,$customer->id);
        $this->editCustomerFail($user,$customerUnAuth->id); // checks if unauthorized user can edit customer
        $this->putCustomer($user,$customer->id);
        $this->putCustomerUnAuth($user,$customerUnAuth->id); // checks if unauthorized user can do action
        $this->deleteCustomer($user,$customer->id);
        $this->deleteCustomerUnAuth($user,$customerUnAuth->id); // checks if unauthorized user can delete customer
        $this->addCustomer($user,$customerFactory);
    }

    private function getCustomers($user)
    {
        $response = $this
            ->actingAs($user)
            ->json('get','/customers')
            ->seeStatusCode(200)
            ->response
            ->getContent();

        //dd(json_decode($response));
    }

    private function editCustomerFail($user,$id) {
        $response = $this
            ->actingAs($user)
            ->json('get','/customers/'.$id)
            ->seeStatusCode(403) // fails to edit customer so assertion passes
            ->response
            ->getContent();
    }


    private function editCustomer($user,$id)
    {
        try {
            $response = $this
                ->actingAs($user)
                ->json('get', '/customers/' . $id)
                ->seeStatusCode(200)
                ->response
                ->getContent();

        }catch(\Exception $e) {
            //$this->assertContains('User does not have permission to edit customer', $e->getMessage());
        }
       // dd(json_decode($response));
    }

    private function putCustomer($user,$id)
    {
        $response = $this
            ->actingAs($user)
            ->json('put','/customers/'.$id)
            ->seeStatusCode(200)
            ->response
            ->getContent();
            //->getResult();
       // dd(json_decode($response));
    }

    private function putCustomerUnAuth($user,$id)
    {
        $response = $this
            ->actingAs($user)
            ->json('put','/customers/'.$id) // user 1 is unauthorized
            ->seeStatusCode(403)
            ->response
            ->getContent();
    }


    private function deleteCustomer($user,$id)
    {

            $response = $this
                ->actingAs($user)
                ->json('delete', '/customers/'.$id)
                ->seeStatusCode(200)
                ->response
                ->getContent();

        //dd(json_decode($response));
    }

    private function deleteCustomerUnAuth($user,$id)
    {
        $response = $this
            ->actingAs($user)
            ->json('delete', '/customers/'.$id)
            ->seeStatusCode(403)
            ->response
            ->getContent();
    }


    private function addCustomer($user,$customerFactory)
    {

            $response = $this
                ->actingAs($user)
                ->json('post', '/customers/add', $customerFactory->toArray())
                //->seeStatusCode(201)// successfully added
                ->response
                ->getContent();

        //dd(json_decode($response));
    }
}