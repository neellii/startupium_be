<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsSocialNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_social_networks', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('projct_id')->unsigned();
            $table->integer('social_network_type_id')->unsigned();
            $table->string('social_network_link', 255);

            $table->primary('projct_id', 'social_network_type_id');

            $table->index('social_network_type_id', 'fk_sociallinks_socialtype_idx');

            $table->foreign('projct_id')
                ->references('project_id')->on('projects');

            $table->foreign('social_network_type_id')
                ->references('social_network_type_id')->on('social_networks_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects_social_networks');
    }
}
