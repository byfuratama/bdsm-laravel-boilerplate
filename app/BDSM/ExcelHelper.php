<?php

namespace App\BDSM;

use Maatwebsite\Excel\Facades\Excel;

class ExcelHelper extends Excel  {

    private $header = [];
    private $data = [];
    private $dataProps = [];
    private $footer = [];
    private $sides;
    private $sheetName;

    private static $xls = [];

    public function __construct($name = "Sheet") {
      $this->sheetName = $name;
      array_push(self::$xls,$this);
    }

    private static function c($x,$y) {
      return chr($x + 64) . $y;
    }

    private static function cc($x1,$y1,$x2,$y2) {
      return self::c($x1,$y1) . ':' . self::c($x2,$y2);
    }

    public function setHeaderMeta($meta,$string,$color="#000000",$size=12,$align="center",$weight="normal") {
      $this->header[$meta] = [
        "string" => $string,
        "color" => $color,
        "align" => $align,
        "size" => $size,
        "weight" => $weight
      ];
    }

    public function setTitle($string,$color="#000000",$size=20,$align="center",$weight="bold") {
      $this->setHeaderMeta('title',$string,$color,$size,$align,$weight);
    }

    public function setSubTitle($string,$color="#000000",$size=16,$align="center",$weight="bold") {
      $this->setHeaderMeta('subtitle',$string,$color,$size,$align,$weight);
    }

    public function setData($data,$headBG="#ffffff",$headText="#000000",$bodyBG="#ffffff",$bodyText="#000000") {
      $this->data = $data;
      $this->dataProps = [
        "headBG" => $headBG,
        "headText" => $headText,
        "bodyBG" => $bodyBG,
        "bodyText" => $bodyText,
      ];
    }

    public static function render($filename = 'excel', $title = "title", $desc = 'Laporan Excel') {

      // dd(self::$xls);

      Excel::create($filename, function($excel) use($title,$desc) {

        $excel->setTitle($title);
        $excel->setCreator('Byte Dream S&M')->setCompany('Byte Dream S&M');
        $excel->setDescription($desc);

        foreach (self::$xls as $xlsheet) {

          $excel->sheet($xlsheet->sheetName, function($sheet) use($xlsheet) {

            $sheet->fromArray($xlsheet->data);
            $sheet->prependRow();

            $nHeader = 1;
            $header = [];
            foreach ($xlsheet->header as $key => $value) {
              if ($key != 'subtitle' && $key != 'title') {
                $sheet->prependRow([$xlsheet->header[$key]['string']]);
                $header[$nHeader] = $xlsheet->header[$key];
                $nHeader++;
              }
            }

            if (isset($xlsheet->header['subtitle'])) {
              $sheet->prependRow([$xlsheet->header['subtitle']['string']]);
              $header[$nHeader] = $xlsheet->header['subtitle'];
              $nHeader++;
            }
            if (isset($xlsheet->header['title'])) {
              $sheet->prependRow([$xlsheet->header['title']['string']]);
              $header[$nHeader] = $xlsheet->header['title'];
              $nHeader++;
            }
            
            $lastX = 0;
            $lastY = 0;
            $lastYTable = 0;
            if (count($xlsheet->data) > 0) {
              $lastX = count($xlsheet->data[0]);
              $lastYTable = $nHeader + count($xlsheet->data) + 1;
            }

            for ($i=1; $i < $nHeader; $i++) { 
              $sheet->mergeCells(self::cc(1,$i,$lastX,$i));

              $props = $header[$nHeader - $i];

              $sheet->cell(self::c(1,$i), function($cell) use($props) {
                  $cell->setAlignment($props['align']); 
                  $cell->setFontSize($props['size']);
                  $cell->setFontWeight($props['weight']);
                  $cell->setFontColor($props['color']);
              });
            }

            if ($lastX > 0) {
              $nHeader++;

              $props = $xlsheet->dataProps;
              $sheet->cell(self::cc(1,$nHeader,$lastX,$nHeader), function($cell) use($props) {
                  $cell->setFontColor($props['headText']);
                  $cell->setBackground($props['headBG']);
                  $cell->setFontWeight('bold');
                  $cell->setBorder('thin', 'thin', 'thin', 'thin');
              });
              for($i = 1; $i <= $lastX; $i++) {
                $sheet->cell(self::cc($i,$nHeader,$i,$lastYTable), function($cell) use($props) {
                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell(self::cc($i,$nHeader + 1,$i,$lastYTable), function($cell) use($props) {
                  $cell->setFontColor($props['bodyText']);
                  $cell->setBackground($props['bodyBG']);
                });
              }
            }

          });
        }

      })->download('xlsx');

    }


}