<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            // Las consultas no se borran: se anulan conservando el registro clínico.
            $table->timestamp('anulada_at')->nullable()->after('observaciones');
            $table->text('motivo_anulacion')->nullable()->after('anulada_at');
            $table->foreignId('anulada_por')->nullable()->after('motivo_anulacion')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('anulada_por');
            $table->dropColumn(['anulada_at', 'motivo_anulacion']);
        });
    }
};
