<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\Medilink;

use radiustheme\medilink\Helper;
use \WP_Query;
$medilink = MEDILINK_THEME_PREFIX;
$cpt      = MEDILINK_THEME_CPT_PREFIX;
wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script( 'isotope-pkgd' );
$departments             = Helper::get_departments();
$_our_pricing_plan_title = get_post_meta( $id, "{$cpt}_our_pricing_plan_title", true );
$_department_services    = get_post_meta( $id, "{$cpt}_department_services", true );
$_emergency_cases        = get_post_meta( $id, "{$cpt}_emergency_cases", true );
$_opening_hour           = get_post_meta( $id, "{$cpt}_opening_hour", true );
// $_doctors                = get_post_meta( $id, "{$cpt}_doctor", true );
// $doctors                 = Helper::get_departments_doctor( $_doctors );

// Obtener los médicos partiendo de la especialidad(medilink_doctor_category)
$_doctor_category = get_field( 'doctor_category' );
$doctors          = vittalia_get_department_doctors( $_doctor_category );

?>
<div class="sidebar-widget-area sidebar-break-md col-xl-3 col-lg-4 col-12">
	<div class="widgets widget-department-info">
		<h2 class="section-title title-bar-primary"><?php echo esc_attr( RDTheme::$options['departments_sidebar_title'] ); ?></h2>
		<ul class="nav tab-nav-list">
		<?php
		foreach ( $departments as $key => $department ) :
			?>
				<li class="nav-item departments_info">
					<a href="<?php echo esc_url( get_permalink( $key ) ); ?>"><?php echo esc_html( $department ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		 <?php if ( ! empty( $_emergency_cases ) ) { ?>  
		<div class="widgets widget-call-to-action">
			<div class="media">
				   <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/figure1.png" alt="<?php esc_html_e( 'figure', 'medilink' ); ?>">
				<div class="media-body space-sm">
					<h4><?php echo esc_html__( 'Emergency Cases', 'medilink' ); ?></h4>
					<span><?php echo esc_html( $_emergency_cases ); ?></span>
				</div>
			</div>
		</div>
	<?php } ?>
		 <?php if ( ! empty( $_opening_hour ) ) { ?>                        
		<div class="widgets widget-schedule">
			<h3 class="section-title title-bar-primary"><?php echo esc_html__( 'Opening Hours', 'medilink' ); ?></h3>
				<ul>
					<?php foreach ( $_opening_hour as $opening_hour ) { ?> 
						<li><?php echo esc_html( $opening_hour['hours_label'] ); ?> <span><?php echo esc_html( $opening_hour['hours'] ); ?></span></li>                                   
					<?php } ?>                               
				</ul>                    
		</div>
	  <?php } ?>
	</div>
</div>        
<div class="col-xl-9 col-lg-8 col-12">         
	<div class="single-departments-box-layout1 sigle-department-data ">
		 <div class="sigle-department-data">
				<div class="single-departments-img">
				   <?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'full' );
					}
					?>
				</div>
				<div class="item-content">
					<div class="item-content-wrap">
						<h3 class="item-title title-bar-primary5"><?php the_title(); ?></h3>
						<?php the_content(); ?>
					</div>
					<?php if ( ! empty( $_department_services ) ) { ?> 
						<div class="row">
							<div class="col-12">
								<div class="item-cost">
									<h3 class="item-title title-bar-primary7"><?php echo esc_html( $_our_pricing_plan_title ); ?></h3>
									<ul>
										<?php foreach ( $_department_services as $services ) { ?> 
											<li><?php echo esc_html( $services['services_name'] ); ?><span><?php echo esc_html( $services['services_price'] ); ?></span></li>                                 
										<?php } ?>                               
									</ul>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if ( ! empty( $doctors ) ) { ?>  
						<div class="item-specialist-wrap">
							 <h3 class="item-title title-bar-primary7"><?php echo esc_html__( 'Meet Our Doctors', 'medilink' ); ?></h3>
						</div>    
						<div class="row"> 
						  <?php
							foreach ( $doctors as $doctor ) :
								$thumb_size   = "{$medilink}-size5";
								$did          = $doctor->ID;
								$_designation = get_post_meta( $did, "{$cpt}_designation", true );
								$_degree      = get_post_meta( $did, "{$cpt}_degree", true );
								$img          = get_the_post_thumbnail_url( $did, $thumb_size );
								?>
											 
							<div class="col-xl-6 col-lg-12 col-12">
								<div class="item-specialist layout-2">
									<div class="media media-none--xs">
										<div class="item-img">
											 <img src="<?php echo esc_url( $img ); ?>" class="img-fluid media-img-auto" alt=" <?php echo esc_html( the_title_attribute( $did ) ); ?>">  
										</div>
										<div class="media-body">
											<h4 class="item-title"><a href="<?php echo the_permalink( $did ); ?>"> <?php echo get_the_title( $doctor->ID ); ?></a></h4>
											<span class="degree"><?php echo esc_html( $_degree ); ?></span>
											<p><?php echo esc_html( $_designation ); ?></p>
											<a href="<?php echo the_permalink( $did ); ?>" class="item-btn"><?php echo esc_html__( 'Make an Appointment', 'medilink' ); ?></a>
										</div>
									</div>
								</div>
							</div>                        
						   <?php endforeach; ?>
						</div>
					<?php } ?>  
				</div> 
			</div>
		</div>
	</div>
