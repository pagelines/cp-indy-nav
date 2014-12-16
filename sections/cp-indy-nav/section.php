<?php
/*
Section: DMS Indy Nav
Author: TourKick (Clifford P)
Description: Indy Nav is a fork of the Navbar section but you can customize every item's color, disable mobile menu, and customize mobile menu's button text. It can display parents, children, and grandchildren (3 levels) but does not display an image or search box (except on mobile nav because that's part of DMS default, not Indy Nav). Indy Nav... make it your own!
Class Name: DMSIndyNav
Version: 1.1
Cloning: true
v3: true
Filter: nav, dual-width
*/



/*

IDEAS:

color theme/scheme instead of doubling number of color options (preset + picker). Presets were commented out in v1.1

centered menu instead of left/right
- would then need to do bg on .navline instead of .indynav

menu button (smaller screen .nav-btn-indynav):
-color options
-float override (default: right)
-button color picker

logo / .plbrand:
-add logo ability
-ability to have taller than navbar 29px

search:
-add search ability
-color options for search


KNOWN ISSUES:
.sub-menu.dropdown-menu {
	overflow-x: visible !important; //does not help to override .drag-drop-editing.display-boxed .page-canvas .pl-area { overflow-x: hidden; } when NOT in DMS Editor "Preview Mode"
}

*/


class DMSIndyNav extends PageLinesSection {

	//$this->
	function tk_color_options() {
    	$sectioncoloroptions = array( // ALL HEX's LOWER-CASE
			'bodybg'	=> array('name' => __('PL Background Base Setting', 'indynav') ),
			'text_primary'	=> array('name' => __('PL Text Base Setting', 'indynav') ),
			'linkcolor'	=> array('name' => __('PL Link Base Setting', 'indynav') ),
			'#fbfbfb'	=> array('name' => __('Light Gray', 'indynav') ),
			'#bfbfbf'	=> array('name' => __('Medium Gray', 'indynav') ),
			'#1abc9c'	=> array('name' => __('* Turquoise', 'indynav') ),
			'#16a085'	=> array('name' => __('* Green Sea', 'indynav') ),
			'#40d47e'	=> array('name' => __('* Emerald', 'indynav') ),
			'#27ae60'	=> array('name' => __('* Nephritis', 'indynav') ),
			'#3498db'	=> array('name' => __('* Peter River', 'indynav') ),
			'#2980b9'	=> array('name' => __('* Belize Hole', 'indynav') ),
			'#9b59b6'	=> array('name' => __('* Amethyst', 'indynav') ),
			'#8e44ad'	=> array('name' => __('* Wisteria', 'indynav') ),
			'#34495e'	=> array('name' => __('* Wet Asphalt', 'indynav') ),
			'#2c3e50'	=> array('name' => __('* Midnight Blue', 'indynav') ),
			'#f1c40f'	=> array('name' => __('* Sun Flower', 'indynav') ),
			'#f39c12'	=> array('name' => __('* Orange', 'indynav') ),
			'#e67e22'	=> array('name' => __('* Carrot', 'indynav') ),
			'#d35400'	=> array('name' => __('* Pumpkin', 'indynav') ),
			'#e74c3c'	=> array('name' => __('* Alizarin', 'indynav') ),
			'#c0392b'	=> array('name' => __('* Pomegranate', 'indynav') ),
			'#ecf0f1'	=> array('name' => __('* Clouds', 'indynav') ),
			'#bdc3c7'	=> array('name' => __('* Silver', 'indynav') ),
			'#95a5a6'	=> array('name' => __('* Concrete', 'indynav') ),
			'#7f8c8d'	=> array('name' => __('* Asbestos', 'indynav') ),
			'#791869'	=> array('name' => __('Plum', 'indynav') ),
			'#c23b3d'	=> array('name' => __('Red', 'indynav') ),
			'#0c5cea'	=> array('name' => __('Blue', 'indynav') ),
			'#00aff0'	=> array('name' => __('Light Blue', 'indynav') ),
			'#88b500'	=> array('name' => __('Lime', 'indynav') ),
			'#cf3f20'	=> array('name' => __('Orangey', 'indynav') ),
			'#f27a00'	=> array('name' => __('Yellowy-Orange', 'indynav') ),
		);

		return $sectioncoloroptions;
	}

