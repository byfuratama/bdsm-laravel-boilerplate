<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;

class ReceiptController extends Controller
{
    private function goprint($data, $fpc = null) {

        if ($fpc == null) {
            $connector = new FilePrintConnector("./receipt.bin");
            $printer = new Printer($connector);
        } else {
            $profile = CapabilityProfile::load("simple");
            $connector = new WindowsPrintConnector($fpc);
            $printer = new Printer($connector,$profile);
        }
        
        $padN = 40;
        //title
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text($data['nama_toko'] . "\n");
        $printer -> text($data['alamat'] . "\n");
        $printer -> text($data['telp'] . "\n");
        $printer -> text($data['kota'] . "\n");
      
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text(str_pad("",$padN,"="). "\n");
      
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("Kasir       : " . $data['kasir'] . "\n");
        $printer -> text("No Bon      : " . $data['bon'] . "\n");
        $printer -> text("Tgl/Jam     : " . $data['tgljam'] . "\n");
      
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text(str_pad("",$padN,"="). "\n");
      
        foreach ($data['detail'] as $key => $detail) {
          $printer -> setJustification(Printer::JUSTIFY_LEFT);
          $printer -> text($detail['item_nama'] . "\n");
          $printer -> setJustification(Printer::JUSTIFY_RIGHT);
          $printer -> text(sprintf("%s %s %s", str_pad($detail['item_qty'],5) , str_pad($detail['item_harga'],10) , str_pad($detail['item_total'],10," ",STR_PAD_LEFT)) . "\n");
        }
      
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text(str_pad("",$padN,"="). "\n");
      
        $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        $printer -> text("Total     : " . str_pad($data['total'],15," ",STR_PAD_LEFT) . "\n");
        $printer -> text("Tunai     : " . str_pad($data['tunai'],15," ",STR_PAD_LEFT) . "\n");
        $printer -> text("Kembalian : " . str_pad($data['kembalian'],15," ",STR_PAD_LEFT) . "\n");
      
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text(str_pad("",$padN,"="). "\n");
        $printer -> text("TERIMA KASIH, SILAHKAN DATANG KEMBALI\n");
        $printer -> text(str_pad("",$padN,"="). "\n");
      
        $printer -> pulse();
        $printer -> close();
    }

    public function print_struk_test(Request $request) {
        
        $detail = [
            [
            "item_nama" => "SARIMI GORENG AYAM KREMES ISI 2",
            "item_qty" => "1",
            "item_harga" => "3,400",
            "item_total" => "3,400",
            ],
            [
            "item_nama" => "INDOMIE GORENG",
            "item_qty" => "1",
            "item_harga" => "2,500",
            "item_total" => "2,500",
            ]
        ];
        
        $data = [
            'nama_toko' => "RYANA",
            'alamat' => "JL. SAWO NO 20X BANJAR PACUNG DESA BITRA",
            'telp' => "087860642228",
            'kota' => "GIANYAR",
            'kasir' => "RYANA",
            'bon' => "002-01103085",
            'tgljam' => "01-10-19 / 17:03",
            'total' => "8,300",
            'tunai' => "10,000",
            'kembalian' => "1,700",
            'detail' => $detail,
        ];
        
        $this->goprint($data);
    }

    public function print_struk(Request $request) {
        
        $detail = [];
        $total = 0;
        $tunai = $request->cash;
        // dump($request->input());
        foreach ($request->detail as $det) {
            // dump($det);
            array_push($detail, [
                "item_nama" => $det['item_name'],
                "item_qty" => number_format($det['item_qty']),
                "item_harga" => number_format($det['item_price']),
                "item_total" => number_format($det['item_price'] * $det['item_qty']),
            ]);
            $total += $det['item_price'] * $det['item_qty'];
        }
        $kembali = $tunai - $total;
        $user = auth()->user()->name;
        
        $data = [
            'nama_toko' => $request->nama_toko ? $request->nama_toko : "RYANA",
            'alamat' => $request->alamat ? $request->alamat : "JL. SAWO NO 20X BANJAR PACUNG DESA BITRA",
            'telp' => $request->telp ? $request->telp : "087860642228",
            'kota' => $request->kota ? $request->kota : "GIANYAR",
            'kasir' => strtoupper($user),
            'bon' => $request->order_no,
            'tgljam' => date('d/m/y H:i',strtotime($request->order_at)),
            'total' => number_format($total),
            'tunai' => number_format($tunai),
            'kembalian' => number_format($kembali),
            'detail' => $detail,
        ];
        
        if ($request->printer) {
            $this->goprint($data,$request->printer);
        } else {
            $this->goprint($data);
        }

        // dd($data);
    }
}
