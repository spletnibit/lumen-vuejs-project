<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 19:49
 */

namespace App;
use LaravelArdent\Ardent\Ardent;
use Illuminate\Support\Facades\Auth;

class ProductCategory extends Ardent {
  public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
  public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
  public $autoPurgeRedundantAttributes = true;

  protected $table = 'product_category';
  public $timestamps = false;

  protected $fillable = ['user_id', 'parent_id', 'name'];

  public static $rules = [
    'name'    => 'required'
  ];

  public static $relationsData = [
    'products' => [self::HAS_MANY, 'App\Product']
  ];


  public function beforeSave() {
    $this->user_id = Auth::id();

    if (empty($this->parent_id)) {
      $this->parent_id = null;
    }
  }
}
