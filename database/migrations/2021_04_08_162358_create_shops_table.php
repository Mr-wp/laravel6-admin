<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->nullable(false);
            $table->string('name',64)->comment('店铺名称');
            $table->string('owner_name',64)->comment('拥有人');
            $table->string('owner_phone',11)->comment('拥有人电话');
            $table->string('contact_name',64)->comment('联系人')->nullable();
            $table->string('contact_phone',11)->comment('联系人电话')->nullable();
            $table->string('address',32)->comment('所在省市区')->nullable();
            $table->string('address_detail',255)->comment('详细地址')->nullable();
            $table->enum('type',[1,2,3,4])->comment('店铺类别')->nullable();
            $table->longText('auth_imgs')->comment('资质照片集')->nullable();
            $table->enum('verify_status',[1,2,3,4])->comment('审核状态')->nullable();
            $table->enum('status',[0,1])->comment('禁用状态')->nullable();
            $table->decimal('balance',10,2)->comment('店铺余额')->nullable();
            $table->enum('ex_status',[0,1])->comment('试用7天')->nullable();
            $table->timestamp('ex_start_time')->comment('试用开始时间')->nullable();
            $table->timestamp('ex_end_time')->comment('试用结束时间')->nullable();
            $table->enum('pay_method',[1,2,3,4,5])->comment('认购方式')->nullable();
            $table->timestamp('use_start_time')->comment('使用开始时间')->nullable();
            $table->timestamp('use_end_time')->comment('使用结束时间')->nullable();
            $table->timestamps();
            $table->string('ali_appid',255)->comment('支付宝应用ID');
            $table->string('ali_private_key',255)->comment('支付宝支付私钥');
            $table->string('ali_public_key',255)->comment('支付宝支付公钥');
            $table->string('wx_mch_id',255)->comment('微信商户号');
            $table->string('wx_appid',255)->comment('微信应用ID');
            $table->string('wx_secret',255)->comment('微信密钥');
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
