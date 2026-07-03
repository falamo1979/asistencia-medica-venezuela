<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Roles: admin | medico | recepcion
            $table->string('role')->default('recepcion')->after('email');
            // Un usuario "medico" puede enlazarse a su ficha profesional.
            $table->foreignId('medico_id')->nullable()->after('role')
                  ->constrained('medicos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('medico_id');
            $table->dropColumn('role');
        });
    }
};
