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
        CREATE OR REPLACE FUNCTION copy_on_users_contacts_info_history ()
            RETURNS TRIGGER
            LANGUAGE plpgsql
        AS \$function\$
        BEGIN
            if  old.phone                       <> new.phone or
                old.email                       <> new.email or
                old.address                     <> new.address or
                old.location                    <> new.location or
                old.zip                         <> new.zip or
                old.user_id                     <> new.user_id or

            then
                insert into users_contacts_info_history (
                    id, phone, email, address, location, zip, user_id,
                    staff_id, created_at, updated_at, deleted_at, users_contacts_info_id, version
                )
                values (
                    uuid_generate_v4(),
                    old.phone,
                    old.email,
                    old.address,
                    old.location,
                    old.zip,
                    old.user_id,

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
            CREATE TRIGGER on_users_contacts_info_update_trigger
            BEFORE update
            ON users_contacts_info_currents
            FOR EACH ROW
            EXECUTE PROCEDURE copy_on_users_contacts_info_history();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS copy_on_users_contacts_info_history() CASCADE");

        DB::unprepared("
            DROP TRIGGER IF EXISTS on_users_contacts_info_update_trigger
            ON users_contacts_info_currents CASCADE;
        ");
    }
};
