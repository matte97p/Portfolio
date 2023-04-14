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
            CREATE OR REPLACE FUNCTION on_roles_insert ()
                RETURNS TRIGGER
                LANGUAGE plpgsql
            AS \$function\$
            BEGIN
                INSERT INTO roles_currents values (new.*);
                RETURN null;
            END;
            \$function\$
        ");

        DB::unprepared("
            CREATE TRIGGER on_roles_insert_trigger
            BEFORE INSERT ON roles
            FOR EACH ROW EXECUTE PROCEDURE on_roles_insert();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS on_roles_insert() CASCADE");

        DB::unprepared("
            DROP TRIGGER IF EXISTS on_roles_insert_trigger
            ON roles CASCADE;
        ");
    }
};
