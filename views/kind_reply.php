<?php
/*
 * Reply Template
 *
 */

$mf2_post = new MF2_Post( get_the_ID() );
		$cite     = $mf2_post->fetch();
		$meta = new Kind_Meta( get_the_ID() );
		$author   = array();
		if ( isset( $cite['author'] ) ) {
			$author = Kind_View::get_hcard( $cite['author'] );
		}
		$url = '';
		if ( isset( $cite['url'] ) ) {
			$url = $cite['url'];
		}
		$site_name = Kind_View::get_site_name( $cite );
		$title     = Kind_View::get_cite_title( $cite );
		$embed     = self::get_embed( $url );
		$embed_html = self::get_embed( $meta->get_url() );
		if ( '' !== $embed_html  ) {
			$dom = new DOMDocument;
			$dom->loadHTML( $embed_html, LIBXML_HTML_NOIMPLIED );

			$nodelinks = $dom->getElementsByTagName( 'a' );
			$links = iterator_to_array( $nodelinks );
			$count = count( $links );
			$i = 0;

			foreach ( $links as $link ) $i++; {
				if ( $i === $count ) {
					$link->setAttribute( 'class', 'u-url' );
				}
			};
			$embed_html = $dom->saveHTML();
		}

// Add in the appropriate type
$type = Kind_Taxonomy::get_kind_info( $kind, 'property' );
if ( ! empty( $type ) ) {
	$type = 'u-' . $type;
}
?>

 <section class="h-cite response <?php echo $type; ?> ">
 <header>
 <?php
echo Kind_Taxonomy::get_before_kind( $kind );
if ( ! $embed ) {
	if ( ! array_key_exists( 'name', $cite ) ) {
		$cite['name'] = self::get_post_type_string( $url );
	}
	if ( isset( $url ) ) {
		echo sprintf( '<a href="%1s" class="p-name u-url">%2s</a>', $url, $cite['name'] );
	} else {
		echo sprintf( '<span class="p-name">%1s</span>', $cite['name'] );
	}
	if ( $author ) {
		echo ' ' . __( 'by', 'indieweb-post-kinds' ) . ' ' . $author;
	}
	if ( array_key_exists( 'publication', $cite ) ) {
		echo sprintf( ' <em>(<span class="p-publication">%1s</span>)</em>', $cite['publication'] );
	}
}
?>
</header>
<?php
	if ( $cite ) {
		if ( $embed_html ) {
			if ( ! get_the_title() ) {
					echo '<div class="p-content">' . $embed_html . '</div>';	} else {
					echo '<div class="p-content">' . $embed_html . '</div>';
					}
		} elseif ( array_key_exists( 'summary', $cite ) ) {
			echo sprintf( '<blockquote class="p-content">%1s</blockquote>', $cite['summary'] );
		}
	}
	// Close Response.
	?>
</section>