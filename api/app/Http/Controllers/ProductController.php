<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 19:54
 */

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController {
  protected $model;

  public function __construct(Product $product) {
    $this->model = $product;
  }

  public function search(Request $r) {
    return $this->model->where('name', 'LIKE', "%{$r->input('q')}%")->with(['category'])->limit(50)->get();
  }

}
