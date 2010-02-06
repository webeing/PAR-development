<?php do_action( 'bp_before_search_login_bar' ) ?>

		<div id="search-login-bar">

			<form action="<?php echo bp_search_form_action() ?>" method="post" id="search-form">
				<input type="text" id="search-terms" name="search-terms" value="" />
				<?php echo bp_search_form_type_select() ?>

				<input type="submit" name="search-submit" id="search-submit" value="<?php _e( 'Search', 'buddypress' ) ?>" />
				<?php wp_nonce_field( 'bp_search_form' ) ?>
			</form>

			<?php if ( !is_user_logged_in() ) : ?>

				<form name="login-form" id="login-form" action="<?php echo site_url( 'wp-login.php' ) ?>" method="post">
					<input type="text" name="log" id="user_login" value="<?php _e( 'Username', 'buddypress' ) ?>" onfocus="if (this.value == '<?php _e( 'Username', 'buddypress' ) ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Username', 'buddypress' ) ?>';}" />
					<input type="password" name="pwd" id="user_pass" class="input" value="" />

					<input type="checkbox" name="rememberme" id="rememberme" value="forever" title="<?php _e( 'Remember Me', 'buddypress' ) ?>" />

					<input type="submit" name="wp-submit" id="wp-submit" value="<?php _e( 'Log In', 'buddypress' ) ?>"/>

					<?php if ( 'none' != bp_get_signup_allowed() && 'blog' != bp_get_signup_allowed() ) : ?>
						<input type="button" name="signup-submit" id="signup-submit" value="<?php _e( 'Sign Up', 'buddypress' ) ?>" onclick="location.href='<?php echo bp_signup_page() ?>'" />
					<?php endif; ?>

					<input type="hidden" name="redirect_to" value="<?php echo bp_root_domain() ?>" />
					<input type="hidden" name="testcookie" value="1" />

					<?php do_action( 'bp_login_bar_logged_out' ) ?>
				</form>

			<?php else : ?>

				<div id="logout-link">
					<?php bp_loggedin_user_avatar( 'width=20&height=20' ) ?> &nbsp; <?php bp_loggedinuser_link() ?> / <?php bp_log_out_link() ?>

					<?php do_action( 'bp_login_bar_logged_in' ) ?>
				</div>

			<?php endif; ?>

			<?php do_action( 'bp_search_login_bar' ) ?>