<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('client')->after('password');
            $table->string('display_name')->nullable()->after('name');
            $table->string('company_name')->nullable()->after('display_name');
            $table->string('phone')->nullable()->after('company_name');
            $table->string('timezone')->default('Europe/Istanbul')->after('phone');
            $table->timestamp('last_seen_at')->nullable()->after('remember_token');
            $table->text('notes')->nullable()->after('last_seen_at');
        });

        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('channel')->default('contact-form');
            $table->string('status')->default('new');
            $table->string('score')->default('warm');
            $table->text('message')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('status')->default('discovery');
            $table->string('priority')->default('normal');
            $table->date('starts_on')->nullable();
            $table->date('due_on')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->text('summary')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('name');
            $table->string('category')->default('managed-service');
            $table->string('plan')->nullable();
            $table->string('status')->default('active');
            $table->string('billing_cycle')->default('monthly');
            $table->decimal('monthly_amount', 12, 2)->default(0);
            $table->timestamp('renews_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('status')->default('draft');
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->date('issued_on')->nullable();
            $table->date('due_on')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_notifications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('status')->default('pending-review');
            $table->string('channel')->default('manual');
            $table->string('reference_code')->nullable();
            $table->text('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('subject');
            $table->string('department')->default('support');
            $table->string('priority')->default('normal');
            $table->string('status')->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('first_replied_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('visibility')->default('public');
            $table->longText('body');
            $table->timestamps();
        });

        Schema::create('servers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('provider')->nullable();
            $table->string('region')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('agent_status')->default('offline');
            $table->string('status')->default('provisioning');
            $table->string('os_name')->nullable();
            $table->decimal('cpu_load', 5, 2)->default(0);
            $table->decimal('ram_usage', 5, 2)->default(0);
            $table->decimal('disk_usage', 5, 2)->default(0);
            $table->timestamp('last_reported_at')->nullable();
            $table->timestamps();
        });

        Schema::create('server_sites', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('domain');
            $table->string('framework')->nullable();
            $table->string('php_version')->nullable();
            $table->string('ssl_status')->default('unknown');
            $table->string('status')->default('active');
            $table->string('deploy_path')->nullable();
            $table->timestamps();
        });

        Schema::create('server_metrics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->string('metric');
            $table->string('unit')->nullable();
            $table->decimal('value', 10, 2)->default(0);
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_metrics');
        Schema::dropIfExists('server_sites');
        Schema::dropIfExists('servers');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('payment_notifications');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('services');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('leads');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'role',
                'display_name',
                'company_name',
                'phone',
                'timezone',
                'last_seen_at',
                'notes',
            ]);
        });
    }
};
