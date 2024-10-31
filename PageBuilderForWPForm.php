<?php

/**
 * Plugin Name: Page Builder for WPForms
 * Plugin URI: http://smartforms.rednao.com/getit
 * Description: Create pages using your wpform entries
 * Author: RedNao
 * Author URI: http://rednao.com
 * Version: 2.33
 * Text Domain: rnpagebuilder
 * Domain Path: /languages/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Slug: page-builder-for-wpform
 */

use rnpagebuilder\core\Integration\Adapters\WPForm\Loader\WPFormSubLoader;

require_once dirname(__FILE__).'/AutoLoad.php';
require_once dirname(__FILE__).'/core/api/Api.php';
new WPFormSubLoader('rnpagebuilder','rednaopdfimpwpform',45,6,__FILE__,
    array(
        'ItemId'=>3442,
        'Author'=>'Edgar Rojas',
        'UpdateURL'=>'https://formwiz.rednao.com',
        'FileGroup'=>'PageBuilderForWPForms'
    ));


