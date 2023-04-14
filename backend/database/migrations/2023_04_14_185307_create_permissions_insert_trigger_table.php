<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE OR REPLACE FUNCTION on_permissions_insert ()
                RETURNS TRIGGER
                LANGUAGE plpgsql
            AS \$function\$
            BEGIN
                INSERT INTO permissions_currents values (new.*);
                RETURN null;
            END;
            \$function\$
        ");

        DB::unprepared("
            CREATE TRIGGER on_permissions_insert_trigger
            BEFORE INSERT ON permissions
            FOR EACH ROW EXECUTE PROCEDURE on_permissions_insert();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS on_permissions_insert() CASCADE");

        DB::unprepared("
            DROP TRIGGER IF EXISTS on_permissions_insert_trigger
            ON permissions CASCADE;
        ");
    }
};
