<?php
/*
 * Plugin Name: Citation Box
 * Plugin URI: https://github.com/obstschale/citationbox
 * Description: Looks for Links in a posts / pages and displays them in a citation box at the end of each site
 * Version: 0.1.1
 * Author: Hans-Helge Buerger
 * Author URI: http://hanshelgebuerger.de/
 * License: GPLv2 or later
 * Text Domain: citationbox
 */

define('TEXTDOMAIN', 'citationbox');
define('CB_OPTION_NAME', 'cb_options' );
$plugin = plugin_basename( __FILE__ );

function cb_init() {
	load_plugin_textdomain( TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'cb_init' );

function cb_setup() {
	register_setting(
		'citationbox_options',
		CB_OPTION_NAME,
		'cb_validate'
	);

	add_settings_section(
		'citationbox_section',
		__( 'General Settings', TEXTDOMAIN ),
		'cb_general_settings',
		'citationbox_options'
	);

	add_settings_field(
		'cb_single',
		__( 'Post', TEXTDOMAIN ),
		'cb_setting_single',
		'citationbox_options',
		'citationbox_section'
	);

	add_settings_field(
		'cb_page',
		__( 'Page', TEXTDOMAIN ),
		'cb_setting_page',
		'citationbox_options',
		'citationbox_section'
	);

	add_settings_field(
		'cb_home',
		__( 'Home', TEXTDOMAIN ),
		'cb_setting_home',
		'citationbox_options',
		'citationbox_section'
	);

	add_settings_field(
		'cb_color_bg',
		__( 'Background Color', TEXTDOMAIN ),
		'cb_setting_color_bg',
		'citationbox_options',
		'citationbox_section'
	);

	add_settings_field(
		'cb_color_border',
		__( 'Bottom Border Color', TEXTDOMAIN ),
		'cb_setting_color_border',
		'citationbox_options',
		'citationbox_section'
	);

	add_settings_field(
		'cb_color_link',
		__( 'Link Color', TEXTDOMAIN ),
		'cb_setting_color_link',
		'citationbox_options',
		'citationbox_section'
	);

	add_settings_field(
		'cb_color_link_hover',
		__( 'Link Hover Color', TEXTDOMAIN ),
		'cb_setting_color_link_hover',
		'citationbox_options',
		'citationbox_section'
	);

	add_settings_field(
		'cb_color_text',
		__( 'Text Color', TEXTDOMAIN ),
		'cb_setting_color_text',
		'citationbox_options',
		'citationbox_section'
	);

	add_settings_field(
		'cb_color_reset',
		__( 'Reset Colors', TEXTDOMAIN ),
		'cb_setting_color_reset',
		'citationbox_options',
		'citationbox_section'
	);
}
add_action( 'admin_init', 'cb_setup' );

function cb_general_settings() {
	echo "<p>" . _e( 'General Settings for Citation Box Plugin', TEXTDOMAIN ) . "</p>";
}

function cb_setting_single() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<input type="checkbox" name="<?php echo CB_OPTION_NAME?>[single]"
	<?php if ( isset( $options['single'] ) and $options['single'] ): ?>
		checked
	<?php endif ?>/>
	<p class="description"><?php _e( 'Display Citation Box on single post pages', TEXTDOMAIN ); ?></p>
<?php }

function cb_setting_page() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<input type="checkbox" name="<?php echo CB_OPTION_NAME?>[page]"
	<?php if ( isset( $options['page'] ) and $options['page'] ): ?>
		checked
	<?php endif ?>/>
	<p class="description"><?php _e( 'Display Citation Box on pages', TEXTDOMAIN ); ?></p>
<?php }

function cb_setting_home() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<input type="checkbox" name="<?php echo CB_OPTION_NAME?>[home]"
	<?php if ( isset( $options['home'] ) and $options['home'] ): ?>
		checked
	<?php endif ?>/>
	<p class="description"><?php _e( 'Display Citation Box on home page', TEXTDOMAIN ); ?></p>
<?php }

function cb_setting_color_bg() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<div class="color-picker" style="position: relative;">
		<input type="text" name="<?php echo CB_OPTION_NAME?>[color_bg]"
			value="<?php if ( isset( $options['color_bg'] ) ):
				echo esc_attr( $options['color_bg'] );
			else:
				echo '#D6E8F2';
			endif ?>" id="color_bg" />
		<input type='button' class='pickcolor button-secondary' value='<?php _e( 'Select Color', TEXTDOMAIN ); ?>' >
		<div class="colorpicker_bg" style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>
	</div>
<?php }

