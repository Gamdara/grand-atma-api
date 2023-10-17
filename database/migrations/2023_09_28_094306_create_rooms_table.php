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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('room_id');
            $table->foreignId('room_type_id')->constrained('room_types','room_type_id')->cascadeOnDelete();
            $table->string('number');
            $table->string('bed_type');
            $table->boolean('is_smoking');
            $table->integer('capacity')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        DB::unprepared('CREATE TRIGGER add_room_slot AFTER INSERT ON `rooms` FOR EACH ROW

        BEGIN

        UPDATE room_types set slot = slot + 1 WHERE room_type_id = NEW.room_type_id;

        END');

        DB::unprepared('CREATE TRIGGER minus_room_slot BEFORE DELETE ON `rooms` FOR EACH ROW

        BEGIN

        UPDATE room_types set slot = slot - 1 WHERE room_type_id = OLD.room_type_id;

        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
