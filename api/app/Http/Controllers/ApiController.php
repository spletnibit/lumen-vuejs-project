<?php

namespace App\Http\Controllers;


class ApiController extends Controller {
    public function index() {
      return $this->model->all();
    }

  public function create() {
    $model = new $this->model;
    if ($model->save()) {
      return response()->json(['status' => true], 200);
    }
    return response()->json(['status' => false, 'messages' => $model->errors()->getMessages()], 403);
  }

  public function update(int $id) {
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
