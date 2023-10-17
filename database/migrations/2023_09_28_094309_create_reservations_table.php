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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservation_id');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons','coupon_id')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers','customer_id')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users','user_id')->cascadeOnDelete();
            $table->foreignId('pic')->nullable()->constrained('users','user_id')->onDelete('cascade');
            $table->integer('total')->default(0);
            $table->string('booking_id');
            $table->string('type');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('adults');
            $table->integer('kids');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->string('status');
            $table->string('request');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        DB::unprepared('
        CREATE TRIGGER use_coupon AFTER INSERT ON `reservations` FOR EACH ROW
        BEGIN
            IF (NEW.coupon_id IS NOT null) then
                UPDATE coupons set is_valid = 0 WHERE coupon_id = NEW.coupon_id;
            END IF;
        END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
