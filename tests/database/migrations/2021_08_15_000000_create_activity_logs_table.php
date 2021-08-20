<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->default(NULL)->index('user_id');

            $table->unsignedBigInteger('table_id')->default(0)->index('table_id');
            $table->string('table')->nullable()->default(NULL)->index('table');
            $table->string('model')->nullable()->default(NULL)->index('model');
            $table->string('action')->nullable()->default(NULL)->index('action');
            $table->text('description')->nullable()->default(NULL);
            $table->longText('old_data')->nullable()->default(NULL);
            $table->longText('new_data')->nullable()->default(NULL);
            $table->text('url')->nullable()->default(NULL);
            $table->string('method', 50)->nullable()->default(NULL)->index('method');
            $table->string('ip', 50)->nullable()->default(NULL)->index('ip');
            $table->string('os', 80)->nullable()->default(NULL)->index('os');
            $table->string('browser', 80)->nullable()->default(NULL)->index('browser');
            $table->string('device', 80)->nullable()->default(NULL)->index('device');
            $table->text('agent')->nullable()->default(NULL);

            $table->string('latitude', 20)->nullable()->default(NULL)->index('latitude');
            $table->string('longitude', 20)->nullable()->default(NULL)->index('longitude');
            $table->string('city', 80)->nullable()->default(NULL)->index('city');
            $table->string('region', 80)->nullable()->default(NULL)->index('region');
            $table->string('region_code', 10)->nullable()->default(NULL)->index('region_code');
            $table->string('region_name', 80)->nullable()->default(NULL)->index('region_name');
            $table->string('area_code', 10)->nullable()->default(NULL)->index('area_code');
            $table->string('dma_code', 50)->nullable()->default(NULL)->index('dma_code');
            $table->string('country_code', 10)->nullable()->default(NULL)->index('country_code');
            $table->string('country_name', 80)->nullable()->default(NULL)->index('country_name');
            $table->string('continent_code', 10)->nullable()->default(NULL)->index('continent_code');
            $table->string('continent_name', 80)->nullable()->default(NULL)->index('continent_name');
            $table->string('timezone', 50)->nullable()->default(NULL)->index('timezone');
            $table->string('location_accuracy_radius', 20)->nullable()->default(NULL)->index('location_accuracy_radius');
            $table->string('geo_location_service_delay', 20)->nullable()->default(NULL)->index('geo_location_service_delay');
            $table->string('geo_location_service_status', 10)->nullable()->default(NULL)->index('geo_location_service_status');
            $table->string('isp_name', 150)->nullable()->default(NULL)->index('isp_name');
            $table->string('isp_organization', 150)->nullable()->default(NULL)->index('isp_organization');
            $table->string('isp_autonomous_system_number', 150)->nullable()->default(NULL)->index('isp_autonomous_system_number');
            $table->string('isp_service_status', 10)->nullable()->default(NULL)->index('isp_service_status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}