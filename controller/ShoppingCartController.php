<?php
include_once('./model/ShoppingCartModel.php');
// Controller is used for List Product and Checkout Product page.
class ShoppingCartController extends BaseController {
    
    public function __construct(){
    }

    // Load list of products with dyanmic values
    // @return void
    public function Index () {
        $module = [];
        $model = ShoppingCartModel::GetProductList();
        if(!empty($model)){
            $model = $this->FetchSessionData($model, "Y");
        }
        parent::RenderPage(
            'Products', 
            'view/layout/layout.php', 
            'view/list_product_view.php',
            $model
        );
    }

    // Load checkout View page with dyanmic values
    // @return void
    public function CheckOutPage () {
        $module = [];
        $model = ShoppingCartModel::GetProductList();
        if(!empty($model)){
            $model = $this->FetchSessionData($model, "N");
        }
        parent::RenderPage(
            'CheckOut', 
            'view/layout/layout.php', 
            'view/checkout_view.php',
            $model
        );
    }

    // Add,Update and Delete Products to Cart and modify session of cart
    // @return bool
    public function ModifyCart() {
        $grandTotal = $_POST['grand_total'];
        if($_POST['action'] == 'Add') {
            if($_POST['buy_quantity'] > 0){
                // Calculate price and set buy_price and buy_qty into set
                $purchaseProductArr = $this->FetchSessionProductData();
                $totalPrice = $this->TotalPriceCal($_POST, $purchaseProductArr);
                if(isset($_SESSION['SHOPPINGCART']['PRODUCTLIST'][$_POST['proid']]['buy_price'])) {
                    $grandTotal = $grandTotal - $_SESSION['SHOPPINGCART']['PRODUCTLIST'][$_POST['proid']]['buy_price'];
                }
                $grandTotal = $grandTotal + $totalPrice;
                $_POST['buy_price'] = $totalPrice;
                $_SESSION['SHOPPINGCART']['PRODUCTLIST'][$_POST['proid']] = $_POST;
                echo json_encode(array('success' => 1, 'price' => $totalPrice, 'grand_total' => $grandTotal));
                return true;
            } else {
                // Quanity Validation
                echo json_encode(array('success' => 0, 'msg' => "select atlest 1 qty"));
                return false;
            }
        } else {
            $totalPrice = 0.00;
            if(isset($_SESSION['SHOPPINGCART']['PRODUCTLIST'][$_POST['proid']])) {
                // Check session is set then update grand total
                if(isset($_SESSION['SHOPPINGCART']['PRODUCTLIST'][$_POST['proid']]['buy_price'])) {
                    $grandTotal = $grandTotal - $_SESSION['SHOPPINGCART']['PRODUCTLIST'][$_POST['proid']]['buy_price'];
                }
                unset($_SESSION['SHOPPINGCART']['PRODUCTLIST'][$_POST['proid']]);
                echo json_encode(array('success' => 1, 'price' => $totalPrice, 'grand_total' => $grandTotal));
                return true;
            } else {
                // Set Error if user wants to remove Product for which session is not set.
                echo json_encode(array('success' => 0, 'msg' => 'something went wrong'));
                return false;
            }
        }
    }

    // For Calculate Buy Price and Grand Total Price with Spceial price conditions 
    // @return total price
    // @param array $model -> Product details array
    private function TotalPriceCal($model,$purchaseProductArr = array()) {
        $totalPrice = 0;
        if($model['offer_values'] !== NULL){
            $offerArr = json_decode(html_entity_decode($model['offer_values']), true);
            if(!empty($offerArr)) { 
                $offerCount = count($offerArr) - 1;
                foreach ($offerArr as $key => $value) {
                    if($model['buy_quantity'] > 0) {
                        $totalPriceValue = $this->CalculatespecialPrice($model['buy_quantity'], $value['qty'], $totalPrice, $model['price'], $value['price'], $offerCount, $value['add'], $purchaseProductArr);
                        if(!empty($totalPriceValue)) {
                            $totalPrice = $totalPriceValue['totalPrice'];
                            $model['buy_quantity'] = $totalPriceValue['buyQuantity'];
                            $offerCount = $totalPriceValue['offerCount'];
                            $purchaseProductArr = $totalPriceValue['purchaseProductArr'];
                        }
                    }
                }
            }
        } 

        // If no specail price is calculate then Calculate with tradition way
        if($totalPrice == 0) {
            $totalPrice = $model['price'] * $model['buy_quantity'];
        }
        return $totalPrice;
    }

