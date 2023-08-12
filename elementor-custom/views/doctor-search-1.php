<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\Medilink_Core;
use \WP_Query;
use radiustheme\Medilink\RDTheme;
use radiustheme\Medilink\Helper;
$prefix      = MEDILINK_CORE_THEME;
$cpt         = MEDILINK_CORE_CPT;
$thumb_size  = "{$prefix}-size5";
$args = array(
	'post_type'      => "{$cpt}_doctor",
	'posts_per_page' => $data['pnumber'],
	'orderby'        => $data['orderby'],
);
if(isset($_GET['doctor-name'])){
	$args['s'] = esc_attr( $_GET['doctor-name'] );
}
$dname = isset( $_GET['doctor-name'] ) ? esc_attr( $_GET['doctor-name'] ) : null;
$catid = (isset($_GET['doctor-cat'])) ? absint($_GET['doctor-cat']) : 0;
if ( $catid) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => "{$cpt}_doctor_category",
			'field' => 'term_id',
			'terms' => $catid,
		)
	);
}
switch ( $data['orderby'] ) {
	case 'title':
	case 'menu_order':
	$args['order'] = 'ASC';
	break;
}
$query = new WP_Query( $args );
$col_class = "col-lg-{$data['col_lg']} col-md-{$data['col_md']} col-sm-{$data['col_sm']} col-xs-{$data['col_xs']}";
$temp = Helper::wp_set_temp_query( $query );
$doctor_cat = Helper::get_doctor_cat();
?>
<div class="team-search-box">   
	<form method="GET">
        <div class="row">
            <div class="col-xl-4">
                <div class="form-group">
                <select class="select2" name="doctor-cat" id="doctor-cat">
				<?php
					if(!empty($doctor_cat)){
						foreach ($doctor_cat as $cat) {
							$slt = isset( $_GET['doctor-cat'] ) && $_GET['doctor-cat'] == $cat['id'] ? "selected" : null;
							echo "<option data-url='{$cat['url']}' value='{$cat['id']}' {$slt} >{$cat['name']}</option>";
						}
					}
				?>
				</select>                    
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group">
                    <input class="doctor-name form-control" type="text" placeholder="<?php esc_attr_e( 'Type Doctor Name Here ...', 'medilink-child' ) ?>" name="doctor-name" value="<?php echo esc_attr($dname) ; ?>">
                </div>
            </div>
            <div class="col-xl-2">
                <div class="form-group">
                <input type="submit" class="item-btn" value="<?php esc_attr_e( 'Search', 'medilink-child' ) ?>">	           
                </div>
            </div>
        </div>
    </form>
	</div>
		<div class="rt-el-doctor-grid-2 row">
			<?php if ( $query->have_posts() ) :?>
				<?php while ( $query->have_posts() ) : $query->the_post();?>
					<?php
						$id            = get_the_id();
						$_appointmnet_schedules   = get_post_meta( $id, "{$cpt}_appointmnet_schedules", true );
						$_designation   = get_post_meta( $id, "{$cpt}_designation", true );
						$_degree   = get_post_meta( $id, "{$cpt}_degree", true );
						?>
						<div class="rtin-item <?php echo esc_attr( $col_class );?>">
							<div class="team-box-layout5">
		                        <div class="media media-none-lg media-none-md media-none--xs">
		                        <?php
								if ( has_post_thumbnail() ){ ?>									    
		                    		<div class="item-img">                           
									   <?php the_post_thumbnail( $thumb_size ); ?>	                          
		                    		</div>
		                        <?php } ?>                                
		                            <div class="media-body">
		                                <div class="item-content">
		                                    <h4 class="item-title">
		                                       <a href="<?php the_permalink();?>"><?php the_title();?></a>
		                                    </h4>
		                                   <?php if ( !empty( $data['designation_display'] ) ): ?>
		                                    	<p class="designation"><?php echo esc_html($_designation); ?></p>
		                                	<?php endif; ?>
											<?php if ( !empty( $data['designation_display'] ) ): ?>
												 <div class="item-degree"><?php echo esc_html($_degree); ?></div>
											<?php endif; ?>                                   
		                                    <ul class="item-btns">
		                                        <li>
		                                            <a href="<?php the_permalink();?>" class="item-btn btn-ghost"><?php esc_html_e( 'View Profile', 'medilink-core' ); ?></a>
		                                        </li>
		                                        <li>
		                                            <a href="<?php the_permalink();?>" class="item-btn btn-fill"><?php esc_html_e( 'Make an Appointment', 'medilink-core' ); ?></a>
		                                        </li>
		                                    </ul>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
				<?php endwhile;?>
			<?php Helper::pagination();?>
		<?php else:?>
			<div class="col-xl-12"><h2 class="page-title"><?php esc_html_e( 'Nothing Found', 'medilink-core' ); ?></h2></div>
		<?php endif;?>
	<?php Helper::wp_reset_temp_query( $temp );?>
</div>