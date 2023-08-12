<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Medilink_Core;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Blog_Post extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name =esc_html__( 'Blog Posts', 'medilink-core' );
		$this->rt_base = 'rt-blog-post';
		$this->rt_translate = array(
			'cols'  => array(
				'12' =>esc_html__( '1 Col', 'medilink-core' ),
				'6'  =>esc_html__( '2 Col', 'medilink-core' ),
				'4'  =>esc_html__( '3 Col', 'medilink-core' ),
				'3'  =>esc_html__( '4 Col', 'medilink-core' ),
				'2'  =>esc_html__( '6 Col', 'medilink-core' ),
			),
		);
		parent::__construct( $data, $args );
	}


	public function rt_fields(){
		$categories = get_categories();
		$category_dropdown = array( '0' =>esc_html__( 'All Categories', 'medilink-core' ) );
		foreach ( $categories as $category ) {
			$category_dropdown[$category->term_id] = $category->name;
		}
		$fields = array(
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_general',
				'label'   =>esc_html__( 'General', 'medilink-core' ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'style',
				'label'   =>esc_html__( 'Style', 'medilink-core' ),
				'options' => array(
					'style1' =>esc_html__( 'Style 1', 'medilink-core' ),
					'style2' =>esc_html__( 'Style 2', 'medilink-core' ),					
					'style3' =>esc_html__( 'Style 3', 'medilink-core' ),					
					'style4' =>esc_html__( 'Style 4', 'medilink-core' ),					
					'style5' =>esc_html__( 'Style 5', 'medilink-core' ),					
					'style6' =>esc_html__( 'Style 6', 'medilink-core' ),					
					'style7' =>esc_html__( 'Style 7', 'medilink-core' ),					
				),
				'default' => 'style1',
			),		
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'cat',
				'label'   =>esc_html__( 'Categories', 'medilink-core' ),
				'options' => $category_dropdown,
				'default' => '0',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'orderby',
				'label'   =>esc_html__( 'Order By', 'medilink-core' ),
				'options' => array(
					'date'        =>esc_html__( 'Date (Recents comes first)', 'medilink-core' ),
					'title'       =>esc_html__( 'Title', 'medilink-core' ),
					'menu_order'  =>esc_html__( 'Custom Order (Available via Order field inside Page Attributes box)', 'medilink-core' ),
				),
				'default' => 'date',
			),
			array(
				'type'    => Controls_Manager::NUMBER,
				'id'      => 'number',
				'label'   =>esc_html__( 'Total number of posts', 'medilink-core' ),
				'default' => 5,
				'description' =>esc_html__( 'Write -1 to show all', 'medilink-core' ),
			),
			array(
				'type'    => Controls_Manager::NUMBER,
				'id'      => 'count',
				'label'   =>esc_html__( 'Word count', 'medilink-core' ),
				'default' => 18,
				'description' =>esc_html__( 'Maximum number of words', 'medilink-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'meta',
				'label'       =>esc_html__( 'Post Meta', 'medilink-core' ),
				'label_on'    =>esc_html__( 'On', 'medilink-core' ),
				'label_off'   =>esc_html__( 'Off', 'medilink-core' ),
				'default'     => 'yes',
				'description' =>esc_html__( 'Show or Hide Date and Comment Counts. Default: On', 'medilink-core' ),
			),		
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'taxmeta',
				'label'       =>esc_html__( 'Tax Meta', 'medilink-core' ),
				'label_on'    =>esc_html__( 'On', 'medilink-core' ),
				'label_off'   =>esc_html__( 'Off', 'medilink-core' ),
				'default'     => 'yes',
				'description' =>esc_html__( 'Show or Hide Categories. Default: On', 'medilink-core' ),
				'condition'   => array( 'meta'  => array( 'yes'), 'style' => array( 'style7' )),
			),		
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'readmorebtn',
				'label'       => esc_html__( 'Read More Botton', 'medilink-core' ),
				'label_on'    => esc_html__( 'On', 'medilink-core' ),
				'label_off'   => esc_html__( 'Off', 'medilink-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Show or Hide Read More Botton. Default: On', 'medilink-core' ),
				'condition'   => array( 'style' => array( 'style2', 'style5', 'style7' ) ),
			),		
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'readmore',
				'label'   => esc_html__( 'Read More Text', 'medilink-core' ),
				'default' => 'Read More',
				'condition'   => array( 'readmorebtn' => array( 'yes')  ),
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'buttontext',
				'label'   => esc_html__( 'Button Text', 'medilink-core' ),
				'default' => 'SEE ALL NEWS',
				'condition'   => array( 'style' => array( 'style1') ),
			),
			array(
				'type'    => Controls_Manager::URL,
				'id'      => 'buttonurl',
				'label'   => esc_html__( 'Button URL', 'medilink-core' ),
				'placeholder' => 'https://your-link.com',
				'condition'   => array( 'style' => array( 'style1') ),
			),
			array(
				'mode' => 'section_end',
			),				
			// Responsive Columns
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_responsive',
				'label'   =>esc_html__( 'Number of Responsive Columns', 'medilink-core' ),
				'condition'   => array( 'style' => array( 'style2', 'style3', 'style4', 'style5', 'style6', 'style7' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_lg',
				'label'   =>esc_html__( 'Desktops: > 1199px', 'medilink-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '4',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_md',
				'label'   =>esc_html__( 'Desktops: > 991px', 'medilink-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '4',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_sm',
				'label'   =>esc_html__( 'Tablets: > 767px', 'medilink-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '6',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_xs',
				'label'   =>esc_html__( 'Phones: < 768px', 'medilink-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '12',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_mobile',
				'label'   =>esc_html__( 'Small Phones: < 480px', 'medilink-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '12',
			),
			array(
				'mode' => 'section_end',
			),
		
		);
		return $fields;
	}
	protected function render() {	
	$data = $this->get_settings();	
		switch ( $data['style'] ) {
			case 'style2':
				$template = 'blog-post-2';			
			break;
			case 'style3':
				$template = 'blog-post-3';			
			break;
			case 'style4':
				$template = 'blog-post-4';			
			break;
			case 'style5':
				$template = 'blog-post-5';			
			break;
			case 'style6':
				$template = 'blog-post-6';			
			break;
			case 'style7':
				$template = 'blog-post-7';			
			break;
			default:
				$template = 'blog-post-1';
			break;
		}
		return $this->rt_template( $template, $data );
	}
}