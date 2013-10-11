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
$GLOBALS['TL_DCA']['tl_shop_orders']['list']['operations']['customerMail'] = array
(
    'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders']['customerMail'],
    'href'                => 'key=customerMail',
    'icon'                => 'system/modules/acquistoShopPDFBill/assets/gfx/email_attach.png'
);

$GLOBALS['TL_DCA']['tl_shop_orders']['list']['operations']['exportPDF'] = array
(
    'label'               => &$GLOBALS['TL_LANG']['tl_shop_orders']['exportPDF'],
    'href'                => 'key=exportPDF',
    'icon'                => 'system/modules/acquistoShopPDFBill/assets/gfx/page_white_acrobat.png'
);

class billingPDFController extends Backend {
    var $PDFClass;
    var $orderID = 0;
    var $documentFile;

    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
        $this->import('acquistoShop', 'Shop');
        $this->Import('acquistoShopProduktLoader', 'Produkt');
        $this->import('Files');

        $this->orderID = $this->Input->Get('id');

        include_once(dirname(__FILE__) . '/../classes/fpdf.php');
        $this->PDFClass = new FPDF('P','mm',array(210, 297));
        $this->PDFClass->SetMargins(0, 0, 0, 0);
        $this->PDFClass->AddPage();

        $this->buildDocument($this->Input->Get('id'));

        $this->PDFClass->Output($this->documentFile = tempnam(null, 'acquistoPDFBill'));

        $objFile = new File('rechnung_' . $this->Input->Get('id') . '.pdf');
        $objFile->write(file_get_contents($this->documentFile));
        $objFile->close();

