<?php

// .../database/migrations/..._add_place_to_teams_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            // Posición final: 1, 2, 3, etc. Puede ser NULL si aún no se asignan.
            $table->integer('place')->nullable()->after('max_members');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('place');
        });
    }
};
