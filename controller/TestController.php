<?php
class TestController extends BaseController {
    
    public function __construct(){
    }

    // @return void
    public function Index () {

        $postArray = Array (
            'proid' => 'c4ca4238a0b923820dcc509a6f75849b',
            'name' => 'A',
            'price' => '50.00',
            'buy_quantity' => '0',
            'offer_values' => '[{"qty":"3","price":"130.00", "add":""}]',
            'action' => 'Add',
            'grand_total' => '0',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"?c=ShoppingCart&a=ModifyCart");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($postArray));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $errorVal = curl_error($ch);
        print_r($errorVal);
        $serverOutput = curl_exec($ch);
        curl_close($ch);
        print_r($serverOutput) ;
    }
}

?>