<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 03/09/2017
 * Time: 09:43
 */

namespace App;


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

  protected $fillable =  ['name','address','city','zip','vat'];
}
