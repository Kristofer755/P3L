<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pembeli', function (Blueprint $table) {
            $table->string('reset_token')->nullable()->after('poin');
        });
    }

    public function down(): void
    {
        Schema::table('pembeli', function (Blueprint $table) {
            $table->dropColumn('reset_token');
        });
    }
};
