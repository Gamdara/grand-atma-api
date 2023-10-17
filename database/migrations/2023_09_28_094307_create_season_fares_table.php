<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('season_fares', function (Blueprint $table) {
            $table->foreignId('room_type_id')->constrained('room_types','room_type_id')->cascadeOnDelete();
            $table->foreignId('season_id')->constrained('seasons','season_id')->cascadeOnDelete();
            $table->integer('discount_amount');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('season_fares');
    }
};
