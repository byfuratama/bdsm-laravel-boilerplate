<?php

namespace App\BDSM;

use Exception;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;
// use Illuminate\Http\Request;

class BuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('joinModel', function ($fm, $as, $pk, $fk) {
            $ft = \DB::raw("({$fm->toSql()}) {$as}");
            $this->addBinding($fm->getBindings(),'join');

            return $this->join($ft,$pk,$fk);
        });

        Builder::macro('searchAllFields', function () {
            $filter = request('filter');
            $query = $this;
            if ($filter != null && func_num_args() > 0) {
                $props = func_get_args();
                $query = $query->where(function($q) use ($props,$filter) {
                    foreach ($props as $prop) {
                        foreach ($prop['fields'] as $col) {
                            $q = $q->orWhere($prop['table'].'.'.$col,'LIKE', '%' . $filter . '%');
                        }
                    }
                });
                return $query->select('*');
            }
            return $query->select('*');
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
