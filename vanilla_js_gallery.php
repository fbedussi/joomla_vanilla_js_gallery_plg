<?php
/**
 * @version		3.0
 * @package		Simple pure JavaScript gallery plugin
 * @author    		Francesco Bedussi
 * @copyright		Copyright (c) 2014 Francesco Bedussi. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.plugin.plugin');
//if (version_compare(JVERSION, '1.6.0', 'ge')){
//	jimport('joomla.html.parameter');
//}

class plgContentVanilla_js_gallery extends JPlugin {

	// Reference parameters
	var $plg_name					= "vanilla_js_gallery";
	var $plg_tag					= "vjsgallery";
	
	
	public function __construct(& $subject, $config)
	{
        parent::__construct($subject, $config);
		
		// Define the DS constant under Joomla! 3.0+
		if (!defined('DS')){
			define('DS', DIRECTORY_SEPARATOR);
		}
	}

	function onContentPrepare($context, &$row, &$params, $page = 0){
		
		$app = JFactory::getApplication();
		$document  = JFactory::getDocument();
		
		// Assign paths
		$sitePath = JPATH_SITE;
		$siteUrl  = JURI::root(true);
		$pluginLivePath = $siteUrl.'/plugins/content/'.$this->plg_name;
		//$defaultImagePath = 'images';
		
		//JFactory::getApplication()->enqueueMessage($pluginLivePath);
		
		// Get parameters value
		$galleries_rootfolder = $this->params->get('galleries_rootfolder','images');
		
		// Cleanups
		// Remove first and last slash if they exist
		if (substr($galleries_rootfolder, 0, 1) == '/') $galleries_rootfolder = substr($galleries_rootfolder, 1);
		if (substr($galleries_rootfolder, -1, 1) == '/') $galleries_rootfolder = substr($galleries_rootfolder, 0, -1);
		
		// Check if plugin is enabled
		if (JPluginHelper::isEnabled('content', $this->plg_name) == false) return;

		// expression to search for
		$regex = "#{".$this->plg_tag."}[^{]*{/".$this->plg_tag."}#i";

		// Find all instances of the plugin and put them in $matches
		preg_match_all($regex, $row->text, $matches);
		
		// Number of plugins
		$count = count($matches[0]);

		// Plugin only processes if there are any images at least in the first gallery
		if (!$count) return;
		
		// Check for basic requirements
		if (!extension_loaded('gd') && !function_exists('gd_info')){
			JError::raiseNotice('', 'GD library missing');
			return;
		}
		if (!is_writable($sitePath.DS.'cache')){
			JError::raiseNotice('', 'Cache folder not writable');
			return;
		}

		// ----------------------------------- Get plugin parameters -----------------------------------

		// Get plugin info
		$plugin = JPluginHelper::getPlugin('content', $this->plg_name);

		// Includes
		require_once (dirname(__FILE__).DS.'includes'.DS.'helper.php');

		// ----------------------------------- Prepare the output -----------------------------------
		
		
		// start the replace loop
		
		foreach ($matches[0] as $key => $match){
			
			//retrive images urls
			//	/	start regex delimiter
			//	src=
			//	(?:	start group and do not capture it
			//	\"	double quote
			//	|	or
			//	'	single 	quote
			//	)	end group
			//	(	start group and capture it
			//	.*	any carcter n times
			//	? non greedy
			//	)
			//	(?:	start group and not capture it
			//	\"	double quote
			//	|	or
			//	'	single 	quote
			//	/	end regex delimiter
			preg_match_all("/src=(?:\"|')(.*?)(?:\"|')/", $match, $imagesUrls);
			
			//retrive images alts
			preg_match_all("/alt=(?:\"|')(.*?)(?:\"|')/", $match, $imagesAlts);
			
			//if there is at least 1 image run the plugin, otherwise simply delete plugin tag
			if (count($imagesUrls[1])) {
				
				$imagesData = vanilla_js_galleryHelper::processImages($galleries_rootfolder,$imagesUrls[1],$imagesAlts[1]);
				
				// Get the path for the layout file
				$path = JPluginHelper::getLayoutPath('content', 'vanilla_js_gallery');

				// Render the pagenav
				ob_start();
				include $path;
				$plg_html = ob_get_clean();
				
				//JFactory::getApplication()->enqueueMessage($plg_html);
			} else {
				$plg_html = "";
			}
			
			// Do the replace
			$row->text = preg_replace("#".$match."#", $plg_html, $row->text);

		}// end foreach

		// Global head includes
		if (JRequest::getCmd('format') == '' || JRequest::getCmd('format') == 'html'){
			$document->addScript($pluginLivePath.'/includes/js/vjsgallery.js');
			$document->addStyleSheet($pluginLivePath.'/includes/css/vjsgallery.css');
		}
		
	} // END FUNCTION

} // END CLASS