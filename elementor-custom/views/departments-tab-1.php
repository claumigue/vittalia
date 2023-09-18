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
$thumb_size  = MEDILINK_CORE_THEME . '-size7';
$thumb_size2 = MEDILINK_CORE_THEME . '-size6';

$args = array(
    'post_type'      => "{$cpt}_departments",
    'posts_per_page' => $data['number'],
    'orderby'        => $data['orderby'],
    'paged' => 1
);

if ( !empty( $data['cat'] ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => "{$cpt}_departments_category",
            'field' => 'term_id',
            'terms' => $data['cat'],
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
$temp = Helper::wp_set_temp_query( $query );

?>

<?php if ( $query->have_posts() ) :?>      

  <div class="departments-box-layout3 slick-carousel-wrap">
            <?php 
            $tabs = null;
            $content= null;            
            while ( $query->have_posts() ) : $query->the_post();?>
                <?php
                    $id                             = get_the_id();   
                    $_department_services           = get_post_meta( $id, "{$cpt}_department_services", true );
                    $_department_investigations     = get_post_meta( $id, "{$cpt}_department_investigations", true );
                    $_doctor                        = get_post_meta( $id, "{$cpt}_doctor", true );                
                    $_opening_hours                 = get_post_meta( $id, "{$cpt}_opening_hour", true );
                    $bgimgid                        = get_post_meta( $id, "medilink_icon_img", true );  
                    $icon_hoverimg_id               = get_post_meta( $id, "{$cpt}_icon_hover_img", true );     
                    $_doctor                = get_post_meta( $id, "{$cpt}_doctor", true );
                    $_doctor_c              = count((array)$_doctor);
                    $hover_image_url        = wp_get_attachment_image_src( $icon_hoverimg_id, $thumb_size, true );
                    $bgimgid_holder         = '';
                    $bgimgid_hover_holder   = '';

                    if ( $bgimgid ){
                         if ($hover_image_url){  
                          $bgimgid_holder       = wp_get_attachment_image( $bgimgid, $thumb_size, "", array( "class" => "icon-image non-hover" ));
                          $bgimgid_hover_holder = wp_get_attachment_image( $icon_hoverimg_id, $thumb_size, "", array( "class" => "icon-image hover" ));
                        } else { 
                            $bgimgid_holder       = wp_get_attachment_image( $bgimgid, $thumb_size, "", array( "class" => "icon-image non-hover" ));;
                            $bgimgid_hover_holder = get_the_post_thumbnail( $id, $thumb_size2 );
                        }
                    }elseif ( has_post_thumbnail()) {                           
                          $bgimgid_holder = get_the_post_thumbnail( $id, $thumb_size2 );
                    } 

                    $shortcontent = Helper::get_current_post_content();
                    $shortcontent = wp_trim_words( $shortcontent, $data['count'] );
                    $buttontext         = $data['buttontext'];
                    $buttonurl          = $data['buttonurl']; 

                    if ( $buttonurl  ){ 
                      $buttonurl = $data['buttonurl'];                           
                    }elseif ( has_post_thumbnail()) {                           
                       $buttonurl = get_the_permalink();
                    } 

                    $tabs .= '<div class="nav-item"> '. $bgimgid_holder .' '. $bgimgid_hover_holder .' '. get_the_title() .'</div>';
                    $content .= '<div class="single-item">
                            <div class="media media-none--lg">
                                <div class="item-img"> '.get_the_post_thumbnail( $id , $thumb_size, array( 'class' => 'alignleft' ) ).'</div>
                                <div class="media-body">
                                    <h2 class="item-title">'. get_the_title() .'</h2>
                                    <p>'. esc_html($shortcontent).'</p>
                                    <ul class="list-item">
                                    <li>';
                                    if($data['doctor_display'] == 'yes'){
                                        $content .= '<div class="item-icon">
                                                <i class="flaticon-people"></i>
                                            </div>
                                            <div class="item-text">
                                                <h3 class="inner-item-title">'.esc_html__( 'Doctors', 'medilink-core' ). '</h3>
                                                <span>'.esc_html($_doctor_c). '</span>
                                            </div>';
                                        } 
                                     $content .= '</li><li>
                                     <a href="'. esc_url($buttonurl).'" class="item-btn">'.esc_html( $buttontext) . '<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>';   
                                    $content .= ' </li></ul>                                       
                                    <div class="ctg-item-icon">'.wp_get_attachment_image( $bgimgid, $thumb_size, "", array( "class" => "icon-image non-hover" )).'</div>
                                </div>
                            </div>
                        </div>';
                    ?>
                     <?php endwhile;?>
                <div class="nav-wrap carousel-nav"><?php echo $tabs; ?></div>
                <div class="carousel-content"><?php echo $content; ?></div>
            <?php endif;?>
        </div>
    <?php Helper::wp_reset_temp_query( $temp );?>

      













