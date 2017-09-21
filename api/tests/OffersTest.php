<?php
/**
 * Created by PhpStorm.
 * User: gpode
 * Date: 9/19/2017
 * Time: 1:40 PM
 */

class OffersTest extends TestCase {

    public function test () {

        $offer_product = \App\OfferProduct::all()->random();
        $user = \App\User::findOrFail(1);
        $offer = \App\Offer::where("user_id","=","1")->first();


        //factories
        //$userFactory = factory(\App\User::class)->make();
        $offerFactory = factory(App\Offer::class)->make();

        $this->addOffer($user,$offerFactory,$offer_product);
        $this->getOffers($user);
        $this->editOffer($user,$offer);
        $this->generatedPDF($user);
        $this->updateOffer($user,$offerFactory,$offer_product);

    }

    private function getOffers($user)
    {
        $response = $this
            ->actingAs($user)
            ->json('get', '/offers')
            ->seeStatusCode(200)    //checks if successful
            ->response
            ->getContent();

        //dd(json_decode($response));

    }

    private function editOffer($user,$offer)
    {
        $response = $this
            ->actingAs($user)
            ->json('get','/offers/'.$offer->id)
            ->seeStatusCode(200) // checks if successful
            ->response
            ->getContent();

        //dd(json_decode($response));
    }

    private function generatedPDF($user)
    {
        $response = $this
            ->actingAs($user)
            ->json('get','/offers/pdf/1')
            ->seeStatusCode(200) // checks if successful
            ->response
            ->getContent();

        //dd(json_decode($response));
    }

    private function updateOffer($user, $offer, $offer_product)
    {
        $off = $offer->toArray();
        $data = [
            'products' => $offer_product->toArray(),
            'subtotal' => $off['subtotal'],
            'total' => $off['total'],
            'subtotal_vat' => $off['subtotal_vat'],
            'subtotal_discount' => $off['subtotal_discount']
        ];

        $response = $this
            ->actingAs($user)
            ->json('put','/offers/1',$data)
            ->seeStatusCode(204) // checks if successful (no payload returned)
            ->response
            ->getContent();

        //dd(json_decode($response));
    }

    private function addOffer($user,$offer,$offer_product)
    {

        $off = $offer->toArray();
        $data = [
            'products' => $offer_product->toArray(),
            'subtotal' => $off['subtotal'],
            'total' => $off['total'],
            'subtotal_vat' => $off['subtotal_vat'],
            'subtotal_discount' => $off['subtotal_discount']
        ];

        $response = $this
            ->actingAs($user)
            ->json('post','/offers/add',$data)
            ->seeStatusCode(201)    // checks if request has been fulfilled
            ->response
            ->getContent();

        //dd(json_decode($response));
    }
}