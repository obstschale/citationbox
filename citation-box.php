<?php
/*
 * Plugin Name: Citation Box
 * Plugin URI: http://gautamthapar.me/
 * Description: Looks for Links in a post and displays them in a citation box at the end of each post
 * Version: 0.1dev
 * Author: Hans-Helge Buerger
 * Author URI: http://hanshelgebuerger.de/
 * License: GPLv3 or later
 * Text Domain: citationbox
 */

define('TEXTDOMAIN', 'citationbox');
define('CB_OPTION_NAME', 'cb_options' );
$plugin = plugin_basename( __FILE__ );

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
		__( 'Single', TEXTDOMAIN ),
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

function cb_add_page() {
	add_options_page(
		'Citation Box Settings',
		'Citation Box',
		'manage_options',
		'citationbox',
		'cb_options_page'
	);
}
add_action( 'admin_menu', 'cb_add_page' );

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

function cb_validate( $input ){
	$valid = array();
	$valid['single'] = ( isset( $input['single'] ) ) ? true : false;
	$valid['page'] = ( isset( $input['page'] ) ) ? true : false;
	$valid['home'] = ( isset( $input['home'] ) ) ? true : false;

	return $valid;
}

function plugin_add_settings_link( $links ) {
	$settings_link = '<a href="options-general.php?page=citationbox">Settings</a>';
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