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
        CREATE OR REPLACE FUNCTION copy_on_users_history ()
            RETURNS TRIGGER
            LANGUAGE plpgsql
        AS \$function\$
        BEGIN
            if  old.name                    <> new.name or
                old.surname                 <> new.surname or
                old.taxid                   <> new.taxid or
                old.gender                  <> new.gender or
                old.birth_date              <> new.birth_date

            then
                insert into users_history (
                    id, name, surname, taxid, gender, birth_date,
                    staff_id, created_at, updated_at, deleted_at, users_id, version
                )
                values (
                    uuid_generate_v4(),
                    old.name,
                    old.surname,
                    old.taxid,
                    old.gender,
                    old.birth_date,

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
            CREATE TRIGGER on_users_update_trigger
            BEFORE update
            ON users_currents
            FOR EACH ROW
            EXECUTE PROCEDURE copy_on_users_history();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS copy_on_users_history() CASCADE");

        DB::unprepared("
            DROP TRIGGER IF EXISTS on_users_update_trigger
            ON users_currents CASCADE;
        ");
    }
};
