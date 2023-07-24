<?php

use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lms_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(User::class, 'author_id');
            $table->foreignIdFor(Publication::class);
            $table->string('like_material');
            $table->string('enough_material');
            $table->jsonb('what_to_add');
            $table->jsonb('what_to_remove');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_feedback');
    }
};
