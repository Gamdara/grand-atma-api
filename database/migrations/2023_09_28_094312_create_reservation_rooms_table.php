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
            $table->integer('fare')->default(0);
            $table->integer('total')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        DB::unprepared('CREATE TRIGGER add_reservation_room AFTER INSERT ON `reservation_rooms` FOR EACH ROW
        BEGIN
            UPDATE reservations set total = total + NEW.total WHERE reservation_id = NEW.reservation_id;
        END');

        DB::unprepared('CREATE TRIGGER update_reservation_room BEFORE UPDATE ON `reservation_rooms` FOR EACH ROW
        BEGIN
            SET @lama = (SELECT DATEDIFF(end_date, start_date) + 1 from reservations where reservation_id = NEW.reservation_id);
            SET NEW.total = (NEW.smoking + NEW.nonsmoking) * (NEW.fare * @lama);
            -- UPDATE reservations set total = total + @lama  WHERE reservation_id = NEW.reservation_id;
            UPDATE reservations set total = total + NEW.total - OLD.total  WHERE reservation_id = NEW.reservation_id;
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
