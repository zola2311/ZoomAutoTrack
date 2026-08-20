<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['phone']);
            $table->dropUnique(['email']);
        });

        // Partial unique index: only enforced while deleted_at IS NULL,
        // so a soft-deleted customer's phone/email can be reused.
        DB::statement('CREATE UNIQUE INDEX customers_phone_unique ON customers ("phone") WHERE "deleted_at" IS NULL');
        DB::statement('CREATE UNIQUE INDEX customers_email_unique ON customers ("email") WHERE "deleted_at" IS NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS customers_phone_unique');
        DB::statement('DROP INDEX IF EXISTS customers_email_unique');

        Schema::table('customers', function (Blueprint $table) {
            $table->unique('phone');
            $table->unique('email');
        });
    }
};
