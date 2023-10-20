<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Enums\PriorityTicketEnum;
use App\Http\Enums\StatusTicketEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->uuid('user_requester_id');
            $table->uuid('user_assigned_id')->nullable();
            $table->string('category');
            $table->string('description');
            $table->enum('status',['open','in progress','resolved'])->default('open');
            $table->enum('priority',['low','medium','high']);

            $table->foreign('user_requester_id')->references('id')->on('users');
            $table->foreign('user_assigned_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
