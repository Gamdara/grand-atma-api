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
            $table->id('reservation_room_id');
            $table->foreignId('room_type_id')->constrained('room_types','room_type_id')->cascadeOnDelete();
            $table->foreignId('reservation_id')->constrained('reservations','reservation_id')->cascadeOnDelete();
            $table->integer('smoking')->default(0);
            $table->integer('nonsmoking')->default(0);
            $table->integer('fare');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        DB::unprepared('CREATE TRIGGER add_reservation_room AFTER INSERT ON `reservation_rooms` FOR EACH ROW
        BEGIN
            UPDATE reservations set total = total + ((NEW.smoking + NEW.nonsmoking) * NEW.fare) WHERE reservation_id = NEW.reservation_id;
        END');

        DB::unprepared('CREATE TRIGGER update_reservation_room AFTER UPDATE ON `reservation_rooms` FOR EACH ROW
        BEGIN
            UPDATE reservations set total = total + ((NEW.smoking + NEW.nonsmoking) * NEW.fare) - ((OLD.smoking + OLD.nonsmoking) * OLD.fare)  WHERE reservation_id = NEW.reservation_id;
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
