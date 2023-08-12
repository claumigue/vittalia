<?php

/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Medilink;

use radiustheme\medilink\Helper;

global $post;
$medilink                         = MEDILINK_THEME_PREFIX;
$cpt                            = MEDILINK_THEME_CPT_PREFIX;
$thumb_size                     = "{$medilink}-size4";
$thumb_size6                    = "{$medilink}-size6";
$id                             = get_the_id();
$_appointmnet_schedules         = get_post_meta($id, "{$cpt}_doctors_schedule", true);
$_appointmnet_schedules_title   = get_post_meta($id, "{$cpt}_schedule_title", true);
$_designation                   = get_post_meta($id, "{$cpt}_designation", true);
$_experienced_title             = get_post_meta($id, "{$cpt}_experienced_title", true);
$_experienced                   = get_post_meta($id, "{$cpt}_experienced", true);
$_education_title               = get_post_meta($id, "{$cpt}_skill_title", true);
$_education                     = get_post_meta($id, "{$cpt}_doctor_skill", true);
$_about_title                   = get_post_meta($id, "{$cpt}_doctor_about_title", true);
$_designation                   = get_post_meta($id, "{$cpt}_designation", true);
$_degree                        = get_post_meta($id, "{$cpt}_degree", true);
$_phone                         = get_post_meta($id, "{$cpt}_phone", true);
$_office                        = get_post_meta($id, "{$cpt}_office", true);
$_email                         = get_post_meta($id, "{$cpt}_email", true);
$_emergency_cases               = get_post_meta($id, "{$cpt}_emergency_cases", true);
$_about                         = get_post_meta($id, "{$cpt}_doctor_about", true);
$_emergency_cases               = get_post_meta($id, "{$cpt}_emergency_cases", true);
$socials                        = get_post_meta($id, "{$cpt}_doctor_social", true);
$social_fields                  = Helper::doctor_socials();
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('team-single'); ?>>
    <div class="row">
        <div class="order-xl-2 order-lg-2 col-xl-9 col-lg-8 col-md-12 col-12">
            <div class="team-detail-box-layout1">
                <div class="single-item">
                    <div class="item-content">
                        <h3 class="section-title item-title mb-1"><?php echo esc_html($_designation); ?></h3>
                        <span class="item-designation text-success"><?php echo esc_html($_degree); ?></span>
                    </div>
                </div>
                <div class="single-item">
                    <?php if (!empty($_about_title)) { ?>
                        <h3 class="section-title title-bar-primary2"><?php echo esc_html($_about_title); ?></h3>
                    <?php }  ?>
                    <?php if (!empty($_about)) { ?>
                        <p><?php echo wp_kses_post($_about); ?></p>
                    <?php }  ?>
                </div>
                <?php if (!empty($_education)) { ?>
                    <div class="single-item">
                        <div class="table-responsive">
                            <?php if (!empty($_education_title)) { ?>
                                <h3 class="section-title title-bar-primary2"><?php echo esc_html($_education_title); ?></h3>
                            <?php }  ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><?php echo esc_html__('Year', 'medilink'); ?></th>
                                        <th><?php echo esc_html__('Degree', 'medilink'); ?></th>
                                        <th><?php echo esc_html__('Institute', 'medilink'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_education as $education) {  ?>
                                        <tr>
                                            <td><?php echo esc_html($education['year']); ?></td>
                                            <td><?php echo esc_html($education['degree']); ?></td>
                                            <td><?php echo esc_html($education['institute']); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }  ?>
                <?php if (!empty($_experienced_title)) { ?>
                    <div class="single-item">
                        <div class="table-responsive">
                            <?php if (!empty($_experienced_title)) { ?>
                                <h3 class="section-title title-bar-primary2"><?php echo esc_html($_experienced_title); ?></h3>
                            <?php } ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><?php echo esc_html__('Year', 'medilink'); ?></th>
                                        <th><?php echo esc_html__('Department', 'medilink'); ?></th>
                                        <th><?php echo esc_html__('Position', 'medilink'); ?></th>
                                        <th><?php echo esc_html__('Hospital', 'medilink'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_experienced as $experienced) {  ?>
                                        <tr>
                                            <td><?php echo esc_html($experienced['year']); ?></td>
                                            <td><?php echo esc_html($experienced['department']); ?></td>
                                            <td><?php echo esc_html($experienced['position']); ?></td>
                                            <td><?php echo esc_html($experienced['hospital']); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }  ?>
                <?php if (!empty($_appointmnet_schedules)) { ?>
                    <div class="single-item">
                        <div class="table-responsive">
                            <?php if (!empty($_appointmnet_schedules_title)) { ?>
                                <h3 class="section-title title-bar-primary2"><?php echo esc_html($_appointmnet_schedules_title); ?></h3>
                            <?php } ?>
                            <table class="table schedule-table">
                                <thead>
                                    <tr>
                                        <th><?php echo esc_html__('Day', 'medilink'); ?></th>
                                        <th><?php echo esc_html__('Time', 'medilink'); ?></th>
                                        <th><?php echo esc_html__('Address', 'medilink'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_appointmnet_schedules as $schedule) {  ?>
                                        <tr>
                                            <td><?php echo esc_html($schedule['week']); ?></td>
                                            <td><?php echo esc_html($schedule['start_time']); ?> - <?php echo esc_html($schedule['end_time']); ?></td>
                                            <?php if ($schedule['address']) { 
                                            ?>
                                            <td><?php echo esc_html($schedule['address']); 
                                                ?></td>
                                            <?php } 
                                            ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }  ?>
            </div>
        </div>
        <div class="order-xl-1 order-lg-1 sidebar-widget-area sidebar-break-md col-xl-3 col-lg-4 col-md-12 col-12">
            <div class="widgets widget-about-team">
                <?php the_post_thumbnail($thumb_size); ?>
                <div class="item-content">
                    <h3 class="item-title"><?php the_title(); ?></h3>
                    <p class="item-ctg"><?php echo esc_html($_designation); ?></p>
                    <span class="item-designation"><?php echo esc_html($_degree); ?></span>
                </div>
            </div>


            <div class="widgets widget-team-contact">
                <h3 class="section-title title-bar-primary2"><?php echo esc_html__('Personal Info', 'medilink'); ?></h3>
                <ul>
                    <?php if (!empty($_phone)) { ?>
                        <li><?php echo esc_html__('Phone:', 'medilink'); ?><span><?php echo esc_html($_phone); ?></span></li>
                    <?php } ?>
                    <?php if (!empty($_office)) { ?>
                        <li><?php echo esc_html__('Office:', 'medilink'); ?><span><?php echo esc_html($_office); ?></span></li>
                    <?php } ?>
                    <?php if (!empty($_email)) { ?>
                        <li><?php echo esc_html__('E-mail:', 'medilink'); ?><span><?php echo esc_html($_email); ?></span></li>
                    <?php } ?>
                    <?php if (!empty($socials) && RDTheme::$options['doctor_arc_social_display']) : ?>
                        <li class="d-flex"><?php echo esc_html__('Social:', 'medilink'); ?>
                            <ul class="widget-social">
                                <?php foreach ($socials as $key => $social) : ?>
                                    <?php if (!empty($social)) : ?>
                                        <li>
                                            <a target="_blank" href="<?php echo esc_url($social); ?>"><i class="fa <?php echo esc_attr($social_fields[$key]['icon']); ?>" aria-hidden="true"></i></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if ($_phone) { ?>
                <div class="widgets widget-call-to-action">
                    <div class="media">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/img/hospital-reception.png" alt="<?php esc_html_e('figure', 'medilink'); ?>">
                        <div class="media-body space-sm">
                            <h4><?php echo esc_html__('Phone:', 'medilink'); ?></h4>
                            <span><?php echo esc_html($_phone); ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if ($_emergency_cases) { ?>
                <div class="widgets widget-call-to-action">
                    <div class="media">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/figure1.png" alt="<?php esc_html_e('figure', 'medilink'); ?>">
                        <div class="media-body space-sm">
                            <h4><?php echo esc_html__('Emergency Cases', 'medilink'); ?></h4>
                            <span><?php echo esc_html($_emergency_cases); ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php if (!empty(get_the_content())) { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="rtin-content-doctor d-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    <?php }  ?>
</div>