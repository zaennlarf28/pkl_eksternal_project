<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('design_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('model_id')->nullable()->index();
            $table->string('image_path'); // path di storage/app/public/designs/...
            $table->longText('fabric_json')->nullable(); // optional: full canvas JSON
            $table->timestamp('sent_at')->useCurrent();
            $table->string('status')->default('pending'); // pending/seen/completed
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // optionally add foreign key for model table if ada
        });
    }

    public function down() {
        Schema::dropIfExists('design_submissions');
    }
};
