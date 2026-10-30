<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="auth">

	<div class="auth-container">

		<div class="auth-header">
			<div class="auth-tabs tab-design">
                <button class="login-choice tab active">ورود</button>
                <button class="register-choice tab">ثبت نام</button>
            </div>
		</div>

		<div class="auth-viewport">

			<div class="auth-slider" id="customer_login">

				<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

				<section class="auth-slide" data-slide="login">
					
					
					<?php endif; ?>
					
							<!-- Login Header -->
							<h2><?php esc_html_e( 'ورود', 'woocommerce' ); ?></h2>
					
							<!-- Login Form -->
							<form class="woocommerce-form" method="post" novalidate>
					
								<?php do_action( 'woocommerce_login_form_start' ); ?>
					
								<!-- Username or Email Field -->
								<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
									<label for="username"><?php esc_html_e( 'نام کاربری یا ایمیل', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
									<input type="text" class="woocommerce-Input" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
								</p>
					
								<!-- Password Field -->
								<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
									<label for="password"><?php esc_html_e( 'رمز عبور', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
									<input class="woocommerce-Input" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
								</p>
					
								<?php do_action( 'woocommerce_login_form' ); ?>
					

								<!-- Remember Checkbox -->
								<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
									<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'من را به یاد داشته باش', 'woocommerce' ); ?></span>
								</label>
								<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				
								<!-- Login Button -->
								<button type="submit" class="general-btn" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'ورود', 'woocommerce' ); ?></button>
								
					
								<!-- Forgot Password-->
								<p class="forgot-password">
									<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'رمز عبور را فراموش کرده ام', 'woocommerce' ); ?></a>
								</p>
					
								<?php do_action( 'woocommerce_login_form_end' ); ?>
					
							</form>
					
					<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

				</section>

				<!-- Register Form = Sign Up Form-->
				<section class="auth-slide" data-slide="register">

					<h2><?php esc_html_e( 'ثبت نام', 'woocommerce' ); ?></h2>
			
					<form method="post" class="woocommerce-form" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
			
						<?php do_action( 'woocommerce_register_form_start' ); ?>
			
						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
			
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_username"><?php esc_html_e( 'نام کاربری', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
								<input type="text" class="woocommerce-Input" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
							</p>
			
						<?php endif; ?>
			
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_email"><?php esc_html_e( 'ایمیل', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
							<input type="email" class="woocommerce-Input" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
						</p>
			
						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
			
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_password"><?php esc_html_e( 'رمز عبور', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
								<input type="password" class="woocommerce-Input" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
							</p>
			
						<?php else : ?>
			
							<p><?php esc_html_e( 'برای تایید، یک لینک به ایمیل شما ارسال خواهد شد', 'woocommerce' ); ?></p>
			
						<?php endif; ?>
			
						<?php do_action( 'woocommerce_register_form' ); ?>
			
						<p class="woocommerce-form-row form-row">
							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
							<button type="submit" class="general-btn" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'ثبت نام', 'woocommerce' ); ?></button>
						</p>
			
						<?php do_action( 'woocommerce_register_form_end' ); ?>
			
					</form>

				</section>

				<?php endif; ?>
			</div>


		</div>


	</div>

</div>


<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
