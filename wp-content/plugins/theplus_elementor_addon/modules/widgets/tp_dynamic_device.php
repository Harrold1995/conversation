<?php 
/*
Widget Name: Dynamic Devices
Description: layout of devices isplay content.
Author: Theplus
Author URI: https://posimyth.com
*/
namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

use TheplusAddons\Theplus_Element_Load;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class ThePlus_Dynamic_Devices extends Widget_Base {
		
	public function get_name() {
		return 'tp-dynamic-device';
	}

    public function get_title() {
        return esc_html__('Dynamic Device', 'theplus');
    }

    public function get_icon() {
        return 'fa fa-laptop theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-creatives');
    }
	
	public function get_keywords() {
		return ['dynamic custom skin', 'dynamic loop', 'loop builder', 'skin builder', 'custom skin', 'post skin', 'post loop','dynamic listing', 'dynamic custom post type listing', 'custom post type listing', 'post type'];
	}
    protected function _register_controls() {
		
		$this->start_controls_section(
			'device_section',
			[
				'label' => esc_html__( 'Content', 'theplus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'device_mode',
			[
				'label' => esc_html__( 'Layout', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal'  => esc_html__( 'Normal', 'theplus' ),
					'carousal' => esc_html__( 'Special Carousel', 'theplus' ),
				],
			]
		);
		$this->add_control(
			'device_mockup',
			[
				'label' => esc_html__( 'Type', 'theplus' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'mobile' => [
						'title' => esc_html__( 'Mobile', 'theplus' ),
						'icon' => 'fa fa-mobile',
					],
					'tablet' => [
						'title' => esc_html__( 'Tablet', 'theplus' ),
						'icon' => 'fa fa-tablet',
					],
					'laptop' => [
						'title' => esc_html__( 'Laptop', 'theplus' ),
						'icon' => 'fa fa-laptop',
					],
					'desktop' => [
						'title' => esc_html__( 'Desktop', 'theplus' ),
						'icon' => 'fa fa-desktop',
					],
				],
				'default' => 'laptop',
				'toggle' => true,
				'condition'    => [
					'device_mode' => [ 'normal' ],
				],
			]
		);
		$this->add_control(
			'device_mockup_carousal',
			[
				'label' => esc_html__( 'Type', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'mobile'  => esc_html__( 'Mobile', 'theplus' ),
				],
				'default' => 'mobile',
				'toggle' => true,
				'condition'    => [
					'device_mode' => [ 'carousal' ],
				],
			]
		);
		$this->add_control(
			'device_mobile',
			[
				'label' => esc_html__( 'Mobile Device', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'iphone-white-flat',
				'options' => [
					'iphone-white-flat'  => esc_html__( 'iPhone White (320px x 594px)', 'theplus' ),
					'iphone-x-black' => esc_html__( 'iPhone X Black (320px x 672px)', 'theplus' ),
					'iphone-browser' => esc_html__( 'iPhone Browser (320px x 470px)', 'theplus' ),
					'iphone-minimal' => esc_html__( 'iPhone Minimal (300px x 527px)', 'theplus' ),
					'iphone-minimal-white' => esc_html__( 'iPhone Minimal White (320px x 564px)', 'theplus' ),
					's9-black' => esc_html__( 'S9 Black (320px x 668px)', 'theplus' ),
					's9-jet-black' => esc_html__( 'S9 Jet Black (320px x 672px)', 'theplus' ),
					's9-white' => esc_html__( 'S9 White (320px x 668px)', 'theplus' ),
				],
				'condition'    => [
					'device_mode' => [ 'normal' ],
					'device_mockup' => [ 'mobile' ],
				],
			]
		);
		$this->add_control(
			'device_mobile_carousal',
			[
				'label' => esc_html__( 'Mobile Device', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'iphone-white-flat-carousal',
				'options' => [
					'iphone-white-flat-carousal'  => esc_html__( 'iPhone White (500px x 890px)', 'theplus' ),
				],
				'condition'    => [
					'device_mode' => [ 'carousal' ],
					'device_mockup_carousal' => [ 'mobile' ],
				],
			]
		);
		$this->add_control(
			'device_tablet',
			[
				'label' => esc_html__( 'Tablet Device', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ipad-vertical-white',
				'options' => [
					'ipad-vertical-white'  => esc_html__( 'Ipad Vertical White (480px x 646px)', 'theplus' ),
					'ipad-horizontal-white'  => esc_html__( 'Ipad Horizontal White (470px x 348px)', 'theplus' ),
					'ipad-browser'  => esc_html__( 'Ipad Browser (550px x 625px)', 'theplus' ),
				],
				'condition'    => [
					'device_mode' => [ 'normal' ],
					'device_mockup' => [ 'tablet' ],
				],
			]
		);
		$this->add_control(
			'device_laptop',
			[
				'label' => esc_html__( 'Laptop Device', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'laptop-macbook-black',
				'options' => [
					'laptop-macbook-black'  => esc_html__( 'Macbook Black (800px x 500px)', 'theplus' ),
					'laptop-macbook-minimal'  => esc_html__( 'Macbook Minimal (700px x 414px)', 'theplus' ),
					'laptop-macbook-white-minimal'  => esc_html__( 'Macbook White Minimal(770px x 480px)', 'theplus' ),
					'laptop-macbook-white'  => esc_html__( 'Macbook White (800px x 525px)', 'theplus' ),
					'laptop-windows'  => esc_html__( 'Windows Laptop (800px x 471px)', 'theplus' ),
				],
				'condition'    => [
					'device_mode' => [ 'normal' ],
					'device_mockup' => [ 'laptop' ],
				],
			]
		);
		$this->add_control(
			'device_desktop',
			[
				'label' => esc_html__( 'Desktop Device', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desktop-imac-minimal',
				'options' => [
					'desktop-imac-minimal'  => esc_html__( 'iMac Minimal (1000px x 565px)', 'theplus' ),
				],
				'condition'    => [
					'device_mode' => [ 'normal' ],
					'device_mockup' => [ 'desktop' ],
				],
			]
		);
		$this->add_control(
			'media_image',
			[
				'label' => esc_html__( 'Media Image', 'theplus' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active'   => true,
				],
				'condition'    => [
					'device_mode' => [ 'normal' ],
				],
			]
		);
		$this->add_control(
			'slider_gallery',
			[
				'label' => esc_html__( 'Select Multiple Images', 'theplus' ),
				'type' => \Elementor\Controls_Manager::GALLERY,
				'default' => [],
				'condition'    => [
					'device_mode' => [ 'carousal' ],
				],
			]
		);
		$this->add_control(
			'device_link_popup',
			[
				'label' => esc_html__( 'Select Link/Popup', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__( 'Select Option', 'theplus' ),
					'link'  => esc_html__( 'Link', 'theplus' ),
					'popup'  => esc_html__( 'Popup', 'theplus' ),
					
				],
				'condition'    => [
					'device_mode' => [ 'normal' ],
				],
			]
		);
		$this->add_control(
			'device_link',
			[
				'label' => esc_html__( 'Link', 'theplus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
				'placeholder' => esc_html__( 'https://www.demo-link.com', 'theplus' ),
				'default' => [
					'url' => '',
				],
				'condition'    => [
					'device_mode' => [ 'normal' ],
					'device_link_popup!' => '', 
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
            'section_icon_content',
            [
                'label' => esc_html__('Icon Options', 'theplus'),
                'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'device_mode' => 'normal',
				],
            ]
        );
		$this->add_control(
			'icon_show',
			[
				'label' => esc_html__( 'Show Icon', 'theplus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'label_on' => esc_html__( 'On', 'theplus' ),
				'default' => 'no',
			]
		);
		$this->add_control(
			'icon_image',
			[
				'label' => esc_html__( 'Upload Icon', 'theplus' ),
				'type' => Controls_Manager::MEDIA,
				'condition'    => [
					'icon_show' => 'yes',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
            'section_icon_styling',
            [
                'label' => esc_html__('Icon Options', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'device_mode' => 'normal',
					'icon_show' => 'yes',
				],
            ]
        );
		$this->add_control(
			'icon_continuous_animation',
			[
				'label'        => esc_html__( 'Continuous Animation', 'theplus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'theplus' ),
				'label_off'    => esc_html__( 'No', 'theplus' ),
				'default' => 'no',
			]
		);
		$this->add_control(
			'icon_animation_effect',
			[
				'label' => esc_html__( 'Animation Effect', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'pulse',
				'options' => [
					'pulse'  => esc_html__( 'Pulse', 'theplus' ),
					'floating'  => esc_html__( 'Floating', 'theplus' ),
					'tossing'  => esc_html__( 'Tossing', 'theplus' ),
					'rotating'  => esc_html__( 'Rotating', 'theplus' ),
					'drop_waves'  => esc_html__( 'Drop Waves', 'theplus' ),
				],
				'render_type'  => 'template',
				'condition' => [
					'icon_continuous_animation' => 'yes',
				],
			]
		);
		$this->add_control(
			'icon_animation_hover',
			[
				'label'        => esc_html__( 'Hover Animation', 'theplus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'theplus' ),
				'label_off'    => esc_html__( 'No', 'theplus' ),					
				'render_type'  => 'template',
				'condition' => [
					'icon_continuous_animation' => 'yes',
				],
			]
		);
		$this->add_control(
			'icon_animation_duration',
			[	
				'label' => esc_html__( 'Duration Time', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => 's',
				'range' => [
					's' => [
						'min' => 0.5,
						'max' => 50,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 's',
					'size' => 2.5,
				],
				'selectors'  => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-icon .plus-device-icon-inner' => 'animation-duration: {{SIZE}}{{UNIT}};-webkit-animation-duration: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_continuous_animation' => 'yes',
					'icon_animation_effect!' => 'drop_waves',
				],
			]
		);
		$this->add_control(
			'icon_transform_origin',
			[
				'label' => esc_html__( 'Transform Origin', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'center center',
				'options' => [
					'top left'  => esc_html__( 'Top Left', 'theplus' ),
					'top center"'  => esc_html__( 'Top Center', 'theplus' ),
					'top right'  => esc_html__( 'Top Right', 'theplus' ),
					'center left'  => esc_html__( 'Center Left', 'theplus' ),
					'center center'  => esc_html__( 'Center Center', 'theplus' ),
					'center right'  => esc_html__( 'Center Right', 'theplus' ),
					'bottom left'  => esc_html__( 'Bottom Left', 'theplus' ),
					'bottom center'  => esc_html__( 'Bottom Center', 'theplus' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'theplus' ),
				],
				'selectors'  => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-icon .plus-device-icon-inner' => '-webkit-transform-origin: {{VALUE}};-moz-transform-origin: {{VALUE}};-ms-transform-origin: {{VALUE}};-o-transform-origin: {{VALUE}};transform-origin: {{VALUE}};',
				],
				'render_type'  => 'template',
				'condition' => [
					'icon_continuous_animation' => 'yes',
					'icon_animation_effect' => 'rotating',
				],
			]
		);
		$this->add_control(
			'drop_waves_color',
			[
				'label' => esc_html__( 'Drop Wave Color', 'theplus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-icon .plus-device-icon-inner.image-drop_waves:after,{{WRAPPER}} .plus-device-wrapper .plus-device-icon .plus-device-icon-inner.hover_drop_waves:after' => 'background: {{VALUE}}'
				],
				'condition' => [
					'icon_continuous_animation' => 'yes',
					'icon_animation_effect' => 'drop_waves',
				],
			]
		);
		$this->add_control(
			'icon_radius',
			[
				'label'      => esc_html__( 'Icon Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-icon img,{{WRAPPER}} .plus-device-wrapper .plus-device-icon .plus-device-icon-inner,{{WRAPPER}} .plus-device-wrapper .plus-device-icon .plus-device-icon-inner.image-drop_waves:after,{{WRAPPER}} .plus-device-wrapper .plus-device-icon .plus-device-icon-inner.hover_drop_waves:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[	
				'label' => esc_html__( 'Icon Size', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => 'px',
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 500,
						'step' => 2,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 90,
				],
				'selectors'  => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
            'section_carousal_styling',
            [
                'label' => esc_html__('Carousal Options', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'device_mode' => 'carousal',
				],
            ]
        );
		$this->add_control(
			'carousal_columns',
			[
				'label' => esc_html__( 'Carousal Column', 'theplus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'multiple',
				'options' => [
					'single'  => esc_html__( 'Single Slide', 'theplus' ),
					'multiple' => esc_html__( 'Multiple', 'theplus' ),
				],
			]
		);
		
		$this->add_control(
			'carousal_infinite',
			[
				'label' => esc_html__( 'Infinite', 'theplus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'default' => 'yes',
			]
		);
		$this->add_control(
			'carousal_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'theplus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'default' => 'no',
			]
		);
		$this->add_control(
			'carousal_autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'' => [
						'min' => 500,
						'max' => 10000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => '',
					'size' => 4000,
				],
				'condition' => [
					'carousal_autoplay' => 'yes',
				],
			]
		);
		$this->add_control(
			'carousal_speed',
			[
				'label' => esc_html__( 'Slide Speed', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'' => [
						'min' => 200,
						'max' => 5000,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => '',
					'size' => 700,
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
            'section_carousel_slide_styling',
            [
                'label' => esc_html__('Carousel Slide', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'carousal_slide_gap',
			[
				'label' => esc_html__( 'Slide Gap/Space', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -10,
						'max' => 300,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-carousal .plus-device-slide.slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'carousal_slide_vertical',
			[
				'label' => esc_html__( 'Adjust Slide Space', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-carousal .plus-device-slide.slick-slide' => 'margin-top: {{SIZE}}{{UNIT}};margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'carousal_width',
			[
				'label' => esc_html__( 'Carousal Width', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
						'step' => 2,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 330,
				],
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper .plus-carousal-device-mokeup,{{WRAPPER}} .plus-device-wrapper .plus-device-carousal.column-single' => 'max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .plus-device-wrapper .plus-device-carousal .plus-device-slide.slick-slide' => 'width: calc({{SIZE}}{{UNIT}} - 15px);',
				],
			]
		);
		$this->start_controls_tabs( 'slide_shadow_style' );
		$this->start_controls_tab(
			'slide_shadow_normal',
			[
				'label' => esc_html__( 'Normal', 'theplus' ),
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'slide_box_shadow',
				'selector' => '{{WRAPPER}} .plus-device-wrapper .plus-device-carousal .plus-device-slide.slick-slide:not(.slick-center)',
			]
		);
		$this->add_control(
			'slide_opacity_normal',[
				'label' => esc_html__( 'Slide Opacity', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '',
					'size' => 1,
				],
				'range' => [
					'' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-carousal .plus-device-slide.slick-slide:not(.slick-center)' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'slide_opacity_scale',[
				'label' => esc_html__( 'Slide Scale', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '',
					'size' => 1,
				],
				'range' => [
					'' => [
						'max' => 2,
						'min' => -0.5,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-carousal .plus-device-slide.slick-slide:not(.slick-center)' => 'transform: scale({{SIZE}});',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'slide_shadow_hover',
			[
				'label' => esc_html__( 'Hover', 'theplus' ),
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'slide_box_hover_shadow',
				'selector' => '{{WRAPPER}} .plus-device-wrapper .plus-device-carousal .plus-device-slide.slick-slide:hover:not(.slick-center)',
			]
		);
		$this->add_control(
			'slide_opacity_hover',[
				'label' => esc_html__( 'Slide Hover Opacity', 'theplus' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '',
					'size' => 1,
				],
				'range' => [
					'' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper .plus-device-carousal .plus-device-slide.slick-slide:hover:not(.slick-center)' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
            'section_device_styling',
            [
                'label' => esc_html__('Device Layout', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
            'device_width',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width Adjust(%)', 'theplus'),
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min'	=> 10,
						'max'	=> 100,
						'step' => 0.5,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper' => 'width: {{SIZE}}%;margin: 0 auto;text-align: center;display: block;',
				],
				'render_type' => 'ui',
            ]
        );
		$this->add_responsive_control(
			'device_margin',
			[
				'label' => esc_html__( 'Margin', 'theplus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'allowed_dimensions' => 'vertical',
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'device_padding',
			[
				'label' => esc_html__( 'Padding', 'theplus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
            'section_device_bg_styling',
            [
                'label' => esc_html__('Device Background', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'scroll_image_effect',
			[
				'label' => esc_html__( 'Scroll Image', 'theplus' ),
				'type' => Controls_Manager::SWITCHER,				
				'label_on' => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default' => 'no',
			]
		);
		$this->add_responsive_control(
			'transition_duration',
			[
				'label'   => esc_html__( 'Transition Duration', 'theplus' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 4,
				],
				'range' => [
					'px' => [
						'step' => 0.1,
						'min'  => 0.1,
						'max'  => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .plus-device-wrapper .plus-media-inner .creative-scroll-image' => 'transition: background-position {{SIZE}}s ease-in-out;-webkit-transition: background-position {{SIZE}}s ease-in-out;',
				],
				'condition' => [
					'scroll_image_effect' => 'yes',
				],
			]
		);
		$this->add_control(
			'shadow_options',
			[
				'label' => esc_html__( 'Box Shadow', 'theplus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->start_controls_tabs( 'shadow_style' );
		$this->start_controls_tab(
			'shadow_normal',
			[
				'label' => esc_html__( 'Normal', 'theplus' ),
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .plus-device-wrapper .plus-device-shape,{{WRAPPER}} .plus-device-wrapper .plus-carousal-device-mokeup',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'shadow_hover',
			[
				'label' => esc_html__( 'Hover', 'theplus' ),
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_hover_shadow',
				'selector' => '{{WRAPPER}} .plus-device-wrapper:hover .plus-device-shape,{{WRAPPER}} .plus-device-wrapper .plus-carousal-device-mokeup:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Adv tab*/
		$this->start_controls_section(
            'section_plus_extra_adv',
            [
                'label' => esc_html__('Plus Extras', 'theplus'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );
		$this->end_controls_section();
		/*Adv tab*/

		/*--On Scroll View Animation ---*/
		include THEPLUS_PATH. 'modules/widgets/theplus-widget-animation.php';
	}
	
    protected function render() {
		$settings = $this->get_settings_for_display();

		/*--On Scroll View Animation ---*/
			include THEPLUS_PATH. 'modules/widgets/theplus-widget-animation-attr.php';

		/*--Plus Extra ---*/
			$PlusExtra_Class = "plus-flip-box-widget";
			include THEPLUS_PATH. 'modules/widgets/theplus-widgets-extra.php';

		$media_content=$layout_shape=$device_class ='';
		if($settings["device_mode"]=='normal'){
		
			if($settings["device_mockup"]=='mobile'){
				$layout_shape='<img src="'.THEPLUS_ASSETS_URL.'images/devices/'.$settings["device_mobile"].'.png" class="plus-device-image" alt="Plus mobile device">';
				$device_class .= $settings["device_mobile"];
			}else if($settings["device_mockup"]=='tablet'){
				$layout_shape='<img src="'.THEPLUS_ASSETS_URL.'images/devices/'.$settings["device_tablet"].'.png" class="plus-device-image" alt="Plus tablet device">';
				$device_class .= $settings["device_tablet"];
			}else if($settings["device_mockup"]=='laptop'){
				$layout_shape='<img src="'.THEPLUS_ASSETS_URL.'images/devices/'.$settings["device_laptop"].'.png" class="plus-device-image" alt="Plus laptop device">';
				$device_class .= $settings["device_laptop"];
			}else if($settings["device_mockup"]=='desktop'){
				$layout_shape='<img src="'.THEPLUS_ASSETS_URL.'images/devices/'.$settings["device_desktop"].'.png" class="plus-device-image" alt="Plus desktop device">';
				$device_class .= $settings["device_desktop"];
			}
			
			$device_url=$device_url_close='';
			if ( !empty($settings["device_link_popup"]) && ! empty( $settings['device_link']['url'] ) ) {
				$this->add_render_attribute( 'device_url', 'href', $settings['device_link']['url'] );
				if ( $settings['device_link']['is_external'] ) {
					$this->add_render_attribute( 'device_url', 'target', '_blank' );
				}
				if ( $settings['device_link']['nofollow'] ) {
					$this->add_render_attribute( 'device_url', 'rel', 'nofollow' );
				}
				if(!empty($settings["device_link_popup"]) && $settings["device_link_popup"]=='popup'){
					$this->add_render_attribute( 'device_url', 'data-lity', '' );
				}
				$device_url = '<a '.$this->get_render_attribute_string( "device_url" ).' class="plus-media-link">';
				$device_url_close = '</a>';
			}
			$icon_effect='';
			if(!empty($settings["icon_continuous_animation"]) && $settings["icon_continuous_animation"]=='yes'){
				if($settings["icon_animation_hover"]=='yes'){
					$animation_class='hover_';
				}else{
					$animation_class='image-';
				}
				$icon_effect=$animation_class.$settings["icon_animation_effect"];
			}
			$device_icon_center='';
			if(!empty($settings["icon_show"]) && $settings["icon_show"]=='yes' && !empty($settings["icon_image"]["url"])){
				$image_id=$settings["icon_image"]["id"];
				$imgSrc= tp_get_image_rander( $image_id,'full');
				$device_icon_center .= '<div class="plus-device-icon">';
					$device_icon_center .= '<div class="plus-device-icon-inner '.esc_attr($icon_effect).'">';
						$device_icon_center .= $imgSrc;
					$device_icon_center .= '</div>';
				$device_icon_center .= '</div>';
			}
			
			$scroll_image=$scroll_image_content='';
			if(!empty($settings["scroll_image_effect"]) && $settings['scroll_image_effect']=='yes'){
				$this->add_render_attribute( 'scroll-image', 'style', 'background-image: url(' . esc_url($settings['media_image']['url']) . ');' );
				$scroll_image_content ='<div class="creative-scroll-image" ' . $this->get_render_attribute_string( 'scroll-image' ) . '></div>';
				$scroll_image='scroll-image-wrap';
			}
			
			if(!empty($layout_shape)){
				$media_content= '<div class="plus-media-inner">';
					$media_content .= '<div class="plus-media-screen">';
						$media_content .= '<div class="plus-media-screen-inner '.esc_attr($scroll_image).'">';
							if(!empty($settings["scroll_image_effect"]) && $settings['scroll_image_effect']=='yes'){
								$media_content .= $scroll_image_content;
							}else if(!empty($settings["media_image"]["url"])){
								$image_id=$settings["media_image"]["id"];
								$imgSrc1= tp_get_image_rander( $image_id,'full', [ 'class' => 'plus-media-image' ] );								
								$media_content .=$imgSrc1;
							}
							$media_content .= $device_url;
								$media_content .= $device_icon_center;
							$media_content .= $device_url_close;
						$media_content .= '</div>';
					$media_content .= '</div>';
				$media_content .= '</div>';
			}
		}
		
		$slide_image=$carousal_device=$carousal_attr='';
		if($settings["device_mode"]=='carousal'){
			if($settings["device_mockup_carousal"]=='mobile'){
				$layout_shape='<img src="'.THEPLUS_ASSETS_URL.'images/devices/'.$settings["device_mobile_carousal"].'.png" class="plus-device-image" alt="Device mobile">';
				$carousal_device .= $settings["device_mobile_carousal"];
			}
			if(!empty($settings['slider_gallery'])){
				foreach ( $settings['slider_gallery'] as $image ) {
					$image_id=$image["id"];
					$imgSrc2= tp_get_image_rander( $image_id,'full');
					
					$slide_image .= '<div class="plus-device-slide">';
						$slide_image .= $imgSrc2;
					$slide_image .= '</div>';
				}
			}
			
			$infinite = ($settings['carousal_infinite']=='yes') ? 'true' : 'false';
			$autoplay = ($settings['carousal_autoplay']=='yes') ? 'true' : 'false';
			$autoplay_speed = (!empty($settings['carousal_autoplay_speed']["size"])) ? $settings['carousal_autoplay_speed']["size"] : '4000';
			$speed = (!empty($settings['carousal_speed']["size"])) ? $settings['carousal_speed']["size"] : '700';
			
			$carousal_attr .= ' data-infinite="'.esc_attr($infinite).'"';
			$carousal_attr .= ' data-autoplay="'.esc_attr($autoplay).'"';
			$carousal_attr .= ' data-autoplay_speed="'.esc_attr($autoplay_speed).'"';
			$carousal_attr .= ' data-speed="'.esc_attr($speed).'"';
		}
		$uid=uniqid("device");
		$device_mockup=$settings["device_mockup"];
		$output= '<div class="plus-device-wrapper device-type-'.esc_attr($device_mockup).' '.esc_attr($device_class).' '.esc_attr($animated_class).'" '.$animation_attr.'>';
			$output .= '<div class="plus-device-inner">';
				if($settings["device_mode"]=='normal'){
					$output .= '<div class="plus-device-content">';
						$output .= '<div class="plus-device-shape">';
							$output .= $layout_shape;
						$output .= '</div>';
						$output .= '<div class="plus-device-media">';
							$output .= $media_content;
						$output .= '</div>';
					$output .= '</div>';
					
				}else if($settings["device_mode"]=='carousal'){
					$output .= '<div class="plus-carousal-device-mokeup">';
						$output .= '<div class="plus-device-content">';
							$output .= $layout_shape;
						$output .= '</div>';
					$output .= '</div>';
					$output .='<div class="plus-device-carousal column-'.esc_attr($settings["carousal_columns"]).' '.esc_attr($uid).'" data-id="'.esc_attr($uid).'" '.$carousal_attr.'>';
						$output .= $slide_image;
					$output .= '</div>';
				}
			$output .= '</div>';
		$output .= '</div>';
		
		echo $before_content.$output.$after_content;
	}

	protected function content_template() {
	
	}
}