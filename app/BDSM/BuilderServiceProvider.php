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

        Builder::macro('addSubSelect', function ($column, $query) {
            if (is_null($this->columns)) {
                $this->select($this->from.'.*');
            }
            return $this->selectSub($query->limit(1), $column);
        });
        
        Builder::macro('orderBySub', function ($query, $direction = 'asc', $nullPosition = null) {
            if (!in_array($direction, ['asc', 'desc'])) {
                throw new Exception('Not a valid direction.');
            }
            if (!in_array($nullPosition, [null, 'first', 'last'], true)) {
                throw new Exception('Not a valid null position.');
            }
            return $this->orderByRaw(
                implode('', ['(', $query->limit(1)->toSql(), ') ', $direction, $nullPosition ? ' NULLS '.strtoupper($nullPosition) : null]),
                $query->getBindings()
            );
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
