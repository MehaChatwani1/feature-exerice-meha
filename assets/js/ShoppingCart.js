$(document).ready(function() {
  // Click Event of Add to Cart btn
  $(".add_to_cart").click(function(e) {
    e.preventDefault();
    indexId = $(this).attr('data-id');
    submitCartFrom(indexId, 'Y');
  });

  // Click Event of Remove
  $(".remove_from_cart").click(function(e) {
    e.preventDefault();
    indexId = $(this).attr('data-id')
    submitCartFrom(indexId, 'N');
  });

  // Do activity for front end
  function displayAddToCart(index, isAdd = 'Y') {
    if(isAdd == 'Y') {
      updateCartCount(isAdd);
      $("#add_to_cart_"+indexId).hide();
      $("#action_"+indexId).val('Remove');
      $("#remove_from_cart_"+indexId).show();
      $("#buy_quantity_"+indexId).prop('disabled', true);
    } else {
      updateCartCount(isAdd);
      $("#add_to_cart_"+indexId).show();
      $("#action_"+indexId).val('Add');
      $("#remove_from_cart_"+indexId).hide();
      $("#buy_quantity_"+indexId).prop('disabled', false);
    }
  }

  // Cart count update
  function updateCartCount(isAdd = 'Y') {
    currentCount = $("#purchase_count").html();
    if(isAdd == 'Y') {
      newCount = parseInt(currentCount) + 1;
    } else {
      newCount = parseInt(currentCount)-1;
    }
    if(newCount >= 0){
      $("#purchase_count").html(newCount);
    } else {
      alert("Somthing went wrong");
    }
  }

  // Submit form from buy or removing product from cart
  function submitCartFrom(indexId, isAdd = 'Y') {
    $("#grand_total_"+indexId).val($("#display_grand_total").html());
    $.ajax({
      type: "POST",
      url: '?c=ShoppingCart&a=ModifyCart',
      data: $("#cartform_"+indexId).serialize(),
      success: function(response) {
        var jsonData = JSON.parse(response);
        if (jsonData.success == "1") {
          if($("#action_"+indexId).val() == 'Remove') {
            $("#buy_quantity_"+indexId).val(0);
          }
          $("#display_grand_total").html(jsonData.grand_total);
          $("#buy_price_"+indexId).html(jsonData.price);
          alert('Activity successfully perfomed !');
          displayAddToCart(indexId, isAdd);
        } else {
          alert(jsonData.msg);
        }
      }
    });
  }
});