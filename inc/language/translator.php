<?php
defined( 'ABSPATH' ) || exit;

// ===============================
//          CHECKOUT
// ===============================
add_filter('woocommerce_checkout_fields', 
            'pezhaal_customize_checkout_fields');
function pezhaal_customize_checkout_fields($fields){
    // نام
    $fields['billing'] ['billing_first_name'] ['label'] = 'نام';
    $fields['billing'] ['billing_first_name'] ['placeholder'] = 'نام خود را وارد کنید';
    $fields['billing'] ['billing_first_name'] ['class'] = array('', 'pezhaal-checkout-field');

    // نام خانوادگی
    $fields['billing'] ['billing_last_name'] ['label'] = 'نام خانوادگی';
    $fields['billing'] ['billing_last_name'] ['placeholder'] = 'نام خانوادگی خود را وارد کنید';
    $fields['billing'] ['billing_last_name'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_country'] ['label'] = 'کشور';
    $fields['billing'] ['billing_country'] ['placeholder'] = 'کشور خود را وارد کنید';
    $fields['billing'] ['billing_country'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_state'] ['label'] = 'استان';
    $fields['billing'] ['billing_state'] ['placeholder'] = 'استان محل سکونت';
    $fields['billing'] ['billing_state'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_city'] ['label'] = 'شهر';
    $fields['billing'] ['billing_city'] ['placeholder'] = 'شهر محل سکونت';
    $fields['billing'] ['billing_city'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_address_1'] ['label'] = 'خیابان';
    $fields['billing'] ['billing_address_1'] ['placeholder'] = 'خیابان اصلی';
    $fields['billing'] ['billing_address_1'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_address_2'] ['label'] = 'آدرس دقیق';
    $fields['billing'] ['billing_address_2'] ['placeholder'] = 'خیابان فرعی، ساختمان، واحد';
    $fields['billing'] ['billing_address_2'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_postcode'] ['label'] = 'کدپستی';
    $fields['billing'] ['billing_postcode'] ['placeholder'] = 'کدپستی خود را وارد کنید';
    $fields['billing'] ['billing_postcode'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_phone'] ['label'] = 'شماره تماس';
    $fields['billing'] ['billing_phone'] ['placeholder'] = 'شماره ی تماس خود را وارد کنید';
    $fields['billing'] ['billing_phone'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_email'] ['label'] = 'ایمیل';
    $fields['billing'] ['billing_email'] ['placeholder'] = 'ایمیل خود را وارد کنید';
    $fields['billing'] ['billing_email'] ['class'] = array('', 'pezhaal-checkout-field');

    return $fields;
}



// ===============================
//  Translate Account navigation
// ===============================
add_filter('woocommerce_account_menu_items', 'pezhaal_translate_account_menu');
function pezhaal_translate_account_menu($items){
    $translation = [
        'dashboard' => 'پیشخوان',
        'orders' => 'سفارشات',
        'downloads' => 'دانلودها',
        'edit-address' => 'آدرس های من',
        'payment-methods' => 'روش های پرداخت',
        'edit-account' => 'جزئیات حساب کاربری',
        'customer-logout' => 'خروج از حساب',
    ];

    foreach($translation as $key => $label){
        if (isset($items[$key])){
            $items[$key] = $label;
        }
    }

    return $items;
}

// ===============================
//      Translate Order Table
// ===============================
add_filter('woocommerce_account_orders_columns', 'pezhaal_translate_order_columns');
function pezhaal_translate_order_columns($columns){
    $translations = [
        'order-number' => 'کد سفارش',
        'order-date' => 'تاریخ ثبت',
        'order-status' => 'وضعیت سفارش',
        'order-total' => 'مبلغ سفارش',
        'order-actions' => 'عملیات',
    ];

    return $translations;
}