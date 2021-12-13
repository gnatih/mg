<?php
defined('ABSPATH') || exit();

$user = LP_Global::user();
?>
<div style="display:inline-block">
	<form name="continue-course" class="continue-course form-button lp-form" method="post" action="" style="display:none">
		<button type="submit" class="lp-button button">
			<?php esc_html_e('Continue', 'learnpress'); ?>
		</button>
	</form>
</div>
