<?php

use App\Models\Publication;
use App\Models\Webinar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lms_feedback', function (Blueprint $table) {
            $table->foreignIdFor(Publication::class)->nullable()->change();
            $table->foreignIdFor(Webinar::class)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            //
        });
    }
};
