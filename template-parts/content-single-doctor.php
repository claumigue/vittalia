<?php

/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Medilink;

use radiustheme\medilink\Helper;

global $post;
$medilink = MEDILINK_THEME_PREFIX;
$cpt      = MEDILINK_THEME_CPT_PREFIX;

// $_appointmnet_schedules       = get_post_meta( $id, "{$cpt}_doctors_schedule", true );
// $_appointmnet_schedules_title = get_post_meta( $id, "{$cpt}_schedule_title", true );
// $_experienced_title           = get_post_meta( $id, "{$cpt}_experienced_title", true );
// $_experienced                 = get_post_meta( $id, "{$cpt}_experienced", true );
// $_education_title             = get_post_meta( $id, "{$cpt}_skill_title", true );
// $_education                   = get_post_meta( $id, "{$cpt}_doctor_skill", true );
// $_about_title                 = get_post_meta( $id, "{$cpt}_doctor_about_title", true );
// $_office                      = get_post_meta( $id, "{$cpt}_office", true );
// $_designation        = get_post_meta( $id, "{$cpt}_designation", true );
$thumb_size          = "{$medilink}-size4";
$thumb_size6         = "{$medilink}-size6";
$id                  = get_the_id();
$_degree             = get_post_meta( $id, "{$cpt}_degree", true );
$_about              = get_post_meta( $id, "{$cpt}_doctor_about", true );
$_phone              = get_post_meta( $id, "{$cpt}_phone", true );
$_whatsapp           = get_post_meta( $id, "{$cpt}_whatsapp", true );
$_email              = get_post_meta( $id, "{$cpt}_email", true );
$_website            = get_post_meta( $id, "{$cpt}_website", true );
$_emergency_cases    = get_post_meta( $id, "{$cpt}_emergency_cases", true );
$_office_floor       = get_post_meta( $id, "{$cpt}_office_floor", true );
$_office_location    = get_post_meta( $id, "{$cpt}_office_location", true );
$_acepta_obra_social = get_post_meta( $id, "{$cpt}_doctor_os", true );
$_medical_group      = get_post_meta( $id, "{$cpt}_medical_group", true );
$socials             = get_post_meta( $id, "{$cpt}_doctor_social", true );
$social_fields       = Helper::doctor_socials();

$_especialidad = get_the_terms( $id, 'medilink_doctor_category' )[0]->name;

