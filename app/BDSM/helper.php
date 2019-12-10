<?php

if (! function_exists('bd_json')) {
  function bd_json($data, $additionalData = []) {
    $json = [];
    $paginate = request('paginate');
    $sortBy = request('sortBy');
    $descending = request('descending');
    if ($data !== null) {
      if (is_array($data)) {
        return jsend_success($data);
      }
      if (get_class($data) === "Illuminate\\Database\\Eloquent\\Builder" || get_class($data) === "Illuminate\\Database\\Query\\Builder") {
        if ($sortBy != null) {
          if ($descending == null) {
            $descending = false;
          }
          if ($descending === true || $descending === 'true')
            $data = $data->orderBy($sortBy,'desc');
          else
            $data = $data->orderBy($sortBy,'asc');
        }
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

if (! function_exists('tgl_indo')) {

  function tgl_indo($tanggal){
    $bulan = array (
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    $pecahkan = explode('-', $tanggal);     
    if (count($pecahkan) == 1) {
        $pecahkan = explode('/', $tanggal);
    }
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
  }
    
}

if (! function_exists('array_replace_element')) {
  function array_replace_element($array,$old,$new){
    for($i=0;$i<count($array);$i++) {
      if($array[$i] == $old) {
        $array[$i] = $new;
      }
    }
    return $array;
  }
}