	//$this->
	function tk_color_setter($colorpickerfield, $coloroptionfield, $colordefault = '') {
		if( !preg_match('/^#/', $coloroptionfield) ) { //does not begin with a hash
			$coloroptionfield = pl_check_color_hash(pl_setting($coloroptionfield)) ? pl_setting($coloroptionfield) : $coloroptionfield;

			if( $coloroptionfield == 'bodybg' ) {
				$coloroptionfield = '#FFFFFF';
			} elseif( $coloroptionfield == 'text_primary' ) {
				$coloroptionfield = '#000000';
			} elseif( $coloroptionfield == 'linkcolor' ) {
				$coloroptionfield = '#337EFF';
			}
		}

		if( pl_check_color_hash($colorpickerfield) ) {
			$setcolor = $colorpickerfield;
		} elseif( pl_check_color_hash($coloroptionfield) ) {
			$setcolor = $coloroptionfield;
		} elseif( pl_check_color_hash($colordefault) ) {
			$setcolor = $colordefault;
		} else {
			$setcolor = '';
		}

		if( pl_check_color_hash($setcolor) ) {
			$setcolor = pl_hashify($setcolor);
		}

		return $setcolor;
	}

	function section_styles() {
		//same script as navbar section so use same JS if already loaded from that
		$scriptsrc = str_replace('http://', '//', $this->base_url.'/navbar.js');
		wp_enqueue_script( 'navbar', $scriptsrc, array( 'jquery' ), pl_get_cache_key(), true );
	}

