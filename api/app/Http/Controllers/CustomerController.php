<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 09:46
 */

namespace App\Http\Controllers;


use App\Customer;

class CustomerController extends ApiController {
  protected $model;

  public function __construct(Customer $customer) {
    $this->model = $customer;
  }

}
