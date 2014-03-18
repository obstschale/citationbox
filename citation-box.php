<?php
/*
 * Plugin Name: Citation Box
 * Plugin URI: http://gautamthapar.me/
 * Description: Looks for Links in a post and displays them in a citation box at the end of each post
 * Version: 0.1dev
 * Author: Hans-Helge Buerger
 * Author URI: http://hanshelgebuerger.de/
 * License: GPLv3 or later
 */

function cb_find_links( $content ) {

	if ( is_single() ) {
		$box = array();
		$domDoc = new DOMDocument;
        $domDoc->loadHTML( $content );

		$links = $domDoc->getElementsByTagName('a');
		$i = $links->length - 1;
		while ( $i > -1 ):
			$link = $links->item( $i );
			$ignore = false;
			$count = $i + 1;

			$text = $link->nodeValue . "[$count]";
			$refElement = $domDoc->createElement( 'span', $text );
			// $countLink = $domDoc->createElement( 'a', "[$count]" );
			// $countLink->setAttribute( 'href', '#citation-box' );
			// $refElement->appendChild( $countLink );

			$boxElement = $link;
			$boxElement->nodeValue = $boxElement->getAttribute( 'href' );
			$box[$count] = $boxElement;

			$link->parentNode->replaceChild( $refElement, $link );

			$i--;
		endwhile;

		$boxObject = new ArrayObject( $box );
		$boxObject->ksort();

		$list = $domDoc->createElement( 'ul' );
		$list->setAttribute( 'id', 'citation-box' );
		$domDoc->appendChild( $list );

		foreach ( $boxObject as $count => $node):
			$item = $domDoc->createElement( 'li' );
			$itemCount = $domDoc->createTextNode( "[$count] " );
			$item->appendChild( $itemCount );
			$item->appendChild( $node );
			$list->appendChild( $item );
		endforeach;

		return $domDoc->saveHTML();
	}

	return $content;
}
add_filter( 'the_content', 'cb_find_links' );

function cb_style() {
	if ( is_single() ) {
		wp_enqueue_style( 'citation-box-style', plugins_url( 'assets/citation-box.css', __FILE__ ) );
	}
}
add_action( 'wp_enqueue_scripts', 'cb_style' );

?>