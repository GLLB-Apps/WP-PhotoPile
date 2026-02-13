<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Fotohog_Widget extends Widget_Base {
    public function get_name() {
        return 'fotohog_stack';
    }

    public function get_title() {
        return esc_html__( 'Fotohog', 'fotohog-elementor' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return array( 'basic' );
    }

    public function get_keywords() {
        return array( 'photo', 'gallery', 'stack', 'polaroid' );
    }

    public function get_style_depends() {
        return array( 'fotohog-widget' );
    }

    public function get_script_depends() {
        return array( 'fotohog-widget' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'fotohog-elementor' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'photo',
            array(
                'label'   => esc_html__( 'Photo', 'fotohog-elementor' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => array(
                    'active' => true,
                ),
            )
        );

        $repeater->add_control(
            'caption',
            array(
                'label'       => esc_html__( 'Caption', 'fotohog-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            )
        );

        $repeater->add_control(
            'link_type',
            array(
                'label'   => esc_html__( 'Click action', 'fotohog-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lightbox',
                'options' => array(
                    'none'     => esc_html__( 'None', 'fotohog-elementor' ),
                    'lightbox' => esc_html__( 'Open lightbox', 'fotohog-elementor' ),
                    'url'      => esc_html__( 'Open URL', 'fotohog-elementor' ),
                ),
            )
        );

        $repeater->add_control(
            'link_url',
            array(
                'label'       => esc_html__( 'Link URL', 'fotohog-elementor' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'show_label'  => true,
                'condition'   => array(
                    'link_type' => 'url',
                ),
            )
        );

        $this->add_control(
            'items',
            array(
                'label'       => esc_html__( 'Photos', 'fotohog-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(),
                'title_field' => '{{{ caption || "Photo" }}}',
            )
        );

        $this->add_control(
            'images',
            array(
                'label'       => esc_html__( 'Quick gallery (legacy)', 'fotohog-elementor' ),
                'description' => esc_html__( 'Optional fallback. Advanced Photo items above takes priority.', 'fotohog-elementor' ),
                'type'        => Controls_Manager::GALLERY,
                'default'     => array(),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'    => 'thumbnail',
                'default' => 'large',
            )
        );

        $this->add_control(
            'display_mode',
            array(
                'label'   => esc_html__( 'Display mode', 'fotohog-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'stack_hover_grid',
                'options' => array(
                    'stack_hover_grid' => esc_html__( 'Stack to grid on hover', 'fotohog-elementor' ),
                    'stack_only'       => esc_html__( 'Stack only', 'fotohog-elementor' ),
                    'grid_only'        => esc_html__( 'Grid only', 'fotohog-elementor' ),
                ),
            )
        );

        $this->add_control(
            'show_caption',
            array(
                'label'        => esc_html__( 'Show captions', 'fotohog-elementor' ),
                'description'  => esc_html__( 'Uses custom caption first, then Media Library caption/title.', 'fotohog-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'fotohog-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'fotohog-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'display_mode!' => 'stack_only',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            array(
                'label' => esc_html__( 'Style', 'fotohog-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'animation_preset',
            array(
                'label'   => esc_html__( 'Animation preset', 'fotohog-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'soft',
                'options' => array(
                    'soft'    => esc_html__( 'Soft', 'fotohog-elementor' ),
                    'bouncy'  => esc_html__( 'Bouncy', 'fotohog-elementor' ),
                    'instant' => esc_html__( 'Instant', 'fotohog-elementor' ),
                ),
            )
        );

        $this->add_control(
            'card_theme',
            array(
                'label'   => esc_html__( 'Card theme', 'fotohog-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'light',
                'options' => array(
                    'light' => esc_html__( 'Light', 'fotohog-elementor' ),
                    'dark'  => esc_html__( 'Dark', 'fotohog-elementor' ),
                ),
            )
        );

        $this->add_control(
            'image_tone_mode',
            array(
                'label'   => esc_html__( 'Image color mode', 'fotohog-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'normal_static',
                'options' => array(
                    'normal_static'   => esc_html__( 'Normal static', 'fotohog-elementor' ),
                    'grayscale_static' => esc_html__( 'Black and white static', 'fotohog-elementor' ),
                    'grayscale_to_color' => esc_html__( 'Black and white to color', 'fotohog-elementor' ),
                    'color_to_grayscale' => esc_html__( 'Color to black and white', 'fotohog-elementor' ),
                ),
            )
        );

        $this->add_responsive_control(
            'stack_height',
            array(
                'label'      => esc_html__( 'Stack height', 'fotohog-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'vh' ),
                'range'      => array(
                    'px' => array(
                        'min' => 220,
                        'max' => 900,
                    ),
                    'vh' => array(
                        'min' => 20,
                        'max' => 90,
                    ),
                ),
                'default'    => array(
                    'unit' => 'px',
                    'size' => 420,
                ),
            )
        );

        $this->add_responsive_control(
            'photo_width',
            array(
                'label'      => esc_html__( 'Photo width', 'fotohog-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 120,
                        'max' => 420,
                    ),
                ),
                'default'    => array(
                    'unit' => 'px',
                    'size' => 220,
                ),
            )
        );

        $this->add_control(
            'spread',
            array(
                'label'     => esc_html__( 'Stack spread', 'fotohog-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 220,
                    ),
                ),
                'default'   => array(
                    'size' => 80,
                ),
                'condition' => array(
                    'display_mode!' => 'grid_only',
                ),
            )
        );

        $this->add_control(
            'max_rotation',
            array(
                'label'     => esc_html__( 'Rotation variance', 'fotohog-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'deg' => array(
                        'min' => 0,
                        'max' => 18,
                    ),
                ),
                'default'   => array(
                    'size' => 8,
                ),
                'condition' => array(
                    'display_mode!' => 'grid_only',
                ),
            )
        );

        $this->add_control(
            'grid_columns',
            array(
                'label'     => esc_html__( 'Grid columns', 'fotohog-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min' => 2,
                        'max' => 6,
                    ),
                ),
                'default'   => array(
                    'size' => 3,
                ),
                'condition' => array(
                    'display_mode!' => 'stack_only',
                ),
            )
        );

        $this->add_control(
            'grid_gap',
            array(
                'label'     => esc_html__( 'Grid gap', 'fotohog-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 70,
                    ),
                ),
                'default'   => array(
                    'size' => 18,
                ),
                'condition' => array(
                    'display_mode!' => 'stack_only',
                ),
            )
        );

        $this->add_control(
            'enable_proximity_tilt',
            array(
                'label'        => esc_html__( 'Grid mouse proximity bend', 'fotohog-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'fotohog-elementor' ),
                'label_off'    => esc_html__( 'Off', 'fotohog-elementor' ),
                'return_value' => 'yes',
                'default'      => '',
                'condition'    => array(
                    'display_mode!' => 'stack_only',
                ),
            )
        );

        $this->add_control(
            'proximity_radius',
            array(
                'label'     => esc_html__( 'Proximity radius', 'fotohog-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min' => 120,
                        'max' => 520,
                    ),
                ),
                'default'   => array(
                    'size' => 260,
                ),
                'condition' => array(
                    'enable_proximity_tilt' => 'yes',
                    'display_mode!'         => 'stack_only',
                ),
            )
        );

        $this->add_control(
            'proximity_tilt',
            array(
                'label'     => esc_html__( 'Max bend angle', 'fotohog-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'deg' => array(
                        'min' => 2,
                        'max' => 20,
                    ),
                ),
                'default'   => array(
                    'size' => 9,
                ),
                'condition' => array(
                    'enable_proximity_tilt' => 'yes',
                    'display_mode!'         => 'stack_only',
                ),
            )
        );

        $this->add_control(
            'enable_hover_autoscale',
            array(
                'label'        => esc_html__( 'Auto-scale hover grid to fit container', 'fotohog-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'fotohog-elementor' ),
                'label_off'    => esc_html__( 'Off', 'fotohog-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'display_mode' => 'stack_hover_grid',
                ),
            )
        );

        $this->add_control(
            'hover_autoscale_min',
            array(
                'label'      => esc_html__( 'Minimum hover scale', 'fotohog-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%' ),
                'range'      => array(
                    '%' => array(
                        'min' => 40,
                        'max' => 100,
                    ),
                ),
                'default'    => array(
                    'unit' => '%',
                    'size' => 72,
                ),
                'condition'  => array(
                    'enable_hover_autoscale' => 'yes',
                    'display_mode'           => 'stack_hover_grid',
                ),
            )
        );

        $this->add_control(
            'hover_autoscale_easing',
            array(
                'label'     => esc_html__( 'Auto-scale easing', 'fotohog-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'ease',
                'options'   => array(
                    'linear'       => esc_html__( 'Linear', 'fotohog-elementor' ),
                    'ease'         => esc_html__( 'Ease', 'fotohog-elementor' ),
                    'ease-out'     => esc_html__( 'Ease Out', 'fotohog-elementor' ),
                    'ease-in-out'  => esc_html__( 'Ease In Out', 'fotohog-elementor' ),
                    'spring-soft'  => esc_html__( 'Spring Soft', 'fotohog-elementor' ),
                    'spring-hard'  => esc_html__( 'Spring Hard', 'fotohog-elementor' ),
                ),
                'condition' => array(
                    'enable_hover_autoscale' => 'yes',
                    'display_mode'           => 'stack_hover_grid',
                ),
            )
        );

        $this->add_control(
            'hover_lift',
            array(
                'label'        => esc_html__( 'Lift on hover', 'fotohog-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'fotohog-elementor' ),
                'label_off'    => esc_html__( 'Off', 'fotohog-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'display_mode' => 'stack_only',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $items    = ! empty( $settings['items'] ) ? $settings['items'] : array();

        if ( empty( $items ) && ! empty( $settings['images'] ) ) {
            foreach ( $settings['images'] as $legacy_image ) {
                $items[] = array(
                    'photo'     => $legacy_image,
                    'caption'   => '',
                    'link_type' => 'lightbox',
                    'link_url'  => array(),
                );
            }
        }

        if ( empty( $items ) ) {
            echo '<div class="fotohog-empty">' . esc_html__( 'Add photos to show the stack.', 'fotohog-elementor' ) . '</div>';
            return;
        }

        $stack_height = isset( $settings['stack_height']['size'] ) ? (float) $settings['stack_height']['size'] : 420;
        $stack_unit   = isset( $settings['stack_height']['unit'] ) ? $settings['stack_height']['unit'] : 'px';
        $photo_width  = isset( $settings['photo_width']['size'] ) ? (float) $settings['photo_width']['size'] : 220;
        $spread       = isset( $settings['spread']['size'] ) ? (float) $settings['spread']['size'] : 80;
        $rotation     = isset( $settings['max_rotation']['size'] ) ? (float) $settings['max_rotation']['size'] : 8;

        $display_mode = isset( $settings['display_mode'] ) ? $settings['display_mode'] : 'stack_hover_grid';
        $show_caption = ( isset( $settings['show_caption'] ) && 'yes' === $settings['show_caption'] );
        $hover_lift   = ( isset( $settings['hover_lift'] ) && 'yes' === $settings['hover_lift'] );
        $card_theme   = isset( $settings['card_theme'] ) ? $settings['card_theme'] : 'light';
        $tone_mode    = isset( $settings['image_tone_mode'] ) ? $settings['image_tone_mode'] : 'normal_static';
        $proximity    = ( isset( $settings['enable_proximity_tilt'] ) && 'yes' === $settings['enable_proximity_tilt'] );
        $hover_scale  = ( isset( $settings['enable_hover_autoscale'] ) && 'yes' === $settings['enable_hover_autoscale'] );

        $image_count = count( $items );
        $columns     = isset( $settings['grid_columns']['size'] ) ? (int) $settings['grid_columns']['size'] : 3;
        $columns     = max( 2, min( 6, $columns ) );
        $columns     = min( $columns, max( 1, $image_count ) );
        $grid_gap    = isset( $settings['grid_gap']['size'] ) ? (float) $settings['grid_gap']['size'] : 18;
        $mobile_cols = min( 2, max( 1, $image_count ) );
        $radius      = isset( $settings['proximity_radius']['size'] ) ? (float) $settings['proximity_radius']['size'] : 260;
        $tilt_max    = isset( $settings['proximity_tilt']['size'] ) ? (float) $settings['proximity_tilt']['size'] : 9;
        $scale_min   = isset( $settings['hover_autoscale_min']['size'] ) ? (float) $settings['hover_autoscale_min']['size'] : 72;
        $scale_min   = max( 40, min( 100, $scale_min ) ) / 100;
        $scale_ease  = isset( $settings['hover_autoscale_easing'] ) ? $settings['hover_autoscale_easing'] : 'ease';

        $ease_map = array(
            'linear'      => 'linear',
            'ease'        => 'ease',
            'ease-out'    => 'ease-out',
            'ease-in-out' => 'ease-in-out',
            'spring-soft' => 'cubic-bezier(0.2, 0.8, 0.2, 1.1)',
            'spring-hard' => 'cubic-bezier(0.16, 1, 0.3, 1.18)',
        );
        $scale_ease_css = isset( $ease_map[ $scale_ease ] ) ? $ease_map[ $scale_ease ] : 'ease';

        $card_height  = $photo_width + 52;
        $rows         = (int) ceil( $image_count / $columns );
        $grid_total_w = ( $columns * $photo_width ) + ( max( 0, $columns - 1 ) * $grid_gap );
        $grid_total_h = ( $rows * $card_height ) + ( max( 0, $rows - 1 ) * $grid_gap );
        $grid_safe_h  = $grid_total_h + 40;

        $wrapper_styles = sprintf(
            '--fotohog-height:%1$s%2$s;--fotohog-photo-width:%3$spx;--fotohog-grid-height:%4$spx;--fotohog-mobile-cols:%5$s;--fotohog-grid-cols:%6$s;--fotohog-grid-gap:%7$spx;--fotohog-autoscale-ease:%8$s;',
            esc_attr( $stack_height ),
            esc_attr( $stack_unit ),
            esc_attr( $photo_width ),
            esc_attr( round( $grid_safe_h, 2 ) ),
            esc_attr( $mobile_cols ),
            esc_attr( $columns ),
            esc_attr( round( $grid_gap, 2 ) ),
            esc_attr( $scale_ease_css )
        );

        $wrapper_classes = 'fotohog-stack';

        if ( 'stack_only' === $display_mode && $hover_lift ) {
            $wrapper_classes .= ' has-hover';
        }
        if ( 'stack_hover_grid' === $display_mode ) {
            $wrapper_classes .= ' grid-on-hover';
        }
        if ( 'grid_only' === $display_mode ) {
            $wrapper_classes .= ' always-grid';
        }
        if ( $show_caption && 'stack_only' !== $display_mode ) {
            $wrapper_classes .= ' has-grid-caption';
        }
        if ( 'dark' === $card_theme ) {
            $wrapper_classes .= ' theme-dark';
        }

        $tone_class_map = array(
            'normal_static'      => 'tone-normal',
            'grayscale_static'   => 'tone-gray-static',
            'grayscale_to_color' => 'tone-gray-to-color',
            'color_to_grayscale' => 'tone-color-to-gray',
        );
        $wrapper_classes .= ' ' . ( isset( $tone_class_map[ $tone_mode ] ) ? $tone_class_map[ $tone_mode ] : 'tone-normal' );

        if ( $proximity && 'stack_only' !== $display_mode ) {
            $wrapper_classes .= ' has-proximity-tilt';
        }
        if ( $hover_scale && 'stack_hover_grid' === $display_mode ) {
            $wrapper_classes .= ' has-hover-autoscale';
        }

        $animation_preset = isset( $settings['animation_preset'] ) ? $settings['animation_preset'] : 'soft';
        $allowed_presets  = array( 'soft', 'bouncy', 'instant' );
        if ( in_array( $animation_preset, $allowed_presets, true ) ) {
            $wrapper_classes .= ' anim-' . $animation_preset;
        } else {
            $wrapper_classes .= ' anim-soft';
        }

        $slideshow_id = 'fotohog-' . $this->get_id();

        $wrapper_attrs = '';
        if ( $proximity && 'stack_only' !== $display_mode ) {
            $wrapper_attrs .= ' data-proximity-radius="' . esc_attr( round( $radius, 2 ) ) . '"';
            $wrapper_attrs .= ' data-proximity-tilt="' . esc_attr( round( $tilt_max, 2 ) ) . '"';
        }
        if ( 'stack_hover_grid' === $display_mode ) {
            $wrapper_attrs .= ' data-autoscale-enabled="' . esc_attr( $hover_scale ? '1' : '0' ) . '"';
            $wrapper_attrs .= ' data-autoscale-min="' . esc_attr( round( $scale_min, 3 ) ) . '"';
        }

        echo '<div class="' . esc_attr( $wrapper_classes ) . '" style="' . esc_attr( $wrapper_styles ) . '"' . $wrapper_attrs . '>';

        foreach ( $items as $index => $item ) {
            $photo = ! empty( $item['photo'] ) ? $item['photo'] : array();
            $id    = ! empty( $photo['id'] ) ? (int) $photo['id'] : 0;

            if ( ! $id && empty( $photo['url'] ) ) {
                continue;
            }

            $x = ( $index % 5 ) - 2;
            $y = (int) floor( $index / 2 ) % 4;

            $translate_x = $x * ( $spread * 0.35 );
            $translate_y = ( $y - 1.5 ) * ( $spread * 0.22 );

            $seed  = ( $index * 37 ) % 11;
            $angle = ( ( $seed / 10 ) * 2 - 1 ) * $rotation;

            $grid_col = $index % $columns;
            $grid_row = (int) floor( $index / $columns );
            $grid_x   = ( -$grid_total_w / 2 ) + ( $grid_col * ( $photo_width + $grid_gap ) ) + ( $photo_width / 2 );
            $grid_y   = ( -$grid_total_h / 2 ) + ( $grid_row * ( $card_height + $grid_gap ) ) + ( $card_height / 2 );

            $card_style = sprintf(
                '--tx:%1$spx;--ty:%2$spx;--rot:%3$sdeg;--gx:%4$spx;--gy:%5$spx;z-index:%6$s;',
                esc_attr( round( $translate_x, 2 ) ),
                esc_attr( round( $translate_y, 2 ) ),
                esc_attr( round( $angle, 2 ) ),
                esc_attr( round( $grid_x, 2 ) ),
                esc_attr( round( $grid_y, 2 ) ),
                esc_attr( 50 + $index )
            );

            $image_html = '';
            $full_url   = '';

            if ( $id ) {
                $image_html = wp_get_attachment_image( $id, $settings['thumbnail_size'], false, array( 'class' => 'fotohog-image' ) );
                $full_url   = wp_get_attachment_image_url( $id, 'full' );
            } elseif ( ! empty( $photo['url'] ) ) {
                $image_html = '<img class="fotohog-image" src="' . esc_url( $photo['url'] ) . '" alt="" />';
                $full_url   = $photo['url'];
            }

            $caption = '';
            if ( $show_caption && 'stack_only' !== $display_mode ) {
                if ( ! empty( $item['caption'] ) ) {
                    $caption = $item['caption'];
                } elseif ( $id ) {
                    $caption = wp_get_attachment_caption( $id );
                    if ( ! $caption ) {
                        $caption = get_the_title( $id );
                    }
                }
            }

            $link_type = isset( $item['link_type'] ) ? $item['link_type'] : 'lightbox';

            echo '<figure class="fotohog-card" style="' . esc_attr( $card_style ) . '">';
            echo '<div class="fotohog-card-inner">';

            if ( 'url' === $link_type && ! empty( $item['link_url']['url'] ) ) {
                $rel = array();
                if ( ! empty( $item['link_url']['nofollow'] ) ) {
                    $rel[] = 'nofollow';
                }
                if ( ! empty( $item['link_url']['is_external'] ) ) {
                    $rel[] = 'noopener';
                    $rel[] = 'noreferrer';
                }

                echo '<a class="fotohog-link" href="' . esc_url( $item['link_url']['url'] ) . '"';
                if ( ! empty( $item['link_url']['is_external'] ) ) {
                    echo ' target="_blank"';
                }
                if ( ! empty( $rel ) ) {
                    echo ' rel="' . esc_attr( implode( ' ', array_unique( $rel ) ) ) . '"';
                }
                echo '>';
                echo $image_html;
                echo '</a>';
            } elseif ( 'lightbox' === $link_type && ! empty( $full_url ) ) {
                echo '<a class="fotohog-link" href="' . esc_url( $full_url ) . '" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . esc_attr( $slideshow_id ) . '">';
                echo $image_html;
                echo '</a>';
            } else {
                echo $image_html;
            }

            if ( $caption ) {
                echo '<figcaption class="fotohog-caption">' . esc_html( $caption ) . '</figcaption>';
            }

            echo '</div>';
            echo '</figure>';
        }

        echo '</div>';
    }
}
