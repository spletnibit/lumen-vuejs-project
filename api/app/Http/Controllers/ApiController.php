<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller {
  public function index() {
    return $this->model
      ->where('user_id', Auth::id())
      ->get();
  }

  public function create(Request $r) {
    $model = new $this->model;

    if ($model->save()) {
      return response()->json($model, 201);
    }
    return response()->json($model->errors()->getMessages(), 403);
  }

  public function update(int $id, Request $r) {
    $model = $this->model->findOrFail($id);
    if ($model->user_id === Auth::id() && $model->save()) {
      return response()->json($model, 200);
    }
    return response()->json([$model->errors()->getMessages()], 403);
  }

  public function edit(int $id) {
    $record = $this->model->findOrFail($id);
    if ($record->user_id == Auth::id()) {
      return $this->model->findOrFail($id);
    }

    return response()->json(['status' => false], 403);
  }

  public function destroy(int $id) {
    $record = $this->model->findOrFail($id);
    if ($record->user_id == Auth::id()) {
      $this->model->destroy($id);
      return response()->json(['id' => $id], 200);
    }

    return response()->json(['status' => false], 403);
  }
}
