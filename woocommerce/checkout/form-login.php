<?php

defined('ABSPATH') || exit;

if (is_user_logged_in() || 'no' === get_option('woocommerce_enable_checkout_login_reminder')) {
  return;
}

?>
<div class="woocommerce-form-login-toggle">
	<?php wc_print_notice('<b>Existing member?</b>'.' <a href="#" class="showlogin" style="background: #dc7927; color: white; text-transform:uppercase; float:right; padding: 4px 8px">'.esc_html__('Click here to login', 'woocommerce').'</a>', 'notice'); ?>
</div>
<?php

woocommerce_login_form(
  [
    'message' => esc_html__('If you are a member, please login. If you are registering for the first time, please skip this section and enter your complete details below.', 'woocommerce'),
    'redirect' => wc_get_checkout_url(),
    'hidden' => true,
  ]
);
