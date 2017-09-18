<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 19:49
 */

namespace App;
use Illuminate\Support\Facades\Auth;
use LaravelArdent\Ardent\Ardent;

class Product extends Ardent {
  public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
  public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
  public $autoPurgeRedundantAttributes = true;

  protected $table = 'products';
  public $timestamps = false;

  public $with = ['category'];

  public static $relationsData = [
    'category'  => [self::BELONGS_TO, 'App\ProductCategory']
  ];

  public static $rules = [
    'name'    => 'required',
    'unit'    => 'required',
    'price'   => 'required',
    'vat'     => 'required'
  ];

  protected $casts = [
    'price'   => 'float',
    'vat'     => 'float'
  ];

  protected $fillable = ['user_id', 'category_id', 'name', 'unit', 'price', 'vat'];

  public function beforeSave() {
    $this->user_id = Auth::id();
  }
}
