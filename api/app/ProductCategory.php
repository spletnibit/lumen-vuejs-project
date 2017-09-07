<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 19:49
 */

namespace App;
use LaravelArdent\Ardent\Ardent;

class ProductCategory extends Ardent {
  public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
  public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
  public $autoPurgeRedundantAttributes = true;

  protected $table = 'product_category';
  public $timestamps = false;

  public static $rules = [
    'name'    => 'required'
  ];

  public static $relationsData = [
    'products' => [self::HAS_MANY, 'App\Product']
  ];

  protected $fillable = ['parent_id', 'name'];
}
