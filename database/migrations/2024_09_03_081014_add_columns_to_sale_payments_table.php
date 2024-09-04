<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_payments', function (Blueprint $table) {
            $table->string('createdBy')->nullable();
            $table->string('processedBy')->nullable();
            $table->boolean('isApproved')->default(false);
            $table->string('memberId')->nullable();
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
            $table->string('branchEmail')->nullable();
            $table->string('projectName')->nullable();
            $table->string('trackingCode')->nullable();
            $table->text('internalComments')->nullable();
            $table->decimal('productTotal', 10, 2)->nullable();
            $table->decimal('freightTotal', 10, 2)->nullable();
            $table->string('freightDescription')->nullable();
            $table->decimal('surcharge', 10, 2)->nullable();
            $table->string('surchargeDescription')->nullable();
            $table->decimal('discountTotal', 10, 2)->nullable();
            $table->string('discountDescription')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('currencyCode')->nullable();
            $table->decimal('currencyRate', 10, 4)->nullable();
            $table->string('currencySymbol')->nullable();
            $table->string('taxStatus')->nullable();
            $table->decimal('taxRate', 5, 2)->nullable();
            $table->string('source')->nullable();
            $table->text('customFields')->nullable();
            $table->boolean('isVoid')->default(false);
            $table->string('memberEmail')->nullable();
            $table->string('memberCostCenter')->nullable();
            $table->decimal('memberAlternativeTaxRate', 5, 2)->nullable();
            $table->string('costCenter')->nullable();
            $table->decimal('alternativeTaxRate', 5, 2)->nullable();
            $table->date('estimatedDeliveryDate')->nullable();
            $table->string('salesPersonId')->nullable();
            $table->string('salesPersonEmail')->nullable();
            $table->string('paymentTerms')->nullable();
            $table->string('voucherCode')->nullable();
            $table->text('deliveryInstructions')->nullable();
            $table->date('cancellationDate')->nullable();
            $table->date('modifiedCOGSDate')->nullable();
            $table->string('status')->nullable();
            $table->string('stage')->nullable();
            $table->date('invoiceDate')->nullable();
            $table->date('dispatchedDate')->nullable();
            $table->string('logisticsCarrier')->nullable();
            $table->string('logisticsStatus')->nullable();
            $table->string('ediStatus')->nullable();
            $table->string('distributionBranchId')->nullable();
            $table->string('departmentNumber')->nullable();
            $table->string('storeLocationNumber')->nullable();
            $table->string('distributionCenter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_payments', function (Blueprint $table) {
            //
        });
    }
}
