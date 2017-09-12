<?php
/**
 * Created by PhpStorm.
 * User: maticprevolsek
 * Date: 08/09/2017
 * Time: 19:54
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use App\Offer;
use Illuminate\Http\Request;

class OfferController extends ApiController {
  protected $model;

  public function __construct(Offer $offer) {
    $this->model = $offer;
  }

  public function edit(int $id) {
    return $this->model
      ->where('id', $id)
      ->with(['products'])
      ->first();
  }

  public function update(int $id, Request $r) {
    $model = $this->model->findOrFail($id);

    /*
     * handle product relation
     */
    $products = $r->input('products');
    $productIds = array_column($products, 'id');
    $existingProducts = $model->products()->get();
    $existingProductsIds = $existingProducts->pluck('id');

    // removal
    $checkIfNeedsToBeRemoved = $existingProductsIds->diff($productIds);
    if ($checkIfNeedsToBeRemoved->count()) {
      foreach ($checkIfNeedsToBeRemoved as $product_id) {
        $model->products()->detach($product_id);
      }
    }

    // attach
    $createProducts = collect($productIds);
    $checkIfNeedsToBeCreated = $createProducts->diff($existingProductsIds->all());
    if ($checkIfNeedsToBeCreated->count()) {
      foreach ($checkIfNeedsToBeCreated as $product_id) {
        $product = $products[array_search($product_id, $productIds)];

        $model->products()->attach($product_id, [
          'offer_id' => $model->id,
          'qty' => $product['pivot']['qty'],
          'discount' => $product['pivot']['discount'],
          'total' => $product['total']
        ]);

        unset($products[array_search($product_id, $productIds)]);
      }
    }

    // update
    foreach ($products as $product) {
      $model->products()->updateExistingPivot($product['id'], [
        'offer_id' => $model->id,
        'qty' => $product['pivot']['qty'],
        'discount' => $product['pivot']['discount'],
        'total' => $product['total']
      ]);
    }

    if ($model->save()) {
      return response()->json(['status' => true], 204);
    }
    return response()->json(['status' => false, 'messages' => $model->errors()->getMessages()], 403);
  }


  public function create(Request $r) {
    $model = new $this->model;
    $model->save();

    foreach ($r->input('products') as $product) {
      $model->products()->attach($product['id'], [
        'offer_id' => $model->id,
        'qty' => $product['qty'],
        'discount' => $product['discount']
      ]);
    }

    if ($model->save()) {
      return response()->json(['status' => true], 201);
    }
    return response()->json(['status' => false, 'messages' => $model->errors()->getMessages()], 403);
  }

  public function generatePdf(int $id) {

    $offer = Offer::where('id', $id)->with(['products'])->first();
    if (is_null($offer->pdf_generated_at) || $offer->modified_at > $offer->pdf_generated_at) {
      $offer->update([
        'pdf_generated_at' => \DB::raw('now()')
      ]);

      $pdf = App::make('dompdf.wrapper');
      $pdf->loadHTML(View::make('invoices.1', [
        'offer' => $offer
      ])->render());
      if ($pdf->save(storage_path('offer_pdf') . DIRECTORY_SEPARATOR . $id . '.pdf')) {
//      $output = $pdf->output();
//      return 'data:application/pdf;base64,'.base64_encode($output);
        return $pdf->stream();
      }
    } else {
      $filename = "$id.pdf";
      $path = storage_path('offer_pdf'.DIRECTORY_SEPARATOR.$filename);

      return response(file_get_contents($path), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$filename.'"'
      ]);
    }
    return response()->json(['status' => false], 403);
  }


}
