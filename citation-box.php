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
	register_setting( 'citationbox_options', CB_OPTION_NAME);
}
add_action( 'admin_init', 'cb_setup' );

function cb_add_page() {
	add_options_page(
		'Citation Box Settings',
		'Citation Box',
		'manage_options',
		'citationbox',
		'cb_do_options_page'
	);
}
add_action( 'admin_menu', 'cb_add_page' );

// Print the menu page itself
function cb_do_options_page() {
	$options = get_option( CB_OPTION_NAME );
	?>
	<div class="wrap">
		<h2><?php _e( 'Citation Box Options', TEXTDOMAIN ); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'citationbox_options' ); ?>
			<table class="form-table">
				<tr valign="top"><th scope="row"><?php _e( 'Single:', TEXTDOMAIN ); ?></th>
					<td>
						<input type="checkbox" name="<?php echo CB_OPTION_NAME?>[single]"
						<?php if ( isset( $options['single'] ) and 0 == strcmp( $options['single'], 'on' ) ): ?>
							checked
						<?php endif ?>/>
						<p class="description"><?php _e( 'Display Citation Box on single post pages', TEXTDOMAIN ); ?></p>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e( 'Page:', TEXTDOMAIN ); ?></th>
					<td>
						<input type="checkbox" name="<?php echo CB_OPTION_NAME?>[page]"
						<?php if ( isset( $options['page'] ) and 0 == strcmp( $options['page'], 'on' ) ): ?>
							checked
						<?php endif ?>/>
						<p class="description"><?php _e( 'Display Citation Box on pages', TEXTDOMAIN ); ?></p>
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div><?php
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
		and 0 == strcmp( $options['single'], 'on' )
		and is_single()
		or isset( $options['page'] )
		and 0 == strcmp( $options['page'], 'on' )
		and is_page()
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