function cb_setting_color_border() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<div class="color-picker" style="position: relative;">
		<input type="text" name="<?php echo CB_OPTION_NAME?>[color_border]"
			value="<?php if ( isset( $options['color_border'] ) ):
				echo esc_attr( $options['color_border'] );
			else:
				echo '#5CACE2';
			endif ?>" id="color_border" />
		<input type='button' class='pickcolor button-secondary' value='<?php _e( 'Select Color', TEXTDOMAIN ); ?>' >
		<div class="colorpicker_border" style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>
	</div>
<?php }

function cb_setting_color_link() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<div class="color-picker" style="position: relative;">
		<input type="text" name="<?php echo CB_OPTION_NAME?>[color_link]"
			value="<?php if ( isset( $options['color_link'] ) ):
				echo esc_attr( $options['color_link'] );
			else:
				echo '#5CACE2';
			endif ?>" id="color_link" />
		<input type='button' class='pickcolor button-secondary' value='<?php _e( 'Select Color', TEXTDOMAIN ); ?>' >
		<div class="colorpicker_link" style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>
	</div>
<?php }

function cb_setting_color_link_hover() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<div class="color-picker" style="position: relative;">
		<input type="text" name="<?php echo CB_OPTION_NAME?>[color_link_hover]"
			value="<?php if ( isset( $options['color_link_hover'] ) ):
				echo esc_attr( $options['color_link_hover'] );
			else:
				echo '#006385';
			endif ?>" id="color_link_hover" />
		<input type='button' class='pickcolor button-secondary' value='<?php _e( 'Select Color', TEXTDOMAIN ); ?>' >
		<div class="colorpicker_link_hover" style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>
	</div>
<?php }

function cb_setting_color_text() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<div class="color-picker" style="position: relative;">
		<input type="text" name="<?php echo CB_OPTION_NAME?>[color_text]"
			value="<?php if ( isset( $options['color_text'] ) ):
				echo esc_attr( $options['color_text'] );
			else:
				echo '#000000';
			endif ?>" id="color_text" />
		<input type='button' class='pickcolor button-secondary' value='<?php _e( 'Select Color', TEXTDOMAIN ); ?>' >
		<div class="colorpicker_text" style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>
	</div>
<?php }

function cb_setting_color_reset() {
	?>
	<div class="color-picker" style="position: relative;">
		<input type='button' id="color_reset" class='button-secondary' value='<?php _e( 'Reset Colors', TEXTDOMAIN ); ?>' >
	</div>
<?php }

function cb_add_page() {
	global $cb_setting_page;

	$cb_setting_page = add_options_page(
		'Citation Box Settings',
		'Citation Box',
		'manage_options',
		'citationbox',
		'cb_options_page'
	);
	add_action( 'admin_enqueue_scripts', 'cb_admin_scripts' );
}
add_action( 'admin_menu', 'cb_add_page' );

function cb_wp_head() {
	$options = get_option( CB_OPTION_NAME );
	$color_bg = ( isset( $options['color_bg'] ) ) ? $options['color_bg'] : '';
	$color_border = ( isset( $options['color_border'] ) ) ? $options['color_border'] : '';
	$color_link = ( isset( $options['color_link'] ) ) ? $options['color_link'] : '';
	$color_link_hover = ( isset( $options['color_link_hover'] ) ) ? $options['color_link_hover'] : '';
	$color_text = ( isset( $options['color_text'] ) ) ? $options['color_text'] : '';

	echo "<style> #citationbox {
		color: $color_text;
		background-color: $color_bg;
		border-bottom-color: $color_border;
	}
	#citationbox a {
		color: $color_link;
	}
	#citationbox a:hover {
		color: $color_link_hover;
	}
	</style>";
}
add_action( 'wp_head', 'cb_wp_head' );

// Print the menu page itself
function cb_options_page() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<div class="wrap">
		<h2><?php _e( 'Citation Box Options', TEXTDOMAIN ); ?></h2>
		<form method="post" action="options.php">
			<?php wp_nonce_field( 'update-options' ); ?>
			<?php settings_fields( 'citationbox_options' ); ?>
			<?php do_settings_sections( 'citationbox_options' ); ?>

			<?php submit_button(); ?>
		</form>
	</div><?php
}

function cb_admin_scripts() {
	global $cb_setting_page;
	$screen = get_current_screen();

	/*
	 * Check if current screen is My Admin Page
	 * Don't add help tab if it's not
	 */
	if ( $screen->id != $cb_setting_page )
		return;

	// include farbtastic and own JS
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
	wp_enqueue_script( 'citationbox-script', plugins_url( 'assets/citationbox.js', __FILE__ ), array( 'farbtastic', 'jquery' ) );

}

