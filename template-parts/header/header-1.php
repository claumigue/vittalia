<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\Medilink;
$nav_menu_args = Helper::nav_menu_args();
// Logo
$rdtheme_dark_logo  = empty( RDTheme::$options['logo']['url'] ) ? Helper::get_img( 'logo-dark.png' ) : RDTheme::$options['logo']['url'];
$rdtheme_light_logo = empty( RDTheme::$options['logo_light']['url'] ) ? Helper::get_img( 'logo-light.png' ) : RDTheme::$options['logo_light']['url'];
$rdtheme_logo_width = (int) RDTheme::$options['logo_width'];

if ( RDTheme::$options['header_btn'] ){
$rdtheme_menu_width = 10 - $rdtheme_logo_width;
$rdtheme_logo_class = "col-sm-{$rdtheme_logo_width} col-xs-12";
$rdtheme_menu_class = "col-sm-{$rdtheme_menu_width} col-xs-12";
}else{
$rdtheme_menu_width = 12 - $rdtheme_logo_width;
$rdtheme_logo_class = "col-sm-{$rdtheme_logo_width} col-xs-12";
$rdtheme_menu_class = "col-sm-{$rdtheme_menu_width} col-xs-12";
}
?>
<div class="masthead-container header-style1">
	<div class="container">
		<div class="row d-flex align-items-center">
			<div class="<?php echo esc_attr( $rdtheme_logo_class );?>">
				<div class="site-branding">
					<a class="dark-logo" href="<?php echo esc_url( home_url( '/' ) );?>"><img src="<?php echo esc_url( $rdtheme_dark_logo );?>" alt="<?php esc_attr( bloginfo( 'name' ) ) ;?>"></a>
					<a class="light-logo" href="<?php echo esc_url( home_url( '/' ) );?>"><img src="<?php echo esc_url( $rdtheme_light_logo );?>" alt="<?php esc_attr( bloginfo( 'name' ) ) ;?>"></a>
				</div>
			</div>
			<div class="<?php echo esc_attr( $rdtheme_menu_class );?>">
				<?php get_template_part( 'template-parts/header/icon', 'area' );?>
				<div id="site-navigation" class="main-navigation">
					<?php wp_nav_menu( $nav_menu_args );?>
				</div>
			</div>
				<?php if ( RDTheme::$options['header_btn'] ): ?>
				<div class="col-lg-2 col-md-2 d-none d-lg-block">
						<ul class="header-action-items">
						    <li>
						        <a href="<?php echo esc_url( RDTheme::$options['header_buttonUrl'] ); ?>" title="<?php echo esc_html( RDTheme::$options['header_buttontext'] ); ?>" class="btn-fill color-yellow btn-header"<?php if ( RDTheme::$options['header_buttonLinkTarget'] ): ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>><?php echo esc_html( RDTheme::$options['header_buttontext'] ); ?></a>
						    </li>
						</ul>
				</div>
			  <?php endif; ?>
		</div>		
	</div>
</div>