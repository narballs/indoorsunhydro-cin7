<?php

namespace App\Helpers;

use App\Models\AdminSetting;


class SettingHelper
{
   
    public static function getSetting($option_name, $default = null) {
        $admin_setting = AdminSetting::where('option_name', $option_name)->first();
        if (!empty($admin_setting)) {
            return $admin_setting->option_value;
        }
        return $default;
    }

    public static function getFriendlySettingValue($option_name) {
        $value = self::getSetting($option_name);
        $value = strtolower($value);
        $value = str_replace('-', ' ', $value);
        return $value;
    }

    public static function getLogo() {
        $logo = self::getSetting('logo');
        if (!empty($logo)) {
            return $logo;
        }
        return '/theme/img/logo.png';
    }

    public static function enableAddToCart($productOption) {
        if ($productOption->stockAvailable <= 0) {
            $allow_order_without_stock = self::getSetting('allow_order_without_stock', 'yes');
            $allow_order_without_stock = strtolower($allow_order_without_stock);

            if ($allow_order_without_stock == 'yes') {
                return true;
            }

            return false;
        }

        return true;
    }
}