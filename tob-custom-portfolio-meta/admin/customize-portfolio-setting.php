<?php
function portfolio_setting_customize_register($wp_customize) 
{
	$wp_customize->add_section("portfoliocustomizer", array(
		"title" => __("Portfolio Settings"),
		"priority" => 30,
	));

	$wp_customize->add_setting("portfolio_maintenance_setting", array(
		'type' => 'theme_mod', // or 'option'
  	'capability' => 'edit_theme_options',
  	'theme_supports' => '', // Rarely needed.
  	'default' => '',
  	'transport' => 'postMessage', // or postMessage
  	'sanitize_callback' => '',
	));

	$wp_customize->add_control(new WP_Customize_Control(
		$wp_customize,
		"portfolio_maintenance_mode_option",
		array(
			"label" => "Enable Portfolio Maintenance Metabox",
			"section" => "portfoliocustomizer",
			"settings" => "portfolio_maintenance_setting",
			"type" => "checkbox",
		)
	));

	$wp_customize->add_setting("portfolio_show_in_search_setting", array(
		"default" => "",
		"transport" => "postMessage",
		'default' => 'show',
	));

	$wp_customize->add_control(new WP_Customize_Control(
		$wp_customize,
		"portfolio_show_in_search_option",
		array(
			"label" => "Show Portfolio In Search",
			"section" => "portfoliocustomizer",
			"settings" => "portfolio_show_in_search_setting",
			"type" => "select",
			"choices" => array(
				"show" => "Show",
				"hide" => "Hide",
			)
		)
	));
}

add_action("customize_register","portfolio_setting_customize_register");