        unset($this->documentFile);
        $this->documentFile = $objFile;
    }

    public function buildDocument($orderID, $structureID = 0) {
        $objStructure = $this->Database->prepare("SELECT * FROM tl_acquistopdfbill WHERE pid=? ORDER BY sorting ASC")->execute($structureID);
        while($objStructure->next()) {
            $objSubcount = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_acquistopdfbill WHERE pid=?")->execute($objStructure->id);
            $this->addElement($objStructure->row());

            if($objSubcount->total) {
                $this->buildDocument($orderID, $objStructure->id);
            }
        }
    }

    public function addElement($row = array()) {
        $objClass = (object) $row;
        $strStyle = null;

        switch(strtolower($objClass->type)) {
            case "text":
                $this->PDFClass->SetTextColor(hexdec(substr($objClass->text_color, 0, 2)), hexdec(substr($objClass->text_color, 2, 2)), hexdec(substr($objClass->text_color, 4, 2)));
                $this->PDFClass->SetFont($objClass->fontFace, '', $objClass->fontSize);
                $this->PDFClass->Text($objClass->posX, $objClass->posY, $objClass->text);
                break;;
            case "bild":
                $this->PDFClass->Image(TL_ROOT . '/' . $objClass->imageSrc, $objClass->posX, $objClass->posY, $objClass->width, $objClass->height);
                break;;
            case "qr-code":
                $objFile = fopen($strTemp = tempnam(null, 'acquistoQR'), 'w+');
                fputs($objFile, file_get_contents('http://chart.apis.google.com/chart?chs=200x200&cht=qr&chl=' . str_replace("field:", "", $objClass->dbfieldOutBlocks)));
                fclose($objFile);

                $this->PDFClass->Image($strTemp, $objClass->posX, $objClass->posY, $objClass->width, $objClass->height, 'PNG');

                unlink($strTemp);
                break;;
            case "rechteck":
                if($objClass->back_color) {
                    $this->PDFClass->SetFillColor(hexdec(substr($objClass->back_color, 0, 2)), hexdec(substr($objClass->back_color, 2, 2)), hexdec(substr($objClass->back_color, 4, 2)));
                    $strStyle .= 'F';
                }

                if($objClass->border_color) {
                    $this->PDFClass->SetDrawColor(hexdec(substr($objClass->border_color, 0, 2)), hexdec(substr($objClass->border_color, 2, 2)), hexdec(substr($border_color->back_color, 4, 2)));
                    $strStyle .= 'D';
                }

                if($objClass->borderWidth) {
                    $this->PDFClass->SetLineWidth($objClass->borderWidth);
                }

                $this->PDFClass->Rect($objClass->posX, $objClass->posY, $objClass->width, $objClass->height, $strStyle);
                break;;
            case "linie":
#                $this->PDFClass->Image(TL_ROOT . '/' . $objClass->imageSrc, $objClass->posX, $objClass->posY, $objClass->width, $objClass->height);
                break;;
            case "datenbank":
                $this->PDFClass->SetTextColor(hexdec(substr($objClass->text_color, 0, 2)), hexdec(substr($objClass->text_color, 2, 2)), hexdec(substr($objClass->text_color, 4, 2)));
                $this->PDFClass->SetFont($objClass->fontFace, '', $objClass->fontSize);

                $arrExplode = explode(":", $objClass->dbfield);
                if(strtolower($arrExplode[0]) == 'block') {
                    $this->addBlock($arrExplode[1], $objClass);
                } elseif(strtolower($arrExplode[0]) == 'field') {
                    $strValue = $this->getDBField($arrExplode[1]);
                    if($arrExplode[2]) {
                        $strValue = $this->formatField($strValue, $arrExplode[2]);
                    }

                    $this->PDFClass->Text($objClass->posX, $objClass->posY, $strValue);
                }
                break;;
        }
    }

    public function addBlock($block, $objClass) {
        $this->PDFClass->SetTextColor(hexdec(substr($objClass->text_color, 0, 2)), hexdec(substr($objClass->text_color, 2, 2)), hexdec(substr($objClass->text_color, 4, 2)));
        $this->PDFClass->SetFont($objClass->fontFace, '', $objClass->fontSize);

        switch(strtolower($block)) {
            case "produkt_list":
                $objBestellung = $this->Database->prepare("SELECT * FROM tl_shop_orders WHERE id=?")->execute($this->orderID);
                $objPositionen = $this->Database->prepare("SELECT * FROM tl_shop_orders_items WHERE pid=?")->execute($this->orderID);

                $this->PDFClass->SetLineWidth(0.1);
                $this->PDFClass->SetDrawColor(0,0,0);

                $this->PDFClass->SetXY($objClass->posX, $objClass->posY);

                /**
                 * Headline
                 **/
                $objClass->back_color = 'EFEFEF';
                $this->PDFClass->SetFillColor(hexdec(substr($objClass->back_color, 0, 2)), hexdec(substr($objClass->back_color, 2, 2)), hexdec(substr($objClass->back_color, 4, 2)));
                $this->PDFClass->Rect($objClass->posX, $objClass->posY, 210 - ($objClass->posX * 2), ($objClass->fontSize / 2), 'F');
                $this->PDFClass->Line($objClass->posX, $this->PDFClass->GetY(), 210 - $objClass->posX, $this->PDFClass->GetY());
                $this->PDFClass->Line($objClass->posX, $this->PDFClass->GetY() + ($objClass->fontSize / 2), 210 - $objClass->posX, $this->PDFClass->GetY() + ($objClass->fontSize / 2));

                $this->PDFClass->Cell(120 - ($objClass->posX * 2), ($objClass->fontSize / 2), 'Produkt', 0, 0, 'L');
                $this->PDFClass->Cell(30, ($objClass->fontSize / 2), 'Menge', 0, 0, 'R');
                $this->PDFClass->Cell(30, ($objClass->fontSize / 2), 'EP', 0, 0, 'R');
                $this->PDFClass->Cell(30, ($objClass->fontSize / 2), 'Summe', 0, 2, 'R');

                /**
                 * Produktliste
                 **/
                while($objPositionen->next()) {
                    $objProdukt = $this->Produkt->load($objPositionen->produkt_id, $objPositionen->attribute);
                    
                    $this->PDFClass->SetX($objClass->posX);
                    $this->PDFClass->Cell(120 - ($objClass->posX * 2), ($objClass->fontSize / 2), $this->formatField($objPositionen->bezeichnung, 'utf8'), 0, 0, 'L');
                    $this->PDFClass->Cell(30, ($objClass->fontSize / 2), $this->formatField($objPositionen->menge, 'utf8'), 0, 0, 'R');
                    $this->PDFClass->Cell(30, ($objClass->fontSize / 2), $this->formatField($objPositionen->preis, 'EUR'), 0, 0, 'R');
                    $this->PDFClass->Cell(30, ($objClass->fontSize / 2), $this->formatField($objPositionen->menge * $objPositionen->preis, 'EUR'), 0, 2, 'R');
//                     if(is_array($objProdukt->filterArray())) {
//                         $varianten = null;
//                         foreach($objProdukt->filterArray() as $item) {
//                             $varianten .= $item->title . ":" . $item->selection . " / ";
//                         }
// 
//                         $this->PDFClass->SetX($objClass->posX);
//                         $this->PDFClass->Cell(210 - ($objClass->posX * 2), ($objClass->fontSize / 2), $this->formatField(substr($varianten, 0, strrpos($varianten, "/")), 'utf8'), 0, 2, 'L');
//                     }
                    $floatEndsumme = $floatEndsumme + ($objPositionen->menge * $objPositionen->preis);

                    $objSteuer = $this->Database->prepare("SELECT * FROM tl_shop_steuersaetze WHERE pid=? && tstamp<? ORDER  BY tstamp DESC")->limit(1)->execute($objPositionen->steuersatz_id, $objBestellung->tstamp);
                    $arrSteuer[$objSteuer->satz]['gesamt'] = $arrSteuer[$objSteuer->satz]['gesamt'] + ($objPositionen->menge * $objPositionen->preis);
                    $arrSteuer[$objSteuer->satz]['steuer'] = $arrSteuer[$objSteuer->satz]['gesamt'] - ($arrSteuer[$objSteuer->satz]['gesamt'] / (($objSteuer->satz + 100) / 100));
                }

                $this->PDFClass->Line($objClass->posX, $this->PDFClass->GetY(), 210 - $objClass->posX, $this->PDFClass->GetY());

                $this->PDFClass->SetX($objClass->posX);
                $this->PDFClass->Cell(180 - ($objClass->posX * 2), ($objClass->fontSize / 2), 'Gesamtsumme:', 0, 0, 'R');
                $this->PDFClass->Cell(30, ($objClass->fontSize / 2), $this->formatField($floatEndsumme, 'EUR'), 0, 2, 'R');

                /**
                 * Steuern auflisten
                 **/
                if(is_array($arrSteuer)) {
                    foreach($arrSteuer as $Satz => $Steuer) {
                        $this->PDFClass->SetX($objClass->posX);
                        $this->PDFClass->Cell(180 - ($objClass->posX * 2), ($objClass->fontSize / 2), 'enth. MwSt. ' . $Satz . '% auf ' . $this->formatField($Steuer['gesamt'], 'EUR') . ':' , 0, 0, 'R');
                        $this->PDFClass->Cell(30, ($objClass->fontSize / 2), $this->formatField($Steuer['steuer'], 'EUR'), 0, 2, 'R');
                    }
                }

                $this->PDFClass->SetX($objClass->posX);
                $this->PDFClass->Cell(180 - ($objClass->posX * 2), ($objClass->fontSize / 2), 'Versand & Zahlung (' . $this->getDBField('tl_shop_orders.versandzonen_id.bezeichnung') . ' / ' . $this->getDBField('tl_shop_orders.zahlungsart_id.bezeichnung') . '):', 0, 0, 'R');
                $this->PDFClass->Cell(30, ($objClass->fontSize / 2), $this->formatField($this->getDBField('tl_shop_orders.versandart_id.preis'), 'EUR'), 0, 2, 'R');

                $this->PDFClass->SetX($objClass->posX);
                $this->PDFClass->Cell(180 - ($objClass->posX * 2), ($objClass->fontSize / 2), 'Endpreis:', 0, 0, 'R');
                $this->PDFClass->Cell(30, ($objClass->fontSize / 2), $this->formatField($this->getDBField('tl_shop_orders.versandart_id.preis') + $floatEndsumme, 'EUR'), 0, 2, 'R');

                $this->PDFClass->Line($objClass->posX, $this->PDFClass->GetY(), 210 - $objClass->posX, $this->PDFClass->GetY());
                break;;
            case "billing_address":
                $objCustomer = (object) unserialize(utf8_encode($this->getDBField('tl_shop_orders.customerData')));

                if($objCustomer->firstname || $objCustomer->lastname) {
                    $this->PDFClass->Text($objClass->posX, $objClass->posY + ($nI++ * ($objClass->fontSize / 2)), $this->formatField($objCustomer->firstname, 'utf8') . " " . $this->formatField($objCustomer->lastname, 'utf8'));
                }

                if($objCustomer->company) {
                    $this->PDFClass->Text($objClass->posX, $objClass->posY + ($nI++ * ($objClass->fontSize / 2)), $this->formatField($objCustomer->company, 'utf8'));
                }

                $this->PDFClass->Text($objClass->posX, $objClass->posY + ($nI++ * ($objClass->fontSize / 2)), $this->formatField($objCustomer->street, 'utf8'));
                $this->PDFClass->Text($objClass->posX, $objClass->posY + ($nI++ * ($objClass->fontSize / 2)), $objCustomer->postal . " " . $this->formatField($objCustomer->city, 'utf8'));
                break;;
            case "delivery_address":                
                $objDeliver = (object) unserialize(utf8_encode($this->getDBField('tl_shop_orders.deliverAddress')));

                if($objDeliver->firstname || $objDeliver->lastname) {
                    $this->PDFClass->Text($objClass->posX, $objClass->posY + ($nI++ * ($objClass->fontSize / 2)), $this->formatField($objDeliver->firstname, 'utf8') . " " . $this->formatField($objDeliver->lastname, 'utf8'));
                }

                if($objDeliver->company) {
                    $this->PDFClass->Text($objClass->posX, $objClass->posY + ($nI++ * ($objClass->fontSize / 2)), $this->formatField($objDeliver->company, 'utf8'));
                }

                $this->PDFClass->Text($objClass->posX, $objClass->posY + ($nI++ * ($objClass->fontSize / 2)), $this->formatField($objDeliver->street, 'utf8'));
                $this->PDFClass->Text($objClass->posX, $objClass->posY + ($nI++ * ($objClass->fontSize / 2)), $objDeliver->postal . " " . $this->formatField($objDeliver->city, 'utf8'));
                break;;
            default:

                break;;
        }
    }

    public function getDBField($field) {
        $arrExplode = explode(".", $field);

        switch(substr_count($field, ".")) {
            case 1:
                $objObject = $this->Database->prepare("SELECT " . $arrExplode[1] . " AS SubSelection FROM " . $arrExplode[0] . " WHERE id =?")->limit(1)->execute($this->orderID);
                break;;
            case 2:
                $objSub = $this->Database->prepare("SELECT " . $arrExplode[1] . " AS SubSelection FROM " . $arrExplode[0] . " WHERE id =?")->limit(1)->execute($this->orderID);

                switch($arrExplode[1]) {
                    case "member_id":
                        $strTable = "tl_shop_orders_customers";
                        $intID = $this->orderID;
                        break;;
                    case "versandzonen_id":
                        $strTable = "tl_shop_versandzonen";
                        $intID = $objSub->SubSelection;
                        break;;
                    case "zahlungsart_id":
                        $strTable = "tl_shop_zahlungsarten";
                        $intID = $objSub->SubSelection;
                        break;;
                    case "versandart_id":
                        $strTable = "tl_shop_versandzonen_varten";
                        $intID = $objSub->SubSelection;
                        break;;
                }

                if($intID && $strTable && $arrExplode[2]) {
                    $objObject = $this->Database->prepare("SELECT " . $arrExplode[2] . " AS SubSelection FROM " . $strTable . " WHERE id =?")->limit(1)->execute($intID);
                }

                break;;
        }

        return utf8_decode(html_entity_decode($objObject->SubSelection));
    }

    public function formatField($value, $format = null) {
        switch(strtolower($format)) {
            case "date":
                $value = date("d.m.Y", $value);
                break;;
            case "float":
                $value = sprintf("%01.2f", $value);
                break;;
            case "eur":
                $value = sprintf("%01.2f", $value) . " €";
                break;;
            case "func":
                $value = $this->Shop->generateOrderID($value);
                break;;
            case "utf8":
                $value = utf8_decode(html_entity_decode($value));
                break;;
            default:
                break;;
        }

        return $value;
    }

    public function customerMail() {
        $objEmail = new Email();
        $objEmail->from = $GLOBALS['TL_CONFIG']['bestell_email'];
        $objEmail->fromName = $GLOBALS['TL_CONFIG']['firmenname'];
        $objEmail->subject = 'Rechnung';

        $objEmail->text = 'Sehr geehrter Kunde,

mit dieser E-Mail erhalten Sie Ihre Rechnung.';

        $objEmail->attachFile(TL_ROOT . '/rechnung_' . $this->Input->Get('id') . '.pdf');
        $objEmail->sendTo($this->getDBField('tl_shop_orders.member_id.email'));
        $this->destroyDocument();
        $this->redirect(ampersand(str_replace('&key=customerMail&id=' . $this->Input->Get('id'), '', $this->Environment->request)));
    }

    public function exportPDF() {
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="rechnung.pdf"');
        header("Content-Transfer-Encoding: binary\n");
        echo($this->documentFile->getContent());
        $this->destroyDocument();
    }

    public function destroyDocument() {
        $this->documentFile->delete();
    }
}

?>