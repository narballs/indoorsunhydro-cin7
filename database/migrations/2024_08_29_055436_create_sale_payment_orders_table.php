<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePaymentOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payment_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_payment_id')->nullable();
            $table->unsignedBigInteger('sale_order_id')->nullable();
            $table->string('createdDate')->nullable();
            $table->string('modifiedDate')->nullable();
            $table->integer('createdBy')->nullable();
            $table->string('processedBy')->nullable();
            $table->boolean('isApproved')->default(true);
            $table->string('reference')->nullable();
            $table->integer('memberId')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('deliveryFirstName')->nullable();
            $table->string('deliveryLastName')->nullable();
            $table->string('deliveryCompany')->nullable();
            $table->string('deliveryAddress1')->nullable();
            $table->string('deliveryAddress2')->nullable();
            $table->string('deliveryCity')->nullable();
            $table->string('deliveryState')->nullable();
            $table->string('deliveryPostalCode')->nullable();
            $table->string('deliveryCountry')->nullable();
            $table->string('billingFirstName')->nullable();
            $table->string('billingLastName')->nullable();
            $table->string('billingCompany')->nullable();
            $table->string('billingAddress1')->nullable();
            $table->string('billingAddress2')->nullable();
            $table->string('billingCity')->nullable();
            $table->string('billingPostalCode')->nullable();
            $table->string('billingState')->nullable();
            $table->string('billingCountry')->nullable();
            $table->integer('branchId')->nullable();
            $table->string('branchEmail')->nullable();
            $table->string('projectName')->nullable();
            $table->string('trackingCode')->nullable();
            $table->string('internalComments')->nullable();
            $table->float('productTotal')->nullable();
            $table->float('freightTotal')->nullable();
            $table->string('freightDescription')->nullable();
            $table->float('surcharge')->nullable();
            $table->string('surchargeDescription')->nullable();
            $table->float('discountTotal')->nullable();
            $table->string('discountDescription')->nullable();
            $table->float('total')->nullable();
            $table->string('currencyCode')->nullable();
            $table->float('currencyRate')->nullable();
            $table->string('currencySymbol')->nullable();
            $table->string('taxStatus')->nullable();
            $table->string('taxRate')->nullable();
            $table->string('source')->nullable();
            $table->boolean('isVoid')->default(false);
            $table->string('memberEmail')->nullable();
            $table->string('memberCostCenter')->nullable();
            $table->string('memberAlternativeTaxRate')->nullable();
            $table->string('costCenter')->nullable();
            $table->string('alternativeTaxRate')->nullable();
            $table->string('estimatedDeliveryDate')->nullable();
            $table->integer('salesPersonId')->nullable();
            $table->string('salesPersonEmail')->nullable();
            $table->string('paymentTerms')->nullable();
            $table->string('customerOrderNo')->nullable();
            $table->string('voucherCode')->nullable();
            $table->string('deliveryInstructions')->nullable();
            $table->string('cancellationDate')->nullable();
            $table->string('modifiedCOGSDate')->nullable();
            $table->string('status')->nullable();
            $table->string('stage')->nullable();
            $table->string('invoiceDate')->nullable();
            $table->integer('invoiceNumber')->nullable();
            $table->string('dispatchedDate')->nullable();
            $table->string('logisticsCarrier')->nullable();
            $table->integer('logisticsStatus')->nullable();
            $table->integer('ediStatus')->nullable();
            $table->integer('distributionBranchId')->nullable();
            $table->string('departmentNumber')->nullable();
            $table->string('storeLocationNumber')->nullable();
            $table->string('distributionCenter')->nullable();

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
        Schema::dropIfExists('sale_payment_orders');
    }
}
