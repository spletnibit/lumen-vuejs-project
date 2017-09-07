<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 19:54
 */

namespace App\Http\Controllers;


use App\ProductCategory;

class ProductCategoryController extends ApiController {

  protected $model;

  public function __construct(ProductCategory $pc) {
    $this->model = $pc;
  }
}
