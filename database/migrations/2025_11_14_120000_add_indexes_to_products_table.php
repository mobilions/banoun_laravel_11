<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'category_id')) {
                $table->index('category_id', 'products_category_id_idx');
            }
            if (Schema::hasColumn('products', 'subcategory_id')) {
                $table->index('subcategory_id', 'products_subcategory_id_idx');
            }
            if (Schema::hasColumn('products', 'brand_id')) {
                $table->index('brand_id', 'products_brand_id_idx');
            }
            if (Schema::hasColumn('products', 'delete_status') && Schema::hasColumn('products', 'category_id')) {
                $table->index(['delete_status', 'category_id'], 'products_delete_status_category_id_idx');
            }
            if (Schema::hasColumn('products', 'created_at')) {
                $table->index('created_at', 'products_created_at_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropIndex('products_category_id_idx');
            }
            if (Schema::hasColumn('products', 'subcategory_id')) {
                $table->dropIndex('products_subcategory_id_idx');
            }
            if (Schema::hasColumn('products', 'brand_id')) {
                $table->dropIndex('products_brand_id_idx');
            }
            if (Schema::hasColumn('products', 'delete_status') && Schema::hasColumn('products', 'category_id')) {
                $table->dropIndex('products_delete_status_category_id_idx');
            }
            if (Schema::hasColumn('products', 'created_at')) {
                $table->dropIndex('products_created_at_idx');
            }
        });
    }
};

