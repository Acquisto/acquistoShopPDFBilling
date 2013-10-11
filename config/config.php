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
 * @copyright  pixelSpread.de - 2009
 * @author     Sascha Brandhoff (brandhoff@pixelspread.de)
 * @package    Acquisto Webshop
 * @license    LGPL
 * @filesource
 */


/**
 * Backend module
 */

$GLOBALS['BE_MOD']['acquisto_addons']['acquistoShopPDFBill'] = array
(
    'tables'     => array('tl_acquistopdfbill'),
    'icon'       => 'system/modules/acquistoShopPDFBill/assets/gfx/page_white_acrobat.png',
    'stylesheet' => 'system/modules/acquistoShop/html/style.css'
);

$GLOBALS['BE_MOD']['acquisto_Orders']['acquistoShopOrders']['customerMail'] = array('billingPDFController', 'customerMail');
$GLOBALS['BE_MOD']['acquisto_Orders']['acquistoShopOrders']['exportPDF']    = array('billingPDFController', 'exportPDF');

define('FPDF_FONTPATH', dirname(__FILE__) . '/../fonts/');

?>