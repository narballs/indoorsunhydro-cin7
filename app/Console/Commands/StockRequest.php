<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;
use App\Models\ProductStockNotification;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StockRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:stockrequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock Request to users from indoorsun';

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
    public function handle()
    {
        // Fetch product stock notifications
        $product_stock_notification_users = ProductStockNotification::with('product')->where('status', 0)->get();

        // Generate PDF
        $pdfContent = $this->generatePdf($product_stock_notification_users);

        // Send email with PDF attachment
        $this->sendEmail($pdfContent);

        // Optionally, you can mark notifications as sent
        foreach ($product_stock_notification_users as $notification) {
            $notification['status'] = 1;
            $notification->save();
        }

        return 0;
    }

    /**
     * Generate PDF from product stock notifications.
     *
     * @param  \Illuminate\Support\Collection  $notifications
     * @return string
     */
    private function generatePdf($notifications)
    {
        
        // Render the Blade view to HTML
        $html = view('pdf.stock_request', ['notifications' => $notifications])->render();

        // Create Dompdf instance
        $pdf = new Dompdf();

        // Load HTML content into Dompdf
        $pdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Render PDF (optional: you can save to a file using $pdf->save('filename.pdf'))
        $pdf->render();

        // Output the PDF as a string
        return $pdf->output();
    }

    /**
     * Send email with PDF attachment.
     *
     * @param  string  $pdfContent
     * @return void
     */
    private function sendEmail($pdfContent)
    {
        $admin_users = DB::table('model_has_roles')->where('role_id', 1)->pluck('model_id')->toArray();

        $users_with_role_admin = User::select("email")->whereIn('id', $admin_users)->get();
        if ($users_with_role_admin->isNotEmpty()) {
            foreach ($users_with_role_admin as $role_admin) {
                Mail::send([], [], function ($message) use ($pdfContent, $role_admin) {
                    $message->to($role_admin->email)
                        ->from(SettingHelper::getSetting('noreply_email_address'))
                        ->subject('Stock Request Notifications')
                        ->attachData($pdfContent, 'stock_request_notifications.pdf')
                        ->setBody("Please find attached the stock request notifications.", 'text/plain');
                });
            }
        }
    }
}
