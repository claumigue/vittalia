<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Medilink;
$medilink = MEDILINK_THEME_PREFIX_VAR;
if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
	$rdtheme_title = woocommerce_page_title( false );
}
elseif ( is_404() ) {
	$rdtheme_title = RDTheme::$options['error_title'];
}
elseif ( is_search() ) {
	$rdtheme_title = esc_html__( 'Search Results for : ', 'medilink' ) . get_search_query();
}
elseif ( is_home() ) {
	if ( get_option( 'page_for_posts' ) ) {
		$rdtheme_title = get_the_title( get_option( 'page_for_posts' ) );
	}
	else {
		$rdtheme_title = apply_filters( "{$medilink}_blog_title", esc_html__( 'All Posts', 'medilink' ) );
	}
}
elseif ( is_archive() ) {
	$cpt = MEDILINK_THEME_CPT_PREFIX;
	if ( is_post_type_archive( "{$cpt}_doctor" ) ) {
		$rdtheme_title = esc_html__( 'All Doctors', 'medilink' );
	}
	elseif ( is_post_type_archive( "{$cpt}_departments" ) ) {
		$rdtheme_title = esc_html__( 'All Departments', 'medilink' );
	}
	else {
		$rdtheme_title = get_the_archive_title();
	}
}elseif (is_single()) {
	$rdtheme_title  = get_the_title();

}else{
	$id                       		= $post->ID;
	$fitness_custom_page_title      = get_post_meta($id, 'medilink_custom_page_title', true);
	if (!empty($fitness_custom_page_title)) {
		$rdtheme_title      = get_post_meta($id, 'medilink_custom_page_title', true);
	 } else { 
		$rdtheme_title = get_the_title();	                   
 	}
}
if ( RDTheme::$bgtype == 'bgcolor' ) { ?>
	<?php if ( RDTheme::$has_banner == '1' || RDTheme::$has_banner == 'on' ): ?>
		<div class="entry-banner inner-page-banner bg-common inner-page-top-margin">
			<?php if( function_exists( 'bcn_display') ){ 
					 echo '<div class="inner-page-banner">';
				 } else{
				 	echo '<div class="inner-page-banner breadcrumbs-off">';
				 } ?>	
			<div class="container">
				<div class="entry-banner-content breadcrumbs-area">
					<h1 class="entry-title"><?php echo wp_kses_post( $rdtheme_title );?></h1>
					<?php if ( RDTheme::$has_breadcrumb == '1' || RDTheme::$has_breadcrumb == 'on' ): ?>
						<?php get_template_part( 'template-parts/content', 'breadcrumb' );?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		</div>
		<?php endif; 
	} else { ?>
		<?php if ( RDTheme::$has_banner == '1' || RDTheme::$has_banner == 'on' ): ?>
		<div class="entry-banner entry-banner-after inner-page-banner bg-common inner-page-top-margin">
			<?php if( function_exists( 'bcn_display') ){ 
					 echo '<div class="inner-page-banner">';
				 } else{
				 	echo '<div class="inner-page-banner breadcrumbs-off">';
				 } ?>	
			<div class="container">
				<div class="entry-banner-content breadcrumbs-area">
					<h1 class="entry-title"><?php echo wp_kses_post( $rdtheme_title );?></h1>
					<?php if ( RDTheme::$has_breadcrumb == '1' || RDTheme::$has_breadcrumb == 'on' ): ?>
						<?php get_template_part( 'template-parts/content', 'breadcrumb' );?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		</div>
	<?php endif; 
	}
?>