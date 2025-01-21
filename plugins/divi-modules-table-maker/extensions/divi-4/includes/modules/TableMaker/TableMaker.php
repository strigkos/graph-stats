<?php


if (!defined('ABSPATH')) exit;


/**
 * Divi-Modules – Table Maker.
 *
 * @since 3.1.2
 *
 */
class DVMD_Table_Maker_Module extends ET_Builder_Module {


    // Bug fix for dynamic property deprecation warning in PHP v8.2.
    public $icon;



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
        $this->vb_support           = 'on';
        $this->icon                 = 'W';
        $this->slug                 = 'dvmd_table_maker';
        $this->child_slug           = 'dvmd_table_maker_item';
        $this->name                 =  esc_html__('Table Maker', 'dvmd-table-maker');
        $this->plural               =  esc_html__('Table Makers', 'dvmd-table-maker');
        $this->child_item_text      =  esc_html__('Column', 'dvmd-table-maker');


        // Credits.
        $this->module_credits       =   array(
            'module_uri'            => 'https://divi-modules.com/products/table-maker',
            'author'                => 'Divi-Modules',
            'author_uri'            => 'https://divi-modules.com',
        );


        // Global assets.
        add_filter('et_global_assets_list', array(__CLASS__, 'dvmd_tm_global_assets_list'), 10);



        //-----------------------------------------------------//
        //---------------------- Toggles ----------------------//
        //-----------------------------------------------------//


