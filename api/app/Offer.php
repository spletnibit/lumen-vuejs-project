<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 19:49
 */

namespace App;
use LaravelArdent\Ardent\Ardent;

class Offer extends Ardent {
  public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
  public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
  public $autoPurgeRedundantAttributes = true;

  protected $table = 'offers';

  public static $relationsData = [
    'products' => [self::BELONGS_TO_MANY, 'App\Product', 'table' => 'offer_product', 'pivotKeys' => ['qty', 'discount', 'total', 'offer_id']],
    'customer' => [self::BELONGS_TO, 'App\Customer']
  ];

  public $with = ['customer'];

  public static $rules = [
//    'customer_id'       => 'required',
    'subtotal'          => 'required|numeric',
    'subtotal_discount' => 'required|numeric',
    'subtotal_vat'      => 'required|numeric',
    'total'             => 'required|numeric',
  ];

  protected $casts = [
    'subtotal'          => 'float',
    'subtotal_discount' => 'float',
    'subtotal_vat'      => 'float',
    'total'             => 'float',
  ];

  protected $fillable = ['user_id', 'customer_id', 'subtotal', 'subtotal_discount', 'subtotal_vat', 'total', 'pdf_generated_at'];
}
