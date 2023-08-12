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

$thumb_size = MEDILINK_CORE_THEME . '-size3';
$args = array(
	'posts_per_page' => $data['number'],
	'cat'            => (int) $data['cat'],
	'orderby'        => $data['orderby'],
);
switch ($data['orderby']) {
	case 'title':
	case 'menu_order':
		$args['order'] = 'ASC';
		break;
}
$query = new WP_Query($args);
$col_class = "col-lg-{$data['col_lg']} col-md-{$data['col_md']} col-sm-{$data['col_sm']} col-xs-{$data['col_xs']}";
$temp = Helper::wp_set_temp_query($query);
?>
<div class="rt-el-blgo-post row">
	<?php if ($query->have_posts()) :
	?>
		<?php while ($query->have_posts()) : $query->the_post();
			$content = Helper::get_current_post_content();
			$content = wp_trim_words($content, $data['count']);
			$content = "<p>$content</p>";
			$comments_number = number_format_i18n(get_comments_number());
			$comments_html   = $comments_number < 2 ? esc_html__('Comment', 'medilink-core') : esc_html__('Comments', 'medilink-core');
			$comments_html  .= ': ' . $comments_number;	?>
			<div class="rtin-item post-each <?php echo esc_attr($col_class); ?>">
				<div class="blog-box-layout5 blog-box-layout7">
					<?php if (has_post_thumbnail()) { ?>
						<div class="item-img">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail($thumb_size); ?>
							</a>
						</div>
					<?php } ?>
					<div class="item-content">
						<?php if (has_post_thumbnail()) { ?>
							<?php if ($data['meta']  == 'yes') : ?>
								<?php if ($data['taxmeta']  == 'yes') : ?>
									<div class="post-date add-pimg">
										<i class="fa fa-folder-open-o" aria-hidden="true"></i> <span class="vcard category"><?php the_category(', '); ?></span>
									</div>
								<?php endif; ?>
							<?php endif ?>
						<?php } else { ?>
							<?php if ($data['meta']  == 'yes') : ?>
								<?php if (RDTheme::$options['blog_date']) : ?>
									<div class="post-date noadd-img"><i class="fa fa-calendar" aria-hidden="true"></i> <?php the_time(get_option('date_format')); ?></div>
								<?php endif; ?>
							<?php endif ?>
						<?php } ?>
						<h3 class="item-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<?php echo wp_kses_post($content); ?>
						<div class="post-actions-wrapper">
							<ul>
								<?php if ($data['readmorebtn']  == 'yes') : ?>
									<li>
										<a class="item-btn" href="<?php the_permalink(); ?>"><?php echo esc_html($data['readmore']); ?><i class="fas fa-long-arrow-alt-right"></i></a>
									</li>
								<?php endif; ?>
								<?php if (RDTheme::$options['blog_comment_num']) : ?>
									<li class="vcard-comments"> <a href="<?php the_permalink(); ?>"><i class="fa fa-comments" aria-hidden="true"></i> <?php echo wp_kses_post($comments_number); ?></a></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
	<?php
		endwhile;
	endif; ?>
	<?php Helper::wp_reset_temp_query($temp); ?>
</div>