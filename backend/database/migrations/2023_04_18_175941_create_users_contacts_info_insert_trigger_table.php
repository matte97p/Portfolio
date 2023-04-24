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
            CREATE OR REPLACE FUNCTION on_users_contacts_info_insert ()
                RETURNS TRIGGER
                LANGUAGE plpgsql
            AS \$function\$
            BEGIN
                INSERT INTO users_contacts_info_currents values (new.*);
                RETURN null;
            END;
            \$function\$
        ");

        DB::unprepared("
            CREATE TRIGGER on_users_contacts_info_insert_trigger
            BEFORE INSERT ON users_contacts_info
            FOR EACH ROW EXECUTE PROCEDURE on_users_contacts_info_insert();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS on_users_contacts_info_insert() CASCADE");

        DB::unprepared("
            DROP TRIGGER IF EXISTS on_users_contacts_info_insert_trigger
            ON users_contacts_info CASCADE;
        ");
    }
};
