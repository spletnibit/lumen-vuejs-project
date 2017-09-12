<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 19:49
 */

namespace App;
use LaravelArdent\Ardent\Ardent;

class OfferProduct extends Ardent {
  public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
  public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
  public $autoPurgeRedundantAttributes = true;

  protected $table = 'offer_product';

  public static $rules = [
    'offer_id'    => 'required|exists:offers',
    'product_id'  => 'required|exists:products',
    'qty'         => 'required|integer',
    'discount'    => 'required|integer|min:0|max:100'
  ];

  protected $fillable = ['offer_id', 'product_id'];
}
