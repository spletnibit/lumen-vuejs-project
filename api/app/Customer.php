<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 09:43
 */

namespace App;

use Illuminate\Support\Facades\Auth;
use LaravelArdent\Ardent\Ardent;

class Customer extends Ardent{
  public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
  public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called
  public $autoPurgeRedundantAttributes = true;

  protected $table = 'customers';

  public static $rules = [
    'name'    => 'required',
    'address' => 'required',
    'city'    => 'required',
    'zip'     => 'required',
    'vat'     => 'required'
  ];

  public static $relationsData = [
    'user' => [self::BELONGS_TO, 'App\User']
  ];

  public function beforeSave() {
    $this->user_id = Auth::id();
  }

  protected $fillable =  ['user_id', 'name','address','city','zip','vat'];
}
