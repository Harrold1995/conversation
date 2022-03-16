<?php 
/*
Widget Name: Countdown 
Description: Display countdown.
Author: Theplus
Author URI: https://posimyth.com
*/
namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class ThePlus_Countdown extends Widget_Base {
		
	public function get_name() {
		return 'tp-countdown';
	}

    public function get_title() {
        return esc_html__('Countdown', 'theplus');
    }

    public function get_icon() {
        return 'fa fa-clock-o theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-essential');
    }

    protected function _register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Countdown Date', 'theplus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'counting_timer',
			[
				'label' => esc_html__( 'Launch Date', 'theplus' ),
				'type' => Controls_Manager::DATE_TIME,
				'default'     => date( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				'description' => sprintf( esc_html__( 'Date set according to your timezone: %s.', 'theplus' ), Utils::get_timezone_string() ),
			]
		);
		$this->add_control(
			'inline_style',
			[
				'label' => esc_html__( 'Inline Style', 'theplus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'theplus' ),
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_downcount',
            [
                'label' => esc_html__('Label', 'theplus'),
            ]
        );
		$this->add_control(
			'show_labels',
			[
				'label'   => esc_html__( 'Show Labels', 'theplus' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
            'text_days',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Days Section Text', 'theplus'),
                'label_block' => true,
                'separator' => 'before',
                'default' => esc_html__('Days', 'theplus'),
				'condition'    => [
					'show_labels!' => '',
				],
            ]
        );
		$this->add_control(
            'text_hours',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Hours Section Text', 'theplus'),
                'label_block' => true,
                'separator' => 'before',
                'default' => esc_html__('Hours', 'theplus'),
				'condition'    => [
					'show_labels!' => '',
				],
            ]
        );
		$this->add_control(
            'text_minutes',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Minutes Section Text', 'theplus'),
                'label_block' => true,
                'separator' => 'before',
                'default' => esc_html__('Minutes', 'theplus'),
                'condition'    => [
					'show_labels!' => '',
				],
            ]
        );
		$this->add_control(
            'text_seconds',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Seconds Section Text', 'theplus'),
                'label_block' => true,
                'separator' => 'before',
                'default' => esc_html__('Seconds', 'theplus'),
				'condition'    => [
					'show_labels!' => '',
				],
            ]
        );
		$this->end_controls_section();
		$this->start_controls_section(
            'section_styling',
            [
                'label' => esc_html__('Counter Styling', 'theplus'),
                'tab' => Controls_Manager::TAB_STYLE,				
            ]
        );
		$this->add_control(
            'number_text_color',
            [
                'label' => esc_html__('Counter Font Color', 'theplus'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li > span' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'numbers_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  .pt_plus_countdown li > span',
				'separator' => 'after',
			)
		);
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => esc_html__('Label Typography', 'theplus'),
                'selector' => '{{WRAPPER}} .pt_plus_countdown li > h6',
				'separator' => 'after',
				'condition'    => [
					'show_labels!' => '',
				],
            ]
        );
		$this->start_controls_tabs( 'tabs_days_style' );

		$this->start_controls_tab(
			'tab_day_style',
			[
				'label' => esc_html__( 'Days', 'theplus' ),
			]
		);
		$this->add_control(
            'days_text_color',
            [
                'label' => esc_html__('Text Color', 'theplus'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_1 h6' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'days_border_color',
            [
                'label' => esc_html__('Border Color', 'theplus'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_1' => 'border-color:{{VALUE}};',
                ],
                'condition'    => [
                    'inline_style!' => 'yes',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'days_background',
				'label'     => esc_html__("Days Background",'theplus'),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .pt_plus_countdown li.count_1',
				
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hour_style',
			[
				'label' => esc_html__( 'Hours', 'theplus' ),
			]
		);
		$this->add_control(
            'hours_text_color',
            [
                'label' => esc_html__('Text Color', 'theplus'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_2 h6' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'hours_border_color',
            [
                'label' => esc_html__('Border Color', 'theplus'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_2' => 'border-color:{{VALUE}};',
                ],
                'condition'    => [
                    'inline_style!' => 'yes',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'hours_background',
				'label'     => esc_html__("Background",'theplus'),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .pt_plus_countdown li.count_2',				
			]
		);
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_minute_style',
			[
				'label' => esc_html__( 'Minutes', 'theplus' ),
			]
		);
		$this->add_control(
            'minutes_text_color',
            [
                'label' => esc_html__('Text Color', 'theplus'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_3 h6' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'minutes_border_color',
            [
                'label' => esc_html__('Border Color', 'theplus'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_3' => 'border-color:{{VALUE}};',
                ],
                'condition'    => [
                    'inline_style!' => 'yes',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'minutes_background',
				'label'     => esc_html__("Background",'theplus'),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .pt_plus_countdown li.count_3',				
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_second_style',
			[
				'label' => esc_html__( 'Seconds', 'theplus' ),
			]
		);
		$this->add_control(
            'seconds_text_color',
            [
                'label' => esc_html__('Text Color', 'theplus'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_4 h6' => 'color:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
            'seconds_border_color',
            [
                'label' => esc_html__('Border Color', 'theplus'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pt_plus_countdown li.count_4' => 'border-color:{{VALUE}};',
                ],
                'condition'    => [
                    'inline_style!' => 'yes',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'seconds_background',
				'label'     => esc_html__("Background",'theplus'),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .pt_plus_countdown li.count_4',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'counter_padding',
			[
				'label' => esc_html__( 'Padding', 'theplus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_responsive_control(
			'counter_margin',
			[
				'label' => esc_html__( 'Margin', 'theplus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_control(
			'count_border_style',
			[
				'label'   => esc_html__( 'Border Style', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'none'   => esc_html__( 'None', 'theplus' ),
					'solid'  => esc_html__( 'Solid', 'theplus' ),
					'dotted' => esc_html__( 'Dotted', 'theplus' ),
					'dashed' => esc_html__( 'Dashed', 'theplus' ),
					'groove' => esc_html__( 'Groove', 'theplus' ),
				],
				'separator' => 'before',
				'selectors'  => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'border-style: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'count_border_width',
			[
				'label' => esc_html__( 'Border Width', 'theplus' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'    => 3,
					'right'  => 3,
					'bottom' => 3,
					'left'   => 3,
				],
				'selectors'  => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'count_border_style!' => 'none',
				]
			]
		);
		$this->add_control(
			'count_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pt_plus_countdown li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'count_hover_shadow',
				'selector' => '{{WRAPPER}} .pt_plus_countdown li',
				'separator' => 'before',
			]			
		);
		
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
		
		$data_attr='';
		
		$uid=uniqid('count_down');
			if (empty($settings['show_labels'])){
				$show_labels=$settings['show_labels'];
			}else{
				$show_labels='yes';
			}
			if (empty($settings['text_days'])){
				$text_days='Days';
			}else{
				$text_days=$settings['text_days'];
			}
			
			if (empty($settings['text_hours'])){
				$text_hours='Hours';
			}else{
				$text_hours=$settings['text_hours'];
			}
			
			if (empty($settings['text_minutes'])){
				$text_minutes='Minutes';
			}else{
				$text_minutes=$settings['text_minutes'];
			}
			
			if (empty($settings['text_seconds'])){
				$text_seconds='Seconds';
			}else{
				$text_seconds=$settings['text_seconds'];
			}
			$offset_time=get_option('gmt_offset');
			if(!empty($settings['counting_timer'])){
				$counting_timer=$settings['counting_timer'];
				$counting_timer= date('m/d/Y H:i:s',strtotime($counting_timer) );
			}else{
				$counting_timer='08/31/2019 12:00:00';
			}
			$data_attr .=' data-days="'.esc_attr($text_days).'"';
			$data_attr .=' data-hours="'.esc_attr($text_hours).'"';
			$data_attr .=' data-minutes="'.esc_attr($text_minutes).'"';
			$data_attr .=' data-seconds="'.esc_attr($text_seconds).'"';

			/*--On Scroll View Animation ---*/
			include THEPLUS_PATH. 'modules/widgets/theplus-widget-animation-attr.php';

			/*--Plus Extra ---*/
			$PlusExtra_Class = "plus-countdown-widget";
			include THEPLUS_PATH. 'modules/widgets/theplus-widgets-extra.php';

			echo $before_content;
			
			$inline_style= (!empty($settings["inline_style"]) && $settings["inline_style"]=='yes') ? 'count-inline-style' : '';

			$lz1 = function_exists('tp_has_lazyload') ? tp_bg_lazyLoad($settings['days_background_image']) : '';
			$lz2 = function_exists('tp_has_lazyload') ? tp_bg_lazyLoad($settings['hours_background_image']) : '';
			$lz3 = function_exists('tp_has_lazyload') ? tp_bg_lazyLoad($settings['minutes_background_image']) : '';
			$lz4 = function_exists('tp_has_lazyload') ? tp_bg_lazyLoad($settings['seconds_background_image']) : '';
			?>

			<ul class="pt_plus_countdown <?php echo esc_attr($uid); ?> <?php echo esc_attr($inline_style); ?> <?php echo $animated_class; ?>" <?php echo $data_attr; ?> data-timer="<?php echo esc_attr($counting_timer); ?>" data-offset="<?php echo esc_attr($offset_time); ?>" <?php echo $animation_attr; ?>>
				<li class="count_1 <?php echo $lz1; ?>">
					<span class="days">00</span>
					<?php if(!empty($show_labels) && $show_labels=='yes'){ ?>
						<h6 class="days_ref"><?php echo esc_html($text_days); ?></h6>
					<?php } ?>
				</li>
				<li class="count_2 <?php echo $lz2; ?>">
					<span class="hours">00</span>
					<?php if(!empty($show_labels) && $show_labels=='yes'){ ?>
						<h6 class="hours_ref"><?php echo esc_html($text_hours); ?></h6>
					<?php } ?>
				</li>
				<li class="count_3 <?php echo $lz3; ?>">
					<span class="minutes">00</span>
					<?php if(!empty($show_labels) && $show_labels=='yes'){ ?>
					<h6 class="minutes_ref"><?php echo esc_html($text_minutes); ?></h6>
					<?php } ?>
				</li>
				<li class="count_4 <?php echo $lz4; ?>">
					<span class="seconds last">00</span>
					<?php if(!empty($show_labels) && $show_labels=='yes'){ ?>
					<h6 class="seconds_ref"><?php echo esc_html($text_seconds); ?></h6>
					<?php } ?>
				</li>
			</ul>
			
			<?php 	
			echo $after_content;
	}
    protected function content_template() {
	
    }
}