<?php

namespace PandP_Blocks\Post_Carousel;


add_action( 'plugins_loaded', __NAMESPACE__ . '\register_pandp_blocks_post_carousel' );
/**
 * Register the dynamic block.
 *
 * @since 2.1.0
 *
 * @return void
 */
function register_pandp_blocks_post_carousel() {

	// Only load if Gutenberg is available.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	// Hook server side rendering into render callback
	register_block_type( 'pandp-blocks/post-carousel', [
		'render_callback' => __NAMESPACE__ . '\render_pandp_blocks_post_carousel',
	] );

}

function render_pandp_blocks_post_carousel() {

  $recent_posts = wp_get_recent_posts( [
    'numberposts' => 6,
    'post_status' => 'publish',
  ] );

  if( empty( $recent_posts ) ) {
    return '<p>No posts</p>';
  }

	$markup = '<div class="wp-block-pandp-blocks-post-carousel"><div class="outer-container"><div class="inner-container"><div class="horizontal-container">';

  foreach( $recent_posts as $post ) {

    $post_id = $post['ID'];
    $image = get_the_post_thumbnail( $post_id, $size = 'medium' );
    $category = get_the_category( $post_id );
    $cat_slug = $category[0]->slug;
    $cat_name = $category[0]->name;
    $cat_color = get_field( 'color', 'category_' . $category[0]->term_id );
    $title = esc_html( get_the_title( $post_id ) );
    $link = esc_url( get_permalink( $post_id ) );
    $content = wp_trim_words( get_the_content( $post_id ), 100, '...' );
    $content = wp_trim_words( get_the_excerpt( $post_id ), 20, '...' );

    $classes = 'post entry post-' . $post_id;
    if( $category ) {
      $classes .= ' category-' . $cat_slug . ' category-color-' . $cat_color;
    }

    $markup .= '<article class="' . $classes . '" aria-label="' . $title . '" itemscope itemtype="https://schema.org/CreativeWork">';
    $markup .= '<header class="entry-header">';
    $markup .= '<a class="entry-image-link" href="' . $link . '" aria-hidden="true" tabindex="-1">' . $image . '</a>';
    $markup .= '<h3 class="entry-title" itemprop="headline">';
    $markup .= '<a class="entry-title-link" rel="bookmark" href="' . $link . '">' . $title . '</a>';
    $markup .= '</h3>';
    $markup .= '<p class="entry-meta">';
    $markup .= '<span class="entry-categories"><a class="is-' . $cat_color . '-category-color" href="/category/' . $cat_slug . '" rel="category">' . $cat_name . '</a></span>';
    $markup .= '</p>';
    $markup .= '</header>';
    $markup .= '<div class="entry-content" itemprop="text"><p>' . $content . '</p></div>';
    $markup .= '</article>';

  }

  $markup .= '</div></div></div></div>';

	return $markup;

}
