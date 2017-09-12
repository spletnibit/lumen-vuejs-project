<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ApiController extends Controller {
    public function index() {
      return $this->model->all();
    }

  public function create(Request $r) {
    $model = new $this->model;
    if ($model->save()) {
      return response()->json(['status' => true], 201);
    }
    return response()->json(['status' => false, 'messages' => $model->errors()->getMessages()], 403);
  }

  public function update(int $id, Request $r) {
    $model = $this->model->findOrFail($id);
    if ($model->save()) {
      return response()->json(['status' => true], 200);
    }
    return response()->json(['status' => false, 'messages' => $model->errors()->getMessages()], 403);
  }

  public function edit(int $id) {
    return $this->model->findOrFail($id);
  }

  public function destroy(int $id) {
    return $this->model->destroy($id);
  }
}
