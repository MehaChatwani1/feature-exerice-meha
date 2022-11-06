<table class="table table-striped table-hover dt-datatable">
  <thead>
    <tr>
      <th>Sr</th>
      <th>Product Name</th>
      <th>Quantity</th>
      <th>Unit Price</th>
      <th>Offers Value</th>
      <th>Select qty to Purchase</th>
      <th>Purchase Price</th>
      <th class="no-sort">Actions</th>
    </tr>
  </thead>
  <?php if(!empty($model)) { 
    $i=1;?>
    <tbody>
      <?php foreach ($model['product_list'] as $article) { ?>
        <tr>
          <form class="form-inline my-2 my-lg-0" id='cartform_<?=$i?>' method="POST">
            <td><?=$i;?></td>
            <td><?=$article['name']?></td>
            <td><?=$article['qty']?></td>
            <td><?=$article['price']?></td>
            <td><?=$article['offer_values']?></td>
            <td>
              <input type="hidden" id="proid_<?=$i?>" name="proid" value="<?=md5($article['id']);?>">
              <input type="hidden" id="name_<?=$i?>" name="name" value="<?=$article['name']?>">
              <input type="hidden" id="price_<?=$i?>" name="price" value="<?=$article['price']?>">
              <input type="number" id="buy_quantity_<?=$i?>" name="buy_quantity" data-id="<?=$i?>" class="buy_quantity" value = "<?=$article['buy_quantity']?>" min="0" max="<?=$article['qty']?>" <?=$article['buy_quantity'] > 0 ? 'disabled' : ''?>>
              <input type="hidden" id="offer_values_<?=$i?>" name="offer_values" data-id="<?=$i?>" class="offer_values" value = "<?=htmlentities($article['offer_values'])?>">
              <input type="hidden" id="action_<?=$i?>" name="action" value="<?=$article['action']?>">
              <input type="hidden" id="grand_total_<?=$i?>" class="grand_total" name="grand_total" value="<?=$model['grand_total']?>">
            </td>
            <td><span id="buy_price_<?=$i?>"><?=$article['buy_price']?></span></td>
            <td>
              <a href="#" class="btn btn-primary add_to_cart" data-id="<?=$i?>" id="add_to_cart_<?=$i?>" <?=$article['action'] == 'Add' ? '' : 'style="display: none;"'?>>Add to Cart</a>
              <a href="#" class="btn btn-danger remove_from_cart" data-id="<?=$i?>" id="remove_from_cart_<?=$i?>" <?=$article['action'] == 'Remove' ? '' : 'style="display: none;"'?>>Remove</a>
            </td>
          </form>
        </tr>
      <?php $i++; } ?>
      <tr bgcolor="silver">
        <b>
          <td colspan="6">Total Price</td>
          <td><span id="display_grand_total"><?=$model['grand_total']?></span></td>
          <td><a href="?c=ShoppingCart&a=CheckOutPage" class="btn btn-success">CheckOut</a></td>
        </b>
      </tr>
    </tbody>
  <?php } else { ?>  
    <tfoot>
      <tr>
        <td colspan="6">No Products Found</td> 
      </tr>
    </tfoot>
  <?php } ?>
</table>