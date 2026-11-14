
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Legacy migration retained for compatibility.
     *
     * The activity_points table is created by:
     * 2026_10_23_231715_create_activity_points_table.php
     *
     * This migration intentionally performs no
     * database changes to prevent duplicate
     * table creation.
     */
    public function up(): void
    {
        // No operation required.
    }

    /**
     * No rollback action is necessary because
     * this migration creates no database objects.
     */
    public function down(): void
    {
        // No operation required.
    }
};
