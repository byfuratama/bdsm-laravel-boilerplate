<?php

namespace App\BDSM;

trait ModelDetailHelper {

//   public function iterateDetail($detail, $callback) {
//     $detailData = [];
//     foreach ($detail as $dtl) {
//         $dtl[$this->detailForeignKey] = $this->id;
//         $detailData[] = (new $this->detailModelClass)->record($dtl);
//         if ($callback !== null && is_callable($callback)) {
//             $callback($dtl);
//         }
//     }
//     $this->detail = $detailData;

//     return $this;
// }

  public function recordDetail($detail, $callback = null) {
      $detailData = [];
      foreach ($detail as $dtl) {
          $dtl[$this->detailForeignKey] = $this->id;
          $detailData[] = (new $this->detailModelClass)->record($dtl);
          if ($callback !== null && is_callable($callback)) {
              $callback($dtl);
          }
      }
      $this->detail = $detailData;

      return $this;
  }

  public function deleteDetail() {
      $class = $this->detailModelClass;
      $detailData = $class::where($this->detailForeignKey, $this->id);
      $this->detail = $detailData->get();
      $detailData->delete();

      return $this;
  }
}