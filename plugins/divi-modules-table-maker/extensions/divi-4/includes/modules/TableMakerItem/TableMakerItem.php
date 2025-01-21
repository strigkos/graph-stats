<?php


if (!defined('ABSPATH')) exit;


/**
 * Divi-Modules – Table Maker.
 *
 * @since 3.0.3
 *
 */
class DVMD_Table_Maker_Item_Module extends ET_Builder_Module {



    //--------------------------------------------------//
    //---------------------- Init ----------------------//
    //--------------------------------------------------//


    /**
     * Module properties initialization.
     *
     * @since   3.0.0
     * @access  public
     *
     * @return  void
     */
    function init() {


        // Configuration.
        $this->vb_support   = 'on';
        $this->type         = 'child';
        $this->slug         = 'dvmd_table_maker_item';
        $this->name         =  esc_html__('Column', 'dvmd-table-maker');
        $this->plural       =  esc_html__('Columns', 'dvmd-table-maker');


        // Item title properties.
        $this->settings_text                =  esc_html__('Column Settings', 'dvmd-table-maker');
        $this->child_title_var              = 'col_label';
        $this->child_title_fallback_var     = 'col_label';
        $this->advanced_setting_title_text  =  esc_html__('Column', 'dvmd-table-maker');


        // Credits.
        /*
        $this->module_credits       = array(
            'module_uri'            => 'https://divi-modules.com/products/table-maker',
            'author'                => 'Divi-Modules',
            'author_uri'            => 'https://divi-modules.com',
        );
        */


        // Custom wrapper.
        $this->wrapper_settings     = array(
            'parallax_background'   => '',
            'video_background'      => '',
            'attrs'                 => array('class' => 'dvmd_outer'),
            'inner_attrs'           => array('class' => 'dvmd_inner'),
        );



        //-----------------------------------------------------//
        //---------------------- Toggles ----------------------//
        //-----------------------------------------------------//


        // Toggles.
        $this->settings_modal_toggles       = array(

            // Content Tab. (general)
            'general'                       => array(
                'toggles'                   => array(
                    'col_content'           => esc_html__('Column Content',         'dvmd-table-maker'),
                    'col_width'             => esc_html__('Column Width',           'dvmd-table-maker'),
                    'col_icons'             => esc_html__('Column Icons',           'dvmd-table-maker'),
                    'col_buttons'           => esc_html__('Column Buttons',         'dvmd-table-maker'),
                    'col_images'            => esc_html__('Column Images',          'dvmd-table-maker'),
                ),
            ),

            // Design Tab. (advanced)
            'advanced'                      => array(
                'toggles'                   => array(
                    'col_tcell_text'        => esc_html__('Column Text',            'dvmd-table-maker'),
                    'col_tcell_cell'        => esc_html__('Column Cells',           'dvmd-table-maker'),
                    'col_chead_text'        => esc_html__('Column Header Text',     'dvmd-table-maker'),
                    'col_chead_cell'        => esc_html__('Column Header Cells',    'dvmd-table-maker'),
                    'col_cfoot_text'        => esc_html__('Column Footer Text',     'dvmd-table-maker'),
                    'col_cfoot_cell'        => esc_html__('Column Footer Cells',    'dvmd-table-maker'),
                    'col_rhead_text'        => esc_html__('Row Header Text',        'dvmd-table-maker'),
                    'col_rhead_cell'        => esc_html__('Row Header Cells',       'dvmd-table-maker'),
                    'col_rfoot_text'        => esc_html__('Row Footer Text',        'dvmd-table-maker'),
                    'col_rfoot_cell'        => esc_html__('Row Footer Cells',       'dvmd-table-maker'),
                ),
            ),

            /* Advance Tab. (custom_css)
            'custom_css'                    => array(
                'toggles'                   => array(
                    // Toggles go here.
                ),
            ),*/
        );



        //--------------------------------------------------------//
        //---------------------- Custom CSS ----------------------//
        //--------------------------------------------------------//


        $this->custom_css_fields = array(

            'col_tcell_cells'               => array(
                'label'                     => esc_html__('Column Cells', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell',
            ),
            'col_tcell_content'             => array(
                'label'                     => esc_html__('Column Content', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell .dvmd_tm_cdata',
            ),
            'col_chead_cells'               => array(
                'label'                     => esc_html__('Column Header Cells', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_chead',
            ),
            'col_chead_content'             => array(
                'label'                     => esc_html__('Column Header Content', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_chead .dvmd_tm_cdata',
            ),
            'col_cfoot_cells'               => array(
                'label'                     => esc_html__('Column Footer Cells', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_cfoot',
            ),
            'col_cfoot_content'             => array(
                'label'                     => esc_html__('Column Footer Content', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_cfoot .dvmd_tm_cdata',
            ),
            'col_rhead_cells'               => array(
                'label'                     => esc_html__('Row Header Cells', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rhead',
            ),
            'col_rhead_content'             => array(
                'label'                     => esc_html__('Row Header Content', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rhead .dvmd_tm_cdata',
            ),
            'col_rfoot_cells'               => array(
                'label'                     => esc_html__('Row Footer Cells', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rfoot',
            ),
            'col_rfoot_content'             => array(
                'label'                     => esc_html__('Row Footer Content', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rfoot .dvmd_tm_cdata',
            ),
            'col_icons'                     => array(
                'label'                     => esc_html__('Column Icons', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell .dvmd_tm_icon',
            ),
            'col_buttons'                   => array(
                'label'                     => esc_html__('Column Buttons', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell .dvmd_tm_button',
            ),
            'col_images'                    => array(
                'label'                     => esc_html__('Column Images', 'dvmd-table-maker'),
                'selector'                  => 'div%%order_class%%.dvmd_tm_tcell .dvmd_tm_image',
            ),
        );

    }



    //-------------------------------------------------------------//
    //---------------------- Advanced Fields ----------------------//
    //-------------------------------------------------------------//


    /**
     * Module advanced fields configuration.
     *
     * @since   3.0.3
     * @access  public
     *
     * @return  array  The module’s advanced fields.
     */
    function get_advanced_fields_config() {


        // Fields.
        $f = array();
        $f['background']         = false;
        $f['link_options']       = false;
        $f['text']               = false;
        $f['max_width']          = false;
        $f['height']             = false;
        $f['margin_padding']     = false;
        $f['filters']            = true;
        $f['scroll_effects']     = false;
        $f['sticky']             = false;
        $f['display_conditions'] = false;
        $f['position_fields']    = false;
        $f['z_index']            = false;
        // $f['button']             = false;
        // $f['borders']            = false;
        // $f['box_shadow']         = false;


        //----------------------------------------------------//
        //---------------------- Button ----------------------//
        //----------------------------------------------------//


        // Table.
        $f['button']['button']          =   array(

            'label'                     =>  esc_html__('Buttons', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'general',
            'toggle_slug'               => 'col_buttons',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%% .dvmd_tm_button',
                'limited_main'          => 'div%%order_class%% .dvmd_tm_button',
            ),
            'box_shadow'                =>  array(
                'css'                   =>  array(
                    'main'              => 'div%%order_class%% .dvmd_tm_button',
                ),
            ),
            'margin_padding'            =>  false,
            'use_alignment'             =>  false,
        );



        //----------------------------------------------------//
        //---------------------- Fonts ----------------------//
        //----------------------------------------------------//


        // Table.
        $f['fonts']['col_tcell_text']   =   array(

            'label'                     =>  esc_html__('Col:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_tcell_text',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell .dvmd_tm_cdata',
            ),
            'hide_text_align'           =>  true,
            'font_size'                 =>  array(
                'default'               => '',
            ),
            'text_color'                =>  array(
                'default'               => '',
            ),
        );


        // Column Header.
        $f['fonts']['col_chead_text']   =   array(

            'label'                     =>  esc_html__('CH:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_chead_text',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_chead .dvmd_tm_cdata',
            ),
            'hide_text_align'           =>  true,
            'font_size'                 =>  array(
                'default'               => '',
            ),
            'text_color'                =>  array(
                'default'               => '',
            ),
        );


        // Column Footer.
        $f['fonts']['col_cfoot_text']   =   array(

            'label'                     =>  esc_html__('CF:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_cfoot_text',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_cfoot .dvmd_tm_cdata',
            ),
            'hide_text_align'           =>  true,
            'font_size'                 =>  array(
                'default'               => '',
            ),
            'text_color'                =>  array(
                'default'               => '',
            ),
        );


        // Row Header.
        $f['fonts']['col_rhead_text']   =   array(

            'label'                     =>  esc_html__('RH:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_rhead_text',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rhead .dvmd_tm_cdata',
            ),
            'hide_text_align'           =>  true,
            'font_size'                 =>  array(
                'default'               => '',
            ),
            'text_color'                =>  array(
                'default'               => '',
            ),
        );



        // Row Footer.
        $f['fonts']['col_rfoot_text']   =   array(

            'label'                     =>  esc_html__('RF:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_rfoot_text',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rfoot .dvmd_tm_cdata',
            ),
            'hide_text_align'           =>  true,
            'font_size'                 =>  array(
                'default'               => '',
            ),
            'text_color'                =>  array(
                'default'               => '',
            ),
        );



        //-----------------------------------------------------//
        //---------------------- Borders ----------------------//
        //-----------------------------------------------------//


        // Table.
        $f['borders']['col_tcell_cell_border'] = array(

            'label_prefix'              =>  esc_html__('Col:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_tcell_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => 'div%%order_class%%.dvmd_tm_tcell',
                    'border_styles'     => 'div%%order_class%%.dvmd_tm_tcell',
                ),
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
            'defaults'                  =>  array(
                'border_radii'          => 'on||||',
                'border_styles'         =>  array(
                    'width'             => '1px',
                    'style'             => 'none',
                ),
            ),
        );

        // Column Header.
        $f['borders']['col_chead_cell_border'] = array(

            'label_prefix'              =>  esc_html__('CH:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_chead_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_chead',
                    'border_styles'     => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_chead',
                ),
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
            'defaults'                  =>  array(
                'border_radii'          => 'on||||',
                'border_styles'         =>  array(
                    'width'             => '1px',
                    'style'             => 'none',
                ),
            ),
        );


        // Column Footer.
        $f['borders']['col_cfoot_cell_border'] = array(

            'label_prefix'              =>  esc_html__('CF:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_cfoot_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_cfoot',
                    'border_styles'     => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_cfoot',
                ),
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
            'defaults'                  =>  array(
                'border_radii'          => 'on||||',
                'border_styles'         =>  array(
                    'width'             => '1px',
                    'style'             => 'none',
                ),
            ),
        );


        // Row Header.
        $f['borders']['col_rhead_cell_border'] = array(

            'label_prefix'              =>  esc_html__('RH:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_rhead_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rhead',
                    'border_styles'     => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rhead',
                ),
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
            'defaults'                  =>  array(
                'border_radii'          => 'on||||',
                'border_styles'         =>  array(
                    'width'             => '1px',
                    'style'             => 'none',
                ),
            ),
        );


        // Row Footer.
        $f['borders']['col_rfoot_cell_border'] = array(

            'label_prefix'              =>  esc_html__('RF:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_rfoot_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rfoot',
                    'border_styles'     => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rfoot',
                ),
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
            'defaults'                  =>  array(
                'border_radii'          => 'on||||',
                'border_styles'         =>  array(
                    'width'             => '1px',
                    'style'             => 'none',
                ),
            ),
        );



        //---------------------------------------------------------//
        //---------------------- Box Shadows ----------------------//
        //---------------------------------------------------------//


        // Table.
        $f['box_shadow']['col_tcell_cell_shadow'] = array(

            'label'                     =>  esc_html__('Col: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_tcell_cell',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Column Header.
        $f['box_shadow']['col_chead_cell_shadow'] = array(

            'label'                     =>  esc_html__('CH: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_chead_cell',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_chead',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Column Footer.
        $f['box_shadow']['col_cfoot_cell_shadow'] = array(

            'label'                     =>  esc_html__('CF: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_cfoot_cell',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_cfoot',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Row Header.
        $f['box_shadow']['col_rhead_cell_shadow'] = array(

            'label'                     =>  esc_html__('RH: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_rhead_cell',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rhead',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Row Footer.
        $f['box_shadow']['col_rfoot_cell_shadow'] = array(

            'label'                     =>  esc_html__('RF: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'col_rfoot_cell',
            'css'                       =>  array(
                'main'                  => 'div%%order_class%%.dvmd_tm_tcell.dvmd_tm_rfoot',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Return
        return $f;
    }



    //--------------------------------------------------------//
    //---------------------- Get Fields ----------------------//
    //--------------------------------------------------------//


    /**
     * Module custom fields configuration.
     *
     * @since   3.0.0
     * @access  public
     *
     * @return  array  The module’s custom fields.
     */
    function get_fields() {



        //-----------------------------------------------------//
        //---------------------- Content ----------------------//
        //-----------------------------------------------------//


        $description = esc_html__(
            'Here you can set a label for the column in the builder. The label will not be shown on the front end.', 'dvmd-table-maker');

        $f['col_label']                     =   array(
            'label'                         =>  esc_html__('Col: Label', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_content',
            'type'                          => 'text',
            'default'                       => 'Column',
        );

        $description = esc_html__(
            'Here you can enter content for this column’s rows. Rows are numbered so that one row in this editor equals one row in the table. Content can included HTML, which means characters such as &quot; &apos; &amp; &lt; &gt; need to be escaped to avoid errors. Here, individual row cells can be styled and icons, buttons and images added using tags which are unique to this module. See documentation for details.', 'dvmd-table-maker');

        $default = esc_html__(
            'Here you can enter content for this column’s rows. Rows are numbered so that one row in this editor equals one row in the table.', 'dvmd-table-maker');

        $f['col_content']                   =   array(
            'label'                         =>  esc_html__('Col: Rows', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_content',
            'type'                          => 'codemirror',
            'mode'                          => 'html',
            'default'                       =>  $default,
            'default_on_front'              => '',
        );



        //-----------------------------------------------------//
        //---------------------- Max-Min ----------------------//
        //-----------------------------------------------------//


        $description = esc_html__(
            "Here you can set a maximum width for this column. For flexible-width columns, it’s recommended to use fraction (fr) units. This can also be set to 'auto'. For fixed-width columns, it’s recommended to use pixel (px) units. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Columns</b> toggle.", 'dvmd-table-maker');

        $f['col_column_max_width']          =   array(
            'label'                         =>  esc_html__('Col: Max Width', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_width',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allowed_units'                 =>  array('fr','%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'allowed_values'                =>  array('default'),
            'allow_empty'                   =>  true,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'fr',
            'default'                       => 'default',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '5',
                'step'                      => '.1',
            ),
        );

        $description = esc_html__(
            'Here you can set a minimum width for this column. It’s recommended to use pixel (px) units. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Columns</b> toggle.', 'dvmd-table-maker');

        $f['col_column_min_width']          =   array(
            'label'                         =>  esc_html__('Col: Min Width', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_width',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'allowed_values'                =>  array('default'),
            'allow_empty'                   =>  true,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'px',
            'default'                       => 'default',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '300',
                'step'                      => '1',
            ),
        );



        //--------------------------------------------------//
        //---------------------- Icon ----------------------//
        //--------------------------------------------------//


        $description = esc_html__(
            'Here you can select this column’s default icon. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Icons</b> toggle.', 'dvmd-table-maker');

        $f['col_icon_type']                 =   array(
            'label'                         =>  esc_html__('Icon: Default', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_icons',
            'type'                          => 'select_icon',
            'default'                       => '',
        );

        $description = esc_html__(
            'Here you can set this column’s default icon size. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Icons</b> toggle.', 'dvmd-table-maker');

        $f['col_icon_size']                 =   array(
            'label'                         =>  esc_html__('Icon: Size', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_icons',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'range_settings'                =>  array(
                'min'                       => '1',
                'max'                       => '120',
                'step'                      => '1',
            ),
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'default_unit'                  => 'em',
        );

        $description = esc_html__(
            'Here you can set this column’s default icon color. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Icons</b> toggle.', 'dvmd-table-maker');

        $f['col_icon_color']                =   array(
            'label'                         =>  esc_html__('Icon: Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_icons',
            'type'                          => 'color-alpha',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
        );



        //----------------------------------------------------//
        //---------------------- Button ----------------------//
        //----------------------------------------------------//


        $description = esc_html__(
            'Here you can set this column’s default button text. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Buttons</b> toggle.', 'dvmd-table-maker');

        $f['col_button_text']               =   array(
            'label'                         =>  esc_html__('Button: Text', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_buttons',
            'type'                          => 'text',
            'default'                       => 'Default',
        );

        $description = esc_html__(
            'Here you can set this column’s default button url. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Buttons</b> toggle.', 'dvmd-table-maker');

        $f['col_button_url']                =   array(
            'label'                         =>  esc_html__('Button: URL', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_buttons',
            'type'                          => 'text',
            'default'                       => '#',
            'dynamic_content'               => 'url',
        );

        $description = esc_html__(
            'Here you can set this column’s default button target. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Buttons</b> toggle.', 'dvmd-table-maker');

        $f['col_button_target']             =   array(
            'label'                         =>  esc_html__('Button: Target', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_buttons',
            'type'                          => 'select',
            'default'                       => 'default',
            'options'                       =>  array(
                'default'                   =>  esc_html__('Default', 'dvmd-table-maker'),
                '_self'                     =>  esc_html__('In The Same Window', 'et_builder'),
                '_blank'                    =>  esc_html__('In The New Tab', 'et_builder'),
            ),
        );

        $description = esc_html__(
            'Here you can set this column’s default button width. If set to Text Width, buttons will be as wide as their text. If set to Cell Width, buttons will stretch to fill their containing cell. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Buttons</b> toggle.', 'dvmd-table-maker');

        $f['col_button_width']              =   array(
            'label'                         =>  esc_html__('Button: Width', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_buttons',
            'type'                          => 'multiple_buttons',
            'default'                       => '',
            'options'                       =>  array(
                'inline-block'              =>  array(
                    'title'                 =>  esc_html__('Text Width', 'dvmd-table-maker'),
                ),
                'block'                     =>  array(
                    'title'                 =>  esc_html__('Cell Width', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  true,
            'multi_selection'               =>  false,
        );



        //---------------------------------------------------//
        //---------------------- Image ----------------------//
        //---------------------------------------------------//


        $description = esc_html__(
            'Here you can set this column’s default image proportion. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Images</b> toggle.', 'dvmd-table-maker');

        $f['col_image_proportion']          =   array(
            'label'                         =>  esc_html__('Image: Proportion', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_images',
            'type'                          => 'select',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'default'                       => '',
            'options'                       =>  array(
                ''                          =>  esc_html__('Default', 'dvmd-table-maker'),
                '300%'                      =>  esc_html__('1:3 – Portrait', 'dvmd-table-maker'),
                '200%'                      =>  esc_html__('1:2 – Portrait', 'dvmd-table-maker'),
                '150%'                      =>  esc_html__('2:3 – Portrait', 'dvmd-table-maker'),
                '133.3%'                    =>  esc_html__('3:4 – Portrait', 'dvmd-table-maker'),
                '125%'                      =>  esc_html__('4:5 – Portrait', 'dvmd-table-maker'),
                '100%'                      =>  esc_html__('1:1 – Square', 'dvmd-table-maker'),
                '80%'                       =>  esc_html__('5:4 – Landscape', 'dvmd-table-maker'),
                '75%'                       =>  esc_html__('4:3 – Landscape', 'dvmd-table-maker'),
                '66.7%'                     =>  esc_html__('3:2 – Landscape', 'dvmd-table-maker'),
                '50%'                       =>  esc_html__('2:1 – Landscape', 'dvmd-table-maker'),
                '33.3%'                     =>  esc_html__('3:1 – Landscape', 'dvmd-table-maker'),
            ),
        );

        $description = esc_html__(
            'Here you can choose how this column’s images are scaled. If set to Fit, images are scaled to fit their containing cell without cropping. If set to Fill, images are scaled to fill or cover their containing cell – this may result in some cropping. If set to Size, you can enter a custom size for the column images. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Images</b> toggle.', 'dvmd-table-maker');

        $f['col_image_scale']               =   array(
            'label'                         =>  esc_html__('Image: Scale', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_images',
            'type'                          => 'multiple_buttons',
            'default'                       => '',
            'options'                       =>  array(
                'contain'                   =>  array(
                    'title'                 =>  esc_html__('Fit', 'dvmd-table-maker'),
                ),
                'cover'                     =>  array(
                    'title'                 =>  esc_html__('Fill', 'dvmd-table-maker'),
                ),
                'size'                      =>  array(
                    'title'                 =>  esc_html__('Size', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  true,
            'multi_selection'               =>  false,
        );

        $description = esc_html__(
            'Here you can set a custom size for this column’s images. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Images</b> toggle.', 'dvmd-table-maker');

        $f['col_image_size']                =   array(
            'label'                         =>  esc_html__('Image: Size', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_images',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'default_unit'                  => '%',
            'default'                       => '',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '300',
                'step'                      => '1',
            ),
            'show_if'                       =>  array(
                'col_image_scale'           => 'size',
            ),
        );

        $description = esc_html__(
            'Here you can set this column’s default horizontal image alignment. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Images</b> toggle.', 'dvmd-table-maker');

        $f['col_image_align_horz']          =   array(
            'label'                         =>  esc_html__('Image: Position X', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_images',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'fixed_unit'                    => '%',
            'default_unit'                  => '%',
            'default'                       => '',
            'fixed_range'                   =>  true,
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '100',
                'step'                      => '1',
            ),
        );

        $description = esc_html__(
            'Here you can set this column’s default vertical image alignment. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Content > Table Images</b> toggle.', 'dvmd-table-maker');

        $f['col_image_align_vert']          =   array(
            'label'                         =>  esc_html__('Image: Position Y', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'col_images',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'fixed_unit'                    => '%',
            'default_unit'                  => '%',
            'default'                       => '',
            'fixed_range'                   =>  true,
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '100',
                'step'                      => '1',
            ),
        );



        //----------------------------------------------------------//
        //---------------------- Cell – Table ----------------------//
        //----------------------------------------------------------//


        $description = esc_html__(
            'Here you can set this column’s cell background color. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Table Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_tcell_cell_color']          =   array(
            'label'                         =>  esc_html__('Col: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_tcell_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              => '',
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of this column’s cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Table Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_tcell_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('Col: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_tcell_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of this column’s cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Table Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_tcell_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('Col: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_tcell_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set this column’s cell padding. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Table Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_tcell_cell_padding']        =   array(
            'label'                         =>  esc_html__('Col: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_tcell_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
        );



        //------------------------------------------------------------------//
        //---------------------- Cell – Column Header ----------------------//
        //------------------------------------------------------------------//


        $description = esc_html__(
            'Here you can set this column’s column header cell background color. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_chead_cell_color']          =   array(
            'label'                         =>  esc_html__('CH: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_chead_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              => '',
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of this column’s column header cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_chead_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('CH: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_chead_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of this column’s column header cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_chead_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('CH: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_chead_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set this column’s column header cell padding. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_chead_cell_padding']        =   array(
            'label'                         =>  esc_html__('CH: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_chead_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
        );



        //------------------------------------------------------------------//
        //---------------------- Cell – Column Footer ----------------------//
        //------------------------------------------------------------------//


        $description = esc_html__(
            'Here you can set this column’s column footer cell background color. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_cfoot_cell_color']          =   array(
            'label'                         =>  esc_html__('CF: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_cfoot_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              => '',
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of this column’s column footer cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_cfoot_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('CF: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_cfoot_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of this column’s column footer cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_cfoot_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('CF: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_cfoot_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set this column’s column footer cell padding. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_cfoot_cell_padding']        =   array(
            'label'                         =>  esc_html__('CF: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_cfoot_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
        );



        //---------------------------------------------------------------//
        //---------------------- Cell – Row Header ----------------------//
        //---------------------------------------------------------------//


        $description = esc_html__(
            'Here you can set this column’s row header cell background color. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_rhead_cell_color']          =   array(
            'label'                         =>  esc_html__('RH: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rhead_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              => '',
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of this column’s row header cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_rhead_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('RH: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rhead_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of this column’s row header cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_rhead_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('RH: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rhead_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set this column’s row header cell padding. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_rhead_cell_padding']        =   array(
            'label'                         =>  esc_html__('RH: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rhead_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
        );



        //---------------------------------------------------------------//
        //---------------------- Cell – Row Header ----------------------//
        //---------------------------------------------------------------//


        $description = esc_html__(
            'Here you can set this column’s row footer cell background color. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_rfoot_cell_color']          =   array(
            'label'                         =>  esc_html__('RF: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rfoot_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              => '',
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of this column’s row footer cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_rfoot_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('RF: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rfoot_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of this column’s row footer cell content. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_rfoot_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('RF: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rfoot_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set this column’s row footer cell padding. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['col_rfoot_cell_padding']        =   array(
            'label'                         =>  esc_html__('RF: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rfoot_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
        );



        //--------------------------------------------------//
        //---------------------- Text ----------------------//
        //--------------------------------------------------//


        $description = esc_html__(
            'Here you can choose whether to allow this column’s text to wrap to multiple lines. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Table Text</b> toggle.', 'dvmd-table-maker');

        $f['col_tcell_text_wrap']           =   array(
            'label'                         =>  esc_html__('Col: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_tcell_text',
            'type'                          => 'multiple_buttons',
            'default'                       => '',
            'options'                       =>  array(
                'normal'                    =>  array(
                    'title'                 =>  esc_html__('Wrap', 'dvmd-table-maker'),
                ),
                'nowrap'                    =>  array(
                    'title'                 =>  esc_html__('No Wrap', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  true,
            'multi_selection'               =>  false,
        );

        $description = esc_html__(
            'Here you can choose whether to allow this column’s column header text to wrap to multiple lines. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Header Text</b> toggle.', 'dvmd-table-maker');

        $f['col_chead_text_wrap']           =   array(
            'label'                         =>  esc_html__('CH: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_chead_text',
            'type'                          => 'multiple_buttons',
            'default'                       => '',
            'options'                       =>  array(
                'normal'                    =>  array(
                    'title'                 =>  esc_html__('Wrap', 'dvmd-table-maker'),
                ),
                'nowrap'                    =>  array(
                    'title'                 =>  esc_html__('No Wrap', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  true,
            'multi_selection'               =>  false,
        );

        $description = esc_html__(
            'Here you can choose whether to allow this column’s column footer text to wrap to multiple lines. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Column Footer Text</b> toggle.', 'dvmd-table-maker');

        $f['col_cfoot_text_wrap']           =   array(
            'label'                         =>  esc_html__('CF: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_cfoot_text',
            'type'                          => 'multiple_buttons',
            'default'                       => '',
            'options'                       =>  array(
                'normal'                    =>  array(
                    'title'                 =>  esc_html__('Wrap', 'dvmd-table-maker'),
                ),
                'nowrap'                    =>  array(
                    'title'                 =>  esc_html__('No Wrap', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  true,
            'multi_selection'               =>  false,
        );

       $description = esc_html__(
            'Here you can choose whether to allow this column’s row header text to wrap to multiple lines. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Header Text</b> toggle.', 'dvmd-table-maker');

        $f['col_rhead_text_wrap']           =   array(
            'label'                         =>  esc_html__('RH: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rhead_text',
            'type'                          => 'multiple_buttons',
            'default'                       => '',
            'options'                       =>  array(
                'normal'                    =>  array(
                    'title'                 =>  esc_html__('Wrap', 'dvmd-table-maker'),
                ),
                'nowrap'                    =>  array(
                    'title'                 =>  esc_html__('No Wrap', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  true,
            'multi_selection'               =>  false,
        );

        $description = esc_html__(
            'Here you can choose whether to allow this column’s row footer text to wrap to multiple lines. This setting can be adjusted for the whole table under the <b>Table Maker Settings > Design > Row Footer Text</b> toggle.', 'dvmd-table-maker');

        $f['col_rfoot_text_wrap']           =   array(
            'label'                         =>  esc_html__('RF: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'col_rfoot_text',
            'type'                          => 'multiple_buttons',
            'default'                       => '',
            'options'                       =>  array(
                'normal'                    =>  array(
                    'title'                 =>  esc_html__('Wrap', 'dvmd-table-maker'),
                ),
                'nowrap'                    =>  array(
                    'title'                 =>  esc_html__('No Wrap', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  true,
            'multi_selection'               =>  false,
        );



        // Return
        return $f;
    }



    //----------------------------------------------------------//
    //---------------------- Render: Main ----------------------//
    //----------------------------------------------------------//


    /**
     * Render module output.
     *
     * @since   3.0.1
     * @access  public
     *
     * @param   array   $attrs        The list of unprocessed attributes.
     * @param   string  $content      The content being processed.
     * @param   string  $render_slug  The Slug of module that's used for rendering.
     *
     * @return  string  The module's rendered output.
     */
    function render($attrs, $content, $render_slug) {


        // Properties.
        $pr = $this->props;



        //---------------------------------------------------------//
        //---------------------- Transitions ----------------------//
        //---------------------------------------------------------//

        /**
         * We could use the get_transition_fields_css_props() function,
         * but considering the repetition of Table Maker fields, in many
         * ways, its just easier to enable transitions like this.
         */

        // Transition settings.
        /*
        $duration = isset($pr['hover_transition_duration']) ? $pr['hover_transition_duration'] : '300ms';
        $duration = sprintf('transition-duration: %s;', $duration);
        $delay    = isset($pr['hover_transition_delay']) ? $pr['hover_transition_delay'] : '0ms';
        $delay    = sprintf('transition-delay: %s;', $delay);
        $timing   = isset($pr['hover_transition_speed_curve']) ? $pr['hover_transition_speed_curve'] : 'ease';
        $timing   = sprintf('transition-timing-function: %s;', $timing);

        // Cells.
        $selector = 'div%%order_class%%.dvmd_tm_tcell';
        $property = 'transition-property: background, border-radius, border-color, border-width;';
        $this->dvmd_tm_set_style_1($selector, $property);
        $this->dvmd_tm_set_style_1($selector, $duration);
        $this->dvmd_tm_set_style_1($selector, $delay);
        $this->dvmd_tm_set_style_1($selector, $timing);

        // Icons.
        $selector = 'div%%order_class%% .dvmd_tm_icon';
        $property = 'transition-property: color, font-size;';
        $this->dvmd_tm_set_style_1($selector, $property);
        $this->dvmd_tm_set_style_1($selector, $duration);
        $this->dvmd_tm_set_style_1($selector, $delay);
        $this->dvmd_tm_set_style_1($selector, $timing);

        // Images.
        $selector = 'div%%order_class%% .dvmd_tm_image';
        $property = 'transition-property: background-size;';
        //$property = 'transition-property: padding-top, background-size, background-position-x, background-position-y;';
        $this->dvmd_tm_set_style_1($selector, $property);
        $this->dvmd_tm_set_style_1($selector, $duration);
        $this->dvmd_tm_set_style_1($selector, $delay);
        $this->dvmd_tm_set_style_1($selector, $timing);
        */


        //------------------------------------------------------------------//
        //---------------------- Column Text & Cell ------------------------//
        //------------------------------------------------------------------//


        foreach (['tcell'=>'30','rhead'=>'31','chead'=>'32','rfoot'=>'33','cfoot'=>'34'] as $type => $order) {

            // Selector.
            $s = ('tcell' === $type) ? 'div%%order_class%%.dvmd_tm_tcell' : "div%%order_class%%.dvmd_tm_tcell.dvmd_tm_{$type}";

            // Text wrap.
            $v = $pr["col_{$type}_text_wrap"];
            if ($v) $this->dvmd_tm_set_style_2("{$s} .dvmd_tm_cdata", 'white-space', esc_html($v), null, $order);

            // Color.
            $v = $pr["col_{$type}_cell_color"];
            if ($v) $this->dvmd_tm_set_style_2($s, 'background', esc_html($v), null, $order);
            $this->dvmd_tm_set_hover_1($s, 'background', "col_{$type}_cell_color");

            // Horizontal alignment.
            $v = $pr["col_{$type}_cell_align_horz"];
            if ($v) $this->dvmd_tm_set_style_2($s, 'text-align', esc_html($v), null, $order);

            // Vertical alignment.
            $v = $pr["col_{$type}_cell_align_vert"];
            if ($v) {
                if ('left' === $v) { $v = 'flex-start'; }
                elseif ('right' === $v) { $v = 'flex-end'; }
                $this->dvmd_tm_set_style_2($s, 'justify-content', esc_html($v), null, $order);
            }

            // Padding.
            $this->dvmd_tm_set_custom_spacing_1($s, "col_{$type}_cell_padding", 'padding', $order);
        }



        //---------------------------------------------------------//
        //---------------------- Column Icon ----------------------//
        //---------------------------------------------------------//


        if (strpos($pr['col_content'], '<icon') !== false) {

            // Icon.
            if ($pr['col_icon_type']) {

                // Icon value.
                $i = $pr['col_icon_type'];
                $s = "div%%order_class%% .dvmd_tm_icon.ei-default:before";

                // Pre 4.13.
                if (false === strpos($i, '||')) {

                    // Icon value.
                    $i = html_entity_decode(esc_attr(et_pb_process_font_icon($i)));
                    $this->dvmd_tm_set_style_2($s, 'content', "'{$i}'", null, '30');
                }

                // Post 4.13.
                elseif (function_exists('et_pb_get_extended_font_icon_value')) {

                    // Icon value.
                    $i = esc_attr(et_pb_get_extended_font_icon_value($i, true));
                    $this->dvmd_tm_set_style_2($s, 'content', "'{$i}'", null, '30');

                    // Icon styles.
                    $this->generate_styles(array(
                        'utility_arg'    => 'icon_font_family',
                        'render_slug'    =>  $this->slug,
                        'base_attr_name' => 'col_icon_type',
                        'selector'       =>  $s,
                        'important'      =>  true,
                        'processor'      =>  array(
                            'ET_Builder_Module_Helper_Style_Processor',
                            'process_extended_icon',
                        ),
                    ));
                }
            }

            // Size.
            $this->dvmd_tm_set_responsive_1(
                'div%%order_class%% .dvmd_tm_icon', 'font-size', 'col_icon_size', 'range', '30');
            $this->dvmd_tm_set_hover_1(
                'div%%order_class%% .dvmd_tm_icon', 'font-size', 'col_icon_size', null, '30');

            // Color.
            $this->dvmd_tm_set_responsive_1(
                'div%%order_class%% .dvmd_tm_icon', 'color', 'col_icon_color', 'color', '30');
            $this->dvmd_tm_set_hover_1(
                'div%%order_class%% .dvmd_tm_icon', 'color', 'col_icon_color', null, '30');
        }



        //-----------------------------------------------------------//
        //---------------------- Column Button ----------------------//
        //-----------------------------------------------------------//


        if (strpos($pr['col_content'], '<button') !== false) {

            // Width.
            if ($pr['col_button_width']) $this->dvmd_tm_set_style_2(
                'div%%order_class%% .dvmd_tm_button', 'display', esc_html($pr['col_button_width']), null, '30');
        }



        //----------------------------------------------------------//
        //---------------------- Column Image ----------------------//
        //----------------------------------------------------------//


        if (strpos($pr['col_content'], '<image') !== false) {

            // Proportion.
            $this->dvmd_tm_set_responsive_1(
                'div%%order_class%% .dvmd_tm_image', 'padding-top', 'col_image_proportion', 'custom', '30');
            $this->dvmd_tm_set_hover_1(
                'div%%order_class%% .dvmd_tm_image', 'padding-top', 'col_image_proportion', null, '30');

            // Size.
            if ('size' !== $pr['col_image_scale']) {
                if ($pr['col_image_scale']) $this->dvmd_tm_set_style_2(
                    'div%%order_class%% .dvmd_tm_image', 'background-size', esc_html($pr['col_image_scale']), null, '30');
            } else {
                $this->dvmd_tm_set_responsive_1(
                    'div%%order_class%% .dvmd_tm_image', 'background-size', 'col_image_size', 'range', '30');
                $this->dvmd_tm_set_hover_1(
                    'div%%order_class%% .dvmd_tm_image', 'background-size', 'col_image_size', null, '30');
            }

            // Alignment.
            $this->dvmd_tm_set_responsive_1(
                'div%%order_class%% .dvmd_tm_image', 'background-position-x', 'col_image_align_horz', 'custom', '30');
            $this->dvmd_tm_set_hover_1(
                'div%%order_class%% .dvmd_tm_image', 'background-position-x', 'col_image_align_horz', null, '30');
            $this->dvmd_tm_set_responsive_1(
                'div%%order_class%% .dvmd_tm_image', 'background-position-y', 'col_image_align_vert', 'custom', '30');
            $this->dvmd_tm_set_hover_1(
                'div%%order_class%% .dvmd_tm_image', 'background-position-y', 'col_image_align_vert', null, '30');
        }


        //-----------------------------------------------------------//
        //---------------------- Column Button ----------------------//
        //-----------------------------------------------------------//


        // Render.
        $b = '';
        if ('on' === $pr['custom_button']) {
            $i = $this->dvmd_tm_get_responsive_1('button_icon');
            $b = $this->render_button(array(
                'has_wrapper'         =>  false,
                'button_classname'    =>  array('dvmd_tm_button'),
                'button_custom'       =>  $pr['custom_button'],
                'button_rel'          =>  $pr['button_rel'],
                'button_text'         => 'Default',
                'button_text_escaped' =>  true,
                'button_url'          => '#',
                'url_new_window'      => 'off',
                'custom_icon'         =>  $i['desktop'],
                'custom_icon_tablet'  =>  $i['tablet'],
                'custom_icon_phone'   =>  $i['phone'],
            ));
        }

        // Properties.
        $button['button'] = $b;
        $button['text']   = $pr['col_button_text'];
        $button['url']    = $pr['col_button_url'];
        $button['target'] = $pr['col_button_target'];

        global $dvmd_tm_column_buttons;
        $dvmd_tm_column_buttons[] = $button;



        //---------------------------------------------------------//
        //---------------------- Column Rows ----------------------//
        //---------------------------------------------------------//


        // Prepare rows.
        $rows = $pr['col_content'];
        // $rows = str_replace(array("\n<p>","</p>\n"), "", $rows);
        // $rows = str_replace("{{dvmd_newline}}", "\n", $rows);

        // Escape characters.
        $find    = array('{{lt}}', '{{gt}}', '{{amp}}', '{{quot}}', '{{apos}}');
        $replace = array('&lt;', '&gt;', '&amp;', '&quot;', '&apos;');
        $rows    = str_replace($find, $replace, $rows);

        // Explode rows.
        $rows = explode("\n", $rows);

        global $dvmd_tm_column_rows;
        $dvmd_tm_column_rows[] = $rows;



        //---------------------------------------------------------------//
        //---------------------- Column Attributes ----------------------//
        //---------------------------------------------------------------//


        // Class
        $class = ET_Builder_Element::get_module_order_class($render_slug);

        // Min/max.
        $mins = $this->dvmd_tm_get_responsive_1('col_column_min_width');
        $maxs = $this->dvmd_tm_get_responsive_1('col_column_max_width');

        // Defaults.
        if ('default' === $mins['desktop']) $mins['desktop'] = '';
        if ('default' === $mins['tablet'])  $mins['tablet']  = '';
        if ('default' === $mins['phone'])   $mins['phone']   = '';
        if ('default' === $maxs['desktop']) $maxs['desktop'] = '';
        if ('default' === $maxs['tablet'])  $maxs['tablet']  = '';
        if ('default' === $maxs['phone'])   $maxs['phone']   = '';

        // Attributes.
        $atts['class'] = $class;
        $atts['mins']  = $mins;
        $atts['maxs']  = $maxs;
        $atts['count'] = count($rows);

        global $dvmd_tm_column_atts;
        $dvmd_tm_column_atts[] = $atts;



        //-----------------------------------------------------------//
        //---------------------- Column Colors ----------------------//
        //-----------------------------------------------------------//


        // Colors.
        $colors['class'] = "%%order_class%% .{$class}";
        $colors['tdata'] = $pr['col_tcell_cell_color'];
        $colors['rhead'] = $pr['col_rhead_cell_color'];
        $colors['chead'] = $pr['col_chead_cell_color'];
        $colors['rfoot'] = $pr['col_rfoot_cell_color'];
        $colors['cfoot'] = $pr['col_cfoot_cell_color'];

        global $dvmd_tm_column_colors;
        $dvmd_tm_column_colors[] = $colors;

    }



    //------------------------------------------------------------//
    //---------------------- Helper: Styles ----------------------//
    //------------------------------------------------------------//


    /**
     * Sets a style by declaration.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string   $s   CSS selector.
     * @param   string   $d   CSS declaration.
     * @param   string   $q   Media query.
     * @param   integer  $o   Priority.
     *
     * @return  void
     */
    private function dvmd_tm_set_style_1($s, $d, $q=null, $o=10) {
        ET_Builder_Element::set_style($this->slug, array(
            'selector'    => $s,
            'declaration' => $d,
            'media_query' => $q,
            'priority'    => $o,
        ));
    }


    /**
     * Sets a style by property and value.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string   $s   CSS selector.
     * @param   string   $p   CSS property.
     * @param   string   $v   CSS value.
     * @param   string   $q   Media query.
     * @param   integer  $o   Priority.
     *
     * @return  void
     */
    private function dvmd_tm_set_style_2($s, $p, $v, $q=null, $o=10) {
        ET_Builder_Element::set_style($this->slug, array(
            'selector'    =>  $s,
            'declaration' => "{$p}:{$v};",
            'media_query' =>  $q,
            'priority'    =>  $o,
        ));
    }


    /**
     * Gets responsive styles for a field.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $f  Field name.
     * @param   mixed   $d  Default value.
     *
     * @return  array
     */
    private function dvmd_tm_get_responsive_1($f, $d='') {
        return et_pb_responsive_options()->get_property_values($this->props, $f, $d);
    }


    /**
     * Gets, fleshes out and sets responsive styles for a field.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $s   CSS selector.
     * @param   string  $p   CSS property.
     * @param   string  $f   Field name.
     * @param   string  $t   Field type.
     * @param   string  $o   Priority.
     * @param   string  $a   Additional CSS. (eg. ' !important;')
     *
     * @return  void
     */
    private function dvmd_tm_set_responsive_1($s, $p, $f, $t='range', $o='', $a='') {
        $v = et_pb_responsive_options()->get_property_values($this->props, $f);
        if (!$v['tablet']) $v['tablet'] = $v['desktop'];
        if (!$v['phone'])  $v['phone']  = $v['tablet'];
        et_pb_responsive_options()->generate_responsive_css($v, $s, $p, $this->slug, $a, $t, $o);
    }


    /**
     * Sets a hover style for a field.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string   $s   CSS selector.
     * @param   string   $p   CSS property.
     * @param   string   $f   Field name.
     * @param   string   $q   Media query.
     * @param   integer  $o   Priority.
     *
     * @return  void
     */
    private function dvmd_tm_set_hover_1($s, $p, $f, $q=null, $o=10) {
        $hover = et_pb_hover_options();
        $v = $hover->get_value($f, $this->props);
        if (!empty($v)) {
            //$s = $hover->add_hover_to_order_class($s);
            $s = $hover->add_hover_to_selectors($s);
            ET_Builder_Element::set_style($this->slug, array(
                'selector'    =>  $s,
                'declaration' => "{$p}:{$v}!important;",
                'media_query' =>  $q,
                'priority'    =>  $o,
            ));
        }
    }


    /**
     * Sets responsive styles for custom margin and padding for a field.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string   $s   CSS selector.
     * @param   string   $f   Field name.
     * @param   string   $t   Type. (ie. margin/padding)
     * @param   integer  $o   Priority.
     * @param   boolean  $i   Important?
     *
     * @return  void
     */
    private function dvmd_tm_set_custom_spacing_1($s, $f, $t, $o=10, $i=false) {

        // Check responsive.
        $last_edited = $this->props["{$f}_last_edited"];
        $responsive  = et_pb_get_responsive_status($last_edited);

        // Desktop.
        $pr = $this->props[$f];
        if (!empty($pr)) {
            $d = et_builder_get_element_style_css($pr, $t, $i);
            ET_Builder_Element::set_style($this->slug, array(
                'selector'    => $s,
                'declaration' => $d,
                'priority'    => $o,
            ));
        }

        // Tablet.
        $pr = $this->props["{$f}_tablet"];
        if (!empty($pr) && $responsive) {
            $d = et_builder_get_element_style_css($pr, $t, $i);
            $q = ET_Builder_Element::get_media_query('max_width_980');
            ET_Builder_Element::set_style($this->slug, array(
                'selector'    => $s,
                'declaration' => $d,
                'media_query' => $q,
                'priority'    => $o,
            ));
        }

        // Phone.
        $pr = $this->props["{$f}_phone"];
        if (!empty($pr) && $responsive) {
            $d = et_builder_get_element_style_css($pr, $t, $i);
            $q = ET_Builder_Element::get_media_query('max_width_767');
            ET_Builder_Element::set_style($this->slug, array(
                'selector'    => $s,
                'declaration' => $d,
                'media_query' => $q,
                'priority'    => $o,
            ));
        }
    }


}


// Init.
new DVMD_Table_Maker_Item_Module;