    // Combine session and listed data
    // @return array which has list of product and grand total
    // @param array $model -> Product details array
    //        boolen $isIndexPage -> Combine data for index page is this flag is 'Y' else for checkout page
    private function FetchSessionData($model, $isIndexPage = 'Y') {
        $return_arr = array();
        if(!empty($model)) {
            $is_session_set = false;
            $grandTotal = 0;
            $purchaseProductArr = array();
            // Check session is set or not
            if(isset($_SESSION['SHOPPINGCART']['PRODUCTLIST']) && !empty($_SESSION['SHOPPINGCART']['PRODUCTLIST'])) {
                $is_session_set = true;
                $sessionCartValue = $_SESSION['SHOPPINGCART']['PRODUCTLIST'];
                $purchaseProductArr = $this->FetchSessionProductData();
            }
            foreach ($model as $key => $value) {
                if($is_session_set && isset($sessionCartValue[md5($value['id'])])) {
                    // If session is set but Procut qty is not aviable then unset session for the product
                    if($value['qty'] < $sessionCartValue[md5($value['id'])]['buy_quantity']) {
                        unset($_SESSION['SHOPPINGCART']['PRODUCTLIST'][md5($value['id'])]);
                        if($isIndexPage == 'Y') {
                            $model[$key]['buy_price'] = 0;
                            $model[$key]['buy_quantity'] = 0;
                            $model[$key]['action'] = 'Add';
                        } else {
                            unset($model[$key]);     
                        }
                    } else {
                        // Calculate price again on loading pages
                        $model[$key]['buy_quantity'] = $sessionCartValue[md5($value['id'])]['buy_quantity'];
                        $model[$key]['action'] = 'Remove';
                        $totalPrice = $this->TotalPriceCal($model[$key], $purchaseProductArr);
                        if($sessionCartValue[md5($value['id'])]['buy_price'] != $totalPrice) {
                            $model[$key]['buy_price'] = $totalPrice;
                            $_SESSION['SHOPPINGCART']['PRODUCTLIST'][md5($value['id'])]['buy_price'] = $totalPrice;
                            $_SESSION['SHOPPINGCART']['PRODUCTLIST'][md5($value['id'])]['price'] = $value['price'];
                            $_SESSION['SHOPPINGCART']['PRODUCTLIST'][md5($value['id'])]['offer_values'] = $value['offer_values'];
                            $grandTotal = $grandTotal + $totalPrice;
                        } else {
                            $model[$key]['buy_price'] = $sessionCartValue[md5($value['id'])]['buy_price'];
                            $grandTotal = $grandTotal + $sessionCartValue[md5($value['id'])]['buy_price'];
                        }
                    }
                } else {
                    if($isIndexPage == 'Y') {
                        $model[$key]['buy_price'] = 0;
                        $model[$key]['buy_quantity'] = 0;
                        $model[$key]['action'] = 'Add';
                    } else {
                        unset($model[$key]);
                    }
                }
            }
            $return_arr = array(
                'product_list' => $model,
                'grand_total' => $grandTotal,
            );
        }
        return $return_arr;
    }

    // For Calculate Buy Price and Grand Total Price with Spceial price conditions 
    // @return total price
    // @param array $model -> Product details array
    private function CalculatespecialPrice($buyQuantity, $offerQty, $totalPrice, $productPrice, $offerPrice, $offerCount, $offerProduct, $purchaseProductArr) {
        if($buyQuantity > 0){
            if($buyQuantity >= $offerQty)  {
                if(!empty($offerProduct)) {
                    if(in_array($offerProduct, array_column($purchaseProductArr, 'name')) && ($buyQuantity >= $purchaseProductArr[$offerProduct]['buy_quantity']) && ($purchaseProductArr[$offerProduct]['buy_quantity'] > 0)) {
                        $totalPrice = $totalPrice + $offerPrice;
                        $buyQuantity = $buyQuantity - $offerQty;
                        $purchaseProductArr[$offerProduct]['buy_quantity']  = $purchaseProductArr[$offerProduct]['buy_quantity'] - $offerQty;
                        return $this->CalculatespecialPrice($buyQuantity, $offerQty, $totalPrice, $productPrice, $offerPrice, $offerCount,$offerProduct, $purchaseProductArr);
                    } else {
                        if($offerCount <= 0) {
                            $totalPrice = $totalPrice + $buyQuantity * $productPrice;
                            $buyQuantity = $buyQuantity - $buyQuantity;
                            return $this->CalculatespecialPrice($buyQuantity, $offerQty, $totalPrice, $productPrice, $offerPrice, $offerCount,$offerProduct, $purchaseProductArr);
                        } else {
                            $offerCount = $offerCount - 1;
                            $return_arr = array('totalPrice' => $totalPrice, 'buyQuantity' => $buyQuantity, 'offerCount' => $offerCount, 'purchaseProductArr' => $purchaseProductArr);
                            return $return_arr;
                        }
                    }
                } else {
                    // Get Combination qty and calculation Prince according to that
                    $totalPrice = $totalPrice + $offerPrice;
                    $buyQuantity = $buyQuantity - $offerQty;
                    return $this->CalculatespecialPrice($buyQuantity, $offerQty, $totalPrice, $productPrice, $offerPrice, $offerCount, $offerProduct, $purchaseProductArr);
                }
            } else {
                if($offerCount <= 0) {
                    $totalPrice = $totalPrice + $buyQuantity * $productPrice;
                    $buyQuantity = $buyQuantity - $buyQuantity;
                    return $this->CalculatespecialPrice($buyQuantity, $offerQty, $totalPrice, $productPrice, $offerPrice, $offerCount, $offerProduct, $purchaseProductArr);
                } else {
                    $offerCount = $offerCount - 1;
                    $return_arr = array('totalPrice' => $totalPrice, 'buyQuantity' => $buyQuantity, 'offerCount' => $offerCount, 'purchaseProductArr' => $purchaseProductArr);
                    return $return_arr;
                }   
            }
        } else {
            $offerCount = $offerCount - 1;
            $return_arr = array('totalPrice' => $totalPrice, 'buyQuantity' => $buyQuantity, 'offerCount' => $offerCount, 'purchaseProductArr' => $purchaseProductArr);
            return $return_arr;   
        }
    }

    // @return Product name with its qty
    // @param void
    public function FetchSessionProductData() {
        $purchaseProductArr = array();
        $sessionCartValue = $_SESSION['SHOPPINGCART']['PRODUCTLIST'];
        foreach ($sessionCartValue as $key => $value) {
            $purchaseProductArr[$value['name']] = array(
                'name' => $value['name'],
                'buy_quantity' => $value['buy_quantity'],
            );
        }
        return $purchaseProductArr;
    }

    public function CheckSession() {
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
    }
}

?>