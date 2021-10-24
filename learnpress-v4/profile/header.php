<?php defined('ABSPATH') || exit;

$profile = LP_Profile::instance();
$user = $profile->get_user();

if (! isset($user)) {
  return;
}

$bio = $user->get_description();
?>
<div class="lp-profile-right">
  <h3 class="lp-profile-username">
    <?php echo $user->get_display_name(); ?>
  </h3>
  <?php if ($bio) { ?>
  <div class="lp-profile-user-bio">
    <?php echo wpautop($bio); ?>
  </div>
  <?php } ?>
</div>