$_doctor_horarios = array();
$_days            = array(
	'mon' => 'Lunes',
	'tue' => 'Martes',
	'wed' => 'Miércoles',
	'thu' => 'Jueves',
	'fri' => 'Viernes',
	'sat' => 'Sábado',
	'sun' => 'Domingo',
);
foreach ( $_days as $day_en => $day_es ) {
	$_day = get_post_meta( $id, "{$cpt}_doctor_horarios_{$day_en}", true );
	if ( isset( $_day['active'] ) && $_day['active'] == 'true' ) {
		$_doctor_horarios[] = array(
			'day'     => $day_es,
			'aa_time' => $_day['aa_time'],
			'ab_time' => $_day['ab_time'],
			'ba_time' => $_day['ba_time'],
			'bb_time' => $_day['bb_time'],
		);
	}
}
// print '<pre>'; print_r( $_doctor_horarios ); print '</pre>';

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'team-single' ); ?>>
	<div class="row">
		<div class="order-xl-2 order-lg-2 col-xl-9 col-lg-8 col-md-12 col-12">
			<div class="team-detail-box-layout1">
				<div class="single-item">
					<div class="item-content">
						<h3 class="section-title item-cat mb-1"><?php echo esc_html( $_especialidad ); ?></h3>
						<span class="item-degree text-success"><?php echo esc_html( $_degree ); ?></span>
					</div>
				</div>
				<div class="single-item">
					<h3 class="section-title title-bar-primary2"><?php echo esc_html( 'Bio' ); ?></h3>
					<?php if ( ! empty( $_about ) ) { ?>
						<p><?php echo wp_kses_post( $_about ); ?></p>
					<?php } ?>
				</div>
				<div class="single-item">
					<div class="section-floor d-flex align-items-center text-uppercase">
						<img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/hospital-dark_blue.png" alt="<?php esc_html_e( 'figure', 'medilink' ); ?>">
						<?php if ( ! empty( $_office_floor ) ) {
							echo "<div class='item-floor ml-4'>$_office_floor&#176; piso</div>";
						} ?>
						<?php if ( ! empty( $_office_location ) ) {
							switch ($_office_location) {
								case 'front':
									$office_location = 'Frente';
									break;
								case 'quiet':
									$office_location = 'Contrafrente';
									break;
								default:
									$office_location = 'Piso completo';
									break;
							}
							echo "<div class='item-location ml-4'>$office_location</div>";
						} ?>
						<?php
						$acepta_obra_social = $_acepta_obra_social ? 'Sí' : 'No';
						$os_msg = '<div class="item-os ml-4">Acepta obras sociales: <span>' . $acepta_obra_social . '</span></div>';
						echo $os_msg;
						?>
					</div>
				</div>
				<?php if ( ! empty( $_doctor_horarios ) ) { ?>
					<div class="single-item">
						<div class="table-responsive">
							<h3 class="section-title title-bar-primary2"><?php echo esc_html( 'Horarios de atención' ); ?></h3>
							<table class="table schedule-table text-uppercase">
								<thead>
									<!-- <tr>
										<th><?php echo esc_html__( 'Day', 'medilink' ); ?></th>
										<th><?php echo esc_html__( 'Time', 'medilink' ); ?></th>
										<th><?php echo esc_html__( 'Time', 'medilink' ); ?></th>
									</tr> -->
								</thead>
								<tbody>
									<?php foreach ( $_doctor_horarios as $schedule ) { ?>
										<tr>
											<td><span class="schedule-table-day"><?php echo esc_html( $schedule['day'] ); ?></span></td>
											<td><?php echo esc_html( $schedule['aa_time'] ); ?> - <?php echo esc_html( $schedule['ab_time'] ); ?></td>
											<td><?php echo esc_html( $schedule['ba_time'] ); ?> - <?php echo esc_html( $schedule['bb_time'] ); ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				<?php } ?>
				<?php if ( ! empty( $_medical_group ) ) { ?>
				<div class="single-item">
					<h3 class="section-title title-bar-primary2"><?php echo esc_html( 'Grupo médico' ); ?></h3>
					<p class="item-medgroup">Pertece al Staff de: <span><?php echo esc_html( $_medical_group ); ?></span></p>
				</div>
				<?php } ?>
			</div>
		</div>
		<div class="order-xl-1 order-lg-1 sidebar-widget-area sidebar-break-md col-xl-3 col-lg-4 col-md-12 col-12">
			<div class="widgets widget-about-team">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( $thumb_size );
					// } else {
					?>
					<!-- <img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/profesional-default-image.jpg" alt="<?php the_title(); ?>"> -->
				<?php } ?>
			</div>
			<?php $socials = is_array( $socials ) ? array_filter( $socials ) : $socials; ?>
			<?php if ( ! empty( $_whatsapp ) || ! empty( $_phone ) || ! empty( $_email ) || ! empty( $_website ) || ! empty( $socials ) ) { ?>
				<div class="widgets widget-team-contact">
					<h3 class="section-title title-bar-primary2"><?php the_title(); ?></h3>
					<!-- <h3 class="section-title title-bar-primary2"><?php echo esc_html__( 'Personal Info', 'medilink' ); ?></h3> -->
					<ul>
						<?php if ( ! empty( $_whatsapp ) ) { ?>
							<li><i class="fab fa-whatsapp"></i>&nbsp;&nbsp;<span><?php echo esc_html( $_whatsapp ); ?></span></li>
						<?php } ?>
						<?php if ( ! empty( $_phone ) ) { ?>
							<li><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp;<span><?php echo esc_html( $_phone ); ?></span></li>
						<?php } ?>
						<?php if ( ! empty( $_email ) ) { ?>
							<li><i class="far fa-envelope" aria-hidden="true"></i><span><?php echo esc_html( $_email ); ?></span></li>
						<?php } ?>
						<?php if ( ! empty( $_website ) ) { ?>
							<li>
								<i class="fas fa-globe" aria-hidden="true"></i>
								<span>
									<a href="<?php echo esc_url( $_website ) ?>" target="_blank"><?php echo esc_html( preg_replace('#^https?://#i', '', $_website) ); ?></a>
								</span>
							</li>
						<?php } ?>
						<?php if ( ! empty( $socials ) && RDTheme::$options['doctor_arc_social_display'] ) : ?>
							<li>
								<h3>Redes</h3>
								<ul class="widget-social">
									<?php foreach ( $socials as $key => $social ) : ?>
										<?php if ( ! empty( $social ) ) : ?>
											<li>
												<a target="_blank" href="<?php echo esc_url( $social ); ?>"><i class="fa <?php echo esc_attr( $social_fields[ $key ]['icon'] ); ?>" aria-hidden="true"></i></a>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			<?php } ?>
			<!-- <?php if ( $_phone ) { ?>
				<div class="widgets widget-call-to-action">
					<div class="media">
						<img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/hospital-reception.png" alt="<?php esc_html_e( 'figure', 'medilink' ); ?>">
						<div class="media-body space-sm">
							<h4><?php echo esc_html__( 'Solicitar turnos:', 'medilink' ); ?></h4>
							<span><?php echo esc_html( $_phone ); ?></span>
						</div>
					</div>
				</div>
			<?php } ?> -->
		</div>
	</div>

	<?php if ( ! empty( get_the_content() ) ) { ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="rtin-content-doctor d-content">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
