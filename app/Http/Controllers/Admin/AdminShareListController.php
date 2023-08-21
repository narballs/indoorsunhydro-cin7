<?php

namespace App\Http\Controllers\Admin;
use \App\Http\Requests\BuyLists\BuyListRequest;
use \App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BuyList;
use App\Models\ProductBuyList;
use Redirect;
use App\Helpers\MailHelper;
use App\Helpers\SettingHelper;




class AdminShareListController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:Admin']);

    }
    
    public function shareList(Request $request) {
        $list = BuyList::where('id', $request->list_id)->with('list_products.product.options')->first();
        $base_url = url('/');
        $data = [
            'name' =>  SettingHelper::getSetting('from_email_name'),
            'email' => $request->email,
            'subject' => 'Buy List shared',
            'link' => $base_url.'/create-cart/' . $request->list_id,
            'from' => SettingHelper::getSetting('noreply_email_address'),
            'list' => $list
        ];
        $subject = '';
        MailHelper::sendMailNotification('emails.admin-share', $data);

       return response()->json([
            'success' => true, 
            'msg' => 'List Shared Successfully !'
        ]);
    }
}