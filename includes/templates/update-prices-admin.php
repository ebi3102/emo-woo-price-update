<?php

/**
 * @package EWPU
 * ========================
 * Admin Update Prices PAGE
 * ========================
 * Text Domain: emo_ewpu
 */

 use EmoWooPriceUpdate\Repository\EWPU_Request_Handler;
 use EmoWooPriceUpdate\EWPU_Notice_Template;
?>
<h1><?php echo __( 'Update prices by uploading excel file', 'emo_ewpu' ) ?></h1>
<?php

//Download Current prices
/* Extract all Poducts site */

if(EWPU_Request_Handler::get_POST('btnSubmit')){
    $result = emo_ewpu_get_product_list();
}

?>
<?php //____________________ Download product lists ________________________________ ?>
<div class="wrap nosubsub">
    <div id="col-container-1" class="wp-clearfix emo-flex-row">
        <div id="col-left">
            <div class="col-wrap">
                <form method="post">
                    <div class="form-wrap">
                        <div style="width:fit-content; margin: 50px auto;">
                            <h3><?php echo __( 'Create product price list', 'emo_ewpu' ) ?></h3>
                            <?php // nounce ?>
                            <?php wp_nonce_field( 'emo_ewpu_action', 'emo_ewpu_nonce_field' ); ?>
                            <?php submit_button( __('Create', 'emo_ewpu'), 'primary', 'btnSubmit');  ?>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- #col-left -->

<?php

if(EWPU_Request_Handler::get_POST('uploadSubmit') && EWPU_Request_Handler::get_FILE('price_list')){
    $result = emo_ewpu_update_products_price_list();
}

?>
<?php //____________________ Upload New prices ________________________________ ?>

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <div style="width:fit-content; margin: 50px auto;">
                        <h3><?php echo __( 'Upload products list', 'emo_ewpu' ) ?></h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div>
                                <label for="price_list"><?php echo __( 'Upload new price list', 'emo_ewpu' ) ?></label>
                                <input id="price_list" type="file" name="price_list">
                            </div>
                            <p>
                                <description><?php echo __( 'It should be a csv file.<br>For getting the sample template you can download and use product list file', 'emo_ewpu' ) ?></description>
                            </p>
                            <?php // nounce ?>
                            <?php wp_nonce_field( 'emo_ewpu_action', 'emo_ewpu_nonce_field' ); ?>
                            <div>
                                <input type="submit" name="uploadSubmit" class="button button-primary" value="<?php echo __( 'Submit', 'emo_ewpu' ) ?>">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div><!-- #col-left -->


    </div>

    <?php
    // Notice when uploading is happened
    if(EWPU_Request_Handler::get_POST('uploadSubmit') && EWPU_Request_Handler::get_FILE('price_list')){
        if(@$result['response']){
	        echo EWPU_Notice_Template::success ($result['response']);
        }
	    if(@$result['error']){
		    echo EWPU_Notice_Template::warning ($result['error']->get_error_message());
        }
    }

    //Notice and download link when product list is created
    if(EWPU_Request_Handler::get_POST('btnSubmit')){
        if(@$result['error']){
            echo EWPU_Notice_Template::warning ($result['error']->get_error_message());
        }elseif(@$result['filePath']){
            $massage = __('You can download the list of price products from ', 'emo_ewpu');
            $massage .= "<a href='".$result['filePath']."'>".$result['fileName']."</a>";
            echo EWPU_Notice_Template::success ($massage);
        }
    }
    ?>
</div><!-- .wrap nosubsub -->

