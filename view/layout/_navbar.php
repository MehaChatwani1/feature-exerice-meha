<nav class="navbar navbar-expand-lg navbar-light bg-light" <?=$PAGE == 'Welcome' ? 'style = "display:none"' : '' ?>>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_toggler_value" aria-controls="navbar_toggler_value" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbar_toggler_value">
    <a class="navbar-brand mr-auto " href="#"><?=$PAGE?></a>
    <ul class="navbar-nav mt-2 mt-lg-0">
      <li class="nav-item">
        <a class="nav-link" href="?c=Welcome">Home</a>
      </li>
      <li class="nav-item <?=$PAGE == 'Products' ? 'active' : '' ?>">
        <a class="nav-link" href="?c=ShoppingCart">Products</a>
      </li>
      <li class="nav-item <?=$PAGE == 'CheckOut' ? 'active' : '' ?>">
        <a class="nav-link" href="?c=ShoppingCart&a=CheckOutPage"> CheckOut (<span id="purchase_count"><?= isset($_SESSION['SHOPPINGCART']['PRODUCTLIST']) && !empty($_SESSION['SHOPPINGCART']['PRODUCTLIST']) ? count($_SESSION['SHOPPINGCART']['PRODUCTLIST']) : 0 ?></span>) </a>
      </li>
    </ul>
  </div>
</nav>