<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('data', 'like', '%user-check%')
                ->chunkById(100, function ($notifications) {
                    foreach ($notifications as $notification) {
                        $updatedData = str_replace(
                            ['heroicon-o-user-check', 'o-user-check'],
                            ['heroicon-o-user-circle', 'heroicon-o-user-circle'],
                            $notification->data
                        );

                        DB::table('notifications')
                            ->where('id', $notification->id)
                            ->update(['data' => $updatedData]);
                    }
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Irreversible cosmetic fix
    }
};
