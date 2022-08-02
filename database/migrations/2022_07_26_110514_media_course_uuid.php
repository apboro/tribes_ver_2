<?php

use App\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MediaCourseUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(DB::raw('ALTER TABLE "courses" ADD COLUMN "uuid" UUID DEFAULT (uuid_generate_v4())'));
        Schema::table('courses', function (Blueprint $table) {
            $table->unique('uuid');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['uuid']);
        });
    }
}
