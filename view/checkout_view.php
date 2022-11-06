<table class="table table-striped table-hover dt-datatable">
  <thead>
    <tr>
      <th>Sr</th>
      <th>Product Name</th>
      <th>Qty</th>
      <th>Price</th>
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
            <td><?=$article['buy_quantity']?></td>
            <td><?=$article['buy_price']?></td>
          </form>
        </tr>
      <?php $i++; } ?>
      <tr>
        <b>
          <td colspan="3">Total Price</td>
          <td><span id="display_grand_total"><?=$model['grand_total']?></span></td>
        </b>
      </tr>
      <tr>
        <b>
          <td colspan="3"></td>
          <td><a href="?c=ShoppingCart" class="btn btn-primary">Back</a></td>
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