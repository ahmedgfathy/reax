<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('has_installments')->default(false);
            $table->decimal('monthly_installment', 12, 2)->nullable();
            $table->integer('installment_years')->nullable();
            $table->decimal('down_payment', 12, 2)->nullable();
            $table->json('installment_details')->nullable();
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'has_installments',
                'monthly_installment',
                'installment_years',
                'down_payment',
                'installment_details'
            ]);
        });
    }
};
