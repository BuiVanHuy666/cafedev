<?php

use App\Models\OptionGroup;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products_option_groups', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)->constrained('products')->cascadeOnDelete();
            $table->foreignIdFor(OptionGroup::class)->constrained('option_groups')->cascadeOnDelete();
            $table->unique(['product_id', 'option_group_id']);

            $table->boolean('is_required')->default(false);
            $table->boolean('is_multiple')->default(false);
            $table->integer('display_order')->default(0);
            $table->integer('max_select')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_option_groups');
    }
};
