<?php
// Adding AMP-related things to the main theme
	global $redux_builder_amp;
	// 0.9. AMP Design Manager Files
	require AMPFORWP_DESIGN_MANGER_FILE;
	require AMPFORWP_CONTENT_ELEMENTS_FUNCTIONS_FILE;
	require AMPFORWP_LOOP_FUNCTIONS_FILE;
	require AMPFORWP_CUSTOMIZER_FILE;
	// Custom AMP Content
	require AMPFORWP_CUSTOM_AMP_CONTENT_FILE;

//----------------------------------------AMPHTML Functions Start---------------------------
	require AMPFORWP_AMPHTML_FILE;
//----------------------------------------AMPHTML Functions End---------------------------


//----------------------------------------AMP code Files Returning Functions Start---------------------------
	require AMPFORWP_FILE_RETURNING_FILE;
//----------------------------------------AMP code Files Returning Functions End---------------------------


//----------------------------------------Ads Functions Start---------------------------
	require AMPFORWP_ADS_FILE;
//----------------------------------------Ads Functions End---------------------------


//----------------------------------------AMP metabox in Editor page Functions Start---------------------------
	require AMPFORWP_METABOX_FILE;
//----------------------------------------AMP metabox in Editor page Functions End---------------------------

//----------------------------------------Misccelenous Feature Functions Start---------------------------
	require AMPFORWP_MISC_FILE;
//----------------------------------------Misccelenous Feature Functions End---------------------------

//----------------------------------------Analytics Functions Start---------------------------
	require AMPFORWP_ANALYTICS_FILE;
//----------------------------------------Analytics Functions End---------------------------


//----------------------------------------Compatibility Functions Start---------------------------
	require AMPFORWP_COMPATIIBLITY_FILE;
//----------------------------------------Compatibility Functions End---------------------------


//----------------------------------------Design-3 Sepecific Functions Start---------------------------
	require AMPFORWP_DESIGN_SPECIFIC_FUNCTIONS;
//----------------------------------------Design-3 Sepecific Functions End---------------------------


//----------------------------------------TItles Functions Start---------------------------
	require AMPFORWP_TITLE_FILE;
//----------------------------------------TItles Functions End---------------------------


//----------------------------------------Widgets output Functions Start--------------------------
	// Code moved from here to widgets.php
	// file and it id required in accelarated-mobile-pages.php file
//----------------------------------------Widgets output Functions Functions End---------------------------


//----------------------------------------SEO Functions Start---------------------------
	require AMPFORWP_SEO_FILE;
//----------------------------------------SEO Functions End---------------------------


//----------------------------------------Structured Data Functions Start---------------------------
	require AMPFORWP_STRUCTURED_DATA_FILE;
//----------------------------------------Structured Data Functions End---------------------------


//----------------------------------------Search Functions Start---------------------------
	require AMPFORWP_SEARCH_FILE;
//----------------------------------------Search Functions End---------------------------


//----------------------------------------Woocommerece ShortCode Functions Start---------------------------
	require AMPFORWP_WOOCOMMERCE_FILE;
//----------------------------------------Woocommerece ShortCode Functions End---------------------------


//----------------------------------------Scripts Functions End---------------------------
	 require AMPFORWP_SCRIPTS_FILE;
//----------------------------------------Scripts Functions End---------------------------
