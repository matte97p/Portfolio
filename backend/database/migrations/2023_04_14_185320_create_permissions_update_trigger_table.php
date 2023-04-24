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
        CREATE OR REPLACE FUNCTION copy_on_permissions_history ()
            RETURNS TRIGGER
            LANGUAGE plpgsql
        AS \$function\$
        BEGIN
            if  old.name                    <> new.name or
                old.guard_name              <> new.guard_name

            then
                insert into permissions_history (
                    id, name, guard_name,
                    staff_id, created_at, updated_at, deleted_at, permissions_id, version
                )
                values (
                    uuid_generate_v4(),
                    old.name,
                    old.guard_name,

                    old.staff_id,
                    now(),
                    old.updated_at,
                    old.deleted_at,
                    old.id,
                    old.version
                );

                new.version = old.version+1;
            end if;

            return new;
        END;
        \$function\$
        ");

        DB::unprepared("
            CREATE TRIGGER on_permissions_update_trigger
            BEFORE update
            ON permissions_currents
            FOR EACH ROW
            EXECUTE PROCEDURE copy_on_permissions_history();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS copy_on_permissions_history() CASCADE");

        DB::unprepared("
            DROP TRIGGER IF EXISTS on_permissions_update_trigger
            ON permissions_currents CASCADE;
        ");
    }
};
