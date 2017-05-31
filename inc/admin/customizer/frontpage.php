<?php


function uswds_frontpage_settings($wp_customize) {


    // Dropdown pages control
     $wp_customize->add_setting( 'frontpage_hero_callout_setting', array(
         'default'        => '1',
         'sanitize_callback' => 'uswds_frontpage_hero_callout_sanitize',
     ) );
     $wp_customize->add_control( 'frontpage_hero_callout_control', array(
         'label'   => 'Callout Button',
         'section' => 'frontpage_section',
         'settings'=> 'frontpage_hero_callout_setting',
         'type'    => 'dropdown-pages',
         'priority' => 1
     ) );


     $wp_customize->add_setting( 'frontpage_section_setting', array(
         'default'        => '',
         'sanitize_callback' => 'uswds_frontpage_sortable_sanitize',
     ) );

     $wp_customize->add_control( new USWDS_Activated_Sortable_Custom_Control( $wp_customize,
        'frontpage_section_control', array(
            'label'   => 'Sortable Sections',
            'section' => 'frontpage_section',
            'settings'=> 'frontpage_section_setting',
            'priority' => 2,
            'choices' => array(
                    'widget-area-1' => 'Widget Area 1',
                    'widget-area-2' => 'Widget Area 2',
                    'widget-area-3' => 'Widget Area 3',
                    'page-content' => 'Page Content'
                )
            )
        )
    );

}
add_action('customize_register', 'uswds_frontpage_settings');



function uswds_frontpage_hero_callout_sanitize($val) {
    $pages = get_posts(array('post_type' => 'page', 'posts_per_page' => -1, 'fields' => 'ids'));

    if( !in_array($val, $pages) && 'publish' == get_post_status( $val ) )
        return null;

    return $val;
}

function uswds_frontpage_sortable_sanitize($val) {
    $valids = array(
        'widget-area-1',
        'widget-area-2',
        'widget-area-3',
        'page-content',
    );

    $valid = true;
    $tmp_val = json_decode($val);
    foreach($tmp_val as $v){
        if( !in_array($v->name, $valids) )
            $valid = false;
    }

    if(!$valid)
        return null;

    return $val;
}