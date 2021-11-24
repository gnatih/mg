<?php
defined('ABSPATH') || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action('woocommerce_cart_is_empty');

$page = get_page_by_title('All Maps');
var_dump($page->link);
exit;

if (wc_get_page_id('shop') > 0) { ?>
<p class="return-to-shop">
	<a class="button wc-backward" href="/mappinggovernance/all-maps">View all Maps</a>
</p>
<?php }
