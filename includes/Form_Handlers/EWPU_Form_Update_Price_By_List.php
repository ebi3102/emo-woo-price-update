<?php

namespace EmoWooPriceUpdate\Form_Handlers;

use EmoWooPriceUpdate\Repository\EWPU_Pass_Error_Msg;
use EmoWooPriceUpdate\Repository\EWPU_Request_Handler;
use EmoWooPriceUpdate\Repository\EWPU_Uploader;
use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_Csv_Handler;

class EWPU_Form_Update_Price_By_List implements EWPU_Form_Field_Setter, EWPU_Form_Submit {
    private $file;
    private $filePath;
    private $fileUrl;

	use  EWPU_Form_Handler;

	/**
	 * @inheritDoc
	 */
	function field_setter( $fields ): void {
        $this->file = EWPU_Request_Handler::get_FILE($fields['file']);
	}

    private function file_info(array $info)
    {
        $this->filePath = $info['fileDir'];
        $this->fileUrl = $info['fileUrl'];
    }

	public function submit( array $args ): array {
        $error = $this->requirement_checker($args['checker_items']);
        if ($error['error']){
            return $error;
        }
        $this->field_setter($args['fields']);

        $uploadedFile = new EWPU_Uploader($this->file, $args['file_info']);
        if($uploadedFile->hasError()){
            return ['error'=> $uploadedFile->getError()];
        }

        // Read and store new prices
        if ( ($handle = new EWPU_Csv_Handler($uploadedFile->getFilePath(), "r")) !== false) {
            $row = 0;
            $args = ['length'=> 1000, 'separator'=> ','];
            while (($data = $handle->readFile($args)) !== false) {
                if($row != 0){
                    $productID = $data[0];
                    $regularPrice_new = (is_numeric($data[3]))? $data[3]:'';
                    $salePrice_new = (is_numeric($data[4]))? $data[4]:'';
                    $productType = ($data[5]== 'variation' || $data[5] == 'simple')?$data[5]:'';
                    if($regularPrice_new !='' && $productType != ''){
                        emo_ewpu_set_new_price($productID, $productType, 'regular_price' ,$regularPrice_new);
                    }
                    if($salePrice_new !='' && $productType != ''){
                        emo_ewpu_set_new_price($productID, $productType, 'sale_price' ,$salePrice_new);
                    }
                }
                $row++;
            }
            $handle->closeFile();
            $response= __('Your prices are updated successfully.', 'emo_ewpu' );
            return [
                'response'=>$response,
                'fileName' => $uploadedFile->getFileName(),
                'fileUrl' => $uploadedFile->getFileUrl()
                ];
        }else{
            $errors = EWPU_Pass_Error_Msg::error_object( 'invalid', __( "The plugin is not able to open the uploaded file ", "emo_ewpu" ) );
            return ['error'=>$errors];
        }
	}
}