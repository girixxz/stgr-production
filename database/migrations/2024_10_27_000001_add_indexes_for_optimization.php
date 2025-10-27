<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan index untuk mempercepat query yang sering digunakan
     */
    public function up(): void
    {
        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->index('customer_id', 'idx_orders_customer');
            $table->index('sales_id', 'idx_orders_sales');
            $table->index('production_status', 'idx_orders_status');
            $table->index('order_date', 'idx_orders_date');
            $table->index('deadline', 'idx_orders_deadline');
            $table->index('created_at', 'idx_orders_created');
            // Composite index untuk filter + sort yang sering
            $table->index(['production_status', 'order_date'], 'idx_orders_status_date');
        });

        // Order Items table indexes
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id', 'idx_items_order');
            $table->index('design_variant_id', 'idx_items_variant');
        });

        // Design Variants table indexes
        Schema::table('design_variants', function (Blueprint $table) {
            $table->index('order_id', 'idx_variants_order');
        });

        // Invoices table indexes
        Schema::table('invoices', function (Blueprint $table) {
            $table->index('order_id', 'idx_invoices_order');
            $table->index('status', 'idx_invoices_status');
            $table->index('invoice_no', 'idx_invoices_no');
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->index('invoice_id', 'idx_payments_invoice');
            $table->index('paid_at', 'idx_payments_date');
        });

        // Customers table indexes
        Schema::table('customers', function (Blueprint $table) {
            $table->index('customer_name', 'idx_customers_name');
            $table->index('phone', 'idx_customers_phone');
            $table->index('village_id', 'idx_customers_village');
        });

        // Extra Services table indexes
        Schema::table('extra_services', function (Blueprint $table) {
            $table->index('order_id', 'idx_extra_order');
            $table->index('service_id', 'idx_extra_service');
        });

        // Order Stages table indexes (untuk tracking produksi)
        Schema::table('order_stages', function (Blueprint $table) {
            $table->index('order_id', 'idx_stages_order');
            $table->index('stage_id', 'idx_stages_production');
            $table->index('status', 'idx_stages_status');
            $table->index(['order_id', 'status'], 'idx_stages_order_status');
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
            // username sudah unique, tidak perlu index lagi
        });

        // Sessions table indexes (jika menggunakan database session)
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->index('user_id', 'idx_sessions_user');
                $table->index('last_activity', 'idx_sessions_activity');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop all indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_customer');
            $table->dropIndex('idx_orders_sales');
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_date');
            $table->dropIndex('idx_orders_deadline');
            $table->dropIndex('idx_orders_created');
            $table->dropIndex('idx_orders_status_date');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('idx_items_order');
            $table->dropIndex('idx_items_variant');
        });

        Schema::table('design_variants', function (Blueprint $table) {
            $table->dropIndex('idx_variants_order');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_order');
            $table->dropIndex('idx_invoices_status');
            $table->dropIndex('idx_invoices_no');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_invoice');
            $table->dropIndex('idx_payments_date');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('idx_customers_name');
            $table->dropIndex('idx_customers_phone');
            $table->dropIndex('idx_customers_village');
        });

        Schema::table('extra_services', function (Blueprint $table) {
            $table->dropIndex('idx_extra_order');
            $table->dropIndex('idx_extra_service');
        });

        Schema::table('order_stages', function (Blueprint $table) {
            $table->dropIndex('idx_stages_order');
            $table->dropIndex('idx_stages_production');
            $table->dropIndex('idx_stages_status');
            $table->dropIndex('idx_stages_order_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
        });

        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropIndex('idx_sessions_user');
                $table->dropIndex('idx_sessions_activity');
            });
        }
    }
};
