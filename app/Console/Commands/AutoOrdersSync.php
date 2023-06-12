<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\ApiOrder;
use App\Jobs\SalesOrders;
use App\Models\ApiOrderItem;
use App\Models\AdminSetting;

use App\Helpers\OrderHelper;

class AutoOrdersSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoOrder:Sync {--minutes=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {

        $option = AdminSetting::where('option_name', 'auto_full_fill')->first();
        if ($option->option_value == 0) {
            return;
        }

        $minutes = $this->option('minutes');

        $execution_time = date('Y-m-d H:i:s', strtotime('-' . $minutes . ' minutes'));

        $this->error('Execution Time: ' . $minutes . ' minutes ago => ' . $execution_time);

        $this->info('---------------------------------------------------------');

        $orders = ApiOrder::where('created_at', '<', $execution_time)
            ->where('order_id', null)
            ->where('isApproved', 0)
            ->with('user.contact')
            ->get();

        if (empty($orders)) {
            $this->info('There are no orders to process.');
            return false;
        }

        foreach ($orders as $order) {
            $this->info('Order Date ' . $order->created_at);

            $order_data = OrderHelper::get_order_data_to_process($order);
            SalesOrders::dispatch('create_order', $order_data)->onQueue(env('QUEUE_NAME'));
        }

        $this->info('Finished.');

    }


    public function handle2()
    {
        $createdDate = $order->created_at;
        $formatedDate = $createdDate->format('jS \of F Y h:i:s A');
        $orderCreatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $createdDate, 'America/Los_Angeles');
        $currentTime = Carbon::now();
        $time_diff = $orderCreatedDate->diffInMinutes($currentTime);
        dd($time_diff);
        $currentOrders = ApiOrder::where('created_at','<', $time_plus_5)->whereNull('order_id')->with('user.contact')->get();
        //dd($currentOrders);
        
        foreach($currentOrders as $currentOrder) {
            $userSwitchUser = [];
            if (!empty($currentOrder->user['contact'])) {
                foreach ($currentOrder->user['contact'] as $contact) {
                    $userSubmiter  =   $contact->email . ',' . $contact->firstName . ',' . $contact->lastName;
                }
            }
            if (!empty($currentOrder->user_switch)) {
                $userSwitchUser = $currentOrder->user_switch;
            } else {
                $userSwitchUser = "";
            }
            $orderSubmiterDetail = $userSubmiter . ',' . $userSwitchUser;

            $userSwitchUser = $currentOrder->user_switch;
            $memberId = $currentOrder->memberId;
            $order_items = ApiOrderItem::with('product.options')->where('order_id', $currentOrder->id)->get();
         
            $lineItems = [];
            foreach ($order_items as $order_item) {
                $lineItems[] = [
                    "id" => $order_item->product->product_id,
                    "createdDate" => '2022-07-31T23:43:38Z',
                    "transaction" => '12',
                    "parentId" => 1,
                    "productId" => $order_item->product->product_id,
                    "productOptionId" => null,
                    "integrationRef" => $orderSubmiterDetail,
                    "sort" => 16,
                    "code" => $order_item->product->code,
                    "name" => $order_item->product->name,
                    "option1" => $order_item->product->option1,
                    "option2" => $order_item->product->option2,
                    "option3" => $order_item->product->option,
                    "qty" => $order_item->quantity,
                    "styleCode" => "sample string 1",
                    "barcode" => "sample string 2",
                    "sizeCodes" => "sample string 4",
                    "lineComments" => null,
                    "unitCost" => $order_item->price,
                    "unitPrice" => $order_item->price,
                    "discount" => null,
                    "qtyShipped" => 7,
                    "holdingQty" => 8,
                    "accountCode" => null,
                    "stockControl" => "Undefined",
                    "stockMovements" => [
                        [
                            "batch" => "sample string 1",
                            "quantity" => 2.0,
                            "serial" => "sample string 3"
                        ],
                        [
                            "batch" => "sample string 1",
                            "quantity" => 2.0,
                            "serial" => "sample string 3"
                        ],
                    ],
                    "sizes" => [
                        [
                            "name" => "sample string 1",
                            "code" => "sample string 2",
                            "barcode" => "sample string 3",
                            "qty" => 4.0
                        ]
                    ],
                ];
            }
            $order = [];
            unset($currentOrder['primaryId']);
            unset($currentOrder['memberId']);
            $dateCreated = Carbon::now();
            $order = [
                [
                    $currentOrder,
                    "createdDate" => $dateCreated,
                    "modifiedDate" => "",
                    "createdBy" => 79914,
                    "processedBy" => 79914,
                    "isApproved" => true,
                    "reference" => $currentOrder->reference,
                    "memberId" => $memberId,
                    "branchId" => 3,
                    "branchEmail" => "wqszeeshan@gmail.com",
                    "projectName" => "",
                    "trackingCode" => "",
                    "internalComments" => $orderSubmiterDetail,
                    "productTotal" => 100,
                    "freightTotal" => null,
                    "freightDescription" => null,
                    "surcharge" => null,
                    "surchargeDescription" => null,
                    "discountTotal" => null,
                    "discountDescription" => null,
                    "total" => 100,
                    "currencyCode" => "USD",
                    "currencyRate" => 59.0,
                    "currencySymbol" => "$",
                    "taxStatus" => "Excl",
                    "taxRate" => 8.75,
                    "source" => "sample string 62",
                    "accountingAttributes" =>
                    [
                        "importDate" => "2022-07-13T15:21:16.1946848+12:00",
                        "accountingImportStatus" => "NotImported"
                    ],
                    "memberEmail" => "wqszeeshan@gmail.com",
                    "memberCostCenter" => "sample string 6",
                    "memberAlternativeTaxRate" => "",
                    "costCenter" => null,
                    "alternativeTaxRate" => "8.75%",
                    // "estimatedDeliveryDate" => "2022-07-13T15:21:16.1946848+12:00",
                    "estimatedDeliveryDate" => $currentOrder->date,
                    "salesPersonId" => 10,
                    "salesPersonEmail" => "wqszeeshan@gmail.com",
                    "paymentTerms" => $currentOrder->paymentTerms,
                    "customerOrderNo" => $currentOrder->po_number,
                    "voucherCode" => "sample string 14",
                    "deliveryInstructions" =>  $currentOrder->memo,
                    "status" => "VOID",
                    "invoiceDate" => null,
                    "invoiceNumber" => 4232,
                    "dispatchedDate" => null,
                    "logisticsCarrier" => "",
                    "logisticsStatus" => 1,
                    "distributionBranchId" => 0,
                    "lineItems" => $lineItems

                ],
            ];
            SalesOrders::dispatch('create_order', $order)->onQueue(env('QUEUE_NAME'));
        }
        
    }
}
