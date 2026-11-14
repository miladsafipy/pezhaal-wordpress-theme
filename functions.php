<?php
defined( 'ABSPATH' ) || exit;

// Theme Setup
require_once get_template_directory() . '/inc/setup/enqueue.php';
require_once get_template_directory() . '/inc/setup/menus.php';
require_once get_template_directory() . '/inc/setup/theme-support.php';

// Custom Admin Dashboard
require_once get_template_directory() . '/inc/admin/hero-slider.php';
require_once get_template_directory() . '/inc/admin/custom-footer.php';

// Translator
require_once get_template_directory() . '/inc/language/translator.php';



// Golden Packages System
 

require_once get_template_directory() . '/inc/golden-packages/register-package.php';
require_once get_template_directory() . '/inc/golden-packages/package-fields.php';
require_once get_template_directory() . '/inc/golden-packages/package-save.php';
require_once get_template_directory() . '/inc/golden-packages/package-pricing.php';
require_once get_template_directory() . '/inc/golden-packages/package-cart.php';
require_once get_template_directory() . '/inc/helpers/disable-package-block.php';

















