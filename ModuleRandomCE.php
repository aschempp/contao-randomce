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

 
class ModuleRandomCE extends Module
{
	/**
	 * Tempalte
	 */
	protected $strTemplate = 'mod_html';


	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### RANDOM CONTENT ELEMENT ###';

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;
		
		if (!strlen($this->inColumn))
		{
			$this->inColumn = 'main';
		}
		
		switch( $this->randomCE )
		{
			// Keep the whole session
			case '2':
				if ($_SESSION['MOD_RANDOMCE'][$this->id]['element'] > 0)
				{
					$objElement = $this->Database->prepare("SELECT * FROM tl_content WHERE id=?")
												 ->limit(1)
												 ->execute($_SESSION['MOD_RANDOMCE'][$this->id]['element']);
												 
					break;
				}
				
			// Keep a number of times
			case '1':
				if ($_SESSION['MOD_RANDOMCE'][$this->id]['element'] > 0 && $this->keepCE > 0 && $this->keepCE > $_SESSION['MOD_RANDOMCE'][$this->id]['count'])
				{
					$objElement = $this->Database->prepare("SELECT * FROM tl_content WHERE id=?")
												 ->limit(1)
												 ->execute($_SESSION['MOD_RANDOMCE'][$this->id]['element']);
					break;
				}
			
			default:
				$_SESSION['MOD_RANDOMCE'][$this->id]['count'] = 0;
				$objElement = $this->Database->prepare("SELECT * FROM tl_content WHERE pid=? " . ((is_array($GLOBALS['RANDOMCES']) && count($GLOBALS['RANDOMCES'])) ? ' AND id NOT IN (' . implode(',', $GLOBALS['RANDOMCES']) . ') ' : '') . (!BE_USER_LOGGED_IN ? " AND invisible=''" : '') . " ORDER BY RAND()")
											 ->limit(1)
											 ->execute($this->articleAlias);
		}


		if ($objElement->numRows < 1)
		{
			return;
		}
		
		$_SESSION['MOD_RANDOMCE'][$this->id]['element'] = $objElement->id;
		$_SESSION['MOD_RANDOMCE'][$this->id]['count'] = strlen($_SESSION['MOD_RANDOMCE'][$this->id]['count']) ? ($_SESSION['MOD_RANDOMCE'][$this->id]['count']+1) : 1;
		$GLOBALS['RANDOMCES'][] = $objElement->id;

		// Parse element
		$this->Template->html = $this->getContentElement($objElement->id);
	}
}

 