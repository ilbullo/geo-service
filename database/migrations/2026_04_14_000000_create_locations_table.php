<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            // Campi per il polimorfismo (model_id e model_type)
            $table->morphs('geolocatable'); 
            
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->string('provider')->nullable(); // es. 'browser', 'ip'
            $table->timestamp('last_seen_at');
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('locations'); }
};