        $this->settings_modal_toggles       =  array(

            // Content Tab. (general)
            'general'                       => array(
                'toggles'                   => array(
                    'tbl_title'             => esc_html__('Table Title',            'dvmd-table-maker'),
                    'tbl_description'       => esc_html__('Table Description',      'dvmd-table-maker'),
                    'tbl_columns'           => esc_html__('Table Columns',          'dvmd-table-maker'),
                    'tbl_rows'              => esc_html__('Table Rows',             'dvmd-table-maker'),
                    'tbl_corners'           => esc_html__('Table Corners',          'dvmd-table-maker'),
                    'tbl_responsive'        => esc_html__('Table Responsive',       'dvmd-table-maker'),
                    'tbl_scrolling'         => esc_html__('Table Scrolling',        'dvmd-table-maker'),
                    'tbl_icons'             => esc_html__('Table Icons',            'dvmd-table-maker'),
                    'tbl_buttons'           => esc_html__('Table Buttons',          'dvmd-table-maker'),
                    'tbl_images'            => esc_html__('Table Images',           'dvmd-table-maker'),
                ),
            ),

            // Design Tab. (advanced)
            'advanced'                      => array(
                'toggles'                   => array(
                    'tbl_title'             => esc_html__('Table Title',            'dvmd-table-maker'),
                    'tbl_description'       => esc_html__('Table Description',      'dvmd-table-maker'),
                    'tbl_accordion'         => esc_html__('Table Accordion',        'dvmd-table-maker'),
                    'tbl_frame'             => esc_html__('Table Frame',            'dvmd-table-maker'),
                    'tbl_stripes'           => esc_html__('Table Stripes',          'dvmd-table-maker'),
                    'tbl_hover'             => esc_html__('Table Hover',            'dvmd-table-maker'),
                    'tbl_tcell_text'        => esc_html__('Table Text',             'dvmd-table-maker'),
                    'tbl_tcell_cell'        => esc_html__('Table Cells',            'dvmd-table-maker'),
                    'tbl_chead_text'        => esc_html__('Column Header Text',     'dvmd-table-maker'),
                    'tbl_chead_cell'        => esc_html__('Column Header Cells',    'dvmd-table-maker'),
                    'tbl_cfoot_text'        => esc_html__('Column Footer Text',     'dvmd-table-maker'),
                    'tbl_cfoot_cell'        => esc_html__('Column Footer Cells',    'dvmd-table-maker'),
                    'tbl_rhead_text'        => esc_html__('Row Header Text',        'dvmd-table-maker'),
                    'tbl_rhead_cell'        => esc_html__('Row Header Cells',       'dvmd-table-maker'),
                    'tbl_rfoot_text'        => esc_html__('Row Footer Text',        'dvmd-table-maker'),
                    'tbl_rfoot_cell'        => esc_html__('Row Footer Cells',       'dvmd-table-maker'),
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

            'tbl_title'                 =>  array(
                'label'                 =>  esc_html__('Title', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_title',
            ),
            'tbl_description'           =>  array(
                'label'                 =>  esc_html__('Description', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_description',
            ),
            'tbl_table'                 =>  array(
                'label'                 =>  esc_html__('Table', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_table',
            ),
            'tbl_blocks'                =>  array(
                'label'                 =>  esc_html__('Table Blocks', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_table .dvmd_tm_tblock',
            ),
            'tbl_tcell_cells'           =>  array(
                'label'                 =>  esc_html__('Table Cells', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell',
            ),
            'tbl_tcell_content'         =>  array(
                'label'                 =>  esc_html__('Table Content', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell .dvmd_tm_cdata',
            ),
            'tbl_chead_cells'           =>  array(
                'label'                 =>  esc_html__('Column Header Cells', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell.dvmd_tm_chead',
            ),
            'tbl_chead_content'         =>  array(
                'label'                 =>  esc_html__('Column Header Content', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell.dvmd_tm_chead .dvmd_tm_cdata',
            ),
            'tbl_cfoot_cells'           =>  array(
                'label'                 =>  esc_html__('Column Footer Cells', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell.dvmd_tm_cfoot',
            ),
            'tbl_cfoot_content'         =>  array(
                'label'                 =>  esc_html__('Column Footer Content', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell.dvmd_tm_cfoot .dvmd_tm_cdata',
            ),
            'tbl_rhead_cells'           =>  array(
                'label'                 =>  esc_html__('Row Header Cells', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell.dvmd_tm_rhead',
            ),
            'tbl_rhead_content'         =>  array(
                'label'                 =>  esc_html__('Row Header Content', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell.dvmd_tm_rhead .dvmd_tm_cdata',
            ),
            'tbl_rfoot_cells'           =>  array(
                'label'                 =>  esc_html__('Row Footer Cells', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell.dvmd_tm_rfoot',
            ),
            'tbl_rfoot_content'         =>  array(
                'label'                 =>  esc_html__('Row Footer Content', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell.dvmd_tm_rfoot .dvmd_tm_cdata',
            ),
            'tbl_icons'                 =>  array(
                'label'                 =>  esc_html__('Table Icons', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell .dvmd_tm_icon',
            ),
            'tbl_buttons'               =>  array(
                'label'                 =>  esc_html__('Table Buttons', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell .dvmd_tm_button',
            ),
            'tbl_images'                =>  array(
                'label'                 =>  esc_html__('Table Images', 'dvmd-table-maker'),
                'selector'              => '.dvmd_tm_tcell .dvmd_tm_image',
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
        $f['text']    = false;
        // $f['fonts']   = false;
        // $f['button']  = false;



        //----------------------------------------------------//
        //---------------------- Common ----------------------//
        //----------------------------------------------------//


        // Background.
        $f['background']                =   array(
            'css'                       =>  array(
                'main'                  => '%%order_class%%',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Height.
        $f['height']                    =   array(
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_table',
            ),
        );


        // Margin/Padding.
        $f['margin_padding']            =   array(
            'css'                       =>  array(
                'padding'               => '%%order_class%%',
                'margin'                => '%%order_class%%',
                'important'             => 'all',
            ),
        );



        //----------------------------------------------------//
        //---------------------- Button ----------------------//
        //----------------------------------------------------//


        // Table.
        $f['button']['button']          =   array(

            'label'                     =>  esc_html__('Buttons', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'general',
            'toggle_slug'               => 'tbl_buttons',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_button',
                'limited_main'          => '%%order_class%% .dvmd_tm_button',
            ),
            'box_shadow'                =>  array(
                'css'                   =>  array(
                    'main'              => '%%order_class%% .dvmd_tm_button',
                ),
            ),
            'margin_padding'            =>  false,
            'use_alignment'             =>  false,
        );



        //---------------------------------------------------//
        //---------------------- Fonts ----------------------//
        //---------------------------------------------------//



        // Table Title.
        $f['fonts']['tbl_title']        =   array(
            'label'                     =>  esc_html__('Title:', 'dvmd-table-maker'),
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_title',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_title',
            ),
            'hide_text_shadow'          =>  true,
            'header_level'              =>  array(
                'default'               => 'h2',
            ),
        );

        // Table Caption.
        $f['fonts']['tbl_description']  =   array(
            'label'                     =>  esc_html__('Description:', 'dvmd-table-maker'),
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_description',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_description',
            ),
            'hide_text_shadow'          =>  true,
        );


        // Table Text.
        $f['fonts']['tbl_tcell_text']   =   array(

            'label'                     =>  esc_html__('Tbl:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_tcell_text',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell .dvmd_tm_cdata',
            ),
            'hide_text_align'           =>  true,
            'text_color'                =>  array(
                'default'               => '',
            ),
            'font_size'                 =>  array(
                'default'               => '',
            ),
        );


        // Column Header.
        $f['fonts']['tbl_chead_text']   =   array(

            'label'                     =>  esc_html__('CH:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_chead_text',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_chead .dvmd_tm_cdata',
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
        $f['fonts']['tbl_cfoot_text']   =   array(

            'label'                     =>  esc_html__('CF:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_cfoot_text',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_cfoot .dvmd_tm_cdata',
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
        $f['fonts']['tbl_rhead_text']   =   array(

            'label'                     =>  esc_html__('RH:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_rhead_text',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_rhead .dvmd_tm_cdata',
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
        $f['fonts']['tbl_rfoot_text']   =   array(

            'label'                     =>  esc_html__('RF:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_rfoot_text',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_rfoot .dvmd_tm_cdata',
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


        // General.
        $f['borders']                   =   array(
            'default'                   =>  array(
                'css'                   =>  array(
                    'main'              =>  array(
                        'border_radii'  => '%%order_class%%',
                        'border_styles' => '%%order_class%%',
                    ),
                    'important'         => 'all', // We need this for Divi-Builder plugin.
                ),
                'defaults'              =>  array(
                    'border_radii'      => 'on||||',
                ),
            ),
        );


        // Table.
        $f['borders']['tbl_tcell_cell_border'] = array(

            'label_prefix'              =>  esc_html__('Tbl:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_tcell_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => '%%order_class%% .dvmd_tm_tcell',
                    'border_styles'     => '%%order_class%% .dvmd_tm_tcell',
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
        $f['borders']['tbl_chead_cell_border'] = array(

            'label_prefix'              =>  esc_html__('CH:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_chead_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_chead',
                    'border_styles'     => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_chead',
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
        $f['borders']['tbl_cfoot_cell_border'] = array(

            'label_prefix'              =>  esc_html__('CF:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_cfoot_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_cfoot',
                    'border_styles'     => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_cfoot',
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
        $f['borders']['tbl_rhead_cell_border'] = array(

            'label_prefix'              =>  esc_html__('RH:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_rhead_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_rhead',
                    'border_styles'     => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_rhead',
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
        $f['borders']['tbl_rfoot_cell_border'] = array(

            'label_prefix'              =>  esc_html__('RF:', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_rfoot_cell',
            'css'                       =>  array(
                'main'                  =>  array(
                    'border_radii'      => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_rfoot',
                    'border_styles'     => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_rfoot',
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


        // General.
        $f['box_shadow']                =   array(
            'default'                   =>  array(
                'css'                   =>  array(
                    'main'              => '%%order_class%%',
                    'important'         => 'all', // We need this for Divi-Builder plugin.
                ),
            ),
        );


        // Table.
        $f['box_shadow']['tbl_tcell_cell_shadow'] = array(

            'label'                     =>  esc_html__('Tbl: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_tcell_cell',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Column Header.
        $f['box_shadow']['tbl_chead_cell_shadow'] = array(

            'label'                     =>  esc_html__('CH: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_chead_cell',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_chead',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Column Footer.
        $f['box_shadow']['tbl_cfoot_cell_shadow'] = array(

            'label'                     =>  esc_html__('CF: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_cfoot_cell',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_cfoot',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Row Header.
        $f['box_shadow']['tbl_rhead_cell_shadow'] = array(

            'label'                     =>  esc_html__('RH: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_rhead_cell',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_rhead',
                'important'             => 'all', // We need this for Divi-Builder plugin.
            ),
        );


        // Row Footer.
        $f['box_shadow']['tbl_rfoot_cell_shadow'] = array(

            'label'                     =>  esc_html__('RF: Box Shadow', 'dvmd-table-maker'),
            'option_category'           => 'layout',
            'tab_slug'                  => 'advanced',
            'toggle_slug'               => 'tbl_rfoot_cell',
            'css'                       =>  array(
                'main'                  => '%%order_class%% .dvmd_tm_tcell.dvmd_tm_rfoot',
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



        //---------------------------------------------------------//
        //---------------------- Table Title ----------------------//
        //---------------------------------------------------------//


        $description = esc_html__(
            'Here you can enable a table title. Providing a title for the table can help people using screen readers better understand the table content.', 'dvmd-table-maker');

        $f['tbl_title_mode']                =   array(
            'label'                         =>  esc_html__('Title: Mode', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_title',
            'type'                          => 'yes_no_button',
            'default'                       => 'off',
            'options'                       =>  array(
                'on'                        =>  esc_html__('On', 'dvmd-table-maker'),
                'off'                       =>  esc_html__('Off', 'dvmd-table-maker'),
            ),
            'affects'                       =>  array(
                'tbl_title_font',
                'tbl_title_text_align',
                'tbl_title_text_color',
                'tbl_title_font_size',
                'tbl_title_letter_spacing',
                'tbl_title_line_height',
                'tbl_title_level',
            ),
        );

        $description = esc_html__(
            'Here you can enter a table title. The table title can be styled under the <b>Design > Table Title</b> toggle.', 'dvmd-table-maker');

        $f['tbl_title_text']                =   array(
            'label'                         =>  esc_html__('Title: Text', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_title',
            'type'                          => 'text',
            'allow_empty'                   =>  false,
            'default'                       => 'Untitled',
            'dynamic_content'               => 'text',
            'show_if'                       =>  array(
                'tbl_title_mode'            => 'on',
            ),
        );

        $description = esc_html__(
            'Here you can position the table title above or below the table. When set to hidden, the title will be visible to screen readers only.', 'dvmd-table-maker');

        $f['tbl_title_position']            =   array(
            'label'                         =>  esc_html__('Title: Position', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_title',
            'type'                          => 'multiple_buttons',
            'default'                       => 'above',
            'options'                       =>  array(
                'above'                     =>  array('title' => esc_html__('Above', 'dvmd-table-maker')),
                'below'                     =>  array('title' => esc_html__('Below', 'dvmd-table-maker')),
                'hidden'                    =>  array('title' => esc_html__('Hidden', 'dvmd-table-maker')),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if'                       =>  array(
                'tbl_title_mode'            => 'on',
            ),
        );

        $description = esc_html__(
            'The title will be visible to screen readers only.', 'dvmd-table-maker');

        $f['tbl_title_warning']             =   array(
            'message'                       =>  $description,
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_title',
            'type'                          => 'warning',
            'value'                         =>  true,
            'display_if'                    =>  true,
            'show_if'                       =>  array(
                'tbl_title_mode'            => 'on',
                'tbl_title_position'        => 'hidden',
            ),
        );

        $description = esc_html__(
            'Here you can set the space between the table title and the table.', 'dvmd-table-maker');

        $f['tbl_title_spacing']             =   array(
            'label'                         =>  esc_html__('Title: Spacing', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_title',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'px',
            'default'                       => '20px',
            'allowed_units'                 =>  array('em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '100',
                'step'                      => '1',
            ),
            'show_if'                       =>  array(
                'tbl_title_mode'            => 'on',
            ),
        );



        //---------------------------------------------------------------//
        //---------------------- Table Description ----------------------//
        //---------------------------------------------------------------//


        $description = esc_html__(
            'Here you can enable a table description. Providing a description for the table can help people using screen readers better understand the table content.', 'dvmd-table-maker');

        $f['tbl_description_mode']          =   array(
            'label'                         =>  esc_html__('Description: Mode', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_description',
            'type'                          => 'yes_no_button',
            'default'                       => 'off',
            'options'                       =>  array(
                'on'                        =>  esc_html__('On', 'dvmd-table-maker'),
                'off'                       =>  esc_html__('Off', 'dvmd-table-maker'),
            ),
            'affects'                       =>  array(
                'tbl_description_font',
                'tbl_description_text_align',
                'tbl_description_text_color',
                'tbl_description_font_size',
                'tbl_description_letter_spacing',
                'tbl_description_line_height',
            ),
        );

        $description = esc_html__(
            'Here you can enter a table description. The table description can be styled under the <b>Design > Table Description</b> toggle.', 'dvmd-table-maker');

        $f['tbl_description_text']          =   array(
            'label'                         =>  esc_html__('Description: Text', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_description',
            'type'                          => 'textarea',
            'allow_empty'                   =>  false,
            'default'                       => 'Your description here…',
            'dynamic_content'               => 'text',
            'show_if'                       =>  array(
                'tbl_description_mode'      => 'on',
            ),
        );

        $description = esc_html__(
            'Here you can position the table description above or below the table. When set to hidden, the description will be visible to screen readers only.', 'dvmd-table-maker');

        $f['tbl_description_position']      =   array(
            'label'                         =>  esc_html__('Description: Position', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_description',
            'type'                          => 'multiple_buttons',
            'default'                       => 'below',
            'options'                       =>  array(
                'above'                     =>  array('title' => esc_html__('Above', 'dvmd-table-maker')),
                'below'                     =>  array('title' => esc_html__('Below', 'dvmd-table-maker')),
                'hidden'                    =>  array('title' => esc_html__('Hidden', 'dvmd-table-maker')),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if'                       =>  array(
                'tbl_description_mode'      => 'on',
            ),
        );

        $description = esc_html__(
            'The description will be visible to screen readers only.', 'dvmd-table-maker');

        $f['tbl_description_warning']       =   array(
            'message'                       =>  $description,
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_description',
            'type'                          => 'warning',
            'value'                         =>  true,
            'display_if'                    =>  true,
            'show_if'                       =>  array(
                'tbl_description_mode'      => 'on',
                'tbl_description_position'  => 'hidden',
            ),
        );

        $description = esc_html__(
            'Here you can set the space between the table description and the table.', 'dvmd-table-maker');

        $f['tbl_description_spacing']       =   array(
            'label'                         =>  esc_html__('Description: Spacing', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_description',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'px',
            'default'                       => '20px',
            'allowed_units'                 =>  array('em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '100',
                'step'                      => '1',
            ),
            'show_if'                       =>  array(
                'tbl_description_mode'      => 'on',
            ),
        );



        //-----------------------------------------------------------------//
        //---------------------- Header/Footer Count ----------------------//
        //-----------------------------------------------------------------//


        $description = esc_html__(
            'Here you can set the number of column headers the table will have. Column headers can be styled under the <b>Design > Column Header Cells</b> and <b>Text</b> toggles.', 'dvmd-table-maker');

        $f['tbl_column_header_count']       =   array(
            'label'                         =>  esc_html__('Col: Header Count', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_columns',
            'type'                          => 'range',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'unitless'                      =>  true,
            'default'                       => '1',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '3',
                'step'                      => '1',
            ),
        );

        $description = esc_html__(
            'Here you can set the number of column footers the table will have. Column footers can be styled under the <b>Design > Column Footer Cells</b> and <b>Text</b> toggles.', 'dvmd-table-maker');

        $f['tbl_column_footer_count']       =   array(
            'label'                         =>  esc_html__('Col: Footer Count', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_columns',
            'type'                          => 'range',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'unitless'                      =>  true,
            'default'                       => '0',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '3',
                'step'                      => '1',
            ),
        );

        $description = esc_html__(
            'Here you can set the number of row headers the table will have. Row headers can be styled under the <b>Design > Row Header Cells</b> and <b>Text</b> toggles.', 'dvmd-table-maker');

        $f['tbl_row_header_count']          =   array(
            'label'                         =>  esc_html__('Row: Header Count', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_rows',
            'type'                          => 'range',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'unitless'                      =>  true,
            'default'                       => '1',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '3',
                'step'                      => '1',
            ),
        );

        $description = esc_html__(
            'Here you can set the number of row footers the table will have. Row footers can be styled under the <b>Design > Row Footer Cells</b> and <b>Text</b> toggles.', 'dvmd-table-maker');

        $f['tbl_row_footer_count']          =   array(
            'label'                         =>  esc_html__('Row: Footer Count', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_rows',
            'type'                          => 'range',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'unitless'                      =>  true,
            'default'                       => '0',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '3',
                'step'                      => '1',
            ),
        );



        //-----------------------------------------------------//
        //---------------------- Max-Min ----------------------//
        //-----------------------------------------------------//


        $description = esc_html__(
            "Here you can set a maximum width for all table columns. For flexible-width columns, it’s recommended to use fraction (fr) units. This can also be set to 'auto'. For fixed-width columns, it’s recommended to use pixel (px) units. This setting can be adjusted per column under the <b>Column Settings > Content > Column Width</b> toggle.", 'dvmd-table-maker');

        $f['tbl_column_max_width']          =   array(
            'label'                         =>  esc_html__('Col: Max Width', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_columns',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allowed_units'                 =>  array('fr','%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'fr',
            'default'                       => '1fr',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '5',
                'step'                      => '.1',
            ),
        );

        $description = esc_html__(
            'Here you can set a minimum width for all table columns. It’s recommended to use pixel (px) units. This setting can be adjusted per column under the <b>Column Settings > Content > Column Width</b> toggle.', 'dvmd-table-maker');

        $f['tbl_column_min_width']          =   array(
            'label'                         =>  esc_html__('Col: Min Width', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_columns',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'px',
            'default'                       => '100px',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '300',
                'step'                      => '1',
            ),
        );

        $description = esc_html__(
            "Here you can set a maximum height for all table rows. For flexible-height rows, it’s recommended to set this to ‘auto’. For fixed-height rows, it’s recommended to use pixel (px) units.", 'dvmd-table-maker');

        $f['tbl_row_max_height']            =   array(
            'label'                         =>  esc_html__('Row: Max Height', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_rows',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'allowed_values'                =>  array('auto'),
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'px',
            'default'                       => 'auto',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '300',
                'step'                      => '1',
            ),
        );

        $description = esc_html__(
            'Here you can set a minimum height for all table rows. It’s recommended to use pixel (px) units.', 'dvmd-table-maker');

        $f['tbl_row_min_height']            =   array(
            'label'                         =>  esc_html__('Row: Min Height', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_rows',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'px',
            'default'                       => '50px',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '300',
                'step'                      => '1',
            ),
        );



        //-----------------------------------------------------------//
        //---------------------- Table Corners ----------------------//
        //-----------------------------------------------------------//


        $description = esc_html__(
            'Here you can choose to hide or show the top-left corner table cells.', 'dvmd-table-maker');

        $f['tbl_top_left_mode']             =   array(
            'label'                         =>  esc_html__('Top-Left: Mode', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_corners',
            'type'                          => 'yes_no_button',
            'default'                       => 'off',
            'options'                       =>  array(
                'on'                        =>  esc_html__('On', 'dvmd-table-maker'),
                'off'                       =>  esc_html__('Off', 'dvmd-table-maker'),
            ),
            'show_if_not'                   =>  array(
                'tbl_column_header_count'   =>  array('0','-1','-2','-3','-4','-5'),
                'tbl_row_header_count'      =>  array('0','-1','-2','-3','-4','-5'),
            ),
        );

        $description = esc_html__(
            'Here you can choose whether the top-left corner table cells will be styled as column or row headers.', 'dvmd-table-maker');

        $f['tbl_top_left_style']            =   array(
            'label'                         =>  esc_html__('Top-Left: Style', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_corners',
            'type'                          => 'multiple_buttons',
            'default'                       => 'chead',
            'options'                       =>  array(
                'chead'                     =>  array(
                    'title'                 =>  esc_html__('Col Header', 'dvmd-table-maker'),
                ),
                'rhead'                     =>  array(
                    'title'                 =>  esc_html__('Row Header', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if_not'                   =>  array(
                'tbl_column_header_count'   =>  array('0','-1','-2','-3','-4','-5'),
                'tbl_row_header_count'      =>  array('0','-1','-2','-3','-4','-5'),
            ),
            'show_if'                       =>  array(
                'tbl_top_left_mode'         => 'on',
            ),
        );

        $description = esc_html__(
            'Here you can choose whether the top-right corner table cells will styled as column headers or row footers.', 'dvmd-table-maker');

        $f['tbl_top_right_style']           =   array(
            'label'                         =>  esc_html__('Top-Right: Style', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_corners',
            'type'                          => 'multiple_buttons',
            'default'                       => 'chead',
            'options'                       =>  array(
                'chead'                     =>  array(
                    'title'                 =>  esc_html__('Col Header', 'dvmd-table-maker'),
                ),
                'rfoot'                     =>  array(
                    'title'                 =>  esc_html__('Row Footer', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if_not'                   =>  array(
                'tbl_column_header_count'   =>  array('0','-1','-2','-3','-4','-5'),
                'tbl_row_footer_count'      =>  array('0','-1','-2','-3','-4','-5'),
            ),
        );

        $description = esc_html__(
            'Here you can choose whether the bottom-left corner table cells will be styled as column footers or row headers.', 'dvmd-table-maker');

        $f['tbl_bottom_left_style']         =   array(
            'label'                         =>  esc_html__('Bottom-Left: Style', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_corners',
            'type'                          => 'multiple_buttons',
            'default'                       => 'rhead',
            'options'                       =>  array(
                'cfoot'                     =>  array(
                    'title'                 =>  esc_html__('Col Footer', 'dvmd-table-maker'),
                ),
                'rhead'                     =>  array(
                    'title'                 =>  esc_html__('Row Header', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if_not'                   =>  array(
                'tbl_column_footer_count'   =>  array('0','-1','-2','-3','-4','-5'),
                'tbl_row_header_count'      =>  array('0','-1','-2','-3','-4','-5'),
            ),
        );

        $description = esc_html__(
            'Here you can choose whether the bottom-right corner table cells will be styled as column or row footers.', 'dvmd-table-maker');

        $f['tbl_bottom_right_style']        =   array(
            'label'                         =>  esc_html__('Bottom-Right: Style', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_corners',
            'type'                          => 'multiple_buttons',
            'default'                       => 'rfoot',
            'options'                       =>  array(
                'cfoot'                     =>  array(
                    'title'                 =>  esc_html__('Col Footer', 'dvmd-table-maker'),
                ),
                'rfoot'                     =>  array(
                    'title'                 =>  esc_html__('Row Footer', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if_not'                   =>  array(
                'tbl_column_footer_count'   =>  array('0','-1','-2','-3','-4','-5'),
                'tbl_row_footer_count'      =>  array('0','-1','-2','-3','-4','-5'),
            ),
        );



        //--------------------------------------------------------//
        //---------------------- Responsive ----------------------//
        //--------------------------------------------------------//


        $description = esc_html__(
            'Here you can choose to enable table responsiveness. When enabled, the table will display as blocks or accordion at desktop, tablet or phone size.', 'dvmd-table-maker');

        $f['tbl_responsive_mode']               =   array(
            'label'                             =>  esc_html__('Responsive: Mode', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'configuration',
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_responsive',
            'type'                              => 'yes_no_button',
            'default'                           => 'on',
            'options'                           =>  array(
                'on'                            =>  esc_html__('On', 'dvmd-table-maker'),
                'off'                           =>  esc_html__('Off', 'dvmd-table-maker'),
            ),
        );

        $description = esc_html__(
            'Here you can set the table breakpoint. The breakpoint is the point at which the table will display as blocks or accordion.', 'dvmd-table-maker');

        $f['tbl_responsive_breakpoint']         =   array(
            'label'                             =>  esc_html__('Responsive: Breakpoint', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'configuration',
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_responsive',
            'type'                              => 'multiple_buttons',
            'default'                           => 'max_width_980',
            'options'                           =>  array(
                'desktop'                       =>  array(
                    'title'                     =>  esc_html__('Desktop', 'dvmd-table-maker'),
                    'icon'                      => 'desktop',
                ),
                'max_width_980'                 =>  array(
                    'title'                     =>  esc_html__('Tablet', 'dvmd-table-maker'),
                    'icon'                      => 'tablet',
                ),
                'max_width_767'                 =>  array(
                    'title'                     =>  esc_html__('Phone', 'dvmd-table-maker'),
                    'icon'                      => 'phone',
                ),
            ),
            'toggleable'                        =>  false,
            'multi_selection'                   =>  false,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
            ),
        );

        $description = esc_html__(
            'Please select a breakpoint from above.', 'dvmd-table-maker');

        $f['tbl_responsive_warning_1']          =   array(
            'message'                           =>  $description,
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_responsive',
            'type'                              => 'warning',
            'value'                             =>  true,
            'display_if'                        =>  true,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
            ),
            'show_if_not'                       =>  array(
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
            ),
        );

        $description = esc_html__(
            'Here you can set whether the table will break by columns or rows. If set to Columns, each column will become a separate block or accordion section. If set to Rows, each row will become a separate block or accordion section.', 'dvmd-table-maker');

        $f['tbl_responsive_break_by']           =   array(
            'label'                             =>  esc_html__('Responsive: Break By', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'configuration',
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_responsive',
            'type'                              => 'multiple_buttons',
            'default'                           => 'column',
            'options'                           =>  array(
                'column'                        =>  array(
                    'title'                     =>  esc_html__('Columns', 'dvmd-table-maker'),
                ),
                'row'                           =>  array(
                    'title'                     =>  esc_html__('Rows', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                        =>  false,
            'multi_selection'                   =>  false,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
            ),
        );

        $description = esc_html__(
            'Your table currently has no column headers and will break by rows regardless of the setting above. To break by columns, please add a column header.', 'dvmd-table-maker');

        $f['tbl_responsive_warning_2']          =   array(
            'message'                           =>  $description,
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_responsive',
            'type'                              => 'warning',
            'value'                             =>  true,
            'display_if'                        =>  true,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
                'tbl_responsive_break_by'       => 'column',
                'tbl_column_header_count'       => '0',
            ),
            'show_if_not'                       =>  array(
                'tbl_row_header_count'          => '0',
            ),
        );

        $description = esc_html__(
            'Your table currently has no row headers and will break by columns regardless of the setting above. To break by rows, please add a row header.', 'dvmd-table-maker');

        $f['tbl_responsive_warning_3']          =   array(
            'message'                           =>  $description,
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_responsive',
            'type'                              => 'warning',
            'value'                             =>  true,
            'display_if'                        =>  true,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
                'tbl_responsive_break_by'       => 'row',
                'tbl_row_header_count'          => '0',
            ),
            'show_if_not'                       =>  array(
                'tbl_column_header_count'       => '0',
            ),
        );

        $description = esc_html__(
            'Here you can choose whether the table will display as blocks or accordion. When Accordion is selected, the accordion can be configured and styled under the <b>Design > Table Accordion</b> toggle.', 'dvmd-table-maker');

        $f['tbl_responsive_display_as']         =   array(
            'label'                             =>  esc_html__('Responsive: Display As', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'layout',
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_responsive',
            'type'                              => 'multiple_buttons',
            'default'                           => 'blocks',
            'options'                           =>  array(
                'blocks'                        =>  array(
                    'title'                     =>  esc_html__('Blocks', 'dvmd-table-maker'),
                ),
                'accordion'                     =>  array(
                    'title'                     =>  esc_html__('Accordion', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                        =>  false,
            'multi_selection'                   =>  false,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
            ),
        );

        $description = esc_html__(
            'Your table currently has no headers and will display as blocks regardless of the setting above. To display as accordion, please add a column or row header.', 'dvmd-table-maker');

        $f['tbl_responsive_warning_4']          =   array(
            'message'                           =>  $description,
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_responsive',
            'type'                              => 'warning',
            'value'                             =>  true,
            'display_if'                        =>  true,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
                'tbl_column_header_count'       => '0',
                'tbl_row_header_count'          => '0',
            ),
        );

        $description = esc_html__(
            'Here you can set the space between each block or accordion section.', 'dvmd-table-maker');

        $f['tbl_responsive_block_margin']       =   array(
            'label'                             =>  esc_html__('Responsive: Spacing', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'layout',
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_responsive',
            'type'                              => 'range',
            'mobile_options'                    =>  true,
            'responsive'                        =>  true,
            'allowed_units'                     =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'allow_empty'                       =>  false,
            'validate_unit'                     =>  true,
            'default_unit'                      => 'px',
            'default'                           => '15px',
            'range_settings'                    =>  array(
                'min'                           => '0',
                'max'                           => '100',
                'step'                          => '1',
            ),
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
            ),
        );



        //-------------------------------------------------------//
        //---------------------- Scrolling ----------------------//
        //-------------------------------------------------------//


        $description = esc_html__(
            'Here you can choose to enable table scrolling. If set to Off, the table may overflow its containing element. If set to On, any overflow will be hidden and scrollbars will appear.', 'dvmd-table-maker');

        $f['tbl_scrolling_active']          =   array(
            'label'                         =>  esc_html__('Scrolling: Mode', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_scrolling',
            'type'                          => 'yes_no_button',
            'default'                       => 'off',
            'options'                       =>  array(
                'on'                        =>  esc_html__('On', 'dvmd-table-maker'),
                'off'                       =>  esc_html__('Off', 'dvmd-table-maker'),
            ),
        );

        $description = esc_html__(
            'Here you can choose to make column headers sticky. If set to Off, column headers will scroll along with table contents. If set to On, column headers will stick to the top edge of the table, remaining visible.', 'dvmd-table-maker');

        $f['tbl_scrolling_col_sticky']      =   array(
            'label'                         =>  esc_html__('Sticky: Column Headers', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_scrolling',
            'type'                          => 'yes_no_button',
            'default'                       => 'off',
            'options'                       =>  array(
                'on'                        =>  esc_html__('On', 'dvmd-table-maker'),
                'off'                       =>  esc_html__('Off', 'dvmd-table-maker'),
            ),
            'show_if'                       =>  array(
                'tbl_scrolling_active'      => 'on',
            ),
            'show_if_not'                   =>  array(
                'tbl_chead_count'           => '0',
            ),
        );

        $description= esc_html__(
            'Here you can choose to make row headers sticky. If set to Off, row headers will scroll along with table contents. If set to On, row headers will stick to the left edge of the table, remaining visible.', 'dvmd-table-maker');

        $f['tbl_scrolling_row_sticky']      =   array(
            'label'                         =>  esc_html__('Sticky: Row Headers', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_scrolling',
            'type'                          => 'yes_no_button',
            'default'                       => 'off',
            'options'                       =>  array(
                'on'                        =>  esc_html__('On', 'dvmd-table-maker'),
                'off'                       =>  esc_html__('Off', 'dvmd-table-maker'),
            ),
            'show_if'                       =>  array(
                'tbl_scrolling_active'      => 'on',
            ),
            'show_if_not'                   =>  array(
                'tbl_rhead_count'           => '0',
            ),
        );

        $description = esc_html__(
            'Table scrolling is only visible when needed. Horizontal scrolling will be visible when the table is wider than the available space. Vertical scrolling will be visible only if the table is given a <b>Height</b> or <b>Max-Height</b> that is less that the height of the table.', 'dvmd-table-maker');

        $f['tbl_scrolling_warning']             =   array(
            'message'                           =>  $description,
            'tab_slug'                          => 'general',
            'toggle_slug'                       => 'tbl_scrolling',
            'type'                              => 'warning',
            'value'                             =>  true,
            'display_if'                        =>  true,
            'show_if'                           =>  array(
                'tbl_scrolling_active'          => 'on',
            ),
        );



        //--------------------------------------------------//
        //---------------------- Icon ----------------------//
        //--------------------------------------------------//


        $description = esc_html__(
            'Here you can select the table’s default icon. This setting can be adjusted per column under the <b>Column Settings > Content > Column Icons</b> toggle.', 'dvmd-table-maker');

        $default = (function_exists('et_pb_get_extended_font_icon_value')) ? '&#x52;||divi||400' : '&#x52;';

        $f['tbl_icon_type']                 =   array(
            'label'                         =>  esc_html__('Icon: Default', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_icons',
            'type'                          => 'select_icon',
            'default'                       =>  $default,
        );

        $description = esc_html__(
            'Here you can set the table’s default icon size. This setting can be adjusted per column under the <b>Column Settings > Content > Column Icons</b> toggle.', 'dvmd-table-maker');

        $f['tbl_icon_size']                 =   array(
            'label'                         =>  esc_html__('Icon: Size', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_icons',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'default'                       => '1em',
            'range_settings'                =>  array(
                'min'                       => '1',
                'max'                       => '120',
                'step'                      => '1',
            ),
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'default_unit'                  => 'em',
        );

        $description = esc_html__(
            'Here you can set the table’s default icon color. This setting can be adjusted per column under the <b>Column Settings > Content > Column Icons</b> toggle.', 'dvmd-table-maker');

        $f['tbl_icon_color']                =   array(
            'label'                         =>  esc_html__('Icon: Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_icons',
            'type'                          => 'color-alpha',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
        );



        //----------------------------------------------------//
        //---------------------- Button ----------------------//
        //----------------------------------------------------//


        $description = esc_html__(
            'Here you can set the table’s default button text. This setting can be adjusted per column under the <b>Column Settings > Content > Column Buttons</b> toggle.', 'dvmd-table-maker');

        $f['tbl_button_text']               =   array(
            'label'                         =>  esc_html__('Button: Text', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_buttons',
            'type'                          => 'text',
            'default'                       => 'Default',
        );

        $description = esc_html__(
            'Here you can set the table’s default button url. This setting can be adjusted per column under the <b>Column Settings > Content > Column Buttons</b> toggle.', 'dvmd-table-maker');

        $f['tbl_button_url']                =   array(
            'label'                         =>  esc_html__('Button: URL', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_buttons',
            'type'                          => 'text',
            'default'                       => '#',
            'dynamic_content'               => 'url',
        );

        $description = esc_html__(
            'Here you can set the table’s default button target. This setting can be adjusted per column under the <b>Column Settings > Content > Column Buttons</b> toggle.', 'dvmd-table-maker');

        $f['tbl_button_target']             =   array(
            'label'                         =>  esc_html__('Button: Target', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_buttons',
            'type'                          => 'select',
            'default'                       => 'default',
            'options'                       =>  array(
                'default'                   =>  esc_html__('Default', 'dvmd-table-maker'),
                '_self'                     =>  esc_html__('In The Same Window', 'et_builder'),
                '_blank'                    =>  esc_html__('In The New Tab', 'et_builder'),
            ),
        );

        $description = esc_html__(
            'Here you can set the table’s default button width. If set to Text Width, buttons will be as wide as their text. If set to Cell Width, buttons will stretch to fill their containing cell. This setting can be adjusted per column under the <b>Column Settings > Content > Column Buttons</b> toggle.', 'dvmd-table-maker');

        $f['tbl_button_width']              =   array(
            'label'                         =>  esc_html__('Button: Width', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_buttons',
            'type'                          => 'multiple_buttons',
            'default'                       => 'inline-block',
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
            'Here you can select images to use within table cells. See documentation for details.', 'dvmd-table-maker');

        $f['tbl_image_ids']                 =   array(
            'label'                         =>  esc_html__('Image: Selection', 'et_builder'),
            'description'                   =>  $description,
            'option_category'               => 'basic_option',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_images',
            'type'                          => 'upload-gallery',
            'computed_affects'              => array(
                '__tbl_image_src',
            ),
        );

        $description = esc_html__(
            'Here you can set the table’s default image quality or resolution.', 'dvmd-table-maker');

        $f['tbl_image_quality']             =   array(
            'label'                         =>  esc_html__('Image: Quality', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_images',
            'type'                          => 'select',
            'default'                       => 'medium',
            'options'                       =>  array(
                'thumbnail'                 =>  esc_html__('Thumbnail', 'dvmd-table-maker'),
                'medium'                    =>  esc_html__('Small', 'dvmd-table-maker'),
                'medium_large'              =>  esc_html__('Medium', 'dvmd-table-maker'),
                'large'                     =>  esc_html__('Large', 'dvmd-table-maker'),
                'full'                      =>  esc_html__('Full Size', 'dvmd-table-maker'),
            ),
            'computed_affects'              => array(
                '__tbl_image_src',
            ),
        );

        $f['__tbl_image_src']               =   array(
            'type'                          => 'computed',
            'computed_callback'             =>  array('DVMD_Table_Maker_Module', 'dvmd_tm_get_image_src'),
            'computed_depends_on'           =>  array(
                'tbl_image_ids',
                'tbl_image_quality',
            ),
        );

        $description = esc_html__(
            'Here you can set the table’s default image proportion. This setting can be adjusted per column under the <b>Column Settings > Content > Column Images</b> toggle.', 'dvmd-table-maker');

        $f['tbl_image_proportion']          =   array(
            'label'                         =>  esc_html__('Image: Proportion', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_images',
            'type'                          => 'select',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'default'                       => '75%',
            'options'                       =>  array(
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
            'Here you can choose how the table’s images are scaled. If set to Fit, images are scaled to fit their containing cell without cropping. If set to Fill, images are scaled to fill or cover their containing cell – this may result in some cropping. If set to Size, you can enter a custom size for the table images. This setting can be adjusted per column under the <b>Column Settings > Content > Column Images</b> toggle.', 'dvmd-table-maker');

        $f['tbl_image_scale']               =   array(
            'label'                         =>  esc_html__('Image: Scale', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_images',
            'type'                          => 'multiple_buttons',
            'default'                       => 'cover',
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
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
        );

        $description = esc_html__(
            'Here you can set a custom size for the table’s images. This setting can be adjusted per column under the <b>Column Settings > Content > Column Images</b> toggle.', 'dvmd-table-maker');

        $f['tbl_image_size']                =   array(
            'label'                         =>  esc_html__('Image: Size', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_images',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'default_unit'                  => '%',
            'default'                       => '100%',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '300',
                'step'                      => '1',
            ),
            'show_if'                       =>  array(
                'tbl_image_scale'           => 'size',
            ),
        );

        $description = esc_html__(
            'Here you can set the table’s default horizontal image alignment. This setting can be adjusted per column under the <b>Column Settings > Content > Column Images</b> toggle.', 'dvmd-table-maker');

        $f['tbl_image_align_horz']          =   array(
            'label'                         =>  esc_html__('Image: Position X', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_images',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'fixed_unit'                    => '%',
            'default_unit'                  => '%',
            'default'                       => '50%',
            'fixed_range'                   =>  true,
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '100',
                'step'                      => '1',
            ),
        );

        $description = esc_html__(
            'Here you can set the table’s default vertical image alignment. This setting can be adjusted per column under the <b>Column Settings > Content > Column Images</b> toggle.', 'dvmd-table-maker');

        $f['tbl_image_align_vert']          =   array(
            'label'                         =>  esc_html__('Image: Position Y', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'general',
            'toggle_slug'                   => 'tbl_images',
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'hover'                         => 'tabs',
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'fixed_unit'                    => '%',
            'default_unit'                  => '%',
            'default'                       => '50%',
            'fixed_range'                   =>  true,
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '100',
                'step'                      => '1',
            ),
        );



        //-------------------------------------------------------//
        //---------------------- Accordion ----------------------//
        //-------------------------------------------------------//


        $description = esc_html__(
            'Here you can set whether the initial state of the accordion toggles should be opened or closed.', 'dvmd-table-maker');

        $f['tbl_toggle_state']                  =   array(
            'label'                             =>  esc_html__('Accordion: Toggle State', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'configuration',
            'tab_slug'                          => 'advanced',
            'toggle_slug'                       => 'tbl_accordion',
            'type'                              => 'multiple_buttons',
            'default'                           => 'closed',
            'options'                           =>  array(
                'closed'                        =>  array(
                    'title'                     =>  esc_html__('Closed', 'dvmd-table-maker'),
                ),
                'opened'                        =>  array(
                    'title'                     =>  esc_html__('Opened', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                        =>  false,
            'multi_selection'                   =>  false,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
            ),
        );

        $description = esc_html__(
            'Here you can set which of the accordion toggles should be opened. Toggles are numbered, starting with number one.', 'dvmd-table-maker');

        $f['tbl_toggle_index']                  =   array(
            'label'                             =>  esc_html__('Accordion: Toggle Opened', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'configuration',
            'tab_slug'                          => 'advanced',
            'toggle_slug'                       => 'tbl_accordion',
            'type'                              => 'range',
            'default'                           => '1',
            'range_settings'                    =>  array(
                'min'                           => '1',
                'max'                           => '10',
                'step'                          => '1',
            ),
            'allow_empty'                       =>  false,
            'validate_unit'                     =>  true,
            'unitless'                          =>  true,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
                'tbl_toggle_state'              => 'opened',
            ),
        );

        $description = esc_html__(
            'Here you can set the accordion toggle icon alignment.', 'dvmd-table-maker');

        $f['tbl_toggle_align']                  =   array(
            'label'                             =>  esc_html__('Accordion: Icon Alignment', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'layout',
            'tab_slug'                          => 'advanced',
            'toggle_slug'                       => 'tbl_accordion',
            'type'                              => 'text_align',
            'default'                           => 'right',
            'options'                           =>  et_builder_get_text_orientation_options(array('justified', 'center')),
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
            ),
        );

        $description = esc_html__(
            'Here you can set the accordion toggle icon size.', 'dvmd-table-maker');

        $f['tbl_toggle_size']                   =   array(
            'label'                             =>  esc_html__('Accordion: Icon Size', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'font_option',
            'tab_slug'                          => 'advanced',
            'toggle_slug'                       => 'tbl_accordion',
            'type'                              => 'range',
            'default'                           => '24px',
            'range_settings'                    =>  array(
                'min'                           => '1',
                'max'                           => '120',
                'step'                          => '1',
            ),
            'allowed_units'                     =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'default_unit'                      => 'px',
            'mobile_options'                    =>  true,
            'responsive'                        =>  true,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
            ),
        );

        $description = esc_html__(
            'Here you can set the accordion toggle icon color.', 'dvmd-table-maker');

        $f['tbl_toggle_color']                  =   array(
            'label'                             =>  esc_html__('Accordion: Icon Color', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'color_option',
            'tab_slug'                          => 'advanced',
            'toggle_slug'                       => 'tbl_accordion',
            'type'                              => 'color-alpha',
            'mobile_options'                    =>  true,
            'responsive'                        =>  true,
            'default'                           => '#ffffff',
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
            ),
        );

        if (function_exists('et_pb_get_extended_font_icon_value')) {
            $default_closed = '&#x4c;||divi||400';
            $default_opened = '&#x4b;||divi||400';
        } else {
            $default_closed = '&#x4c;';
            $default_opened = '&#x4b;';
        }

        $description = esc_html__(
            'Here you can set the icon for when an accordion toggle is closed.', 'dvmd-table-maker');

        // This field was previously named 'Accordion: Icon (Open)'.
        $f['tbl_toggle_icon_open']              =   array(
            'label'                             =>  esc_html__('Accordion: Icon (Closed)', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'basic_option',
            'tab_slug'                          => 'advanced',
            'toggle_slug'                       => 'tbl_accordion',
            'type'                              => 'select_icon',
            'default'                           =>  $default_closed,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
            ),
        );

        $description = esc_html__(
            'Here you can set the icon for when an accordion toggle is opened.', 'dvmd-table-maker');

        // This field was previously named 'Accordion: Icon (Close)'.
        $f['tbl_toggle_icon_close']             =   array(
            'label'                             =>  esc_html__('Accordion: Icon (Opened)', 'dvmd-table-maker'),
            'description'                       =>  $description,
            'option_category'                   => 'basic_option',
            'tab_slug'                          => 'advanced',
            'toggle_slug'                       => 'tbl_accordion',
            'type'                              => 'select_icon',
            'default'                           =>  $default_opened,
            'show_if'                           =>  array(
                'tbl_responsive_mode'           => 'on',
                'tbl_responsive_breakpoint'     =>  array('desktop', 'max_width_980', 'max_width_767'),
                'tbl_responsive_display_as'     => 'accordion',
            ),
        );



        //---------------------------------------------------//
        //---------------------- Frame ----------------------//
        //---------------------------------------------------//


        $description = esc_html__(
            'Here you can set how the table frame or grid will display. If set to Gaps, table cells will be separated by a gap of your choice, allowing background colors and images to show through. If set to Lines, table cells will be separated by a line of your choice.', 'dvmd-table-maker');

        $f['tbl_frame_type']                =   array(
            'label'                         =>  esc_html__('Frame: Type', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_frame',
            'type'                          => 'multiple_buttons',
            'default'                       => 'gaps',
            'options'                       =>  array(
                'none'                      =>  array(
                    'title'                 =>  esc_html__('None', 'dvmd-table-maker'),
                ),
                'gaps'                      =>  array(
                    'title'                 =>  esc_html__('Gaps', 'dvmd-table-maker'),
                ),
                'lines'                     =>  array(
                    'title'                 =>  esc_html__('Lines', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
        );

        $description = esc_html__(
            'Here you can set the column gap.', 'dvmd-table-maker');

        $f['tbl_frame_gap_col']             =   array(
            'label'                         =>  esc_html__('Gap: Column', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_frame',
            'show_if'                       =>  array(
                'tbl_frame_type'            => 'gaps',
            ),
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'px',
            'default'                       => '2px',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '50',
                'step'                      => '1',
            ),
        );

        $description = esc_html__(
            'Here you can set the row gap.', 'dvmd-table-maker');

        $f['tbl_frame_gap_row']             =   array(
            'label'                         =>  esc_html__('Gap: Row', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_frame',
            'show_if'                       =>  array(
                'tbl_frame_type'            => 'gaps',
            ),
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allowed_units'                 =>  array('%','em','rem','px','cm','mm','in','pt','pc','ex','vh','vw'),
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'px',
            'default'                       => '2px',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '50',
                'step'                      => '1',
            ),
        );

        $description = esc_html__(
            'Here you can set the line style.', 'dvmd-table-maker');

        $f['tbl_frame_line_style']          =   array(
            'label'                         =>  esc_html__('Line: Style', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_frame',
            'show_if'                       =>  array(
                'tbl_frame_type'            => 'lines',
            ),
            'type'                          => 'select',
            'default'                       => 'solid',
            'options'                       =>  et_builder_get_border_styles(),
        );

        $description = esc_html__(
            'Here you can set the line color.', 'dvmd-table-maker');

        $f['tbl_frame_line_color']          =   array(
            'label'                         =>  esc_html__('Line: Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_frame',
            'show_if'                       =>  array(
                'tbl_frame_type'            => 'lines',
            ),
            'type'                          => 'color-alpha',
        );

        $description = esc_html__(
            'Here you can set the line width.', 'dvmd-table-maker');

        $f['tbl_frame_line_width']          =   array(
            'label'                         =>  esc_html__('Line: Width', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_frame',
            'show_if'                       =>  array(
                'tbl_frame_type'            => 'lines',
            ),
            'type'                          => 'range',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default_unit'                  => 'px',
            'default'                       => '1px',
            'range_settings'                =>  array(
                'min'                       => '0',
                'max'                       => '50',
                'step'                      => '1',
            ),
        );




        //-----------------------------------------------------------//
        //---------------------- Table Stripes ----------------------//
        //-----------------------------------------------------------//


        $description = esc_html__(
            'Here you can choose to apply a horizontal or vertical stripes effect to the table.', 'dvmd-table-maker');

        // This field and it’s options (ie. Horz = 'on') were named...
        // in order to maintain some backwards compatibility.
        $f['tbl_stripes_active']            =   array(
            'label'                         =>  esc_html__('Stripes: Mode', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_stripes',
            'type'                          => 'multiple_buttons',
            'default'                       => 'off',
            'options'                       =>  array(
                'off'                       =>  array('title' => esc_html__('None', 'dvmd-table-maker')),
                'on'                        =>  array('title' => esc_html__('Horz', 'dvmd-table-maker')),
                'vert'                      =>  array('title' => esc_html__('Vert', 'dvmd-table-maker')),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
        );

        $description = esc_html__(
            'Here you can choose the direction of the stripes effect at responsive sizes.', 'dvmd-table-maker');

        $f['tbl_stripes_responsive']        =   array(
            'label'                         =>  esc_html__('Stripes: Responsive Mode', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_stripes',
            'type'                          => 'multiple_buttons',
            'default'                       => 'horz',
            'options'                       =>  array(
                'off'                       =>  array('title' => esc_html__('None', 'dvmd-table-maker')),
                'horz'                      =>  array('title' => esc_html__('Horz', 'dvmd-table-maker')),
                'vert'                      =>  array('title' => esc_html__('Vert', 'dvmd-table-maker')),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if_not'                   =>  array(
                'tbl_stripes_active'        => 'off',
            ),
            'show_if'                       =>  array(
                'tbl_responsive_mode'       => 'on',
                'tbl_responsive_breakpoint' =>  array('max_width_980', 'max_width_767'),
            ),
        );

        $description = esc_html__(
            'Here you can choose whether the stripes effect is applied to odd or even rows and columns.', 'dvmd-table-maker');

        $f['tbl_stripes_order']             =   array(
            'label'                         =>  esc_html__('Stripes: Order', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_stripes',
            'type'                          => 'multiple_buttons',
            'default'                       => 'even',
            'options'                       =>  array(
                'even'                      =>  array(
                    'title'                 =>  esc_html__('Even', 'dvmd-table-maker'),
                ),
                'odd'                       =>  array(
                    'title'                 =>  esc_html__('Odd', 'dvmd-table-maker'),
                ),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if_not'                   =>  array(
                'tbl_stripes_active'        => 'off',
            ),
        );

        $description = esc_html__(
            'Here you can choose the type of stripes effect to apply. When Tint is selected the stripes effect will be a darker or lighter version of the existing table colors. When Color is selected you can choose a custom color for the stripes effect. When Blend is selected, the color you choose for the stripes effect will be blended with the exisiting table colors.', 'dvmd-table-maker');

        $f['tbl_stripes_effect']            =   array(
            'label'                         =>  esc_html__('Stripes: Effect', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_stripes',
            'type'                          => 'multiple_buttons',
            'default'                       => 'tint',
            'options'                       =>  array(
                'tint'                      =>  array('title' => esc_html__('Tint', 'dvmd-table-maker')),
                'blend'                     =>  array('title' => esc_html__('Blend', 'dvmd-table-maker')),
                'color'                     =>  array('title' => esc_html__('Color', 'dvmd-table-maker')),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if_not'                   =>  array(
                'tbl_stripes_active'        => 'off',
            ),
        );

        $description = esc_html__(
            'Here you can set the stripes color as a tint of the existing table colors. Negative values will darken the existing colors. Positive values will lighten the existing colors.', 'dvmd-table-maker');

        $f['tbl_stripes_tint']              =   array(
            'label'                         =>  esc_html__('Stripes: Tint', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_stripes',
            'type'                          => 'range',
            'unitless'                      =>  true,
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default'                       => '-5',
            'range_settings'                =>  array(
                'min'                       => '-100',
                'max'                       => '100',
                'step'                      => '1',
            ),
            'show_if_not'                   =>  array(
                'tbl_stripes_active'        => 'off',
            ),
            'show_if'                       =>  array(
                'tbl_stripes_effect'        => 'tint',
            ),
        );

        $description = esc_html__(
            'Here you can set a custom color for the stripes effect. You can use the color selector’s transparency slider to create subtle color effects.', 'dvmd-table-maker');

        $f['tbl_stripes_color']             =   array(
            'label'                         =>  esc_html__('Stripes: Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_stripes',
            'type'                          => 'color-alpha',
            'default'                       => '#eaedee',
            'show_if_not'                   =>  array(
                'tbl_stripes_active'        => 'off',
            ),
            'show_if'                       =>  array(
                'tbl_stripes_effect'        =>  array('color', 'blend'),
            ),
        );

        $description = esc_html__(
            'Here you can choose which parts of the table the stripes effect will be applied to.', 'dvmd-table-maker');

        $f['tbl_stripes_apply']             =   array(
            'label'                         =>  esc_html__('Stripes: Apply To', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_stripes',
            'type'                          => 'multiple_checkboxes',
            'default'                       => 'on|on|on|on|on',
            'options'                       =>  array(
                'tdata'                     =>  esc_html__('Table Body', 'dvmd-table-maker'),
                'chead'                     =>  esc_html__('Column Header', 'dvmd-table-maker'),
                'cfoot'                     =>  esc_html__('Column Footer', 'dvmd-table-maker'),
                'rhead'                     =>  esc_html__('Row Header', 'dvmd-table-maker'),
                'rfoot'                     =>  esc_html__('Row Footer', 'dvmd-table-maker'),
            ),
            'show_if_not'                   =>  array(
                'tbl_stripes_active'        => 'off',
            ),
        );




        //---------------------------------------------------------//
        //---------------------- Table Hover ----------------------//
        //---------------------------------------------------------//


        $description = esc_html__(
            'Here you can choose to apply a horizontal or vertical hover effect to the table.', 'dvmd-table-maker');

        $f['tbl_hover_active']              =   array(
            'label'                         =>  esc_html__('Hover: Mode', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_hover',
            'type'                          => 'multiple_buttons',
            'default'                       => 'off',
            'options'                       =>  array(
                'off'                       =>  array('title' => esc_html__('None', 'dvmd-table-maker')),
                'horz'                      =>  array('title' => esc_html__('Horz', 'dvmd-table-maker')),
                'vert'                      =>  array('title' => esc_html__('Vert', 'dvmd-table-maker')),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
        );

        $description = esc_html__(
            'Here you can choose the direction of the hover effect at responsive sizes.', 'dvmd-table-maker');

        $f['tbl_hover_responsive']          =   array(
            'label'                         =>  esc_html__('Hover: Responsive Mode', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_hover',
            'type'                          => 'multiple_buttons',
            'default'                       => 'horz',
            'options'                       =>  array(
                'off'                       =>  array('title' => esc_html__('None', 'dvmd-table-maker')),
                'horz'                      =>  array('title' => esc_html__('Horz', 'dvmd-table-maker')),
                'vert'                      =>  array('title' => esc_html__('Vert', 'dvmd-table-maker')),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if_not'                   =>  array(
                'tbl_hover_active'          => 'off',
            ),
            'show_if'                       =>  array(
                'tbl_responsive_mode'       => 'on',
                'tbl_responsive_breakpoint' =>  array('max_width_980', 'max_width_767'),
            ),
        );

        $description = esc_html__(
            'Here you can choose the type of hover effect to apply. When Tint is selected the hover effect will be a darker or lighter version of the existing table colors. When Color is selected you can choose a custom color for the hover effect. When Blend is selected, the color you choose for the hover effect will be blended with the exisiting table colors.', 'dvmd-table-maker');

        $f['tbl_hover_effect']              =   array(
            'label'                         =>  esc_html__('Hover: Effect', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_hover',
            'type'                          => 'multiple_buttons',
            'default'                       => 'tint',
            'options'                       =>  array(
                'tint'                      =>  array('title' => esc_html__('Tint', 'dvmd-table-maker')),
                'blend'                     =>  array('title' => esc_html__('Blend', 'dvmd-table-maker')),
                'color'                     =>  array('title' => esc_html__('Color', 'dvmd-table-maker')),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if_not'                   =>  array(
                'tbl_hover_active'          => 'off',
            ),
        );

        $description = esc_html__(
            'Here you can set the hover color as a tint of the existing table colors. Negative values will darken the existing colors. Positive values will lighten the existing colors.', 'dvmd-table-maker');

        $f['tbl_hover_tint']                =   array(
            'label'                         =>  esc_html__('Hover: Tint', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_hover',
            'type'                          => 'range',
            'unitless'                      =>  true,
            'allow_empty'                   =>  false,
            'validate_unit'                 =>  true,
            'default'                       => '-10',
            'range_settings'                =>  array(
                'min'                       => '-100',
                'max'                       => '100',
                'step'                      => '1',
            ),
            'show_if_not'                   =>  array(
                'tbl_hover_active'          => 'off',
            ),
            'show_if'                       =>  array(
                'tbl_hover_effect'          => 'tint',
            ),
        );

        $description = esc_html__(
            'Here you can set a custom color for the hover effect. You can use the color selector’s transparency slider to create subtle color effects.', 'dvmd-table-maker');

        $f['tbl_hover_color']               =   array(
            'label'                         =>  esc_html__('Hover: Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_hover',
            'type'                          => 'color-alpha',
            'default'                       => '#dde0e2',
            'show_if_not'                   =>  array(
                'tbl_hover_active'          => 'off',
            ),
            'show_if'                       =>  array(
                'tbl_hover_effect'          =>  array('color', 'blend'),
            ),
        );

        $description = esc_html__(
            'Here you can choose which parts of the table the hover effect will be applied to.', 'dvmd-table-maker');

        $f['tbl_hover_apply']               =   array(
            'label'                         =>  esc_html__('Hover: Apply To', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_hover',
            'type'                          => 'multiple_checkboxes',
            'default'                       => 'on|on|on|on|on',
            'options'                       =>  array(
                'tdata'                     =>  esc_html__('Table Body', 'dvmd-table-maker'),
                'chead'                     =>  esc_html__('Column Header', 'dvmd-table-maker'),
                'cfoot'                     =>  esc_html__('Column Footer', 'dvmd-table-maker'),
                'rhead'                     =>  esc_html__('Row Header', 'dvmd-table-maker'),
                'rfoot'                     =>  esc_html__('Row Footer', 'dvmd-table-maker'),
            ),
            'show_if_not'                   =>  array(
                'tbl_hover_active'          => 'off',
            ),
        );



        //----------------------------------------------------------//
        //---------------------- Cell – Table ----------------------//
        //----------------------------------------------------------//


        $description = esc_html__(
            'Here you can set the table cell background color. This setting can be adjusted per column under the <b>Column Settings > Design > Column Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_tcell_cell_color']          =   array(
            'label'                         =>  esc_html__('Tbl: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_tcell_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              => '#f6f9fb',
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of table cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Column Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_tcell_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('Tbl: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_tcell_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of table cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Column Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_tcell_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('Tbl: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_tcell_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the table cell padding. This setting can be adjusted per column under the <b>Column Settings > Design > Column Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_tcell_cell_padding']        =   array(
            'label'                         =>  esc_html__('Tbl: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_tcell_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            'default'                       => '',
            'default_on_front'              => '10px|10px|10px|10px',
        );



        //------------------------------------------------------------------//
        //---------------------- Cell – Column Header ----------------------//
        //------------------------------------------------------------------//


        $description = esc_html__(
            'Here you can set the column header cell background color. This setting can be adjusted per column under the <b>Column Settings > Design > Column Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_chead_cell_color']          =   array(
            'label'                         =>  esc_html__('CH: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_chead_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              =>  et_builder_accent_color(), // '#6b35b6'
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of column header cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Column Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_chead_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('CH: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_chead_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of column header cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Column Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_chead_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('CH: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_chead_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the column header cell padding. This setting can be adjusted per column under the <b>Column Settings > Design > Column Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_chead_cell_padding']        =   array(
            'label'                         =>  esc_html__('CH: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_chead_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            //'default'                       => '',
            //'default_on_front'              => '|||',
        );



        //------------------------------------------------------------------//
        //---------------------- Cell – Column Footer ----------------------//
        //------------------------------------------------------------------//


        $description = esc_html__(
            'Here you can set the column footer cell background color. This setting can be adjusted per column under the <b>Column Settings > Design > Column Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_cfoot_cell_color']          =   array(
            'label'                         =>  esc_html__('CF: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_cfoot_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              => '#d7e2ed',
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of column footer cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Column Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_cfoot_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('CF: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_cfoot_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of column footer cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Column Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_cfoot_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('CF: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_cfoot_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the column footer cell padding. This setting can be adjusted per column under the <b>Column Settings > Design > Column Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_cfoot_cell_padding']        =   array(
            'label'                         =>  esc_html__('CF: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_cfoot_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            //'default'                       => '',
            //'default_on_front'              => '|||',
        );



        //---------------------------------------------------------------//
        //---------------------- Cell – Row Header ----------------------//
        //---------------------------------------------------------------//


        $description = esc_html__(
            'Here you can set the row header cell background color. This setting can be adjusted per column under the <b>Column Settings > Design > Row Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rhead_cell_color']          =   array(
            'label'                         =>  esc_html__('RH: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rhead_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              =>  et_builder_accent_color(), // '#1fc3ab'
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of row header cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Row Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rhead_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('RH: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rhead_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of row header cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Row Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rhead_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('RH: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rhead_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the row header cell padding. This setting can be adjusted per column under the <b>Column Settings > Design > Row Header Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rhead_cell_padding']        =   array(
            'label'                         =>  esc_html__('RH: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rhead_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            //'default'                       => '',
            //'default_on_front'              => '|||',
        );



        //---------------------------------------------------------------//
        //---------------------- Cell – Row Footer ----------------------//
        //---------------------------------------------------------------//


        $description = esc_html__(
            'Here you can set the row footer cell background color. This setting can be adjusted per column under the <b>Column Settings > Design > Row Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rfoot_cell_color']          =   array(
            'label'                         =>  esc_html__('RF: Background Color', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'color_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rfoot_cell',
            'type'                          => 'color-alpha',
            'hover'                         => 'tabs',
            'default'                       => '',
            'default_on_front'              => '#d7e2ed',
        );

        $description = esc_html__(
            'Here you can set the horizontal alignment of row footer cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Row Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rfoot_cell_align_horz']     =   array(
            'label'                         =>  esc_html__('RF: Horizontal Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rfoot_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the vertical alignment of row footer cell content. This setting can be adjusted per column under the <b>Column Settings > Design > Row Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rfoot_cell_align_vert']     =   array(
            'label'                         =>  esc_html__('RF: Vertical Alignment', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rfoot_cell',
            'type'                          => 'text_align',
            'options'                       =>  et_builder_get_text_orientation_options(array('justified')),
        );

        $description = esc_html__(
            'Here you can set the row footer cell padding. This setting can be adjusted per column under the <b>Column Settings > Design > Row Footer Cells</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rfoot_cell_padding']        =   array(
            'label'                         =>  esc_html__('RF: Padding', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'layout',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rfoot_cell',
            'type'                          => 'custom_padding',
            'mobile_options'                =>  true,
            'responsive'                    =>  true,
            //'default'                       => '',
            //'default_on_front'              => '|||',
        );



        //--------------------------------------------------//
        //---------------------- Text ----------------------//
        //--------------------------------------------------//


        $description = esc_html__(
            'Here you can choose whether to allow table text to wrap to multiple lines. This setting can be adjusted per column under the <b>Column Settings > Design > Column Text</b> toggle.', 'dvmd-table-maker');

        $f['tbl_tcell_text_wrap']           =   array(
            'label'                         =>  esc_html__('Tbl: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_tcell_text',
            'type'                          => 'multiple_buttons',
            'default'                       => 'normal',
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
            'Here you can choose whether to allow column header text to wrap to multiple lines. This setting can be adjusted per column under the <b>Column Settings > Design > Column Header Text</b> toggle.', 'dvmd-table-maker');

        $f['tbl_chead_text_wrap']           =   array(
            'label'                         =>  esc_html__('CH: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_chead_text',
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
            'Here you can choose to enable a column header heading level.', 'dvmd-table-maker');

        $f['tbl_chead_text_level_active']   =   array(
            'label'                         =>  esc_html__('CH: Heading Level', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_chead_text',
            'type'                          => 'yes_no_button',
            'default'                       => 'off',
            'options'                       =>  array(
                'on'                        =>  esc_html__('On', 'dvmd-table-maker'),
                'off'                       =>  esc_html__('Off', 'dvmd-table-maker'),
            ),
        );

        $f['tbl_chead_text_level']          =   array(
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_chead_text',
            'type'                          => 'multiple_buttons',
            'default'                       => 'h3',
            'options'                       =>  array(
                'h1'                        =>  array(
                    'title'                 =>  esc_html__('h1', 'dvmd-table-maker'),
                    'icon'                  => 'text-h1',
                ),
                'h2'                        =>  array(
                    'title'                 =>  esc_html__('h2', 'dvmd-table-maker'),
                    'icon'                  => 'text-h2',
                ),
                'h3'                        =>  array(
                    'title'                 =>  esc_html__('h3', 'dvmd-table-maker'),
                    'icon'                  => 'text-h3',
                ),
                'h4'                        =>  array(
                    'title'                 =>  esc_html__('h4', 'dvmd-table-maker'),
                    'icon'                  => 'text-h4',
                ),
                'h5'                        =>  array(
                    'title'                 =>  esc_html__('h5', 'dvmd-table-maker'),
                    'icon'                  => 'text-h5',
                ),
                'h6'                        =>  array(
                    'title'                 =>  esc_html__('h6', 'dvmd-table-maker'),
                    'icon'                  => 'text-h6',
                ),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if'                           =>  array(
                'tbl_chead_text_level_active'   => 'on',
            ),
        );

        $description = esc_html__(
            'Using this setting can prevent screen readers from reading your table. We recommend leaving this off as it may be removed in a future version.', 'dvmd-table-maker');

        $f['tbl_chead_text_level_warning']  =   array(
            'message'                       =>  $description,
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_chead_text',
            'type'                          => 'warning',
            'value'                         =>  true,
            'display_if'                    =>  true,
            'show_if'                       =>  array(
                'tbl_chead_text_level_active'   => 'on',
            ),
        );

        $description = esc_html__(
            'Here you can choose whether to allow column footer text to wrap to multiple lines. This setting can be adjusted per column under the <b>Column Settings > Design > Column Footer Text</b> toggle.', 'dvmd-table-maker');

        $f['tbl_cfoot_text_wrap']           =   array(
            'label'                         =>  esc_html__('CF: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_cfoot_text',
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
            'Here you can choose whether to allow row header text to wrap to multiple lines. This setting can be adjusted per column under the <b>Column Settings > Design > Row Header Text</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rhead_text_wrap']           =   array(
            'label'                         =>  esc_html__('RH: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rhead_text',
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
            'Here you can choose to enable a row header heading level.', 'dvmd-table-maker');

        $f['tbl_rhead_text_level_active']   =   array(
            'label'                         =>  esc_html__('RH: Heading Level', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'configuration',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rhead_text',
            'type'                          => 'yes_no_button',
            'default'                       => 'off',
            'options'                       =>  array(
                'on'                        =>  esc_html__('On', 'dvmd-table-maker'),
                'off'                       =>  esc_html__('Off', 'dvmd-table-maker'),
            ),
        );

        $f['tbl_rhead_text_level']          =   array(
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rhead_text',
            'type'                          => 'multiple_buttons',
            'default'                       => 'h3',
            'options'                       =>  array(
                'h1'                        =>  array(
                    'title'                 =>  esc_html__('h1', 'dvmd-table-maker'),
                    'icon'                  => 'text-h1',
                ),
                'h2'                        =>  array(
                    'title'                 =>  esc_html__('h2', 'dvmd-table-maker'),
                    'icon'                  => 'text-h2',
                ),
                'h3'                        =>  array(
                    'title'                 =>  esc_html__('h3', 'dvmd-table-maker'),
                    'icon'                  => 'text-h3',
                ),
                'h4'                        =>  array(
                    'title'                 =>  esc_html__('h4', 'dvmd-table-maker'),
                    'icon'                  => 'text-h4',
                ),
                'h5'                        =>  array(
                    'title'                 =>  esc_html__('h5', 'dvmd-table-maker'),
                    'icon'                  => 'text-h5',
                ),
                'h6'                        =>  array(
                    'title'                 =>  esc_html__('h6', 'dvmd-table-maker'),
                    'icon'                  => 'text-h6',
                ),
            ),
            'toggleable'                    =>  false,
            'multi_selection'               =>  false,
            'show_if'                           =>  array(
                'tbl_rhead_text_level_active'   => 'on',
            ),
        );

        $description = esc_html__(
            'Using this setting can prevent screen readers from reading your table. We recommend leaving this off as it may be removed in a future version.', 'dvmd-table-maker');

        $f['tbl_rhead_text_level_warning']  =   array(
            'message'                       =>  $description,
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rhead_text',
            'type'                          => 'warning',
            'value'                         =>  true,
            'display_if'                    =>  true,
            'show_if'                       =>  array(
                'tbl_rhead_text_level_active'   => 'on',
            ),
        );

        $description = esc_html__(
            'Here you can choose whether to allow row footer text to wrap to multiple lines. This setting can be adjusted per column under the <b>Column Settings > Design > Row Footer Text</b> toggle.', 'dvmd-table-maker');

        $f['tbl_rfoot_text_wrap']           =   array(
            'label'                         =>  esc_html__('RF: Text Wrap', 'dvmd-table-maker'),
            'description'                   =>  $description,
            'option_category'               => 'font_option',
            'tab_slug'                      => 'advanced',
            'toggle_slug'                   => 'tbl_rfoot_text',
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



    //-------------------------------------------------------------//
    //---------------------- Computed Fields ----------------------//
    //-------------------------------------------------------------//


    /**
     * Converts gallery image ids into urls.
     * Used by the Visual Builder.
     *
     * @since   3.0.0
     * @access  static
     *
     * @param   array   $args {
     *   @type  string  $tbl_image_ids
     *   @type  string  $tbl_image_quality
     * }
     * @param   array   $conditional_tags
     * @param   array   $current_page
     *
     * @return  string  Attachments data
     */
    static function dvmd_tm_get_image_src($args = array(), $conditional_tags = array(), $current_page = array()) {

        // Defaults.
        $defaults = array(
            'tbl_image_ids'     =>  array(),
            'tbl_image_quality' => 'medium',
        );

        // Parse defaults.
        $args = wp_parse_args($args, $defaults);

        // Image properties.
        $ids  = explode(',', $args['tbl_image_ids']);
        $size = $args['tbl_image_quality'];
        $src  = array();

        // Get the urls.
        foreach ($ids as $id) {
            $img = wp_get_attachment_image_src($id, $size);
            $img = ($img) ? esc_url($img[0]) : '{{placeholder}}';
            array_push($src, $img);
        }

        // Return.
        return implode(',', $src);
    }



    //------------------------------------------------------------//
    //---------------------- Render: Before ----------------------//
    //------------------------------------------------------------//


    /**
     * Sets up a global variables for passing child module
     * properties (TableMakerItem) to the parent (TableMaker).
     *
     * @since   3.0.0
     * @access  public
     *
     * @return  void
     */
    public function before_render() {

        // Column attributes.
        global $dvmd_tm_column_atts;
        $dvmd_tm_column_atts = array();

        // Column rows.
        global $dvmd_tm_column_rows;
        $dvmd_tm_column_rows = array();

        // Column buttons.
        global $dvmd_tm_column_buttons;
        $dvmd_tm_column_buttons = array();

        // Column colors.
        global $dvmd_tm_column_colors;
        $dvmd_tm_column_colors = array();
    }



    //----------------------------------------------------------//
    //---------------------- Render: Main ----------------------//
    //----------------------------------------------------------//


    /**
     * Render module output.
     *
     * @since   3.0.0
     * @access  public
     *
     * @param   array   $attrs        The list of unprocessed attributes.
     * @param   string  $content      The content being processed.
     * @param   string  $render_slug  The Slug of module that's used for rendering.
     *
     * @return  string  The module's rendered output.
     */
    function render($attrs, $content, $render_slug) {


        // Globals.
        global $dvmd_tm_column_atts;
        global $dvmd_tm_column_rows;
        global $dvmd_tm_column_buttons;
        global $dvmd_tm_column_colors;

        // Properties.
        $pr = $this->props;



        //----------------------------------------------------------//
        //---------------------- Rows Min/Max ----------------------//
        //----------------------------------------------------------//


        // Table.
        $trmins = $this->dvmd_tm_get_responsive_2('tbl_row_min_height');
        $trmaxs = $this->dvmd_tm_get_responsive_2('tbl_row_max_height');
        $rgrid = array('desktop'=>'', 'tablet'=>'', 'phone'=>'');
        $empty = array('desktop'=>'', 'tablet'=>'', 'phone'=>'');
        $rgrid['desktop'] .= $this->dvmd_tm_get_grid('desktop', $trmins, $trmaxs, $empty, $empty, '50px', 'auto');
        $rgrid['tablet']  .= $this->dvmd_tm_get_grid('tablet',  $trmins, $trmaxs, $empty, $empty, '50px', 'auto');
        $rgrid['phone']   .= $this->dvmd_tm_get_grid('phone',   $trmins, $trmaxs, $empty, $empty, '50px', 'auto');



        //-------------------------------------------------------------//
        //---------------------- Columns Min/Max ----------------------//
        //-------------------------------------------------------------//


        // Table.
        $tcmins = $this->dvmd_tm_get_responsive_2('tbl_column_min_width');
        $tcmaxs = $this->dvmd_tm_get_responsive_2('tbl_column_max_width');
        $cgrid  = array('desktop'=>'', 'tablet'=>'', 'phone'=>'');
        $rcount = 0;

        // Columns.
        foreach ($dvmd_tm_column_atts as $col) {
            $cmins = $col['mins'];
            $cmaxs = $col['maxs'];
            self::dvmd_tm_fleshout_responsive_1($cmins);
            self::dvmd_tm_fleshout_responsive_1($cmaxs);
            $cgrid['desktop'] .= $this->dvmd_tm_get_grid('desktop', $cmins, $cmaxs, $tcmins, $tcmaxs, '100px', '1fr');
            $cgrid['tablet']  .= $this->dvmd_tm_get_grid('tablet',  $cmins, $cmaxs, $tcmins, $tcmaxs, '100px', '1fr');
            $cgrid['phone']   .= $this->dvmd_tm_get_grid('phone',   $cmins, $cmaxs, $tcmins, $tcmaxs, '100px', '1fr');

            // Let’s get the row count too.
            $rcount = max($col['count'], $rcount);
        }



        //--------------------------------------------------------------//
        //---------------------- Table Attributes ----------------------//
        //--------------------------------------------------------------//


        $a = new stdClass;

        // Globals.
        $a->gColumnAtts    = $dvmd_tm_column_atts;
        $a->gColumnRows    = $dvmd_tm_column_rows;
        $a->gColumnButtons = $dvmd_tm_column_buttons;
        $a->gColumnColors  = $dvmd_tm_column_colors;

        // Cell colors.
        $a->cellColors = array();

        // Title & Description.
        $a->hasTitle            = false;
        $a->hasDescription      = false;
        $a->isTitleAbove        = ('above'  === $pr['tbl_title_position']);
        $a->isTitleBelow        = ('below'  === $pr['tbl_title_position']);
        $a->isTitleHidden       = ('hidden' === $pr['tbl_title_position']);
        $a->isDescriptionAbove  = ('above'  === $pr['tbl_description_position']);
        $a->isDescriptionBelow  = ('below'  === $pr['tbl_description_position']);
        $a->isDescriptionHidden = ('hidden' === $pr['tbl_description_position']);

        // Grids.
        $a->colGrid = $cgrid;
        $a->rowGrid = $rgrid;

        // Counts.
        $a->colCount = count($dvmd_tm_column_atts);
        $a->rowCount = $rcount;

        // Headers & Footers.
        $a->colHeadCount = intval($pr['tbl_column_header_count']);
        $a->colFootCount = intval($pr['tbl_column_footer_count']);
        $a->rowHeadCount = intval($pr['tbl_row_header_count']);
        $a->rowFootCount = intval($pr['tbl_row_footer_count']);
        $a->colHeadCount = max(0, min($a->colHeadCount, $a->rowCount-1));
        $a->rowHeadCount = max(0, min($a->rowHeadCount, $a->colCount-1));
        $a->colFootCount = max(0, min($a->colFootCount, $a->rowCount-1));
        $a->rowFootCount = max(0, min($a->rowFootCount, $a->colCount-1));
        $a->colFootStart = $a->rowCount-$a->colFootCount;
        $a->rowFootStart = $a->colCount-$a->rowFootCount;

        // Corner state.
        $a->hasFiller      = ('on' !== $pr['tbl_top_left_mode'] && $a->colHeadCount > 0 && $a->rowHeadCount > 0);
        $a->hasTopLeft     = ('on' === $pr['tbl_top_left_mode'] && $a->colHeadCount > 0 && $a->rowHeadCount > 0);
        $a->hasTopRight    = ($a->colHeadCount > 0 && $a->rowFootCount > 0);
        $a->hasBottomLeft  = ($a->rowHeadCount > 0 && $a->colFootCount > 0);
        $a->hasBottomRight = ($a->colFootCount > 0 && $a->rowFootCount > 0);

        // Corner types.
        $a->hasTLcHead = ($a->hasTopLeft     && 'chead' === $pr['tbl_top_left_style']);
        $a->hasTLrHead = ($a->hasTopLeft     && 'rhead' === $pr['tbl_top_left_style']);    //-> Unused.
        $a->hasTRcHead = ($a->hasTopRight    && 'chead' === $pr['tbl_top_right_style']);
        $a->hasTRrFoot = ($a->hasTopRight    && 'rfoot' === $pr['tbl_top_right_style']);   //-> Unused.
        $a->hasBLcFoot = ($a->hasBottomLeft  && 'cfoot' === $pr['tbl_bottom_left_style']); //-> Unused.
        $a->hasBLrHead = ($a->hasBottomLeft  && 'rhead' === $pr['tbl_bottom_left_style']);
        $a->hasBRcFoot = ($a->hasBottomRight && 'cfoot' === $pr['tbl_bottom_right_style']);
        $a->hasBRrFoot = ($a->hasBottomRight && 'rfoot' === $pr['tbl_bottom_right_style']);

        // Responsive.
        // This is all very verbose for legacy reasons.
        $a->breakPoint   =  $pr['tbl_responsive_breakpoint'];
        $a->isResponsive = ('on' === $pr['tbl_responsive_mode']);
        $a->isDesktop    = ($a->isResponsive && 'desktop'       === $a->breakPoint);
        $a->isTablet     = ($a->isResponsive && 'max_width_980' === $a->breakPoint);
        $a->isPhone      = ($a->isResponsive && 'max_width_767' === $a->breakPoint);
        $a->isResponsive = ($a->isResponsive && ($a->isDesktop || $a->isTablet || $a->isPhone));

        // Display as.
        $a->displayAs = $pr['tbl_responsive_display_as'];
        if (0 == $a->colHeadCount && 0 == $a->rowHeadCount) $a->displayAs = 'blocks';
        $a->isAccordion = ($a->isResponsive && 'accordion' === $a->displayAs);

        // Break by.
        $a->breakBy = $pr['tbl_responsive_break_by'];
        if (0 == $a->colHeadCount && $a->isAccordion) $a->breakBy = 'row';
        if (0 == $a->rowHeadCount && $a->isAccordion) $a->breakBy = 'column';
        if (!$a->isResponsive) $a->breakBy = 'row';

        // Orientation. (relative coordinates)
        $a->isFlipped  = ($a->isResponsive && 'column' === $a->breakBy);
        $a->xCount     = ($a->isFlipped) ? $a->rowCount : $a->colCount;
        $a->yCount     = ($a->isFlipped) ? $a->colCount : $a->rowCount;
        $a->xHeadCount = ($a->isFlipped) ? $a->rowHeadCount : $a->colHeadCount;
        $a->yHeadCount = ($a->isFlipped) ? $a->colHeadCount : $a->rowHeadCount;

        // Media queries.
        if ($a->isTablet) {
            $a->mquery1 = '@media only screen and (max-width: 980px)';
            $a->mquery2 = '@media only screen and (min-width: 981px)';
        }
        elseif ($a->isPhone) {
            $a->mquery1 = '@media only screen and (max-width: 767px)';
            $a->mquery2 = '@media only screen and (min-width: 768px)';
        }
        else {
            $a->mquery1 = null;
            $a->mquery2 = null;
        }

        // Flags.
        $a->hasIcons   = false;
        $a->hasButtons = false;
        $a->hasImages  = false;

        // Button.
        $i = $this->dvmd_tm_get_responsive_1('button_icon');
        $a->tableButton = $this->render_button(array(
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

        // Accordion Icon.
        $a->accordionIcon =
           '<span class="dvmd_tm_toggle" tabindex="0" role="button" aria-pressed="false">
                <i aria-hidden="true"></i>
            </span>';



        //---------------------------------------------------------------//
        //---------------------- Build Table Array ----------------------//
        //---------------------------------------------------------------//


        // Table array.
        $table = array_fill(0, $a->rowCount, null);
        $table = array_fill(0, $a->colCount, $table);


        // Loop Columns.
        for ($c = 0; $c < $a->colCount; $c++) {

            // Column atts.
            $a->columnButton = $dvmd_tm_column_buttons[$c];
            $a->columnClass  = $dvmd_tm_column_atts[$c]['class'];

            // Loop Rows.
            for ($r = 0; $r < $a->rowCount; $r++) {

                // Bail.
                if (null !== $table[$c][$r]) continue;

                // Cell data.
                if ($a->hasFiller && $c < $a->rowHeadCount && $r < $a->colHeadCount) {
                    $a->cellData = '<cell class="dvmd_tm_filler"></cell>';
                } else {
                    $a->cellData = isset($dvmd_tm_column_rows[$c][$r]) ? $dvmd_tm_column_rows[$c][$r] : '<cell></cell>';
                }

                // Cell render.
                $this->dvmd_tm_render_cell_data ($cell, $a, $c, $r);
                $this->dvmd_tm_render_cell_inner($cell, $a, $c, $r);
                $this->dvmd_tm_render_cell_outer($cell, $a, $c, $r, $table);
            }
        }



        //--------------------------------------------------------------//
        //---------------------- Build Table Body ----------------------//
        //--------------------------------------------------------------//


        if (!$a->isResponsive) :

            // Builds the table body.
            // Table cells are wrapped in discreet rows.
            $body = '';
            for ($r = 0; $r < $a->yCount; $r++) {

                $cells = '';
                for ($c = 0; $c < $a->xCount; $c++) $cells .= $table[$c][$r];

                $body .= sprintf(
                   '<div class="dvmd_tm_trow dvmd_tm_trow_%s" role="row" aria-rowindex="%s">%s</div>',
                    /* 01 */  esc_attr($r),
                    /* 02 */  esc_attr($r+1),
                    /* 03 */  $cells
                );
            }

        else :

            // Block count.
            $a->blockCount = $a->yCount-$a->xHeadCount;

            // Accordion.
            if ($a->isAccordion && 'opened' === $pr['tbl_toggle_state']) {
                $tindex = $pr['tbl_toggle_index'];
                $tindex = (is_numeric($tindex)) ? intval($tindex)-1 : 0;
                $tindex = max(0, min($a->blockCount-1, $tindex));
            } else {
                $tindex = -1;
            }

            // Prepares the table headers.
            // Table header cells are wrapped in discreet rows.
            $heads1 = $heads2 = '';
            for ($r = 0; $r < $a->xHeadCount; $r++) {

                $cells = '';
                for ($c = 0; $c < $a->xCount; $c++) $cells .= ($a->isFlipped) ? $table[$r][$c] : $table[$c][$r];

                $heads1 .= sprintf(
                   '<div class="dvmd_tm_trow dvmd_tm_trow_%s" role="row" aria-rowindex="%s">%s</div>',
                    /* 01 */  esc_attr($r),
                    /* 02 */  esc_attr($r+1),
                    /* 03 */  $cells
                );

                $heads2 .= sprintf(
                   '<div class="dvmd_tm_trow dvmd_tm_trow_%s" role="row" aria-rowindex="%s" aria-hidden="true">%s</div>',
                    /* 01 */  esc_attr($r),
                    /* 02 */  esc_attr($r+1),
                    /* 03 */  $cells
                );
            }

            // Separates the table into discreet blocks (rowgroups).
            // Each block includes the table headers and one row of table data.
            // The first block’s table header’s are visible, the rest are hidden.
            // Table data cells are wrapped in discreet rows.
            $body = '';
            for ($b = 0; $b < $a->blockCount; $b++) {

                $cells = '';
                $r = $a->xHeadCount + $b;
                for ($c = 0; $c < $a->xCount; $c++) $cells .= ($a->isFlipped) ? $table[$r][$c] : $table[$c][$r];

                $body .= sprintf(
                   '<div class="dvmd_tm_tblock dvmd_tm_tblock_%s%s%s" role="rowgroup">
                        %s<div class="dvmd_tm_trow dvmd_tm_trow_%s" role="row" aria-rowindex="%s">%s</div>
                    </div>',
                    /* 01 */  esc_attr($b),
                    /* 02 */  ($a->isFlipped) ? ' dvmd_tm_cblock' : ' dvmd_tm_rblock',
                    /* 03 */  ($b == $tindex) ? ' dvmd_tm_active' : '',
                    /* 04 */  (0 === $b) ? $heads1 : $heads2,
                    /* 05 */  esc_attr($r),
                    /* 06 */  esc_attr($r+1),
                    /* 07 */  $cells
                );
            }

        endif;



        //-------------------------------------------------------------------//
        //---------------------- Title & Description ------------------------//
        //-------------------------------------------------------------------//


        // Module index.
        $module_index = self::_get_index(array(self::INDEX_MODULE_ORDER, $render_slug));


        // Title.
        $title = ('on' === $pr['tbl_title_mode']) ? $pr['tbl_title_text'] : '';
        $aria_label = '';

        if ('' !== $title) {

            // Hidden.
            if ($a->isTitleHidden) {
                $aria_label = sprintf('aria-label="%s"', esc_attr($title));
                $title = '';

            // Visible.
            } else {
                $a->hasTitle = true;
                $aria_label = sprintf('aria-labelledby="dvmd_tm_title_%s"', esc_attr($module_index));
                $title = sprintf('<%1$s id="dvmd_tm_title_%2$s" class="dvmd_tm_title">%3$s</%1$s>',
                    /* 01 */ esc_html($pr['tbl_title_level']),
                    /* 02 */ esc_html($module_index),
                    /* 03 */ esc_html($title)
                );
            }
        }


        // Description.
        $description = ('on' === $pr['tbl_description_mode']) ? $pr['tbl_description_text'] : '';
        $aria_description = '';

        if ('' !== $description) {

            // Hidden.
            if ($a->isDescriptionHidden) {
                $aria_description = sprintf('aria-description="%s"', esc_attr($description));
                $description = '';

            // Visible.
            } else {
                $a->hasDescription = true;
                $aria_description = sprintf('aria-describedby="dvmd_tm_description_%s"', esc_attr($module_index));
                $description = sprintf('<p id="dvmd_tm_description_%s" class="dvmd_tm_description">%s</p>',
                    /* 01 */ esc_html($module_index),
                    /* 02 */ esc_html($description)
                );
            }
        }



        //--------------------------------------------------------------//
        //---------------------- Table & Output ------------------------//
        //--------------------------------------------------------------//


        // Render styles.
        $this->dvmd_tm_render_styles($a);


        // Table.
        $table = '';
        if ('' !== $body) {
            $table = sprintf(
               '<div
                    role="table"
                    class="dvmd_tm_table dvmd_preload%s%s%s%s"
                    %s
                    %s
                    aria-rowcount="%s"
                    aria-colcount="%s">
                        %s
                </div>',
                /* 01 */ ($a->isResponsive) ? esc_attr(" dvmd_tm_{$a->breakPoint}") : '',
                /* 02 */ ($a->isResponsive) ? esc_attr(" dvmd_tm_breakby_{$a->breakBy}") : '',
                /* 03 */ ($a->isResponsive) ? esc_attr(" dvmd_tm_{$a->displayAs}") : '',
                /* 04 */ ('off' !== $pr['tbl_hover_active']) ? ' dvmd_tm_hover_enabled' : '',
                /* 05 */  $aria_label,
                /* 06 */  $aria_description,
                /* 07 */  esc_attr($a->yCount),
                /* 08 */  esc_attr($a->xCount),
                /* 09 */  $body
            );
        }


        // Title, description and table.
        $table = sprintf('%s%s%s%s%s',
            /* 01 */ ($a->isTitleBelow)        ? '' : et_core_intentionally_unescaped($title,       'html'),
            /* 02 */ ($a->isDescriptionBelow)  ? '' : et_core_intentionally_unescaped($description, 'html'),
            /* 03 */                                  et_core_intentionally_unescaped($table,       'html'),
            /* 04 */ ($a->isTitleAbove)        ? '' : et_core_intentionally_unescaped($title,       'html'),
            /* 05 */ ($a->isDescriptionAbove)  ? '' : et_core_intentionally_unescaped($description, 'html')
        );


        // Clean-up.
        unset($a);
        unset($dvmd_tm_column_atts);
        unset($dvmd_tm_column_rows);
        unset($dvmd_tm_column_buttons);
        unset($dvmd_tm_column_colors);

        // Add version class.
        $this->add_classname('dvmd_tm_version_' . str_replace('.', '_', DVMD_TM_PLUGIN_VERSION));

        // Return.
        return $table;

    }



    //---------------------------------------------------------------//
    //---------------------- Render: Cell Data ----------------------//
    //---------------------------------------------------------------//


    /**
     * Converts a single row of table column data into a DOMDocument.
     *
     * The data is wrapped in either an inner <div> and outer <div> element.
     * If the data comes pre-wrapped in a <cell> element, the <cell> attributes
     * are copied to the outer <div>. Only the first found <cell> is used.
     *
     * @since   3.0.2
     * @access  private
     *
     * @param   var      $dom   Unset var for DOMDocument. (byref)
     * @param   array    $a     Table attributes.
     * @param   integer  $c     Column index. (0 based)
     * @param   integer  $r     Row index (0 based)
     *
     * @return  void
     */
    private function dvmd_tm_render_cell_data(&$dom, $a, $c, $r) {

        // Properties.
        $pr  = $this->props;
        $tag = 'div';

        // Get header tags.
        if ('on' === $pr['tbl_chead_text_level_active'] || 'on' === $pr['tbl_rhead_text_level_active']) {

            // Type.
            if     ($c <  $a->rowHeadCount && $r <  $a->colHeadCount) : $t = $pr['tbl_top_left_style'];
            elseif ($c >= $a->rowFootStart && $r <  $a->colHeadCount) : $t = $pr['tbl_top_right_style'];
            elseif ($c <  $a->rowHeadCount && $r >= $a->colFootStart) : $t = $pr['tbl_bottom_left_style'];
            elseif ($r <  $a->colHeadCount) : $t = 'chead';
            elseif ($c <  $a->rowHeadCount) : $t = 'rhead';
            elseif ($r >= $a->colFootStart) : $t = 'cfoot';
            elseif ($c >= $a->rowFootStart) : $t = 'rfoot';
            else :                            $t = 'tdata';
            endif;

            // Tags.
            if ('chead' === $t && 'on' === $pr['tbl_chead_text_level_active']) $tag = $pr['tbl_chead_text_level'];
            if ('rhead' === $t && 'on' === $pr['tbl_rhead_text_level_active']) $tag = $pr['tbl_rhead_text_level'];
        }

        // Prepare table data.
        $data = trim($a->cellData);
        $data = "<{$tag}>{$data}</{$tag}>";

        // Get DOMDocument cells.
        if (!self::dvmd_tm_get_DOMDocument($dom, $data)) return;
        $cell = $dom->getElementsByTagName('cell');

        // Change cell tag type.
        if ($cell->length > 0) {
            $data = $dom->saveHTML($cell[0]);
            $data = self::dvmd_tm_str_replace_first('<cell', "<{$tag}", $data);
            $data = self::dvmd_tm_str_replace_last('</cell>', "</{$tag}>", $data);
        }

        // Prepare table data.
        $data = "<div>{$data}</div>";

        // Get DOMDocument.
        if (!self::dvmd_tm_get_DOMDocument($dom, $data)) return;
        $cell = $dom->documentElement;
        $data = $cell->childNodes[0];

        // Copy attributes to cell.
        $i = $data->attributes->length-1;
        for ($i; $i >= 0 ; $i--) {
            $att = $data->attributes[$i];
            $n   = $att->nodeName;
            $v   = $att->nodeValue;
            $cell->setAttribute(esc_html($n), esc_attr($v));
            $data->removeAttribute($att->nodeName);
        }

        // Add class to data.
        $data->setAttribute('class', 'dvmd_tm_cdata');
    }



    //----------------------------------------------------------------//
    //---------------------- Render: Cell Inner ----------------------//
    //----------------------------------------------------------------//


    /**
     * Processes a single table cell’s inner elements, including icons,…
     * images, and buttons – keeping any attributes set by the user.
     *
     * @since   3.1.0
     * @access  private
     *
     * @param   object   $cell  Table cell as DOMDocument. (byref)
     * @param   array    $a     Table attributes. (byref)
     * @param   integer  $c     Column index. (0 based)
     * @param   integer  $r     Row index (0 based)
     *
     * @return  void
     */
    private function dvmd_tm_render_cell_inner(&$cell, &$a, $c, $r) {


        // Properties.
        $pr = $this->props;
        $sl = "%%order_class%% .dvmd_tm_col_{$c}.dvmd_tm_row_{$r}";


        //---------------------------------------------------//
        //---------------------- Icons ----------------------//
        //---------------------------------------------------//


        // Get icon elements.
        $icons = $cell->getElementsByTagName('icon');
        $i = $icons->length-1;

        // Process icons.
        if ($i >= 0):

            // Raise flag.
            $a->hasIcons = true;

            for ($i; $i >= 0 ; $i--):

                // Get old and new elements.
                $old = $icons[$i];
                $elm = ($old->hasAttribute('href')) ? 'a' : 'span';
                $new = $cell->createElement($elm);

                // Set color.
                if ($old->hasAttribute('color')) {
                    $v = esc_html($old->getAttribute('color'));
                    $this->dvmd_tm_set_style_1("{$sl} .dvmd_tm_icon", "color: {$v} !important;");
                    $old->removeAttribute('color');
                }

                // Set hover color.
                if ($old->hasAttribute('color-hover')) {
                    $v = esc_html($old->getAttribute('color-hover'));
                    $this->dvmd_tm_set_style_1("{$sl} .dvmd_tm_icon:hover", "color: {$v} !important;");
                    $old->removeAttribute('color-hover');
                }

                // Set size.
                if ($old->hasAttribute('size')) {
                    $v = esc_html($old->getAttribute('size'));
                    $this->dvmd_tm_set_style_1("{$sl} .dvmd_tm_icon", "font-size: {$v} !important;");
                    $old->removeAttribute('size');
                }

                // Set hover size.
                if ($old->hasAttribute('size-hover')) {
                    $v = esc_html($old->getAttribute('size-hover'));
                    $this->dvmd_tm_set_style_1("{$sl} .dvmd_tm_icon:hover", "font-size: {$v} !important;");
                    $old->removeAttribute('size-hover');
                }

                // Set onclick.
                if ($old->hasAttribute('onclick')) {
                    $v = $old->getAttribute('onclick');
                    $new->setAttribute('onclick', $v);
                    $old->removeAttribute('onclick');
                }

                // Copy old atts to new.
                foreach ($old->attributes as $att) {
                    $n = $att->nodeName;
                    $v = $att->nodeValue;
                    $new->setAttribute(esc_html($n), esc_attr($v));
                }

                // Set nodeValue as icon class name.
                $icon  = $old->nodeValue;
                $icon  = ($icon) ? $icon : 'default';
                $class = $new->getAttribute('class');
                $new->setAttribute('class', trim(esc_attr("et-pb-icon dvmd_tm_icon ei ei-{$icon} {$class}")));

                // Replace old with new.
                $old->parentNode->replaceChild($new, $old);

            endfor;
        endif;


        //-----------------------------------------------------//
        //---------------------- Buttons ----------------------//
        //-----------------------------------------------------//


        // Get button elements.
        $buttons = $cell->getElementsByTagName('button');
        $i = $buttons->length-1;

        // Process buttons.
        if ($i >= 0):

            // Raise flag.
            $a->hasButtons = true;

            // Get table or column button.
            $column_button = $a->columnButton;
            $button = ($column_button['button']) ? $column_button['button'] : $a->tableButton;

            for ($i; $i >= 0 ; $i--):

                // Get old button element.
                $old = $buttons[$i];

                // Create new element.
                if (!self::dvmd_tm_get_DOMDocument($new, $button)) continue;
                $new = $new->documentElement;

                // Set text.
                $v = $old->nodeValue;
                $v = ($v) ? $v : $column_button['text'];
                $v = ('Default' !== $v) ? $v : $pr['tbl_button_text'];
                $new->nodeValue = esc_html($v);

                // Set class.
                $class1 = $new->getAttribute('class');
                $class2 = $old->getAttribute('class');
                $new->setAttribute('class', trim(esc_attr("{$class1} {$class2}")));
                $old->removeAttribute('class');

                // Set href.
                $v = $old->getAttribute('href');
                $v = ($v) ? $v : $column_button['url'];
                $v = ('#' !== $v) ? $v : $pr['tbl_button_url'];
                $new->setAttribute('href', esc_url_raw($v));
                $old->removeAttribute('href');

                // Set target.
                $v = $old->getAttribute('target');
                $v = ($v) ? $v : $column_button['target'];
                $v = ('default' !== $v) ? $v : $pr['tbl_button_target'];
                $v = ('default' !== $v) ? $v : '_self';
                $new->setAttribute('target', esc_attr($v));
                $old->removeAttribute('target');

                // Set onclick.
                if ($old->hasAttribute('onclick')) {
                    $v = $old->getAttribute('onclick');
                    $new->setAttribute('onclick', $v);
                    $old->removeAttribute('onclick');
                }

                // Copy old atts to new.
                foreach ($old->attributes as $att) {
                    $n = $att->nodeName;
                    $v = $att->nodeValue;
                    $new->setAttribute(esc_html($n), esc_attr($v));
                }

                // Replace old with new.
                $new = $cell->importNode($new, true);
                $old->parentNode->replaceChild($new, $old);

            endfor;
        endif;



        //----------------------------------------------------//
        //---------------------- Images ----------------------//
        //----------------------------------------------------//


        // Get image elements.
        $images = $cell->getElementsByTagName('image');
        $i = $images->length-1;

        // Process images.
        if ($i >= 0):

            // Raise flag.
            $a->hasImages = true;

            // Image ids.
            $ids = $pr['tbl_image_ids'];
            $ids = ($ids) ? $ids : '0';
            $ids = explode(',', $ids);

            for ($i; $i >= 0 ; $i--):

                // Get old and new elements.
                $old = $images[$i];
                $elm = ($old->hasAttribute('href')) ? 'a' : 'div';
                $new = $cell->createElement($elm);

                // Set onclick.
                if ($old->hasAttribute('onclick')) {
                    $v = $old->getAttribute('onclick');
                    $new->setAttribute('onclick', $v);
                    $old->removeAttribute('onclick');
                }

                // Copy old atts to new.
                foreach ($old->attributes as $att) {
                    $n = $att->nodeName;
                    $v = $att->nodeValue;
                    $new->setAttribute(esc_html($n), esc_attr($v));
                }

                // Get image.
                $id   = (is_numeric($old->nodeValue)) ? intval($old->nodeValue) : 1;
                $id   = max(1, min(count($ids), $id));
                $id   = $ids[$id-1];
                $size = $pr['tbl_image_quality'];
                $img  = wp_get_attachment_image_src($id, $size);
                $img  = ($img) ? esc_url($img[0]) : ET_BUILDER_PLACEHOLDER_LANDSCAPE_IMAGE_DATA;

                // Set image.
                $style = esc_html($new->getAttribute('style'));
                $new->setAttribute('style', "background-image: url('{$img}');{$style}");

                // Set title.
                if (!$old->hasAttribute('title')) {
                    $title = get_the_title($id);
                    $title = ('' !== $title) ? $title : esc_html__('Image', 'dvmd-table-maker');
                    $new->setAttribute('title', $title);
                }

                // Set alt.
                if (!$old->hasAttribute('alt')) {
                    $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                    $alt = ('' !== $alt) ? $alt : esc_html__('Image', 'dvmd-table-maker');
                    $new->setAttribute('alt', $alt);
                }

                // Set class.
                $class = $new->getAttribute('class');
                $new->setAttribute('class', trim(esc_attr("dvmd_tm_image {$class}")));

                // Replace old with new.
                $old->parentNode->replaceChild($new, $old);

            endfor;
        endif;
    }



    //----------------------------------------------------------------//
    //---------------------- Render: Cell Outer ----------------------//
    //----------------------------------------------------------------//


    /**
     * Sets a single table cell’s outer element attributes, including style,
     * classes, and aria-role, while keeping any attributes set by the user.
     * Generates CSS to position the cell on the desktop and responsive grids,
     * Populates the table array with the cell while allowing for col/row spans.
     *
     * This function is only called on the cell if its position in the table
     * array (represented by c/r) is equal to null. By default, all cells in
     * the table array are equal to null when the table array is initialised,
     * but some wil be 'not null' once filled by spanning cells, see below.
     *
     * Cells with col/row span are duplicated in the table array for each grid
     * position that they span. Some duplicate are used by screen-readers and
     * others are shown in the responsive blocks and accordion modes.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   object   $cell   Table cell as DOMDocument. (byref)
     * @param   array    $a      Table attributes.
     * @param   integer  $c      Column index. (0 based)
     * @param   integer  $r      Row index (0 based)
     * @param   array    $table  Table array. (byref)
     *
     * @return  void
     */
    private function dvmd_tm_render_cell_outer($cell, $a, $c, $r, &$table) {


        // Properties.
        $pr = $this->props;
        $cl = '';
        $sl = "%%order_class%% .dvmd_tm_col_{$c}.dvmd_tm_row_{$r}";
        $el = $cell->documentElement;

        // Type and corner.
        if     ($c <  $a->rowHeadCount && $r <  $a->colHeadCount) : $t = $pr['tbl_top_left_style'];     $p = 'tleft';
        elseif ($c >= $a->rowFootStart && $r <  $a->colHeadCount) : $t = $pr['tbl_top_right_style'];    $p = 'trght';
        elseif ($c <  $a->rowHeadCount && $r >= $a->colFootStart) : $t = $pr['tbl_bottom_left_style'];  $p = 'bleft';
        elseif ($c >= $a->rowFootStart && $r >= $a->colFootStart) : $t = $pr['tbl_bottom_right_style']; $p = 'brght';
        elseif ($r <  $a->colHeadCount) : $t = 'chead'; $p = '';
        elseif ($c <  $a->rowHeadCount) : $t = 'rhead'; $p = '';
        elseif ($r >= $a->colFootStart) : $t = 'cfoot'; $p = '';
        elseif ($c >= $a->rowFootStart) : $t = 'rfoot'; $p = '';
        else :                            $t = 'tdata'; $p = '';
        endif;

        // Type.
        $cellType      =  $t;
        $cellClass     = "dvmd_tm_{$t}";

        // Booleans.
        $isColHead     = ('chead' === $t);
        $isColFoot     = ('cfoot' === $t);
        $isRowHead     = ('rhead' === $t);
        $isRowFoot     = ('rfoot' === $t);
        $isTopLeft     = ('tleft' === $p);
        $isTopRight    = ('trght' === $p);
        $isBottomLeft  = ('bleft' === $p);
        $isBottomRight = ('brght' === $p);

        // Rowspan & Colspan
        $cspan = $el->getAttribute('colspan');
        $rspan = $el->getAttribute('rowspan');
        $el->removeAttribute('colspan');
        $el->removeAttribute('rowspan');
        $cspan = (is_numeric($cspan)) ? intval($cspan) : 1;
        $rspan = (is_numeric($rspan)) ? intval($rspan) : 1;
        $cspan = max(1, min($cspan, $a->colCount-$c));
        $rspan = max(1, min($rspan, $a->rowCount-$r));



        //-----------------------------------------------------------------//
        //---------------------- Position Attributes ----------------------//
        //-----------------------------------------------------------------//

        /**
         * Sets the cell’s role, colspan, rowspan, and class based upon it’s position in the table.
         *
         * Roles include: rowheader, columnheader, and cell.
         * Positions include: top-left, top-right, bottom-left, bottom-right, and table-body.
         * Colspan and rowspan are truncated as necessary so cells don’t cross table boundaries.
         */

        // Column header cells.
        if ($isColHead) {
            $role  = ($a->isFlipped) ? 'rowheader' : 'columnheader';
            $cspan = ($a->hasTRcHead) ? $cspan : min($cspan, $a->rowFootStart-$c);
            $rspan = min($rspan, $a->colHeadCount-$r);
            if ($isTopLeft) {
                $cl = 'dvmd_tm_top_left ';
                if ($a->isResponsive) $cspan = min($cspan, $a->rowHeadCount-$c);
            }
            elseif ($isTopRight) {
                $cl = 'dvmd_tm_top_right ';
            }
        }

        // Row header cells.
        elseif ($isRowHead) {
            $role  = ($a->isFlipped) ? 'columnheader' : 'rowheader';
            $rspan = ($a->hasBLrHead) ? $rspan : min($rspan, $a->colFootStart-$r);
            $cspan = min($cspan, $a->rowHeadCount-$c);
            if ($isTopLeft) {
                $cl = 'dvmd_tm_top_left ';
                if ($a->isResponsive) $rspan = min($rspan, $a->colHeadCount-$r);
            }
            elseif ($isBottomLeft) {
                $cl = 'dvmd_tm_bottom_left ';
            }
        }

        // Column footer cells.
        elseif ($isColFoot) {
            $role  = 'cell';
            $cspan = ($a->hasBRcFoot) ? $cspan : min($cspan, $a->rowFootStart-$c);
            $rspan = min($rspan, $a->rowCount-$r);
            if ($isBottomLeft) {
                $cl = 'dvmd_tm_bottom_left ';
                if ($a->isResponsive) $cspan = min($cspan, $a->rowHeadCount-$c);
            }
            elseif ($isBottomRight) {
                $cl = 'dvmd_tm_bottom_right ';
            }
        }

        // Row footer cells.
        elseif ($isRowFoot) {
            $role  = 'cell';
            $rspan = ($a->hasBRrFoot) ? $rspan : min($rspan, $a->colFootStart-$r);
            $cspan = min($cspan, $a->colCount-$c);
            if ($isTopRight) {
                $cl = 'dvmd_tm_top_right ';
                if ($a->isResponsive) $rspan = min($rspan, $a->colHeadCount-$r);
            }
            elseif ($isBottomRight) {
                $cl = 'dvmd_tm_bottom_right ';
            }
        }

        // Table body cells.
        else {
            $role  = 'cell';
            $cspan = min($cspan, $a->rowFootStart-$c);
            $rspan = min($rspan, $a->colFootStart-$r);
        }



        //------------------------------------------------------------------//
        //---------------------- Relative Coordinates ----------------------//
        //------------------------------------------------------------------//

        /**
         * Sets relative col/row and colspan/rowspan properties depending upon
         * the table orientation. Reduces the amount dupplicate code required.
         */

        if ($a->isFlipped) {
            $x = $r; $xspan = $rspan;
            $y = $c; $yspan = $cspan;
        } else{
            $x = $c; $xspan = $cspan;
            $y = $r; $yspan = $rspan;
        }



        //--------------------------------------------------------//
        //---------------------- Appearance ----------------------//
        //--------------------------------------------------------//

        /**
         * Processes a cell’s appearance attributes as set by the user.
         *
         * We use !important on most of these styles because they are the
         * last-word on cell appearance. We avoid using !important on the
         * background so that it can be overridden by stripes and hover.
         */

        // Hide cell.
        if ($el->hasAttribute('hide')) {
            if ('true' === $el->getAttribute('hide')) $el->setAttribute('aria-hidden', 'true');
            $el->removeAttribute('hide');
        }

        // Background color.
        if ($el->hasAttribute('background')) {
            $v = $el->getAttribute('background');
            $v = ('clear' === $v) ? 'rgba(0,0,0,0)' : esc_html($v);
            $this->dvmd_tm_set_style_1("{$sl}.dvmd_tm_tcell", "background: {$v};");
            $el->removeAttribute('background');

            // Add the color to the cell colors array. This allows the
            // colors to be processes by the stripes and hover functions.
            $a->cellColors[] = array('class' => $sl, $cellType => $v);
        }

        // Text color.
        if ($el->hasAttribute('color')) {
            $v = esc_html($el->getAttribute('color'));
            $this->dvmd_tm_set_style_1("{$sl} .dvmd_tm_cdata", "color: {$v} !important;");
            $el->removeAttribute('color');
        }

        // Border.
        if ($el->hasAttribute('border')) {
            $v = esc_html($el->getAttribute('border'));
            $this->dvmd_tm_set_style_1($sl, "border: {$v} !important;");
            $el->removeAttribute('border');
        }

        // Border-top.
        if ($el->hasAttribute('border-top')) {
            $v = esc_html($el->getAttribute('border-top'));
            $this->dvmd_tm_set_style_1($sl, "border-top: {$v} !important;");
            $el->removeAttribute('border-top');
        }

        // Border-right.
        if ($el->hasAttribute('border-right')) {
            $v = esc_html($el->getAttribute('border-right'));
            $this->dvmd_tm_set_style_1($sl, "border-right: {$v} !important;");
            $el->removeAttribute('border-right');
        }

        // Border-bottom.
        if ($el->hasAttribute('border-bottom')) {
            $v = esc_html($el->getAttribute('border-bottom'));
            $this->dvmd_tm_set_style_1($sl, "border-bottom: {$v} !important;");
            $el->removeAttribute('border-bottom');
        }

        // Border-left.
        if ($el->hasAttribute('border-left')) {
            $v = esc_html($el->getAttribute('border-left'));
            $this->dvmd_tm_set_style_1($sl, "border-left: {$v} !important;");
            $el->removeAttribute('border-left');
        }



        //-------------------------------------------------------------------//
        //---------------------- HREF, Target & Cursor ----------------------//
        //-------------------------------------------------------------------//

        /**
         * Makes a cell clickable and accessible and generates cursor styles.
         */

        if ($el->hasAttribute('href')) {

            // Make cell clickable.
            $href = esc_url_raw($el->getAttribute('href'));
            $trgt = ($el->hasAttribute('target')) ? esc_attr($el->getAttribute('target')) : '_self';
            $el->setAttribute('onclick', "window.open('{$href}','{$trgt}')");
            $el->removeAttribute('href');
            $el->removeAttribute('target');

            // Make cell accessible.
            $el->setAttribute('tabindex', '0');
            if (!$el->hasAttribute('title')) {
                $title = esc_html__('Link', 'dvmd-table-maker');
                $el->setAttribute('title', $title);
            }

            // Add pointer.
            $this->dvmd_tm_set_style_1($sl, 'cursor: pointer;');
        }



        //-----------------------------------------------------------------------//
        //---------------------- Classes & ARIA Attributes ----------------------//
        //-----------------------------------------------------------------------//

        /**
         * Sets the cell’s classes, ARIA attributes, and accordion toggle.
         *
         * Position Classes: first-column, last-column, first-row and last-row.
         * Order Classes: odd-column, even-column, odd-row, and even-row.
         * ARIA Attributes: role, colindex, rowindex, colspan, and rowspan.
         *
         * Classes always reflect the visual appearance of the table at desktop size.
         * ARIA attributes reflect the DOM order of table cells, which is different
         * to the visual appearance when a tables breaks by columns (ie. flipped).
         *
         * ARIA attributes and responsive elements use relative coordinates.
         */

        // ARIA index and span.
        $el->setAttribute('aria-colindex', esc_attr($x+1));
        $el->setAttribute('aria-rowindex', esc_attr($y+1));
        if (1 !== $yspan) $el->setAttribute('aria-rowspan', esc_attr($yspan));
        if (1 !== $xspan) $el->setAttribute('aria-colspan', esc_attr($xspan));

        if ($a->isResponsive) {

            // Block header.
            if ($x < $a->yHeadCount) $cl .= 'dvmd_tm_bhead ';

            // Accordion toggle.
            if ($a->isAccordion && $y >= $a->xHeadCount && ($x == $a->yHeadCount-1 || ($x+$xspan == $a->yHeadCount))) {
                if (self::dvmd_tm_get_DOMDocument($icon, $a->accordionIcon)) {
                    $icon = $icon->documentElement;
                    $name = sprintf('Accordion toggle %s', $y-$a->xHeadCount+1);
                    $icon->setAttribute('title', esc_html($name));
                    $icon->setAttribute('aria-label', esc_html($name));
                    $icon = $cell->importNode($icon, true);
                    $el->appendChild($icon);
                }
            }
        }

        // Position classes.
        $cl .= (0  == $c) ? 'dvmd_tm_col_first ' : (($a->colCount == $c+$cspan) ? 'dvmd_tm_col_last ' : '');
        $cl .= (0  == $r) ? 'dvmd_tm_row_first ' : (($a->rowCount == $r+$rspan) ? 'dvmd_tm_row_last ' : '');
        $cl .= (0 !== $c % 2) ? 'dvmd_tm_col_even ' : 'dvmd_tm_col_odd ';
        $cl .= (0 !== $r % 2) ? 'dvmd_tm_row_even ' : 'dvmd_tm_row_odd ';

        // ARIA role.
        $el->setAttribute('role', esc_attr($role));

        // Finalise classes.
        $cl .=  $el->getAttribute('class');
        $cl  = "{$a->columnClass} dvmd_tm_tcell {$cellClass} dvmd_tm_col_{$c} dvmd_tm_row_{$r} {$cl}";



        //----------------------------------------------------------------//
        //---------------------- Set Grid Positions ----------------------//
        //----------------------------------------------------------------//

        /**
         * Pins a cell to its position on the desktop and responsive grids.
         *
         * Desktop styles are not needed if table responsive is set to
         * desktop. Responsive styles use relative coordinates which are
         * based upon the table’s orientation (ie. Break by setting).
         */

        // Desktop.
        if (!$a->isDesktop) {
            $v = sprintf('%s/%s/%s/%s', $r+1, $c+1, $r+$rspan+1, $c+$cspan+1);
            $this->dvmd_tm_set_style_2($sl, 'grid-area', esc_html($v));
        }

        // Responsive.
        if ($a->isResponsive) {
            if ($y < $a->xHeadCount) {
                $v = sprintf('%s/%s/%s/%s', $x+1, $y+1, $x+$xspan+1, $y+$yspan+1);
            } else {
                $v = sprintf('%s/%s/%s/%s', $x+1, $a->xHeadCount+1, $x+$xspan+1, $a->xHeadCount+2);
            }
            $this->dvmd_tm_set_style_2($sl, 'grid-area', esc_html($v), $a->mquery1);
        }



        //------------------------------------------------------------------//
        //---------------------- Populate Table Array ----------------------//
        //------------------------------------------------------------------//

        /**
         * Loops the row/col spans adding cells to the table array as needed.
         * All cells are added to the table array at least once.
         *
         * Cells with row/col spans are duplicated for screen-readers and blocks.
         * A) For non-responsive tables, all spans are hidden.
         * B) For responsive tables, spans within a single block are hidden,
         *    while spans which fall accross a multiple blocks are not.
         */

        for ($cs = 0; $cs < $cspan; $cs++) {
            for ($rs = 0; $rs < $rspan; $rs++) {

                $span_class = '';

                if ($cs > 0 || $rs > 0) {
                    $span_class = ' dvmd_tm_span';
                    if (!$a->isResponsive) : $span_class .= ' dvmd_tm_span_hidden';
                    elseif ( $isTopLeft)   : $span_class .= ' dvmd_tm_span_hidden';
                    elseif ( $a->isFlipped && ($isRowHead || $rs > 0)) : $span_class .= ' dvmd_tm_span_hidden';
                    elseif (!$a->isFlipped && ($isColHead || $cs > 0)) : $span_class .= ' dvmd_tm_span_hidden';
                    endif;
                }

                // Set final classes and print the cell.
                $el->setAttribute('class', esc_attr("{$cl}{$span_class}"));
                $table[$c+$cs][$r+$rs] = do_shortcode($cell->saveHTML());
            }
        }

    }



    //--------------------------------------------------------------------//
    //---------------------- Render: Dynamic Styles ----------------------//
    //--------------------------------------------------------------------//


    /**
     * Renders table dynamic styles.
     *
     * @since   3.0.3
     * @access  public
     *
     * @param   array  $a  The table attributes.
     *
     * @return  void
     */
    private function dvmd_tm_render_styles($a) {


        // Properties.
        $pr = $this->props;



        //-------------------------------------------------------------------//
        //---------------------- Render: Static Styles ----------------------//
        //-------------------------------------------------------------------//


        // Note: Rather than use a style-sheet we are rendering static styles here because:
        // 1) It ensure styles are properly prefixed with '.et-db #et-boc .et-l' when necessary.
        // 2) It overcomes issues with the Extra Theme overriding dynamic styles with static styles.
        // 3) It allows us to only output responsive styles at the correct breakpoint when needed.


        //----------------------------//
        //---------- Common ----------//
        //----------------------------//


        // The dvmd_preload class is used to block transitions on page load.
        // We remove it with Javascript it so that transistions are reactivated.
        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_preload .dvmd_tm_tcell,
             %%order_class%% .dvmd_preload .dvmd_tm_icon,
             %%order_class%% .dvmd_preload .dvmd_tm_image',
            'transition-duration: 0ms !important;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_title, %%order_class%% .dvmd_tm_description',
            'padding: 0;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_table',
            'display: grid;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_tblock, %%order_class%% .dvmd_tm_trow',
            'display: contents;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_trow[aria-hidden="true"]',
            'display: none;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_toggle',
            'display: none;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_tcell',
            'display: flex; flex-direction: column; border-width: 1px; overflow: hidden;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_tcell h1, %%order_class%% .dvmd_tm_tcell h2, %%order_class%% .dvmd_tm_tcell h3,
             %%order_class%% .dvmd_tm_tcell h4, %%order_class%% .dvmd_tm_tcell h5, %%order_class%% .dvmd_tm_tcell h6',
            'padding: 0;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_tcell[aria-hidden="true"]',
            'display: none;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_chead .dvmd_tm_cdata, %%order_class%% .dvmd_tm_rhead .dvmd_tm_cdata',
            'color: #ffffff;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_button',
            'display: inline-block; text-align: center;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_image',
            'background-repeat: no-repeat;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% a.dvmd_tm_image',
            'display: block;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_filler',
            'opacity: 0 !important; padding: 0 !important;'
        );

        $this->dvmd_tm_set_style_1(
            '%%order_class%% .dvmd_tm_span, %%order_class%% .dvmd_tm_span_hidden',
            'opacity: 0; z-index: -1;'
        );


        //--------------------------------//
        //---------- Responsive ----------//
        //--------------------------------//


        if ($a->isResponsive) {

            // Responsive prefix.
            // $prefix = "%%order_class%% .dvmd_tm_table.dvmd_tm_{$a->breakPoint}";
            $prefix = '%%order_class%% .dvmd_tm_table';

            $this->dvmd_tm_set_style_1(
                "{$prefix} .dvmd_tm_trow[aria-hidden='true']",
                'display: contents;',
                $a->mquery1
            );

            $this->dvmd_tm_set_style_1(
                "{$prefix} .dvmd_tm_filler",
                'position: absolute !important;',
                $a->mquery1
            );

            $this->dvmd_tm_set_style_1(
                "{$prefix} .dvmd_tm_span",
                'opacity: 1; z-index: 0;',
                $a->mquery1
            );

            $this->dvmd_tm_set_style_1(
                "{$prefix} .dvmd_tm_span_hidden",
                'opacity: 0; z-index: -1;',
                $a->mquery1
            );


            //-------------------------------//
            //---------- Accordion ----------//
            //-------------------------------//


            if ($a->isAccordion) {

                // Accordion prefix.
                $prefix = "{$prefix}.dvmd_tm_accordion";

                $this->dvmd_tm_set_style_1(
                    "{$prefix} .dvmd_tm_tcell",
                    'display: none;',
                    $a->mquery1
                );

                $this->dvmd_tm_set_style_1(
                    "{$prefix} .dvmd_tm_bhead",
                    'display: flex; position: relative;',
                    $a->mquery1
                );

                $this->dvmd_tm_set_style_1(
                    "{$prefix} .dvmd_tm_toggle",
                    'display: block; position: absolute; top: 50%; transform: translateY(-53%);',
                    $a->mquery1
                );

                $this->dvmd_tm_set_style_1(
                    "{$prefix} .dvmd_tm_toggle i:after",
                    'display: block; font-family: ETmodules; font-style: normal; font-variant: normal;
                     font-weight: 400; line-height: 1; text-transform: none; speak: none;',
                    $a->mquery1
                );

                $this->dvmd_tm_set_style_1(
                    "{$prefix} .dvmd_tm_tblock.dvmd_tm_active .dvmd_tm_tcell",
                    'display: flex;',
                    $a->mquery1
                );
            }
        }



        //---------------------------------------------------------//
        //---------------------- Transitions ----------------------//
        //---------------------------------------------------------//

        /**
         * We could use the get_transition_fields_css_props() function,
         * but considering the repetition of Table Maker fields, in many
         * ways, its just easier to enable transitions like this.
         */

        // Transition settings.
        $duration = isset($pr['hover_transition_duration']) ? $pr['hover_transition_duration'] : '300ms';
        $duration = sprintf('transition-duration: %s;', $duration);
        $delay    = isset($pr['hover_transition_delay']) ? $pr['hover_transition_delay'] : '0ms';
        $delay    = sprintf('transition-delay: %s;', $delay);
        $timing   = isset($pr['hover_transition_speed_curve']) ? $pr['hover_transition_speed_curve'] : 'ease';
        $timing   = sprintf('transition-timing-function: %s;', $timing);

        // Cells.
        $selector = '%%order_class%% .dvmd_tm_tcell';
        $property = 'transition-property: background, border-radius, border-color, border-width;';
        $this->dvmd_tm_set_style_1($selector, $property);
        $this->dvmd_tm_set_style_1($selector, $duration);
        $this->dvmd_tm_set_style_1($selector, $delay);
        $this->dvmd_tm_set_style_1($selector, $timing);

        // Icons.
        $selector = '%%order_class%% .dvmd_tm_icon';
        $property = 'transition-property: color, font-size;';
        $this->dvmd_tm_set_style_1($selector, $property);
        $this->dvmd_tm_set_style_1($selector, $duration);
        $this->dvmd_tm_set_style_1($selector, $delay);
        $this->dvmd_tm_set_style_1($selector, $timing);

        // Images.
        $selector = '%%order_class%% .dvmd_tm_image';
        $property = 'transition-property: background-size;';
        //$property = 'transition-property: padding-top, background-size, background-position-x, background-position-y;';
        $this->dvmd_tm_set_style_1($selector, $property);
        $this->dvmd_tm_set_style_1($selector, $duration);
        $this->dvmd_tm_set_style_1($selector, $delay);
        $this->dvmd_tm_set_style_1($selector, $timing);



        //-------------------------------------------------------------------//
        //---------------------- Title & Description ------------------------//
        //-------------------------------------------------------------------//


        // Title.
        if ($a->hasTitle) {
            $p = ($a->isTitleAbove) ? 'margin-bottom' : 'margin-top';
            $this->dvmd_tm_set_responsive_2('%%order_class%% .dvmd_tm_title', $p, 'tbl_title_spacing');
        }

        // Description.
        if ($a->hasDescription) {
            $p = ($a->isDescriptionAbove) ? 'margin-bottom' : 'margin-top';
            $this->dvmd_tm_set_responsive_2('%%order_class%% .dvmd_tm_description', $p, 'tbl_description_spacing');
        }



        //----------------------------------------------------------//
        //---------------------- Table Grid ------------------------//
        //----------------------------------------------------------//


        // Desktop.
        $this->dvmd_tm_set_responsive_1('%%order_class%% .dvmd_tm_table', 'grid-template-columns', $a->colGrid, 'custom');
        $this->dvmd_tm_set_responsive_1('%%order_class%% .dvmd_tm_table', 'grid-auto-rows', $a->rowGrid, 'custom');

        // Responsive.
        if ($a->isResponsive) {

            // Disable grid on table element.
            $this->dvmd_tm_set_style_1('%%order_class%% .dvmd_tm_table', 'display: contents;', $a->mquery1);

            // Enable grid on block elements.
            $this->dvmd_tm_set_style_1('%%order_class%% .dvmd_tm_tblock', 'display: grid;', $a->mquery1);

            // Set grid columns.
            $v = $a->xHeadCount+1;
            $this->dvmd_tm_set_style_2(
                '%%order_class%% .dvmd_tm_tblock', 'grid-template-columns',
                esc_html("repeat({$v}, minmax(50px, 1fr))"), $a->mquery1);

            // Set grid rows.
            $this->dvmd_tm_set_responsive_3(
                '%%order_class%% .dvmd_tm_tblock', 'grid-template-rows',
                $a->rowGrid, esc_html("repeat({$a->xCount}, %s)"), 'custom');

            // Reset grid template when toggled.
            $this->dvmd_tm_set_responsive_3(
                '%%order_class%% .dvmd_tm_accordion .dvmd_tm_tblock:not(.dvmd_tm_active)',
                'grid-template-rows', $a->rowGrid, esc_html("repeat({$a->yHeadCount}, %s)"), 'custom');

            // Set header’s start/end column.
            if (!$a->hasTopLeft) {
                $v = $a->xHeadCount+2;
                $this->dvmd_tm_set_style_2(
                    '%%order_class%% .dvmd_tm_tblock .dvmd_tm_bhead',
                    'grid-column', esc_html("1/{$v} !important"), $a->mquery1);
            }

            // Block/Accordion margin.
            $this->dvmd_tm_set_responsive_2(
                '%%order_class%% .dvmd_tm_tblock:not(:first-child)', 'margin-top', 'tbl_responsive_block_margin');
        }



        //---------------------------------------------------------------//
        //---------------------- Table Scrolling ------------------------//
        //---------------------------------------------------------------//


        if ('on' === $pr['tbl_scrolling_active']) {

            // Scrolling.
            $this->dvmd_tm_set_style_1('%%order_class%% .dvmd_tm_table', 'overflow: auto;');

            // Sticky Column Headers.
            if ('on' === $pr['tbl_scrolling_col_sticky']) {
                $v = sprintf('position: sticky; top: 0; z-index: %s;', ($a->hasTLcHead) ? '999' : '998');
                $this->dvmd_tm_set_style_1('%%order_class%% .dvmd_tm_chead', $v);
            }

            // Sticky Row Headers.
            if ('on' === $pr['tbl_scrolling_row_sticky']) {
                $v = sprintf('position: sticky; left: 0; z-index: %s;', ($a->hasTLcHead) ? '998' : '999');
                $this->dvmd_tm_set_style_1('%%order_class%% .dvmd_tm_rhead', $v);
            }
        }



        //-----------------------------------------------------------//
        //---------------------- Table Frame ------------------------//
        //-----------------------------------------------------------//


        // Gaps.
        if ('gaps' === $pr['tbl_frame_type']) {

            // Gaps.
            $cg = $this->dvmd_tm_get_responsive_1('tbl_frame_gap_col');
            $rg = $this->dvmd_tm_get_responsive_1('tbl_frame_gap_row');

            // Table.
            $this->dvmd_tm_set_responsive_1('%%order_class%% .dvmd_tm_table', 'column-gap', $cg);
            $this->dvmd_tm_set_responsive_1('%%order_class%% .dvmd_tm_table', 'row-gap', $rg);

            // Blocks.
            if ($a->isResponsive) {
                $this->dvmd_tm_set_responsive_1('%%order_class%% .dvmd_tm_tblock', 'column-gap', $cg);
                $this->dvmd_tm_set_responsive_1('%%order_class%% .dvmd_tm_tblock', 'row-gap', $rg);
            }
        }

        // Lines.
        elseif ('lines' === $pr['tbl_frame_type']) {

            // Color and style.
            $this->dvmd_tm_set_style_2(
                '%%order_class%% .dvmd_tm_tcell', 'outline-color', esc_html($pr['tbl_frame_line_color']));
            $this->dvmd_tm_set_style_2(
                '%%order_class%% .dvmd_tm_tcell', 'outline-style', esc_html($pr['tbl_frame_line_style']));

            // Width.
            $v = $this->dvmd_tm_get_responsive_1('tbl_frame_line_width');
            $this->dvmd_tm_set_responsive_1('%%order_class%% .dvmd_tm_tcell', 'outline-width', $v);

            // Prepare offset and padding.
            foreach ($v as $i => $w) {
                if (!$w) continue;
                $unit  = preg_replace('/[0-9]+/', '', $w);
                $value = preg_replace('/[^0-9.]/', '', $w);
                $value = $value / 2;
                $offsets[$i] = "-{$value}{$unit}";
                $padding[$i] = "{$value}{$unit}";
            }

            // Offset and padding.
            $this->dvmd_tm_set_responsive_1('%%order_class%% .dvmd_tm_tcell', 'outline-offset', $offsets);
            $this->dvmd_tm_set_responsive_1('%%order_class%% .dvmd_tm_table', 'padding', $padding);
        }



        //-----------------------------------------------------------------//
        //---------------------- Table Text & Cell ------------------------//
        //-----------------------------------------------------------------//


        foreach (['tcell'=>'','rhead'=>'21','chead'=>'22','rfoot'=>'23','cfoot'=>'24'] as $type => $order) {

            // Selector.
            $s = ('tcell' === $type) ? '%%order_class%% .dvmd_tm_tcell' : "%%order_class%% .dvmd_tm_tcell.dvmd_tm_{$type}";

            // Text wrap.
            $v = $pr["tbl_{$type}_text_wrap"];
            if ($v) $this->dvmd_tm_set_style_2("{$s} .dvmd_tm_cdata", 'white-space', esc_html($v), null, $order);

            // Color.
            $v = $pr["tbl_{$type}_cell_color"];
            if ($v) $this->dvmd_tm_set_style_2($s, 'background', esc_html($v), null, $order);
            $this->dvmd_tm_set_hover_1($s, 'background', "tbl_{$type}_cell_color");

            // Horizontal alignment.
            $v = $pr["tbl_{$type}_cell_align_horz"];
            if ($v) $this->dvmd_tm_set_style_2($s, 'text-align', esc_html($v), null, $order);

            // Vertical alignment.
            $v = $pr["tbl_{$type}_cell_align_vert"];
            if ($v) {
                if ('left' === $v) { $v = 'flex-start'; }
                elseif ('right' === $v) { $v = 'flex-end'; }
                $this->dvmd_tm_set_style_2($s, 'justify-content', esc_html($v), null, $order);
            }

            // Padding.
            $this->dvmd_tm_set_custom_spacing_1($s, "tbl_{$type}_cell_padding", 'padding', $order);
        }



        //---------------------------------------------------------------------//
        //---------------------- Table Stripes & Hover ------------------------//
        //---------------------------------------------------------------------//

        /**
         * Applies stripes and/or hover effects to table cells.
         *
         * There are three levels of processing:
         * 1) Colors for the table headers, footers, and body sections.
         * 2) Colors for the columns headers, footers, and body sections.
         * 3) Individual cell colors applied by the cell 'background' attribute.
         *
         * There are also potentially three colors generated for each level:
         * 1) Stripes color.
         * 2) Hover color.
         * 3) Stripes color + Hover color.
         */

        // Get the stripes and hover modes.
        $stripes = $pr['tbl_stripes_active'];
        $hover   = $pr['tbl_hover_active'];

        if ('off' !== $stripes || 'off' !== $hover) :

            // Get table colors.
            $table_colors[] = array(
                'class' => '%%order_class%% ',
                'tdata' => $pr['tbl_tcell_cell_color'],
                'rhead' => $pr['tbl_rhead_cell_color'],
                'chead' => $pr['tbl_chead_cell_color'],
                'rfoot' => $pr['tbl_rfoot_cell_color'],
                'cfoot' => $pr['tbl_cfoot_cell_color'],
            );

            // Combine table, column and individual cell colors.
            $table_colors = array_merge($table_colors, $a->gColumnColors, $a->cellColors);

            // Stripes.
            if ('off' !== $stripes) {

                // Order.
                $ord = $pr['tbl_stripes_order'];

                // Apply effect to...
                $eff = ['tdata', 'chead', 'cfoot', 'rhead', 'rfoot'];
                $eff = $this->process_multiple_checkboxes_field_value($eff, $pr["tbl_stripes_apply"]);

                // Selector 1.
                $sd1 = ('on' === $stripes); //-> Horizontal.
                $sd1 = ($a->isDesktop && !$a->isFlipped) ? !$sd1 : $sd1;
                $sd1 = ($sd1) ? 'row' : 'col';
                $ss1 = ".dvmd_tm_{$sd1}_{$ord}";

                // Selector 2.
                $ss2 = null;
                if ('off' !== $pr["tbl_stripes_responsive"] && ($a->isTablet || $a->isPhone)) {
                    $sd2 = ('horz' === $pr['tbl_stripes_responsive']);
                    $sd2 = (!$a->isFlipped) ? !$sd2 : $sd2;
                    $sd2 = ($sd2) ? 'row' : 'col';
                    $ss2 = ".dvmd_tm_{$sd2}_{$ord}";
                }

                // Apply effect.
                $clr = $this->dvmd_tm_apply_color_effect($a, $table_colors, $eff, 'stripes', $ss1, $ss2, 40);
            }


            // Hover.
            if ('off' !== $hover) {

                // Apply effect to...
                $eff = ['tdata', 'chead', 'cfoot', 'rhead', 'rfoot'];
                $eff = $this->process_multiple_checkboxes_field_value($eff, $pr["tbl_hover_apply"]);

                // Selector 1.
                $hd1 = ('horz' === $hover);
                $hd1 = ($a->isDesktop && !$a->isFlipped) ? !$hd1 : $hd1;
                $hd1 = ($hd1) ? 'row' : 'col';
                $hs1 = ".dvmd_tm_{$hd1}_hover";

                // Selector 2.
                $hs2 = null;
                if ('off' !== $pr["tbl_hover_responsive"] && ($a->isTablet || $a->isPhone)) {
                    $hd2 = ('horz' === $pr['tbl_hover_responsive']);
                    $hd2 = (!$a->isFlipped) ? !$hd2 : $hd2;
                    $hd2 = ($hd2) ? 'row' : 'col';
                    $hs2 = ".dvmd_tm_{$hd2}_hover";
                }

                // Apply effect.
                $this->dvmd_tm_apply_color_effect($a, $table_colors, $eff, 'hover', $hs1, $hs2, 50);

                // Stripes plus hover.
                if ('off' !== $stripes) {
                    $shs1 = ".dvmd_tm_{$sd1}_{$ord}.dvmd_tm_{$hd1}_hover";
                    $shs2 = ($ss2 && $hs2) ? "{$ss2}{$hs2}" : null;
                    $this->dvmd_tm_apply_color_effect($a, $clr, $eff, 'hover', $shs1, $shs2, 60);
                }
            }

        endif;



        //--------------------------------------------------------//
        //---------------------- Table Icon ----------------------//
        //--------------------------------------------------------//


        if ($a->hasIcons) {

            // Icon.
            if ($pr['tbl_icon_type']) {

                $i = $pr['tbl_icon_type'];
                $s = '%%order_class%% .dvmd_tm_icon.ei-default:before';

                // Pre 4.13.
                if (false === strpos($i, '||')) {

                    // Icon value.
                    $i = html_entity_decode(esc_attr(et_pb_process_font_icon($i)));
                    $this->dvmd_tm_set_style_2($s, 'content', "'{$i}'", null, '20');
                }

                // Post 4.13.
                elseif (function_exists('et_pb_get_extended_font_icon_value')) {

                    // Icon value.
                    $i = esc_attr(et_pb_get_extended_font_icon_value($i, true));
                    $this->dvmd_tm_set_style_2($s, 'content', "'{$i}'", null, '20');

                    // Icon styles.
                    $this->generate_styles(array(
                        'utility_arg'    => 'icon_font_family',
                        'render_slug'    =>  $this->slug,
                        'base_attr_name' => 'tbl_icon_type',
                        'selector'       =>  $s,
                        'important'      =>  true,
                        // Processor modified to allow support for WP and DIVI Icons by WP Zone. (Aspen Grove Studios)
                        'processor' => apply_filters('dvmd_table_maker_icon_style_processor', array(
                            'ET_Builder_Module_Helper_Style_Processor',
                            'process_extended_icon',
                        )),
                    ));
                }
            }

            // Size.
            $this->dvmd_tm_set_responsive_2(
                '%%order_class%% .dvmd_tm_icon', 'font-size', 'tbl_icon_size', 'range', '20');
            $this->dvmd_tm_set_hover_1(
                '%%order_class%% .dvmd_tm_icon', 'font-size', 'tbl_icon_size', null, '20');

            // Color.
            $this->dvmd_tm_set_responsive_2(
                '%%order_class%% .dvmd_tm_icon', 'color', 'tbl_icon_color', 'color', '20');
            $this->dvmd_tm_set_hover_1(
                '%%order_class%% .dvmd_tm_icon', 'color', 'tbl_icon_color', null, '20');
        }



        //----------------------------------------------------------//
        //---------------------- Table Button ----------------------//
        //----------------------------------------------------------//


        // Width.
        if ($a->hasButtons && 'block' === $pr['tbl_button_width']) {
            $this->dvmd_tm_set_style_1('%%order_class%% .dvmd_tm_button', 'display: block;', null, '20');
        }



        //---------------------------------------------------------//
        //---------------------- Table Image ----------------------//
        //---------------------------------------------------------//


        if ($a->hasImages) {

            // Proportion.
            $this->dvmd_tm_set_responsive_2(
                '%%order_class%% .dvmd_tm_image', 'padding-top', 'tbl_image_proportion', 'custom', '20');
            $this->dvmd_tm_set_hover_1(
                '%%order_class%% .dvmd_tm_image', 'padding-top', 'tbl_image_proportion', null, '20');

            // Size.
            if ('size' !== $pr['tbl_image_scale']) {
                $this->dvmd_tm_set_style_2(
                    '%%order_class%% .dvmd_tm_image', 'background-size', esc_html($pr['tbl_image_scale']), null, '20');
            } else {
                $this->dvmd_tm_set_responsive_2(
                    '%%order_class%% .dvmd_tm_image', 'background-size', 'tbl_image_size', 'range', '20');
                $this->dvmd_tm_set_hover_1(
                    '%%order_class%% .dvmd_tm_image', 'background-size', 'tbl_image_size', null, '20');
            }

            // Alignment.
            $this->dvmd_tm_set_responsive_2(
                '%%order_class%% .dvmd_tm_image', 'background-position-x', 'tbl_image_align_horz', 'custom', '20');
            $this->dvmd_tm_set_hover_1(
                '%%order_class%% .dvmd_tm_image', 'background-position-x', 'tbl_image_align_horz', null, '20');
            $this->dvmd_tm_set_responsive_2(
                '%%order_class%% .dvmd_tm_image', 'background-position-y', 'tbl_image_align_vert', 'custom', '20');
            $this->dvmd_tm_set_hover_1(
                '%%order_class%% .dvmd_tm_image', 'background-position-y', 'tbl_image_align_vert', null, '20');
        }



        //-------------------------------------------------------------//
        //---------------------- Table Accordion ----------------------//
        //-------------------------------------------------------------//


        if ($a->isAccordion) {

            // Focus ring.
            $v = $this->dvmd_tm_get_responsive_1('tbl_toggle_color');
            $this->dvmd_tm_set_responsive_3(
                '%%order_class%% .dvmd_tm_accordion .dvmd_tm_toggle:focus', 'box-shadow', $v, '0 0 0 2px %s', 'color');

            // Alignment.
            if ('right' === $pr['tbl_toggle_align']) {
                $this->dvmd_tm_set_style_1(
                    '%%order_class%% .dvmd_tm_accordion .dvmd_tm_toggle', 'right:10px;', $a->mquery1);
            } else {
                $this->dvmd_tm_set_style_1(
                    '%%order_class%% .dvmd_tm_accordion .dvmd_tm_toggle', 'left:10px;', $a->mquery1);
            }

            // Size and color.
            $this->dvmd_tm_set_responsive_2(
                '%%order_class%% .dvmd_tm_accordion .dvmd_tm_toggle i:after', 'font-size', 'tbl_toggle_size');
            $this->dvmd_tm_set_responsive_2(
                '%%order_class%% .dvmd_tm_accordion .dvmd_tm_toggle i:after', 'color', 'tbl_toggle_color', 'color');


            // Opened.
            $i = $pr['tbl_toggle_icon_close'];
            $s = '%%order_class%% .dvmd_tm_accordion .dvmd_tm_active .dvmd_tm_toggle i:after';

            // Pre 4.13.
            if (false === strpos($i, '||')) {

                // Icon value.
                $i = html_entity_decode(esc_attr(et_pb_process_font_icon($i)));
                $this->dvmd_tm_set_style_2($s, 'content', "'{$i}'", $a->mquery1);
            }

            // Post 4.13.
            elseif (function_exists('et_pb_get_extended_font_icon_value')) {

                // Icon value.
                $i = esc_attr(et_pb_get_extended_font_icon_value($i, true));
                $this->dvmd_tm_set_style_2($s, 'content', "'{$i}'", $a->mquery1);

                // Icon styles.
                $this->generate_styles(array(
                    'utility_arg'    => 'icon_font_family',
                    'render_slug'    =>  $this->slug,
                    'base_attr_name' => 'tbl_toggle_icon_close',
                    'selector'       =>  $s,
                    'important'      =>  true,
                    // Processor modified to allow support for WP and DIVI Icons by WP Zone. (Aspen Grove Studios)
                    'processor' => apply_filters('dvmd_table_maker_icon_style_processor', array(
                        'ET_Builder_Module_Helper_Style_Processor',
                        'process_extended_icon',
                    )),
                ));
            }


            // Closed.
            $i = $pr['tbl_toggle_icon_open'];
            $s = '%%order_class%% .dvmd_tm_accordion .dvmd_tm_toggle i:after';

            // Pre 4.13.
            if (false === strpos($i, '||')) {

                // Open.
                $i = html_entity_decode(esc_attr(et_pb_process_font_icon($i)));
                $this->dvmd_tm_set_style_2($s, 'content', "'{$i}'", $a->mquery1);
            }

            // Post 4.13.
            elseif (function_exists('et_pb_get_extended_font_icon_value')) {

                // Icon value.
                $i = esc_attr(et_pb_get_extended_font_icon_value($i, true));
                $this->dvmd_tm_set_style_2($s, 'content', "'{$i}'", $a->mquery1);

                // Icon styles.
                $this->generate_styles(array(
                    'utility_arg'    => 'icon_font_family',
                    'render_slug'    =>  $this->slug,
                    'base_attr_name' => 'tbl_toggle_icon_open',
                    'selector'       =>  $s,
                    'important'      =>  true,
                    // Processor modified to allow support for WP and DIVI Icons by WP Zone. (Aspen Grove Studios)
                    'processor' => apply_filters('dvmd_table_maker_icon_style_processor', array(
                        'ET_Builder_Module_Helper_Style_Processor',
                        'process_extended_icon',
                    )),
                ));
            }
        }


    }



    //----------------------------------------------------------//
    //---------------------- Helper: Grid ----------------------//
    //----------------------------------------------------------//


    /**
     * Returns a min/max value pair for a specific media query.
     * Checks three sets of mix/max properties to find the first…
     * with a 'defined' value.
     *
     * @since   2.0.0
     * @access  public
     *
     * @param   string  $media  The media query to check. (desktop/tablet/phone)
     * @param   array   $min1   1st user specified min value.
     * @param   array   $max1   1st user specified max value.
     * @param   array   $min2   2nd user specified min value.
     * @param   array   $max2   2nd user specified max value.
     * @param   string  $min3   Default min value.
     * @param   string  $max3   Default max value.
     *
     * @return  string
     */
    private function dvmd_tm_get_grid($media, $min1, $max1, $min2, $max2, $min3, $max3) {
        $min = self::dvmd_tm_get_grid_value($min1[$media], $min2[$media], $min3);
        $max = self::dvmd_tm_get_grid_value($max1[$media], $max2[$media], $max3);
        return "minmax({$min}, {$max}) ";
    }


    /**
     * Finds the first 'defined' property out of three.
     *
     * @since   2.0.0
     * @access  public
     *
     * @param   string  $p1  1st property to check.
     * @param   string  $p2  2nd property to check.
     * @param   string  $p3  3rd property to check.
     *
     * @return  string
     */
    private static function dvmd_tm_get_grid_value($p1, $p2, $p3) {
        return ($p1) ? $p1 : (($p2) ? $p2 : $p3);
    }



    //-----------------------------------------------------------------//
    //---------------------- Helper: DOMDocument ----------------------//
    //-----------------------------------------------------------------//


    /**
     * Safely loads html data as DOMDocument.
     *
     * @since   3.1.2
     * @access  private
     *
     * @param   var      $ddoc  Unset var for DOMDocument. (byref)
     * @param   string   $data  The raw html data.
     *
     * @return  boolean
     */
    private static function dvmd_tm_get_DOMDocument(&$ddoc, $data) {

        // The mb_convert_encoding function will be deprecated in php 8.2.
        // $ddoc = new DOMDocument('1.0', 'utf-8');
        // $data = mb_convert_encoding($data, 'HTML-ENTITIES', 'UTF-8');
        // $prev = libxml_use_internal_errors(TRUE);
        // $ddoc->strictErrorChecking = FALSE;

        // Convert special characters to numeric entities and then handle HTML entities.
        $ddoc = new DOMDocument('1.0', 'utf-8');
        $data = mb_encode_numericentity($data, array(0x80, 0x10FFFF, 0, 0xFFFF), 'UTF-8');
        $data = htmlentities($data, ENT_NOQUOTES, 'UTF-8');
        $data = mb_decode_numericentity($data, array(0x80, 0x10FFFF, 0, 0xFFFF), 'UTF-8');
        $data = htmlspecialchars_decode($data);

        // Suppress libxml errors.
        $prev = libxml_use_internal_errors(TRUE);
        $ddoc->strictErrorChecking = FALSE;

        // Try.
        if (self::dvmd_tm_try_DOMDocument($ddoc, $data)) {
            libxml_clear_errors();
            libxml_use_internal_errors($prev);
            return true;
        }

        // Error: Escape data.
        $data = esc_html($data);
        $data = "<div>$data</div>";

        // Try again.
        if (self::dvmd_tm_try_DOMDocument($ddoc, $data)) {
            libxml_clear_errors();
            libxml_use_internal_errors($prev);
            return false;
        }

        // Prepare error.
        $error = sprintf('<div><p><strong>%s</strong> %s</p></div>',
            /* 01 */ esc_html__('Error:', 'dvmd-table-maker'),
            /* 02 */ esc_html__('Please check your table content.', 'dvmd-table-maker')
        );

        // Die.
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        die(et_core_esc_previously($error));
    }


    /**
     * Trys to load html data.
     *
     * Manually removes the doctype, html and body elements from the html.
     * We could use LoadHTML($data, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED).
     * However, this adds a dependency on libxml version 2.7.7.
     *
     * @since   3.0.2
     * @access  private
     *
     * @param   var     $ddoc  Unset var for DOMDocument. (byref)
     * @param   string  $data  The raw html data.
     *
     * @return  boolean
     */
    private static function dvmd_tm_try_DOMDocument(&$ddoc, $data) {

        // Handle empty data. (This is probably not really necessary).
        if (!$data) return self::dvmd_tm_get_DOMDocument($ddoc, '<cell></cell>');

        // Load html and remove doctype, html and body elements.
        if ($ddoc->LoadHTML($data)) {
            if ($ddoc->doctype) $ddoc->removeChild($ddoc->doctype);
            if ($ddoc->firstChild && $ddoc->firstChild->firstChild && $ddoc->firstChild->firstChild->firstChild) {
                $ddoc->replaceChild($ddoc->firstChild->firstChild->firstChild, $ddoc->firstChild);
                return true;
            }
        }

        // Return.
        return false;
    }



    //-----------------------------------------------------------//
    //---------------------- Helper: Color ----------------------//
    //-----------------------------------------------------------//


    /**
     * Applies stripes and hover effect to table cell colors.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   array    $a   The table attributes.
     * @param   array    $c   The colors to apply affect to.
     * @param   string   $e   The table parts to apply effect to.
     * @param   string   $t   The effect type. (stripes or hover)
     * @param   string   $s1  The desktop selector.
     * @param   string   $s2  The responsive selector. (or null)
     * @param   string   $o   The style order. (priority)
     *
     * @return  array
     */
    function dvmd_tm_apply_color_effect($a, $c, $e, $t, $s1, $s2, $o) {

        // Properties.
        $pr = $this->props;

        // Modified colors.
        $mColors = $c;

        // Cell types and priorities.
        $types = array('tdata' => $o, 'rhead' => $o+1, 'chead' => $o+2, 'rfoot' => $o+3, 'cfoot' => $o+4);

        // Loop cell types.
        foreach ($types as $type => $order) {

            // Bail.
            if (strpos($e, $type) === false) continue;

            // Loop cell colors.
            foreach ($c as $i => $colors) {

                // Color.
                $color = (array_key_exists($type, $colors)) ? $colors[$type] : '';
                if (!$color) continue;

                // Effect.
                if ('tint' === $pr["tbl_{$t}_effect"]) {
                    $color = self::dvmd_tm_tint_color($color, $pr["tbl_{$t}_tint"]);
                }
                elseif ('blend' === $pr["tbl_{$t}_effect"]) {
                    $color = self::dvmd_tm_multiply_colors($color, $pr["tbl_{$t}_color"]);
                }
                else {
                    $color = $pr["tbl_{$t}_color"];
                }

                // Save modified color.
                $mColors[$i][$type] = $color;

                // Desktop.
                $slug = $colors['class'];
                $this->dvmd_tm_set_style_2(
                    "{$slug}.dvmd_tm_tcell.dvmd_tm_{$type}{$s1}", 'background', esc_html($color), $a->mquery2, $order);

                // Responsive.
                if (!$s2) continue;
                $this->dvmd_tm_set_style_2(
                    "{$slug}.dvmd_tm_tcell.dvmd_tm_{$type}{$s2}", 'background', esc_html($color), $a->mquery1, $order);
            }
        }

        // Return.
        return $mColors;
    }


    /**
     * Prepares a color for tinting.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $c  Hex | rgba() | RGBA().
     * @param   string  $t  Value from -100 to 100.
     *
     * @return  string
     */
    private static function dvmd_tm_tint_color($c, $t) {
        if (0 == $t) return $c;
        self::dvmd_tm_prepare_color($c, $a);
        $t = max(-100, (min(100, $t)));
        if ($t < 0) {
            $t  = ($t + 100) / 100;
            $c = self::dvmd_tm_darken($c, $t);
        }
        elseif ($t > 0) {
            $t  = ($t - 100) / 100;
            $c = self::dvmd_tm_lighten($c, abs($t));
        }
        $c = implode(',', $c);
        return "rgba({$c},{$a})";
    }


    /**
     * Prepares two colors for multiplying.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $c1  Hex | rgba() | RGBA().
     * @param   string  $c2  Hex | rgba() | RGBA().
     *
     * @return  string
     */
    private static function dvmd_tm_multiply_colors($c1, $c2) {
        self::dvmd_tm_prepare_color($c1, $a1);
        self::dvmd_tm_prepare_color($c2, $a2);
        $c1 = self::dvmd_tm_multiply($c1, $c2, $a2);
        $c1 = implode(',', $c1);
        return "rgba({$c1},{$a1})";
    }


    /**
     * Converts a HEX or RGBA() string to RGB array.
     * The alpha value is stripped from the array and stored seperately.
     * See: https://stackoverflow.com/questions/32673760/
     * how-can-i-know-if-a-given-string-is-hex-rgb-rgba-or-hsl-color-using-javascript/32685393
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $c  Hex | rgba() | RGBA().       (byref)
     * @param   string  $a  The alpha value placeholder. (byref)
     *
     * @return  void
     */
    private static function dvmd_tm_prepare_color(&$c, &$a) {

        // RegEx.
        $validateHex = '/^(#)((?:[A-Fa-f0-9]{3}){1,2})$/m';
        $validateRGB = '/^rgb[(](?:\s*0*(?:\d\d?(?:\.\d+)?(?:\s*%)?|\.\d+\s*%|100(?:\.0*)?\s*%|(?:1\d\d|2[0-4]\d|25[0-5])(?:\.\d+)?)\s*(?:,(?![)])|(?=[)]))){3}[)]$/m';
        $validateRGBA = '/^rgba[(](?:\s*0*(?:\d\d?(?:\.\d+)?(?:\s*%)?|\.\d+\s*%|100(?:\.0*)?\s*%|(?:1\d\d|2[0-4]\d|25[0-5])(?:\.\d+)?)\s*,){3}\s*0*(?:\.\d+|1(?:\.0*)?)\s*[)]$/m';

        // Cleanup.
        $c = preg_replace('/\s+/', '', $c);
        $c = strtolower($c);

        // Color.
        if (preg_match($validateHex, $c)) {
            $c = self::dvmd_tm_hex2rgb($c);
        }
        elseif (preg_match($validateRGB, $c)) {
            $c = self::dvmd_tm_rgb2array($c);
        }
        elseif (preg_match($validateRGBA, $c)) {
            $c = self::dvmd_tm_rgb2array($c);
        }
        else {
            $c = self::dvmd_tm_rgb2array('rgb(255,255,255)');
        }

        // Alpha.
        $a = (4 == count($c)) ? array_pop($c) : 1;
        if (3 !== count($c)) $c = [255,255,255];
    }


    /**
     * Converts a RGB string to RGB array.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $rgb  RGB string.
     *
     * @return  array
     */
    private static function dvmd_tm_rgb2array($rgb = 'rgb(255,255,255)') {
        $rgb = trim($rgb, 'rgba()');
        return explode(',', $rgb);
    }


    /**
     * Converts a HEX string to RGB array.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $hex  HEX string.
     *
     * @return  array
     */
    private static function dvmd_tm_hex2rgb($hex = '#ffffff') {
        $hex = str_replace("#", "", $hex);
        $hex = (3 === strlen($hex)) ? "{$hex[0]}{$hex[0]}{$hex[1]}{$hex[1]}{$hex[2]}{$hex[2]}" : $hex;
        $f = function ($x) { return hexdec($x); };
        return array_map($f, str_split($hex, 2));
    }


    /**
     * Converts an RGB array to HEX string.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   array  $rgb  RGB array.
     *
     * @return  string
     */
    /*private static function dvmd_tm_rgb2hex($rgb = [255,255,255]) {
        $f = function ($x) { return str_pad(dechex($x), 2, "0", STR_PAD_LEFT); };
        return "#" . implode("", array_map($f, $rgb));
    }*/


    /**
     * Darkens a color based on weight value.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   array   $c  Hex string or RGB array.
     * @param   double  $w  Value from 0 to 1.
     *
     * @return  array
     */
    private static function dvmd_tm_darken($c, $w = 1) {
        return self::dvmd_tm_mix($c, [0, 0, 0], $w);
    }


    /**
     * Lightens a color based on weight value.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   array   $c  Hex string or RGB array.
     * @param   double  $w  Value from 0 to 1.
     *
     * @return  array
     */
    private static function dvmd_tm_lighten($c, $w = 1) {
        return self::dvmd_tm_mix($c, [255, 255, 255], $w);
    }


    /**
     * Mixes colors based on weight value.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   array   $c1  RGB array.
     * @param   array   $c2  RGB array.
     * @param   double  $w   Value from 0 to 1.
     *
     * @return  array
     */
    private static function dvmd_tm_mix($c1 = [255,255,255], $c2 = [255,255,255], $w = 1) {
        $a = function ($x) use ($w) { return $w * $x; };
        $b = function ($x) use ($w) { return (1 - $w) * $x; };
        $m = function ($x, $y) { return round($x + $y); };
        return array_map($m, array_map($a, $c1), array_map($b, $c2));
    }


    /**
     * Multiplies two colors based on weight value of second color.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   array   $c1  RGB array.
     * @param   array   $c2  RGB array.
     * @param   double  $w   Value from 0 to 1.
     *
     * @return  array
     */
    private static function dvmd_tm_multiply($c1 = [255,255,255], $c2 = [255,255,255], $w = 1) {
        $c2 = self::dvmd_tm_lighten($c2, $w);
        $m = function ($x, $y) { return round($x * $y / 255); };
        return array_map($m, $c1, $c2);
    }



    //-----------------------------------------------------------//
    //---------------------- Helper: Other ----------------------//
    //-----------------------------------------------------------//


    /**
     * Add assets to the late global asset list.
     *
     * @since   3.0.0
     * @access  public
     * @hook    et_global_assets_list
     *
     * @param   array  $assets  The list of assets.
     *
     * @return  array
     */
    public static function dvmd_tm_global_assets_list($assets) {

        // Post ID.
        $post_id = get_the_ID();

        // CPT suffix.
        if (function_exists('et_builder_should_wrap_styles')) {
            // For Divi 4.10.7 onwards.
            $cpt_suffix = et_builder_should_wrap_styles() && ! et_is_builder_plugin_active() ? '_cpt' : '';
        } else {
            // For Divi 4.10.0 to 4.10.6.
            $cpt_suffix = et_builder_post_is_of_custom_post_type($post_id)
                && et_pb_is_pagebuilder_used($post_id) && ! et_is_builder_plugin_active() ? '_cpt' : '';
        }

        // Bail.
        if (   isset($assets['et_icons_all'])
            && isset($assets['et_icons_fa'])
            && isset($assets["button{$cpt_suffix}"])
            && isset($assets["buttons{$cpt_suffix}"])) {
            return $assets;
        }

        // Add asssets.
        $assets_prefix = et_get_dynamic_assets_path();
        $assets['et_icons_all'] = array('css' => "{$assets_prefix}/css/icons_all.css",);
        $assets['et_icons_fa']  = array('css' => "{$assets_prefix}/css/icons_fa_all.css",);
        $assets['button']       = array('css' => "{$assets_prefix}/css/button{$cpt_suffix}.css",);
        $assets['buttons']      = array('css' => "{$assets_prefix}/css/buttons{$cpt_suffix}.css",);

        // Return.
        return $assets;
    }


    /**
     * Replaces the first found instance of a string.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $search   The search string.
     * @param   string  $replace  The replacement string.
     * @param   string  $string   The source string.
     *
     * @return  string
     */
    private static function dvmd_tm_str_replace_first($search, $replace, $source) {
        if (($pos = strpos($source, $search)) !== false) {
            return substr_replace($source, $replace, $pos, strlen($search));
        }
        return $source;
    }


    /**
     * Replaces the last found instance of a string.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $search   The search string.
     * @param   string  $replace  The replacement string.
     * @param   string  $string   The source string.
     *
     * @return  string
     */
    private static function dvmd_tm_str_replace_last($search, $replace, $source) {
        if (($pos = strrpos($source, $search)) !== false) {
            return substr_replace($source, $replace, $pos, strlen($search));
        }
        return $source;
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
     * Gets and fleshes out responsive styles for a field.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $f  Field name.
     * @param   mixed   $d  Default value.
     *
     * @return  array
     */
    private function dvmd_tm_get_responsive_2($f, $d='') {
        $v = et_pb_responsive_options()->get_property_values($this->props, $f, $d);
        if (!$v['tablet']) $v['tablet'] = $v['desktop'];
        if (!$v['phone'])  $v['phone']  = $v['tablet'];
        return $v;
    }


    /**
     * Fleshes-out responsive styles for a field.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   array  $st  CSS styles. (byref)
     *
     * @return  void
     */
    private static function dvmd_tm_fleshout_responsive_1(&$st) {
        if (!$st['tablet']) $st['tablet'] = $st['desktop'];
        if (!$st['phone'])  $st['phone']  = $st['tablet'];
    }


    /**
     * Sets responsive styles by property and values.
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $s   CSS selector.
     * @param   string  $p   CSS property.
     * @param   array   $v   CSS values.
     * @param   string  $t   Field type.
     * @param   string  $o   Priority.
     * @param   string  $a   Additional CSS. (eg. ' !important;')
     *
     * @return  void
     */
    private function dvmd_tm_set_responsive_1($s, $p, $v, $t='range', $o='', $a='') {
        et_pb_responsive_options()->generate_responsive_css($v, $s, $p, $this->slug, $a, $t, $o);
    }


    /**
     * Gets and sets responsive styles for a field.
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
    private function dvmd_tm_set_responsive_2($s, $p, $f, $t='range', $o='', $a='') {
        $v = et_pb_responsive_options()->get_property_values($this->props, $f);
        et_pb_responsive_options()->generate_responsive_css($v, $s, $p, $this->slug, $a, $t, $o);
    }


    /**
     * Sets responsive styles by property and formatted values.
     *
     * For example:
     * $v  =  ['desktop' => [50, 50], 'tablet' => '', 'phone' => [25, 25]];
     * $vf = 'translate(%s%%, %s%%)'
     * $v  =  ['desktop' => 'translate(50%, 50%)', 'tablet' => '', 'phone' => 'translate(25%, 25%)'];
     *
     * @since   3.0.0
     * @access  private
     *
     * @param   string  $s   CSS selector.
     * @param   string  $p   CSS property.
     * @param   array   $v   CSS values.
     * @param   string  $vf  CSS values format.
     * @param   string  $t   Field type.
     * @param   string  $o   Priority.
     * @param   string  $a   Additional CSS. (eg. ' !important;')
     *
     * @return  void
     */
    private function dvmd_tm_set_responsive_3($s, $p, $v, $vf='%s', $t='range', $o='', $a='') {
        foreach ($v as &$val) if (!empty($val)) $val = vsprintf($vf, (array)$val);
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
new DVMD_Table_Maker_Module;
