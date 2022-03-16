<?php 
/*
Widget Name: Dark Mode
Description: Dark Mode.
Author: Theplus
Author URI: https://posimyth.com
*/
namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class ThePlus_Dark_Mode extends Widget_Base {
		
	public function get_name() {
		return 'tp-dark-mode';
	}

    public function get_title() {
        return esc_html__('Dark Mode', 'theplus');
    }

    public function get_icon() {
        return 'fa fa-adjust theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-essential');
    }
	
    protected function _register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Dark Mode', 'theplus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
            'dm_style', [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Style', 'theplus'),
                'default' => 'tp_dm_style2',
                'options' => [                                  
                    'tp_dm_style2' => esc_html__('Style 1', 'theplus'),
					 'tp_dm_style1' => esc_html__('Style 2', 'theplus'),
                    
                ],
            ]
        );
		/*$this->add_control(
			'dm_mixcolor',
			[
				'label' => esc_html__( 'Mix Color', 'theplus' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'.tp_dm_style2.darkmode--activated .darkmode-background' => 'color: {{VALUE}};',
				],
				'condition' => [
					'dm_style' => 'tp_dm_style1',
				],
			]
		);*/
		/*$this->add_control(
			'dm_backgroundcolor',
			[
				'label' => esc_html__( 'Background Color', 'theplus' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'.tp_dm_style2 .darkmode-background' => 'background:{{VALUE}}',
				],
				'condition' => [
					'dm_style' => 'tp_dm_style1',
				],
			]
		);*/
		$this->add_control(
			'dm_backgroundcolor_activate',
			[
				'label' => esc_html__( 'Background Color', 'theplus' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
			]
		);
		$this->add_control(
            'dm_mix_blend_mode', [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Mix Blend Mode', 'theplus'),
                'default' => 'difference',
                'options' => [
                    'difference' => esc_html__('Difference', 'theplus'),                    
                    'multiply' => esc_html__('multiply', 'theplus'),
                    'screen' => esc_html__('screen', 'theplus'),
                    'overlay' => esc_html__('overlay', 'theplus'),
                    'darken' => esc_html__('darken', 'theplus'),
                    'lighten' => esc_html__('lighten', 'theplus'),
                    'color-dodge' => esc_html__('color-dodge', 'theplus'),
                    'color-burn' => esc_html__('color-burn', 'theplus'),
                    'exclusion' => esc_html__('exclusion', 'theplus'),
                    'hue' => esc_html__('hue', 'theplus'),
                    'saturation' => esc_html__('saturation', 'theplus'),                    
                ],
				'condition' => [
					'dm_style' => 'tp_dm_style2',
				],
				'selectors'  => [
					'body .darkmode-layer' => 'mix-blend-mode: {{VALUE}};',
				],
				'separator' => 'before',
            ]
        );
		$this->add_responsive_control(
			'dm_time',
			[
				'label' => esc_html__( 'Animation Time', 'theplus' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'separator' => 'before',
				'condition' => [
					'dm_style' => 'tp_dm_style1',
				],
			]
		);
		
		$this->end_controls_section();
		
		/*position option start*/
		$this->start_controls_section(
			'content_position_option',
			[
				'label' => esc_html__( 'Position', 'theplus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		/*$this->add_control(
			'dm_left',
			[
				'label' => esc_html__( 'Left Offset', 'theplus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'theplus' ),
				'label_off' => esc_html__( 'No', 'theplus' ),				
				'default' => 'no',				
			]
		);
		$this->add_responsive_control(
            'dm_left_offset',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Left', 'theplus'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 150,
						'step' => 1,
					],
				],				
                'selectors' => [
                    '.elementor-default .darkmode-toggle, .elementor-default  .darkmode-layer' => 'left: {{SIZE}}{{UNIT}};right:auto;',
                ],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'condition' => [
					'dm_left' => 'yes',
				],
            ]
        );*/
		$this->add_control(
			'dm_right',
			[
				'label' => esc_html__( 'Right Offset', 'theplus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'theplus' ),
				'label_off' => esc_html__( 'No', 'theplus' ),				
				'default' => 'yes',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
            'dm_right_offset',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Right', 'theplus'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 150,
						'step' => 1,
					],
				],				
                'selectors' => [
                    '.elementor-default .darkmode-toggle, .elementor-default  .darkmode-layer' => 'right: {{SIZE}}{{UNIT}};',					
                ],
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'condition' => [
					'dm_right' => 'yes',
				],
            ]
        );
		/*$this->add_control(
			'dm_top',
			[
				'label' => esc_html__( 'Top Offset', 'theplus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'theplus' ),
				'label_off' => esc_html__( 'No', 'theplus' ),				
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
            'dm_top_offset',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Top', 'theplus'),
				'default' => [
					'unit' => 'px',
					'size' => 32,
				],
				'range' => [
					'px' => [
						'min'	=> 1,
						'max'	=> 150,
						'step' => 1,
					],
				],				
                'selectors' => [
                    '.elementor-default .darkmode-toggle, .elementor-default  .darkmode-layer' => 'top: {{SIZE}}{{UNIT}};bottom:auto;',					
                ],
				'condition' => [
					'dm_top' => 'yes',
				],
            ]
        );*/
		$this->add_control(
			'dm_bottom',
			[
				'label' => esc_html__( 'Bottom Offset', 'theplus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'theplus' ),
				'label_off' => esc_html__( 'No', 'theplus' ),				
				'default' => 'yes',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
            'dm_bottom_offset',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Bottom', 'theplus'),
				'default' => [
					'unit' => 'px',
					'size' => 32,
				],
				'range' => [
					'px' => [
						'min'	=> 1,
						'max'	=> 150,
						'step' => 1,
					],
				],				
                'selectors' => [
                    '.elementor-default .darkmode-toggle, .elementor-default  .darkmode-layer' => 'bottom: {{SIZE}}{{UNIT}};',					
                ],
				'condition' => [
					'dm_bottom' => 'yes',
				],
            ]
        );
		$this->end_controls_section();
		/*position end*/
		
		/*extra option start*/
		$this->start_controls_section(
			'content_extra_option',
			[
				'label' => esc_html__( 'Extra Option', 'theplus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);				
		$this->add_control(
            'dm_save_in_cookies',
            [
				'label'   => esc_html__( 'Save in Cookies', 'theplus' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'If enabled, It will remember choice of user and load accordingly on next website visit.', 'theplus' ),
				'separator' => 'before',
			]
		);		
		$this->add_control(
            'dm_auto_match_os_theme',
            [
				'label'   => esc_html__( 'Auto Match OS Theme', 'theplus' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',	
				'description' => esc_html__( 'If enabled, It will automatically apply based on Mode of Visitor device settings.', 'theplus' ),
				'separator' => 'before',
			]
		);
		$this->add_control(
            'dm_ignore_class',
            [
				'label'   => esc_html__( 'Ignore Dark Mode', 'theplus' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'dm_ignore',
			[
				'label' => __( 'Ignore Dark Mode Classes', 'theplus' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,				
				'placeholder' => __( 'Enter All Classes with Comma to ignore those in Dark Mode', 'theplus' ),
				'condition' => [
					'dm_ignore_class' => 'yes',
				],
			]
		);
		$this->add_control(
            'dm_ignore_pre_class',
            [
				'label'   => esc_html__( 'The Plus Ignore Class Default', 'theplus' ),
				'type'    => Controls_Manager::SWITCHER,				
				'separator' => 'before',
				'label_on' => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
				'default' => 'true',
				'condition' => [
					'dm_ignore_class' => 'yes',
				],
			]
		);
		$this->add_control(
			'dm_ignore_pre_class_note',
			[
				'label' => ( 'Note : You can Ignore classes you want from Dark Mode using above options.'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'dm_ignore_class' => 'yes',
				],
			]
		);
		/*$this->add_control(
            'dm_ignore_mode', 
			[
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Mix Blend Ignore Mode', 'theplus'),
                'default' => 'difference',
                'options' => [
                    'difference' => esc_html__('Difference', 'theplus'),                    
                    'multiply' => esc_html__('multiply', 'theplus'),                    
                    'darken' => esc_html__('darken', 'theplus'),
                    'lighten' => esc_html__('lighten', 'theplus'),
                ],				
				'selectors'  => [
					'.darkmode--activated .theplus-darkmode-ignore' => 'mix-blend-mode: {{VALUE}} !important;',
				],
				'condition' => [
					'dm_ignore_class' => 'yes',
				],
            ]
        );*/
		$this->end_controls_section();
		/*extra option end*/
		
		/*for style2 start*/
		/* style section start*/
		$this->start_controls_section(
            'section_switcher_st2_styling',
            [
                'label' => esc_html__('Switcher Style', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'dm_style' => 'tp_dm_style1',
				],
            ]
        );
		$this->add_responsive_control(
            'st2_size_d',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Size', 'theplus'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
						'step' => 1,
					],
				],
				'separator' => 'after',
				'render_type' => 'ui',
				'selectors' => [
					'.darkmode-toggle' => 'font-size: {{SIZE}}{{UNIT}}',
				],
            ]
        );
		$this->add_responsive_control(
            'st2_bg_size_d',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Background Size', 'theplus'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
						'step' => 1,
					],
				],
				'separator' => 'after',
				'render_type' => 'ui',
				'selectors' => [
					'.darkmode-toggle, .darkmode-layer:not(.darkmode-layer--expanded)' => 'height: {{SIZE}}{{UNIT}} !important;width: {{SIZE}}{{UNIT}} !important;',
				],
            ]
        );
		$this->start_controls_tabs( 'tabs_si2_style' );
		$this->start_controls_tab(
			'tab_si2_light',
			[
				'label' => esc_html__( 'Light', 'theplus' ),
			]
		);
		$this->add_control(
			'st2_bg',
			[
				'label' => esc_html__( 'Background', 'theplus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'.darkmode-toggle' => 'background-color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'st2_border',
				'label' => esc_html__( 'Border', 'theplus' ),
				'selector' => '.darkmode-toggle',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'st2_br',
			[
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'.darkmode-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'st2_shadow',
				'label' => esc_html__( 'Box Shadow', 'theplus' ),
				'selector' => '.darkmode-toggle',
				'separator' => 'before',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_si2_dark',
			[
				'label' => esc_html__( 'Dark', 'theplus' ),
			]
		);		
		$this->add_control(
			'st2_bg_d',
			[
				'label' => esc_html__( 'Background', 'theplus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'.darkmode-toggle.darkmode-toggle--white' => 'background-color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'st2_border_d',
				'label' => esc_html__( 'Border', 'theplus' ),
				'selector' => '.darkmode-toggle.darkmode-toggle--white',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'st2_br_d',
			[
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'.darkmode-toggle.darkmode-toggle--white' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'st2_shadow_d',
				'label' => esc_html__( 'Box Shadow', 'theplus' ),
				'selector' => '.darkmode-toggle.darkmode-toggle--white',
				'separator' => 'before',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		/*for style2 end*/
		
		/* style section start*/
		$this->start_controls_section(
            'section_switcher_text_styling',
            [
                'label' => esc_html__('Switcher Text Style', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'dm_style!' => 'tp_dm_style1',
				],
            ]
        );		
		$this->start_controls_tabs( 'tabs_s_b_a_style' );
		$this->start_controls_tab(
			'tab_s_b_a_before',
			[
				'label' => esc_html__( 'Before', 'theplus' ),
			]
		);
		$this->add_control(
			'switcher_before_text',
			[
				'label' => esc_html__( 'Switcher Before Text', 'theplus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Normal', 'theplus' ),
				'placeholder' => esc_html__( 'Before Text', 'theplus' ),
				'selectors' => [
                    '.tp_dm_style2 .darkmode-toggle:before' => ' content:"{{VALUE}}";',
				],				
			]
		);
		$this->add_responsive_control(
            'switcher_before_text_offset',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Offset', 'theplus'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 0,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => -65,
				],
				'separator' => 'before',
				'selectors' => [
					'.tp_dm_style2 .darkmode-toggle:before' => 'left: {{SIZE}}{{UNIT}}',
				],
            ]
        );
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_s_b_a_after',
			[
				'label' => esc_html__( 'After', 'theplus' ),
			]
		);
		$this->add_control(
			'switcher_after_text',
			[
				'label' => esc_html__( 'Switcher After Text', 'theplus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Dark', 'theplus' ),
				'placeholder' => esc_html__( 'After Text', 'theplus' ),
				'selectors' => [
                    '.tp_dm_style2 .darkmode-toggle:after' => ' content:"{{VALUE}}";',
				],				
			]
		);
		$this->add_responsive_control(
            'switcher_after_text_offset',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Offset', 'theplus'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 0,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => -45,
				],
				'separator' => 'before',				
				'selectors' => [
					'.tp_dm_style2 .darkmode-toggle:after' => 'right: {{SIZE}}{{UNIT}}',
				],
            ]
        );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'switcher_b_a_text_typ',
				'selector' => '.tp_dm_style2 .darkmode-toggle:before,.tp_dm_style2 .darkmode-toggle:after',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'switcher_b_a_text_typ_color',
			[
				'label' => esc_html__( 'Color', 'theplus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.tp_dm_style2 .darkmode-toggle:before,.tp_dm_style2 .darkmode-toggle:after' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
		
		/*switcher inner start*/
		$this->start_controls_section(
            'section_switcher_styling',
            [
                'label' => esc_html__('Switcher Style', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'dm_style!' => 'tp_dm_style1',					
				],
            ]
        );
		$this->add_responsive_control(
            'switcher_overall_size',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Switcher Size', 'theplus'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
						'step' => 1,
					],
				],
				'separator' => 'before',
				'render_type' => 'ui',
				'selectors' => [					
					'.tp_dm_style2 .darkmode-toggle' => 'width: calc(10px + {{SIZE}}{{UNIT}});height: calc(-20px + {{SIZE}}{{UNIT}});',			
					'.tp_dm_style2 .darkmode-toggle .tp-dark-mode-slider:before' => 'height: calc(26px + {{SIZE}}{{UNIT}});width: calc(26px + {{SIZE}}{{UNIT}});',
				],
            ]
        );
		$this->add_responsive_control(
            'switcher_dot_size',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Dot Size', 'theplus'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'separator' => 'before',
				'render_type' => 'ui',
				'selectors' => [
					'.tp_dm_style2 .darkmode-toggle .tp-dark-mode-slider:before' => 'height: {{SIZE}}{{UNIT}} !important;width: {{SIZE}}{{UNIT}} !important;',
				],
            ]
        );
		$this->add_responsive_control(
            'switcher_dot_offset',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Dot Offset', 'theplus'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 50,
						'step' => 1,
					],
				],
				'separator' => 'before',
				'render_type' => 'ui',
				'selectors' => [
					'.tp_dm_style2 .darkmode-toggle .tp-dark-mode-slider:before' => 'left: -{{SIZE}}{{UNIT}} !important;',
					'.tp_dm_style2 .darkmode-toggle .tp-dark-mode-checkbox:checked + .tp-dark-mode-slider:before' => 'transform: translateX(calc(26px + {{SIZE}}{{UNIT}}))translateY(-50%) !important;',
				],
            ]
        );
		$this->start_controls_tabs( 'tabs_si_style' );
		$this->start_controls_tab(
			'tab_si_normal',
			[
				'label' => esc_html__( 'Light', 'theplus' ),
			]
		);
		$this->add_control(
			'si_normal_dot',
			[
				'label' => esc_html__( 'Dot Background', 'theplus' ),
				'type' => \Elementor\Controls_Manager::HEADING,				
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'si_normal_dot_bg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '.tp_dm_style2 .darkmode-toggle .tp-dark-mode-slider:before',
			]
		);
		$this->add_control(
			'si_normal_switch',
			[
				'label' => esc_html__( 'Switcher Background', 'theplus' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'si_normal_switch_bg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '.tp_dm_style2 .darkmode-toggle .tp-dark-mode-slider',
			]
		);		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'si_switch_n_border',
				'label' => esc_html__( 'Border', 'theplus' ),
				'selector' => '.tp_dm_style2 .darkmode-toggle .tp-dark-mode-slider',
				'separator' => 'before',
			]
		);		
		$this->add_control(
			'si_switch_normal_border_color',
			[
				'label' => esc_html__( 'Box Shadow color', 'theplus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.tp_dm_style2 .darkmode-toggle .tp-dark-mode-slider' => 'box-shadow:0 0 1px {{VALUE}};',
				],				
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_si_active',
			[
				'label' => esc_html__( 'Dark', 'theplus' ),
			]
		);
		$this->add_control(
			'si_active_dot',
			[
				'label' => esc_html__( 'Dot Background', 'theplus' ),
				'type' => \Elementor\Controls_Manager::HEADING,				
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'si_active_dot_bg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '.tp_dm_style2.darkmode--activated .darkmode-toggle .tp-dark-mode-slider:before',
			]
		);
		$this->add_control(
			'si_active_switch',
			[
				'label' => esc_html__( 'Switcher Background', 'theplus' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'si_switch_active_bg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '.tp_dm_style2.darkmode--activated .darkmode-toggle .tp-dark-mode-slider',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'si_switch_active_border',
				'label' => esc_html__( 'Border', 'theplus' ),
				'selector' => '.tp_dm_style2.darkmode--activated .darkmode-toggle .tp-dark-mode-slider',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'si_switch_active_border_color_n',
			[
				'label' => esc_html__( 'Box Shadow Color', 'theplus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'.tp_dm_style2 .darkmode-toggle .tp-dark-mode-checkbox:focus + .tp-dark-mode-slider' => 'box-shadow:0 0 1px {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		/*switcher inner end*/
		/* style section start*/
	}
	 protected function render() {

        $settings = $this->get_settings_for_display();
		
		$dm_style = $settings['dm_style'];
		$dm_save_in_cookies=($settings['dm_save_in_cookies']) ? 'true' : 'false';
		$dm_auto_match_os_theme=($settings['dm_auto_match_os_theme']) ? 'true' : 'false';
		
		$dm_time=($settings['dm_time']) ? $settings['dm_time'] : '5';
		$dm_backgroundcolor=($settings['dm_backgroundcolor_activate']) ? $settings['dm_backgroundcolor_activate'] : '#fff';		
		
		echo '<div class="tp-dark-mode-wrapper" data-time="0.'.esc_attr($dm_time).'s" data-dm_mixcolor="#fff" data-bgcolor="'.esc_attr($dm_backgroundcolor).'" data-save-cookies="'.esc_attr($dm_save_in_cookies).'" data-auto-match-os-theme="'.esc_attr($dm_auto_match_os_theme).'" data-style="'.esc_attr($dm_style).'">';
		
		
		/*append class start*/
		$dm_ignore_js='';
		if(!empty($settings['dm_ignore'])){		
		$dm_ignore_js = 'jQuery(document).ready(function() {
			
			jQuery( "'.esc_js($settings['dm_ignore']).'" ).addClass( "theplus-darkmode-ignore" );
		});';
		echo wp_print_inline_script_tag($dm_ignore_js);
		}
		/*append class end*/
		
		/*append predefine class start*/
		$dm_ignore_pre_class_js='';
		if(!empty($settings['dm_ignore_pre_class']) && $settings['dm_ignore_pre_class']=='yes'){		
		$dm_ignore_pre_class_js = 'jQuery(document).ready(function() {			
			jQuery( ".theplus-hotspot,.pt-plus-animated-image-wrapper .pt_plus_animated_image,.elementor-image img,.elementor-widget-image img,.elementor-image, .animated-image-parallax,.pt_plus_before_after,.pt_plus_animated_image,.team-list-content .post-content-image,.product-list .product-content-image,.gallery-list .gallery-list-content,.bss-list,.blog-list.list-isotope-metro,.blog-list .post-content-image,.blog-list-content:hover .post-content-image,.blog-list.blog-style-1 .grid-item" ).addClass( "theplus-darkmode-ignore" );
		});';
		echo wp_print_inline_script_tag($dm_ignore_pre_class_js);
		}
		/*append predefine class end*/
		
		echo '</div>';
		
	}
    protected function content_template() {
	
    }	
}