	function section_head(){
		$sectionid = '#cp-indy-nav' . $this->get_the_id();

		//GET VALUES

		//$bgimg = $this->opt('indynav_bgimg') ? $this->opt('indynav_bgimg') : '';
		$bgcolor = $this->tk_color_setter(
			$this->opt('indynav_color_bg_picker'),
			$this->opt('indynav_color_bg'));

		$toplinkscolor = $this->tk_color_setter(
			$this->opt('indynav_color_links_top_picker'),
			$this->opt('indynav_color_links_top'));

		$toplinkscurrentcolor = $this->tk_color_setter(
			$this->opt('indynav_color_links_top_current_picker'),
			$this->opt('indynav_color_links_top_current'));

		$toplinkscurrentbgcolor = $this->tk_color_setter(
			$this->opt('indynav_color_links_top_current_bg_picker'),
			$this->opt('indynav_color_links_top_current_bg'));

		$subitemscolor = $this->tk_color_setter(
			$this->opt('indynav_color_sub_items_picker'),
			$this->opt('indynav_color_sub_items'));

		$subitemsbgcolor = $this->tk_color_setter(
			$this->opt('indynav_color_sub_items_bg_picker'),
			$this->opt('indynav_color_sub_items_bg'));

		$subitemshovercolor = $this->tk_color_setter(
			$this->opt('indynav_color_sub_items_hover_picker'),
			$this->opt('indynav_color_sub_items_hover'));

		$subitemshoverbg = $this->tk_color_setter(
			$this->opt('indynav_color_sub_items_bg_hover_picker'),
			$this->opt('indynav_color_sub_items_bg_hover'));

		$subitemscurrentcolor = $this->tk_color_setter(
			$this->opt('indynav_color_sub_items_current_picker'),
			$this->opt('indynav_color_sub_items_current'));

		$subitemscurrentbgcolor = $this->tk_color_setter(
			$this->opt('indynav_color_sub_items_current_bg_picker'),
			$this->opt('indynav_color_sub_items_current_bg'));

		$textshadowyes = $this->opt('indynav_dropdown_add_textshadow') ? $this->opt('indynav_dropdown_add_textshadow') : '';

		$caretcolor = $this->tk_color_setter(
			$this->opt('indynav_color_caret_picker'),
			$this->opt('indynav_color_caret'));

		$subcaretcolor = $this->tk_color_setter(
			$this->opt('indynav_color_gchild_caret_picker'),
			$this->opt('indynav_color_gchild_caret'));

		$dropdownsurround = $this->tk_color_setter(
			$this->opt('indynav_color_drop_down_surround_picker'),
			$this->opt('indynav_color_drop_down_surround'));

		$dropdownpaddingbg = $this->tk_color_setter(
			$this->opt('indynav_color_drop_down_padding_bg_picker'),
			$this->opt('indynav_color_drop_down_padding_bg'));

		// GENERATE OUTPUT
		$styleoutput = '<style type="text/css">';

		if($bgcolor) {
			$styleoutput .= sprintf('%s.section-cp-indy-nav .indynav { background-color: %s; }', $sectionid, $bgcolor);
			$styleoutput .= "\n"; //must use double-quotes -- http://php.net/manual/en/language.types.string.php#language.types.string.syntax.double
		}
		if($toplinkscolor) {
			$styleoutput .= sprintf('%s.section-cp-indy-nav .indynav .navline > li > a { color: %s; }', $sectionid, $toplinkscolor);
			$styleoutput .= "\n"; //must use double-quotes
		}
		if($toplinkscurrentcolor || $toplinkscurrentbgcolor) {
			$styleoutput .= sprintf('%s.section-cp-indy-nav .indynav .navline > li.current-menu-item > a { ', $sectionid);
			if($toplinkscurrentcolor) {
				$styleoutput .= sprintf('color: %s; ', $toplinkscurrentcolor);
			}
			if($toplinkscurrentbgcolor) {
				$styleoutput .= sprintf('background-color: %s; ', $toplinkscurrentbgcolor);
			}
			$styleoutput .= "}\n"; //must use double-quotes
		}
		if($subitemscolor || $subitemsbgcolor) { //MIGHT NOT NEED THIS
			$styleoutput .= sprintf('%1$s.section-cp-indy-nav .dropdown-menu li > a, %1$s.section-cp-indy-nav .dropdown-menu li > a, %1$s .dropdown-submenu > a { font-weight: inherit; ', $sectionid);
			if($subitemscolor) {
				$styleoutput .= sprintf('color: %s; ', $subitemscolor);
			}
			if($subitemsbgcolor) {
				$styleoutput .= sprintf('background-color: %s; ', $subitemsbgcolor);
			}
			$styleoutput .= "}\n"; //must use double-quotes
		}
		if($subitemshovercolor || $subitemshoverbg) {
			$styleoutput .= sprintf('%1$s.section-cp-indy-nav .dropdown-menu li > a:hover, %1$s.section-cp-indy-nav .dropdown-menu li > a:focus, %1$s .dropdown-submenu:hover > a { ', $sectionid);
			if($subitemshovercolor) {
				$styleoutput .= sprintf('color: %s; ', $subitemshovercolor);
			}
			if($subitemshoverbg) {
				$styleoutput .= sprintf('background-color: %s; background-image: none; ', $subitemshoverbg);
			}
			$styleoutput .= "}\n"; //must use double-quotes
		}
		if($subitemscurrentcolor || $subitemscurrentbgcolor) {
			$styleoutput .= sprintf('%1$s.section-cp-indy-nav .dropdown-menu li.current-menu-item > a { ', $sectionid);
			if($subitemscurrentcolor) {
				$styleoutput .= sprintf('color: %s; ', $subitemscurrentcolor);
			}
			if($subitemscurrentbgcolor) {
				$styleoutput .= sprintf('background-color: %s; background-image: none; ', $subitemscurrentbgcolor);
			}
			$styleoutput .= "}\n"; //must use double-quotes
		}
		if(!$textshadowyes) {
			$styleoutput .= sprintf('%1$s.section-cp-indy-nav .dropdown-menu li > a:hover, %1$s.section-cp-indy-nav .dropdown-menu li > a:focus, %1$s .dropdown-submenu:hover > a, %1$s.section-cp-indy-nav .dropdown-menu li.current-menu-item > a { text-shadow: none; }', $sectionid);
			$styleoutput .= "\n"; //must use double-quotes
		}
		if($caretcolor) {
			$styleoutput .= sprintf('%s.section-cp-indy-nav .indynav .navline .caret { border-top: 4px solid %s; }', $sectionid, $caretcolor);
			$styleoutput .= "\n"; //must use double-quotes
		}
		if($subcaretcolor) {
			$styleoutput .= sprintf('%s.section-cp-indy-nav .indynav .navline > li:last-child .dropdown-menu .dropdown-submenu > a:after { border-left-color: %s; }', $sectionid, $subcaretcolor);
			$styleoutput .= "\n"; //must use double-quotes
		}
		if($dropdownsurround) {
			$styleoutput .= sprintf('%1$s.section-cp-indy-nav .dropdown-menu { border: 1px solid %2$s; /* Fallback for IE7-8 */ border: 1px solid %2$s; /* box-shadow: 0 5px 10px rgba(0,0,0,.2); */ }', $sectionid, $dropdownsurround);
			$styleoutput .= "\n"; //must use double-quotes
			$styleoutput .= sprintf('%s.section-cp-indy-nav .indynav .navline > .dropdown > .dropdown-menu:before { border-bottom-color: %s; }', $sectionid, $dropdownsurround);
			$styleoutput .= "\n"; //must use double-quotes
		}
		if($dropdownpaddingbg) {
			$styleoutput .= sprintf('%s.section-cp-indy-nav .dropdown-menu { background-color: %s; }', $sectionid, $dropdownpaddingbg);
			$styleoutput .= "\n"; //must use double-quotes
		}

		$styleoutput .= '</style>';
		echo $styleoutput;

	}

