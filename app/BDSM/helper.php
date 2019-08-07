<?php

if (! function_exists('bd_json')) {
  function bd_json($data, $additionalData = []) {
    $json = [];
    $paginate = request('paginate');
    if ($data != null) {
      if (get_class($data) === "Illuminate\\Database\\Eloquent\\Builder") {
        if ($paginate != null && is_numeric($paginate)) {
          $data = $data->paginate($paginate);
        } else {
          $data = $data->get();
        }
      } 
      if (get_class($data) === "Illuminate\\Database\\Eloquent\\Collection" || get_class($data) === "Illuminate\\Pagination\\LengthAwarePaginator" || $data instanceof Illuminate\Database\Eloquent\Model) {  
        foreach ($additionalData as $key => $value) {
          $json[$key] = $value;
        }
        $json['data'] = $data;
        if ($data)
          return jsend_success($json);
        return jsend_fail($json);
      }
    }
    $json['data'] = $data;
    $json['class'] = is_object($data) ? get_class($data) : null;
    return jsend_fail($json);
  }
}