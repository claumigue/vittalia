<?php

/**

 * @author  RadiusTheme

 * @since   1.0

 * @version 1.0

 */

namespace radiustheme\Medilink;

?>

<?php get_template_part( 'template-parts/footer/footer', RDTheme::$footer_style ); ?>

<!-- Search Box Start Here -->

<div id="header-search" class="header-search">

	<button type="button" class="close">×</button>

		<form class="header-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">				   

		    	<input type="search" name="s" class="search-text" placeholder="<?php esc_attr_e( 'Search Here...', 'medilink' );?>" required>

		    <button type="submit" class="search-btn">

		        <i class="flaticon-search"></i>

		    </button>

		</form>

</div>

<!-- Search Box End Here -->

</div>

<?php wp_footer();?>

</body>

</html>