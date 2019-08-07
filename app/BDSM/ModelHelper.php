<?php

namespace App\BDSM;

trait ModelHelper {
    public function record($request) {
        if ($request instanceof \Illuminate\Http\Request) {
            $req = $request->all();
        } elseif (is_object($request)) {
            $req = (array) $request;
        } elseif (is_array($request)) {
            $req = $request;
        } else {
            $req = json_decode($req);
        }

        $this->fill($req);
        $this->save();

        return $this;
    }
}