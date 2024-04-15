<?php

namespace Intranet;

class Login {

	/**
	 * @param Intranet $param
	 */
	public function __construct( private readonly Intranet $intranet ) {

		add_filter( 'login_display_language_dropdown', '__return_false' );
		add_filter( 'login_site_html_link', '__return_false' );
		add_filter( 'lost_password_html_link', '__return_false' );
		add_filter( 'register', '__return_false' );
		add_filter( 'login_link_separator', '__return_false' );

		add_filter( 'login_headerurl', [ $this, 'login_headerurl' ] );
		add_filter( 'login_errors', [ $this, 'change_login_errors' ] );

		add_action( 'login_enqueue_scripts', [ $this, 'login_logo' ] , 90);
		add_action( 'login_enqueue_scripts', [ $this, 'hidden_form' ], 90 );

		add_action( 'wp_login', [ $this, 'wp_login_out' ], 20 );
		add_action( 'wp_logout', [ $this, 'wp_login_out' ] );

	}

	public function wp_login_out(): void {
		$redirect_url = site_url();
		wp_safe_redirect( $redirect_url );

	}

	public function login_headerurl() {
		return get_bloginfo( 'url' );
	}

	public function change_login_errors() {
		return __( 'Looks like something&#8217;s gone wrong. Wait a couple seconds, and then try again.' );
	}

	public function login_logo() {
		$this->intranet->plugin->load_dependencies('login');
		$image = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
		if ( $image ) {
			$this->login_custom_css( $image[1], $image[2], $image[0] );
		}
	}

	private function login_custom_css( $width, $height, $url ): void {
		$custom_css = "#login h1 a, .login h1 a {background-image: url({$url}); width: {$width}px; height: {$height}px;}";
		wp_add_inline_style( 'login', $custom_css );
	}
	public function hidden_form(): void {
		if ( isset( $_GET['form'] )  && $_GET['form'] === "true" ):
			$custom_css = "#loginform { display: block !important }";
			wp_add_inline_style( 'login', $custom_css );
		endif;
	}

}