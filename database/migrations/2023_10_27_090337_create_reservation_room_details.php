<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservation_room_details', function (Blueprint $table) {
            $table->foreignId('reservation_room_id')->constrained('reservation_rooms','reservation_room_id')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms','room_id')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        DB::unprepared('CREATE TRIGGER add_reservation_room_detail AFTER INSERT ON `reservation_room_details` FOR EACH ROW
        BEGIN
            SET @v_is_smoking = (SELECT is_smoking from rooms where room_id = NEW.room_id);

            IF @v_is_smoking is true THEN
                UPDATE reservation_rooms set smoking = smoking + 1 WHERE reservation_room_id = NEW.reservation_room_id ;
            ELSE
                UPDATE reservation_rooms set nonsmoking = nonsmoking + 1 WHERE reservation_room_id = NEW.reservation_room_id ;
            END IF;
        END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_room_details');
    }
};
