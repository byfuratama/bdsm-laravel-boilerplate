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

    public function selectAll() {
        $filter = request('filter');
        $query = $this;
        if ($filter != null) {
            $fillable = $this->fillable;
            $query = $query->where(function($q) use ($fillable,$filter) {
                foreach ($fillable as $col) {
                    $q = $q->orWhere($col,'LIKE', '%' . $filter . '%');
                }
            });
            return $query;
        }
        return $query;
    }

    public function getTableProperties() {
        return [
            "table" => $this->table,
            "fields" => $this->fillable
        ];
    }
}