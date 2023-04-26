<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class AppMigrationServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Crea campi in comune tra tutte le tabelle */
        Blueprint::macro('commonFields', function () {
            $this->foreignUuid('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
            $this->timestamps();
            $this->softDeletes();
            $this->uuid($this->table.'_id')->nullable();
            $this->integer('version')->default(1);
        });

        /* Eredita dalla tabella base i campi, setta primary e fk su staff_id */
        Blueprint::macro('inherits', function ($table) {
            DB::statement('CREATE TABLE IF NOT EXISTS ' . $this->table . ' () inherits (' . $table . ');');

            $this->primary('id');
            $this->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
        });

        /* Setta FK sulla tabella *_currents */
        Blueprint::macro('foreignCurrent', function ($table) {
            $this->foreign($table.'_id')->references('id')->on($table.'_currents')->onDelete('cascade');
        });
    }
}
