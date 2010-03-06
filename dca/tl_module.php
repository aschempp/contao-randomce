<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

 
/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]	= 'randomCE';
$GLOBALS['TL_DCA']['tl_module']['palettes']['randomce']	= '{title_legend},name,type;{reference_legend},articleAlias;{config_legend},randomCE;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['randomce1']	= '{title_legend},name,type;{reference_legend},articleAlias;{config_legend},randomCE,keepCE;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['randomce2']	= '{title_legend},name,type;{reference_legend},articleAlias;{config_legend},randomCE;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
 

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['randomCE'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_module']['randomCE'],
	'inputType'		=> 'radio',
	'exclude'		=> true,
	'options'		=> array('', '1', '2'),
	'reference'		=> &$GLOBALS['TL_LANG']['tl_module']['randomCE_ref'],
	'eval'			=> array('submitOnChange'=>true),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['keepCE'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_module']['keepCE'],
	'inputType'		=> 'text',
	'exclude'		=> true,
	'default'		=> 10,
	'eval'			=> array('mandatory'=>true, 'rgxp'=>'digit', 'maxlength'=>3),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['articleAlias'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['articleAlias'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_randomce', 'getArticleAlias'),
	'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true),
/*
	'wizard' => array
	(
		array('tl_content', 'editArticleAlias')
	)
*/
);



class tl_module_randomce extends Backend
{
	
		/**
	 * Get all articles and return them as array
	 * @param object
	 * @return array
	 */
	public function getArticleAlias(DataContainer $dc)
	{
		$arrAlias = array();
		$this->loadLanguageFile('tl_article');

		$objAlias = $this->Database->execute("SELECT id, title, inColumn, (SELECT title FROM tl_page WHERE tl_page.id=tl_article.pid) AS parent FROM tl_article ORDER BY parent, sorting");

		while ($objAlias->next())
		{
			$arrAlias[$objAlias->parent][$objAlias->id] = $objAlias->id . ' - ' . $objAlias->title . ' (' . (strlen($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn]) ? $GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] : $objAlias->inColumn) . ')';
		}

		return $arrAlias;
	}
}

