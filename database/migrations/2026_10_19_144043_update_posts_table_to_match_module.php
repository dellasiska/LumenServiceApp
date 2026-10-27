
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('body');
            $table->string('title', 100)->change();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->text('content');
            $table->integer('user_id')->index('user_id_foreign');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('user_id_foreign');
            $table->dropColumn(['status', 'content', 'user_id']);
            $table->string('title')->change();
            $table->text('body');
        });
    }
};
