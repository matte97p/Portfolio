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
        CREATE OR REPLACE FUNCTION copy_on_users_credentials_history ()
            RETURNS TRIGGER
            LANGUAGE plpgsql
        AS \$function\$
        BEGIN
            if  old.username                    <> new.username or
                old.password                    <> new.password or
                old.user_id                     <> new.user_id

            then
                insert into users_credentials_history (
                    id, username, password, user_id, remember_token,
                    staff_id, created_at, updated_at, deleted_at, users_credentials_id, version
                )
                values (
                    uuid_generate_v4(),
                    old.username,
                    old.password,
                    old.user_id,
                    old.remember_token,

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
            CREATE TRIGGER on_users_credentials_update_trigger
            BEFORE update
            ON users_credentials_currents
            FOR EACH ROW
            EXECUTE PROCEDURE copy_on_users_credentials_history();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS copy_on_users_credentials_history() CASCADE");

        DB::unprepared("
            DROP TRIGGER IF EXISTS on_users_credentials_update_trigger
            ON users_credentials_currents CASCADE;
        ");
    }
};
