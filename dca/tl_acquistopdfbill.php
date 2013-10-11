<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_cds
 */
$GLOBALS['TL_DCA']['tl_acquistopdfbill'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'switchToEdit'                => true,
    		'sql' => array
    		(
            'keys' => array
            (
                'id'    => 'primary'
            )
    		),        
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 5,
            'fields'                  => array('sorting', 'type'),
            'flag'                    => 11,
            'panelLayout'             => 'search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('type'),
            'format'                  => '%s',
            'label_callback'          => array('tl_acquistopdfbill', 'createLabel')
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'cut' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_warengruppen']['cut'],
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_shop_hersteller']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('type'),
        'default'                     => '{title_legend},type,title;',
        'Text'                        => '{title_legend},type;{config_legend},text;{size_pos},posX,posY;{font},fontFace,fontSize,text_color;',
        'Gruppe'                      => '{title_legend},type,title;',
        'Bild'                        => '{title_legend},type;{config_legend},imageSrc;{size_pos},posX,posY,width,height;{border};',
        'Rechteck'                    => '{title_legend},type;{size_pos},posX,posY,width,height;{color},back_color;{border},border_color,borderWidth;',
        'Linie'                       => '{title_legend},type;{size_pos},posX,posY,width,height;{border},border_color,borderWidth;',
        'Datenbank'                   => '{title_legend},type;{config_legend},dbfield;{size_pos},posX,posY;{font},fontFace,fontSize,text_color;',
        'QR-Code'                     => '{title_legend},type;{config_legend},dbfieldOutBlocks;{size_pos},posX,posY,width,height;{border};',
    ),


    // Fields
    'fields' => array
    (
        'id' => array
    		(
    			   'sql'                    => "int(10) unsigned NOT NULL auto_increment"
    		),    
        'pid' => array
    		(
    			   'sql'                    => "int(10) unsigned NOT NULL default '0'"
    		),    
        'tstamp' => array
    		(
    			   'sql'                    => "int(10) unsigned NOT NULL default '0'"
    		),    
        'sorting' => array
    		(
    			   'sql'                    => "int(10) unsigned NOT NULL default '0'"
    		),    
        'type' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['type'],
            'inputType'               => 'select',
            'search'                  => true,
            'options'                 => array('Gruppe', 'Rechteck', 'Linie', 'Bild', 'Text', 'Datenbank', 'QR-Code'),
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'submitOnChange'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['title'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'posX' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['posX'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'), 
            'sql'                     => "float NOT NULL default '0'"
        ),
        'posY' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['posY'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>false, 'maxlength'=>64, 'tl_class'=>'w50'),
            'sql'                     => "float NOT NULL default '0'"
        ),
        'width' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['width'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
            'sql'                     => "float NOT NULL default '0'"
        ),
        'height' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['height'],
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
            'sql'                     => "float NOT NULL default '0'"
        ),
        'imageSrc' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['imageSrc'],
            'inputType'               => 'fileTree',
            'search'                  => false,
            'eval'                    => array('mandatory'=>false,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions'=>'jpg,png,gif'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'fore_color' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['fore_color'],
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>6, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'),
            'sql'                     => "char(6) NOT NULL default ''"
        ),
        'text_color' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['text_color'],
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>6, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'),
            'sql'                     => "char(6) NOT NULL default ''"
        ),
        'back_color' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['back_color'],
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>6, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'),
            'sql'                     => "char(6) NOT NULL default ''"
        ),
        'fontFace' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['fontFace'],
            'inputType'               => 'select',
            'options_callback'        => array('tl_acquistopdfbill', 'listFonts'),
            'eval'                    => array('decodeEntities'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'fontSize' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['fontSize'],
            'default'                 => 10,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>6, 'decodeEntities'=>true, 'tl_class'=>'w50'),       
            'sql'                     => "float NOT NULL default '0'"
        ),
        'text' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['text'],
            'inputType'               => 'textarea',
            'search'                  => false,
            'eval'                    => array('style'=>'height: 60px;', 'mandatory'=>false, 'tl_class'=>'clr'),
            'sql'                     => "text NOT NULL"
        ),
        'borderWidth' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['borderWidth'],
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>6, 'decodeEntities'=>true, 'tl_class'=>'w50'),
            'sql'                     => "float NOT NULL default '0'"
        ),
        'border_color' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['border_color'],
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>6, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'),
            'sql'                     => "char(6) NOT NULL default ''"
        ),
        'dbfield' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['dbfield'],
            'inputType'               => 'radio',
            'options'                 => array
            (
                'block:delivery_address'                                   => 'Block > Lieferadresse',
                'block:billing_address'                                    => 'Block > Rechnungsadresse',
                'block:produkt_list'                                       => 'Block > Produktliste',
                'field:tl_shop_orders.order_id:func'                       => 'Bestellung > Bestellnummer',
                'field:tl_shop_orders.tstamp:date'                         => 'Bestellung > Datum',
                'field:tl_shop_orders.versandpreis:float'                  => 'Bestellung > Versandpreis',
                'field:tl_shop_orders.versandzonen_id'                     => 'Versandzonen > ID',
                'field:tl_shop_orders.versandzonen_id.bezeichnung'         => 'Versandzonen > Bezeichnung',
                'field:tl_shop_orders.zahlungsart_id'                      => 'Zahlungsart > ID',
                'field:tl_shop_orders.zahlungsart_id.bezeichnung'          => 'Zahlungsart > Bezeichnung',
                'field:tl_shop_orders.versandart_id'                       => 'Versandart > ID',
                'field:tl_shop_orders.versandart_id.ab_einkaufpreis:float' => 'Versandart > ab Einkaufspreis',
                'field:tl_shop_orders.versandart_id.preis:float'           => 'Versandart > Preis',
                'field:tl_shop_orders.member_id'                           => 'Kunde > Kundennummer',
                'field:tl_shop_orders.member_id.firstname'                 => 'Kunde > Vorname',
                'field:tl_shop_orders.member_id.lastname'                  => 'Kunde > Nachname',
                'field:tl_shop_orders.member_id.street'                    => 'Kunde > Stra&szlig;e',
                'field:tl_shop_orders.member_id.postalcode'                => 'Kunde > Postleitzahl',
                'field:tl_shop_orders.member_id.city'                      => 'Kunde > Stadt',
                'field:tl_shop_orders.member_id.email'                     => 'Kunde > E-Mail Adresse',
                'field:tl_shop_orders.member_id.deliver_firstname'         => 'Lieferadresse > Vorname',
                'field:tl_shop_orders.member_id.deliver_lastname'          => 'Lieferadresse > Nachname',
                'field:tl_shop_orders.member_id.deliver_lastname'          => 'Lieferadresse > Firma / Verein',
                'field:tl_shop_orders.member_id.deliver_street'            => 'Lieferadresse > Stra&szlig;e',
                'field:tl_shop_orders.member_id.deliver_postalcode'        => 'Lieferadresse > Postleitzahl',
                'field:tl_shop_orders.member_id.deliver_city'              => 'Lieferadresse > Stadt',


            ),
            'eval'                    => array('decodeEntities'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'dbfieldOutBlocks' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_acquistopdfbill']['dbfield'],
            'inputType'               => 'radio',
            'options'                 => array
            (
                'field:tl_shop_orders.tstamp:date'                         => 'Bestellung > Datum',
                'field:tl_shop_orders.versandpreis:float'                  => 'Bestellung > Versandpreis',
                'field:tl_shop_orders.versandzonen_id'                     => 'Versandzonen > ID',
                'field:tl_shop_orders.versandzonen_id.bezeichnung'         => 'Versandzonen > Bezeichnung',
                'field:tl_shop_orders.zahlungsart_id'                      => 'Zahlungsart > ID',
                'field:tl_shop_orders.zahlungsart_id.bezeichnung'          => 'Zahlungsart > Bezeichnung',
                'field:tl_shop_orders.versandart_id'                       => 'Versandart > ID',
                'field:tl_shop_orders.versandart_id.ab_einkaufpreis:float' => 'Versandart > ab Einkaufspreis',
                'field:tl_shop_orders.versandart_id.preis:float'           => 'Versandart > Preis',
                'field:tl_shop_orders.member_id'                           => 'Kunde > Kundennummer',
                'field:tl_shop_orders.member_id.firstname'                 => 'Kunde > Vorname',
                'field:tl_shop_orders.member_id.lastname'                  => 'Kunde > Nachname',
                'field:tl_shop_orders.member_id.street'                    => 'Kunde > Stra&szlig;e',
                'field:tl_shop_orders.member_id.postalcode'                => 'Kunde > Postleitzahl',
                'field:tl_shop_orders.member_id.city'                      => 'Kunde > Stadt',
                'field:tl_shop_orders.member_id.email'                     => 'Kunde > E-Mail Adresse',
                'field:tl_shop_orders.member_id.deliver_firstname'         => 'Lieferadresse > Vorname',
                'field:tl_shop_orders.member_id.deliver_lastname'          => 'Lieferadresse > Nachname',
                'field:tl_shop_orders.member_id.deliver_lastname'          => 'Lieferadresse > Firma / Verein',
                'field:tl_shop_orders.member_id.deliver_street'            => 'Lieferadresse > Stra&szlig;e',
                'field:tl_shop_orders.member_id.deliver_postalcode'        => 'Lieferadresse > Postleitzahl',
                'field:tl_shop_orders.member_id.deliver_city'              => 'Lieferadresse > Stadt'
            ),
            'eval'                    => array('decodeEntities'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''"
        )
    )
);

