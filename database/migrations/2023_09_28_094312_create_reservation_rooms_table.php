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
        Schema::create('reservation_rooms', function (Blueprint $table) {
            $table->foreignId('room_id')->constrained('rooms','room_id')->cascadeOnDelete();
            $table->foreignId('reservation_id')->constrained('reservations','reservation_id')->cascadeOnDelete();
            $table->integer('fare');
            $table->integer('amount');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        DB::unprepared('CREATE TRIGGER add_reservation_room AFTER INSERT ON `reservation_rooms` FOR EACH ROW
        BEGIN
            UPDATE reservations set total = total + (NEW.fare * NEW.amount) WHERE reservation_id = NEW.reservation_id;
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_rooms');
    }
};
