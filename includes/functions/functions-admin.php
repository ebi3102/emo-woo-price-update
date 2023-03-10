<?php

/**
 * @package EWPU
 * ========================
 * ADMIN PAGE
 * ========================
 * Text Domain: emo_ewpu
 */


function emo_ewpu_add_admin_page() {
    //Generate ECB Admin Page
    add_menu_page( 
        __( 'Update whole prices', 'emo_ewpu' ), 
        __( 'Prices management', 'emo_ewpu' ), 
        'manage_options', 'emo_ewpu_slug', 
        'emo_ewpu_update_prices_create_page', 
        EWPU_URI . 'assets/img/logo-icon.png', 
        110 
    );

    //Generate update prices by file page
    add_submenu_page(
        'emo_ewpu_slug',
        __( 'Update whole prices', 'emo_ewpu' ),
        __( 'Update whole prices', 'emo_ewpu' ) ,
        'manage_options' , 
        'emo_ewpu_slug' , 
        'emo_ewpu_update_prices_create_page'
    );

    //Generate group update prices
	add_submenu_page(
        'emo_ewpu_slug', 
        __( 'Group price update', 'emo_ewpu' ), 
        __( 'Group price update', 'emo_ewpu' ) , 
        'manage_options' , 
        'group_price_update' , 
        'emo_ewpu_group_price_update'
    );

	//Generate group on-sale prices
    add_submenu_page(
        'emo_ewpu_slug',
        __( 'Group discount', 'emo_ewpu' ),
        __( 'Group discount', 'emo_ewpu' ) ,
        'manage_options' ,
        'group_discount' ,
        'emo_ewpu_group_discount'
    );


}
add_action( 'admin_menu', 'emo_ewpu_add_admin_page' );

//Template submenu functions
function emo_ewpu_update_prices_create_page() {
    $url_template = EWPU_DIR.'/includes/templates/update-prices-admin.php';
    require_once $url_template;
}

function emo_ewpu_group_price_update(){
    $url_template = EWPU_DIR.'/includes/templates/group-price-update.php';
    require_once $url_template;
}

function emo_ewpu_group_discount(){
    $url_template = EWPU_DIR.'/includes/templates/group-discount.php';
    require_once $url_template;
}

