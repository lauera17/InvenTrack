
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (! Schema::hasColumn('loans', 'return_note')) {
                $table->text('return_note')->nullable();
            }

            if (! Schema::hasColumn('loans', 'return_photo')) {
                $table->string('return_photo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'return_note')) {
                $table->dropColumn('return_note');
            }

            if (Schema::hasColumn('loans', 'return_photo')) {
                $table->dropColumn('return_photo');
            }
        });
    }
};