function cb_validate( $input ){
	$valid = array();
	$valid['single'] = ( isset( $input['single'] ) ) ? true : false;
	$valid['page'] = ( isset( $input['page'] ) ) ? true : false;
	$valid['home'] = ( isset( $input['home'] ) ) ? true : false;
	$valid['color_bg'] = ( isset( $input['color_bg'] ) ) ? $input['color_bg'] : '#D6E8F2';
	$valid['color_border'] = ( isset( $input['color_border'] ) ) ? $input['color_border'] : '#5CACE2';
	$valid['color_link'] = ( isset( $input['color_link'] ) ) ? $input['color_link'] : '#5CACE2';
	$valid['color_link_hover'] = ( isset( $input['color_link_hover'] ) ) ? $input['color_link_hover'] : '#006385';
	$valid['color_text'] = ( isset( $input['color_text'] ) ) ? $input['color_text'] : '#000000';

	return $valid;
}

function plugin_add_settings_link( $links ) {
	$settings_link = '<a href="options-general.php?page=citationbox">' . __( 'Settings', TEXTDOMAIN ) . '</a>';
	array_push( $links, $settings_link );
	return $links;
}
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );


add_filter( 'the_content', 'cb_run' );
add_action( 'wp_enqueue_scripts', 'cb_run' );
function cb_run( $content ) {
	$options = get_option( CB_OPTION_NAME );

	if ( isset( $options['single'] )
		and $options['single']
		and is_single()

		or isset( $options['page'] )
		and $options['page']
		and is_page()

		or isset( $options['home'] )
		and $options['home']
		and is_home()
	):
		$content = cb_find_links( $content );
		cb_style();
	endif;

	return $content;
}

function cb_find_links( $content ) {

	$box = array();
	$domDoc = new DOMDocument( '1.0', 'utf-8' );
	$domDoc->loadHTML(
		'<?xml encoding="UTF-8">'
		. $content
	);
	$links = $domDoc->getElementsByTagName('a');

	$i = $links->length - 1;
	$count = 0;
	while ( $i > -1 ):
		$link = $links->item( $i );
		$ignore = false;
		if ( $link->hasAttribute( 'href' )
			and strcmp( $link->firstChild->nodeName, 'img' ) != 0
			and strcmp( $link->getAttribute( 'class' ), 'more-link' ) != 0 ) {
			$count++;
		}
		$i--;
	endwhile;

	$i = $links->length - 1;
	while ( $i > -1 ):
		$link = $links->item( $i );
		$ignore = false;

		if ( $link->hasAttribute( 'href' )
			and strcmp( $link->firstChild->nodeName, 'img' ) != 0
			and strcmp( $link->getAttribute( 'class' ), 'more-link' ) != 0 ) {

				$text = $link->nodeValue . "[$count]";
				$refElement = $domDoc->createElement( 'span', $text );
				// $countLink = $domDoc->createElement( 'a', "[$count]" );
				// $countLink->setAttribute( 'href', '#citationbox' );
				// $refElement->appendChild( $countLink );

				$boxElement = $link;
				$url = htmlspecialchars( $boxElement->getAttribute( 'href' ) );
				if ( strlen( $url ) > 40 ) {
					$url = substr( $url, 0, 40 );
					$url .= '...';
				}
				$boxElement->nodeValue = $url;
				$box[$count] = $boxElement;
				$link->parentNode->replaceChild( $refElement, $link );
				$count--;
		}

		$i--;

	endwhile;

	if (sizeof( $box ) > 0 ):
		$boxObject = new ArrayObject( $box );
		$boxObject->ksort();

		$list = $domDoc->createElement( 'ul' );
		$list->setAttribute( 'id', 'citationbox' );
		$domDoc->appendChild( $list );
		$cb_title = $domDoc->createElement( 'h3', __( 'Links', TEXTDOMAIN ) );
		$list->appendChild( $cb_title );

		foreach ( $boxObject as $count => $node):
			$item = $domDoc->createElement( 'li' );
			$itemCount = $domDoc->createTextNode( "[$count] " );
			$item->appendChild( $itemCount );
			$item->appendChild( $node );
			$list->appendChild( $item );
		endforeach;

		return $domDoc->saveHTML();
	endif;
	return $content;
}

function cb_style() {
	wp_enqueue_style( 'citationbox-style', plugins_url( 'assets/citationbox.css', __FILE__ ) );
}

?>