	function section_opts(){

		$opts = array(

			array(
				'key'		=> 'indynav_multi_option_styling',
				'type' 		=> 'multi',
				'col'		=> 2,
				'title'		=> __( 'Indy Nav Styling', 'indynav' ),
				//'help'		=> __( 'Color drop-down options beginning with an <strong>asterisk (*)</strong> are from <a href="http://flatuicolors.com/" target="_blank">FlatUIcolors.com</a><br><br>Color Picker overrides Drop-Down Selection, if both are entered for the same setting.', 'indynav' ),
				'opts'		=> array(
					//
/*
					array(
						'key'	=> 'indynav_color_bg',
						'type' 	=> 'select',
						'label'	=> __('Indy Nav Background Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_bg_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Indy Nav Background Color', 'indynav' ),
		                'default'	=> '',
		            ),
					//
/*
					array(
						'key'	=> 'indynav_color_links_top',
						'type' 	=> 'select',
						'label'	=> __('Top Level Links Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_links_top_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Top Level Links Color<br>Default: <span class="pl-link">PL Link Base Setting</span>', 'indynav' ),
		                'default'	=> '',
		            ),
					//
/*
					array(
						'key'	=> 'indynav_color_links_top_current',
						'type' 	=> 'select',
						'label'	=> __('Top Level <em>Current Page</em> Item Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_links_top_current_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Top Level <em>Current Page</em> Item Color', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_links_top_current_bg',
						'type' 	=> 'select',
						'label'	=> __('Top Level <em>Current Page</em> Item <em>Background</em> Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_links_top_current_bg_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Top Level <em>Current Page</em> Item <em>Background</em> Color', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_sub_items',
						'type' 	=> 'select',
						'label'	=> __('Non-Top Level Items Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_sub_items_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Non-Top Level Items Color', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_sub_items_bg',
						'type' 	=> 'select',
						'label'	=> __('Non-Top Level Items <em>Background</em> Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_sub_items_bg_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Non-Top Level Items <em>Background</em> Color', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_sub_items_hover',
						'type' 	=> 'select',
						'label'	=> __('Non-Top Level Items Color <em>On Hover</em>', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_sub_items_hover_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Non-Top Level Items Color <em>On Hover</em>', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_sub_items_bg_hover',
						'type' 	=> 'select',
						'label'	=> __('Non-Top Level Items <em>Background</em> Color <em>On Hover</em>', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_sub_items_bg_hover_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Non-Top Level Items <em>Background</em> Color <em>On Hover</em><br>Default: <span style="color: #2278e8;">#2278e8</span>', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_sub_items_current',
						'type' 	=> 'select',
						'label'	=> __('Non-Top Level <em>Current Page</em> Item Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_sub_items_current_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Non-Top Level <em>Current Page</em> Item Color<br>Default: White', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_sub_items_current_bg',
						'type' 	=> 'select',
						'label'	=> __('Non-Top Level <em>Current Page</em> Item <em>Background</em> Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_sub_items_current_bg_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Non-Top Level <em>Current Page</em> Item <em>Background</em> Color<br>Default: <span style="color: #2278e8;">#2278e8</span>', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
					array(
						'key'			=> 'indynav_dropdown_add_textshadow',
						'type'			=> 'check',
						'label'			=> __( 'Add text-shadow effect to <em>Current Page</em> top-level menu item and when hovering over items in drop-down?<br>Style: <span style="color: rgba(0,0,0,0.5);">0 -1px 0 rgba(0,0,0,0.5)</span>', 'indynav' ),
					),
					//
/*
					array(
						'key'	=> 'indynav_color_caret',
						'type' 	=> 'select',
						'label'	=> __('Top Level Caret (Drop Down Indicator) Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_caret_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Top Level Caret (Drop Down Indicator) Color<br>Default: <span style="color: rgba(0,0,0,0.3);">rgba(0,0,0,0.3)</span>', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_gchild_caret',
						'type' 	=> 'select',
						'label'	=> __('Child Level Caret (Grandchild Indicator) Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_gchild_caret_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Child Level Caret (Grandchild Indicator) Color', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_drop_down_surround',
						'type' 	=> 'select',
						'label'	=> __('Drop Down Border/Outline (and Triangle Pointer) Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_drop_down_surround_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Drop Down Border/Outline (and Triangle Pointer) Color<br>Default: <span style="color: #ccc;">#ccc</span>', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
/*
					array(
						'key'	=> 'indynav_color_drop_down_padding_bg',
						'type' 	=> 'select',
						'label'	=> __('Drop Down Background/Padding Color', 'indynav'),
						'opts' => $this->tk_color_options(),
					),
*/
		            array(
		                'key'		=> 'indynav_color_drop_down_padding_bg_picker',
		                'type'		=> 'color',
		                'label'		=> __( 'Drop Down Background/Padding Color', 'indynav' ),
		                'default'	=> '',
		            ),
		            //
				),

			),
			array(
				'key'		=> 'indynav_multi_option_menu',
				'type' 		=> 'multi',
				'title'		=> __( 'Indy Nav Menu', 'indynav' ),
				'help'		=> __( 'The Indy Nav uses WordPress menus. Select one for use.', 'indynav' ),
				'opts'		=> array(
					array(
							'key'			=> 'indynav_menu' ,
							'type' 			=> 'select_menu',
							'label' 	=> __( 'Select Menu', 'indynav' ),
						),
				),
			),
			array(
				'key'		=> 'indynav_multi_check',
				'type' 		=> 'multi',
				'title'					=> __( 'Indy Nav Configuration Options', 'indynav' ),
				'opts'		=> array(

					array(
						'key'			=> 'indynav_enable_hover',
						'type'			=> 'check',
						'label'			=> __( 'Activate dropdowns on hover.', 'indynav' ),
					),

					array(
						'key'			=> 'indynav_alignment',
						'type'			=> 'check',
						'default'		=> true,
						'label'			=> __( 'Align Menu Right? (Defaults Left)', 'indynav' ),
					),
					array(
						'key'			=> 'indynav_mobile_menu_on',
						'type'			=> 'check',
						'label'			=> __( 'Show Mobile Menu Button / Slide Out Nav Fallback for smaller-width screens?', 'indynav' ),
						'help'			=> __( 'If unchecked, Indy Nav essentially will not display at all on smaller-width screens.<br><br>However, if checked, the menu that will appear is the <em>Primary Navigation Menu</em> (and the <em>Disable Mobile Menu Search Field</em> will be in effect too) from your Global Site Settings -> Layout & Nav -> Default Navigation Setup, <em>NOT</em> the one from your Indy Nav section settings.', 'indynav' ),
					),
					array(
						'key'			=> 'indynav_mobile_menu_text',
						'type'			=> 'text_small',
						'label'			=> __( 'Text to display in Mobile Menu button', 'indynav' ),
						'help'			=> __( 'Default: none<br>Suggestion: You could type in <em>Menu</em> if you want it to appear like the Navbar section button.<br>FYI: Will display in ALL CAPS unless you override the <em>text-transform</em> CSS', 'indynav' ),
					),
/*
					array(
						'key'			=> 'indynav_hidesearch',
						'type'			=> 'check',
						'default'		=> true,
						'label'			=> __(  'Hide Search?', 'indynav' ),
					),
*/
				),

			),



		);

		return $opts;

	}

