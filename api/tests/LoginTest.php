<?php
/**
 * Created by PhpStorm.
 * User: gpode
 * Date: 9/19/2017
 * Time: 10:29 AM
 */

class LoginTest extends TestCase {


    public function test() {
        $this->login();
        $this->loginUnAuth();
        $this->logoutFail();
    }

    public function login(){

        $user = \App\User::findOrFail(1);

        $payload = [
            'email' => $user['email'] ,
            'password' => 'secret'
            ];

        $response = $this
            ->json('post','/login',$payload)
            ->seeStatusCode(200) // cheks to ensure success
            ->response
            ->getContent();

        $this->logout(json_decode($response)->token,$payload);

    }

    public function loginUnAuth(){

        $user = factory(App\User::class)->make();

        $payload = [
            'email' => $user['email'] ,
            'password' => $user['password']
        ];

        //dd($payload);

        $response = $this
            ->json('post','/login',$payload)
            ->seeStatusCode(404) // checks to see if fails (user not found)
            ->response
            ->getContent();

    }


    public function logout($token,$payload){

        // need token
        $payload['token'] = $token;

        $response = $this
            ->json('post','/logout', $payload)
            ->seeStatusCode(200) // check to ensure successs
            ->response
            ->getContent();

    }

    public function logoutFail()
    {

        $response = $this
            ->json('post','/logout', array())
            ->seeStatusCode(500)
            ->response
            ->getContent();

    }

}