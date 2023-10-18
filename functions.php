<?php
function free_shipping_for_qualified_products( $rates ) {
  $eligibletotal = 0;
  $free_shipping_threshold = 200; //Free shipping threshold set to $200
  
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$product = $cart_item['data'];
		$price = $cart_item['quantity'] * $product->get_price();

    //Ignore products with tag 'free_shipping_ignore' from counting towards free shipping threshold
		if ( !is_object_in_term( $cart_item['product_id'], 'product_tag', 'free_shipping_ignore' ) ) {
			$eligibletotal = $eligibletotal + $price;
		}
	}

  if ( $eligibletotal < $free_shipping_threshold ) {
    foreach( $rates as $rate_id => $rate_val ) {
      if ( 'free_shipping' === $rate_val->get_method_id() ) {
        unset( $rates[ $rate_id ] );
        break;
      }
    }
  }
  return $rates;
}

add_filter( 'woocommerce_package_rates', 'free_shipping_for_qualified_products', 10 );
?>