	function section_template() {

	$class = array();

	//$this->meta['draw'] == 'area' if a full-width section
	//$this->meta['draw'] == 'content' if a content-width section
	if( $this->meta['draw'] == 'area'){ // if full-width mode
		$class[] = 'indynav-full-width';
		$content_width_class = 'pl-content boxed-wrap boxed-nobg'; //has no styling so why added?
	} else {
		$class[] = 'indynav-content-width';
		$content_width_class = '';
	}

	$mobilemenuclasses = $this->opt('indynav_mobile_menu_on') ? 'nav-btn nav-btn-indynav mm-toggle' : '';
	$mobilemenutext = $this->opt('indynav_mobile_menu_text') ? $this->opt('indynav_mobile_menu_text') : '';
		$mobilemenutext = $mobilemenutext ? $mobilemenutext.' ' : ''; //add space after to go before icon

	$align = ( $this->opt('indynav_alignment' ) ) ? $this->opt( 'indynav_alignment' ) : false;
	//$hidesearch = ( $this->opt( 'indynav_hidesearch' ) ) ? $this->opt( 'indynav_hidesearch' ) : false;
		$hidesearch = true;
	$menu = ( $this->opt( 'indynav_menu' ) ) ? $this->opt( 'indynav_menu' ) : null;
	$class[] = ( $this->opt( 'indynav_enable_hover' ) ) ? 'plnav_hover' : '';

	$pull = ( $align ) ? 'right' : 'left';
	$align_class = sprintf( 'pull-%s', $pull );

	$classes = join(' ', $class);

	?>
	<div class="indynav fix <?php echo $classes; ?>">
	  <div class="indynav-inner <?php echo $content_width_class;?>">
	    <div class="indynav-content-pad fix">

		<?php
		//if($indynavtitle) printf( '<span class="indynav-title">%s</span>',$indynavtitle );

		if($mobilemenuclasses) {
			//icon-reorder (from navbar) is alias for fa-bars (used here)
			printf('<a href="javascript:void(0)" class="%s">%s<i class="fa fa-bars"></i> </a>',
			$mobilemenuclasses,
			$mobilemenutext);
		}

		pagelines_register_hook('indynav_before_menu');

		?>
	      		<div class="nav-collapse collapse">
			 <?php
		   		if( ! $hidesearch ) {
       				pagelines_register_hook('indynav_before_search');
					pl_get_search_form();
					pagelines_register_hook('indynav_after_search');
				}

				if ( is_array( wp_get_nav_menu_items( $menu ) ) ) {
					wp_nav_menu(
						array(
							'menu_class'		=> 'font-sub navline pldrop ' . $align_class,
							'menu'				=> $menu,
							'container'			=> null,
							'container_class'	=> '',
							'depth'				=> 3,
							'fallback_cb'		=> '',
							'theme_location'	=> 'main_nav',
						)
					);
				} else {
					if(function_exists('blank_nav_fallback')){
						return blank_nav_fallback();
					} else {
						return '';
					}
				}
			?>
				</div>
				<?php pagelines_register_hook('indynav_after_menu'); ?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
<?php } //section_template

}