class tl_acquistopdfbill extends Backend {

    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function listFonts() {
        if ($handle = opendir(FPDF_FONTPATH)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $strSchrift = $file;
                    $strSchrift = str_replace("b.php", "", $strSchrift);
                    $strSchrift = str_replace("bi.php", "", $strSchrift);
                    $strSchrift = str_replace("i.php", "", $strSchrift);
                    $strSchrift = str_replace(".php", "", $strSchrift);

                    $arrSchriften[$strSchrift] = ucfirst($strSchrift);
                }
            }
            closedir($handle);
        }

        return $arrSchriften;
    }

    public function createLabel($arrRow, $strLabel, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false) {

        switch(strtolower($arrRow['type'])) {
            case "gruppe":
                $strIcon = 'folder_page_white.png';
                $strLabel = $arrRow['title'];
                break;;
            case "bild":
                $strIcon = 'image.png';
                $strAdditional = ' <span style="color:#b3b3b3; padding-left:3px;">[' . $arrRow['imageSrc'] . ']</span>';
                break;;
            case "rechteck":
                $strIcon = 'shape_square.png';
                $strAdditional = ' <span style="color:#b3b3b3; padding-left:3px;">[' . $arrRow['width'] . 'x' . $arrRow['height'] . 'mm]</span>';
                break;;
            case "text":
                $strIcon = 'text_align_justify.png';
                $strAdditional = ' <span style="color:#b3b3b3; padding-left:3px;">[' . $arrRow['text'] . ']</span>';
                break;;
            case "datenbank":
                $strIcon = 'database.png';
                $strAdditional = ' <span style="color:#b3b3b3; padding-left:3px;">[' . $arrRow['dbfield'] . ']</span>';
                break;;
            default:
                $strIcon = 'page_white.png';
                break;;
        }

        $image = '/system/modules/acquistoShop_PDFBill/html/icons/' . $strIcon;
        return '<a href="'.$this->generateFrontendUrl($arrRow).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'"' . (($dc->table != 'tl_page') ? ' class="tl_gray"' : '') . LINK_NEW_WINDOW . '>'.$this->generateImage($image, '', $imageAttribute).'</a> '.$strLabel . $strAdditional;
    }
}

?>