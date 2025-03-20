    <?php

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
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->string('codes')->unique();
                $table->foreignId('created_by')->constrained('users');
                $table->foreignId('project_id')->constrained('projects');
                $table->foreignId('responsible_by')->nullable()->constrained('users');
                $table->text('description');
                $table->integer('resolution_time')->nullable();
                $table->integer('response_time')->nullable();
                $table->integer('escalated_time')->nullable();
                $table->tinyInteger('status')->default(0);// 0 = open, 1 = closed, 2 = escalated
                $table->softDeletes();
                $table->timestamps();


            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('tickets');
        }
    };
