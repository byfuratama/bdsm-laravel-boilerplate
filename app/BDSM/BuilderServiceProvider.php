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
