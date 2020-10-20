<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateDispensesTable extends Migration
{
    public function up(): void
    {
        Schema::create('dispenses', static function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('token');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispenses');
    }
}
