<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'excuse_request_documents',
            function (Blueprint $table) {

                $table->id(
                    'excuse_request_document_id'
                );


                $table->unsignedBigInteger(
                    'excuse_request_id'
                );


                $table->string(
                    'file_name'
                );


                $table->string(
                    'file_path'
                );


                $table->string(
                    'mime_type'
                )
                ->nullable();


                $table->unsignedBigInteger(
                    'file_size'
                )
                ->nullable();


                $table->timestamps();


                $table
                    ->foreign(
                        'excuse_request_id'
                    )
                    ->references(
                        'excuse_request_id'
                    )
                    ->on(
                        'excuse_requests'
                    )
                    ->cascadeOnDelete();

            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'excuse_request_documents'
        );
    }
};