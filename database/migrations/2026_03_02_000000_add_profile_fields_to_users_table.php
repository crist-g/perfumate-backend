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
        Schema::table('users', function (Blueprint $table) {
            // guardar dirección y método de pago como JSON
            if (!Schema::hasColumn('users', 'address')) {
                $table->json('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'payment')) {
                $table->json('payment')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address', 'payment']);
        });
    }
};
