<?php

/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 26.08.2015
 * Time: 15:14
 */
class ExelGenerator
{
    const INVOICE_TYPE_KG10 = 5;
    const INVOICE_TYPE_KG9 = 6;
    const INVOICE_TYPE_PRICES = 1;
    const INVOICE_TYPE_SECTORS = 2;
    const INVOICE_TYPE_SECTORS_PRICES = 3;
    const INVOICE_TYPE_DETAIL = 4;
    public static $types = array(
        self::INVOICE_TYPE_PRICES => "Розбиття по цінах",
        self::INVOICE_TYPE_SECTORS => "Розбиття по секторах",
        self::INVOICE_TYPE_SECTORS_PRICES => "Розбиття по секторах та цінах",
        self::INVOICE_TYPE_DETAIL => "Деталізований вал, по місцях"
    );
    private $fileName;
    private $document;
    private $creator;
    private $ticketsData;
    private $headerData;
    private $instance;
    private $limit = 2000;

    public function __construct($instance, $fileName='exel-invoice', $creator='WebClever', $onlyData = false)
    {
        if(!$onlyData) {
            $this->fileName = $fileName;
            $this->creator = $creator;
            $this->instance = $instance;
            $this->initExel();
        }
    }

    private function initExel()
    {
        if(!$this->document) {

            $this->document = new PHPExcel();
            $properties = new PHPExcel_DocumentProperties();
            $properties->setCreator($this->creator);
            $this->document->setProperties($properties);
            header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . $this->fileName.".xls");
        }
        return $this->document;
    }

    public function generateQuoteInvoice()
    {
        $exel = $this->initExel();
        $sheet = $this->changeSheet(0);
        $quote = $this->instance;
        $order_id = $quote->order_id;
        $event_id = $quote->event_id;
        $ticketsData = $this->getDataFromTickets($order_id, $event_id);
        $this->headerData = $this->getHeaderDataQuote();
        $sheet->setTitle("Накладна");

        $sheet->getSheetView()->setZoomScale(80);

//        building header structure
        $sheet->getColumnDimension('A')->setWidth(13,29);
        $sheet->getColumnDimension('B')->setWidth(8,43);
        $sheet->getColumnDimension('C')->setWidth(9,43);
        $sheet->getColumnDimension('D')->setWidth(13,29);
        $sheet->getColumnDimension('E')->setWidth(5,29);
        $sheet->getColumnDimension('F')->setWidth(15,29);
        $sheet->getColumnDimension('G')->setWidth(11,29);
        $sheet->getColumnDimension('H')->setWidth(7,43);
        $sheet->getColumnDimension('I')->setWidth(5,43);
        $sheet->mergeCells('B2:H2');
        $sheet->mergeCells('B3:H3');
        $sheet->mergeCells('B4:D4');
        $sheet->mergeCells('E4:F4');
        $sheet->mergeCells('B6:F6');
        $sheet->mergeCells('E7:F7');
        $sheet->mergeCells('B10:I10');
        $sheet->mergeCells('B11:I11');
        $sheet->mergeCells('B13:I13');
        $sheet->mergeCells('B14:I14');
        $sheet->mergeCells('B17:F17');
        $sheet->mergeCells('H17:I17');
        $sheet->mergeCells('B18:F18');
        $sheet->mergeCells('H18:I18');
        $sheet->mergeCells('B20:I20');
        $sheet->mergeCells('B22:I22');
        $sheet->mergeCells('B23:I23');
        $sheet->mergeCells('A24:B24');
        $sheet->mergeCells('C24:I24');
        $sheet->mergeCells('C25:I25');
        $sheet->mergeCells('A27:E27');
        $sheet->mergeCells('H27:I27');
        $sheet->getRowDimension(27)->setRowHeight(15);
        $sheet->getRowDimension(2)->setRowHeight(18,75);
        $sheet->getRowDimension(1)->setRowHeight(5);
//        add bottom border for editing fields
        $sheet->getStyle('B2:H2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('B2:H2')->getFont()->setBold(true);
        $sheet->getStyle('B2:H2')->getFont()->setItalic(true);
        $sheet->getStyle('B6:F6')->getFont()->setBold(true);
        $sheet->getStyle('E4:F4')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('G6')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('G6')->getFont()->setBold(true);
        $sheet->getStyle('G6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E7:F7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('E7:F7')->getFont()->setBold(true);
        $sheet->getStyle('E7:F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B10:I10')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('B13:I13')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('B17:F17')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('H17:I17')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('B20:I20')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('B20:I20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B22:I22')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('C24:I24')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A27:I27')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A27:I27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B22:I22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A27:I27')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A27:I27')->getAlignment()->setWrapText(true);
//        add static values to header
        $data = $this->headerData;
        $sheet->setCellValue("B2", $data['fromCompany']);
        $sheet->setCellValue("B3", '(найменування підприємства, організації, установи)');
        $sheet->setCellValue("E4", $data['fromYerdpou']);
        $sheet->setCellValue("B10", $data['fromPostAndName']);
        $sheet->setCellValue("B11", '(посада, ініціали, прізвище)');
        $sheet->setCellValue("B13", $data['toPostAndName']);
        $sheet->setCellValue("B14", '(посада, ініціали, прізвище)');
        $sheet->setCellValue("B17", $data['eventFirstTimingDate']);
        $sheet->setCellValue("B18", '(число, місяць, рік)');
        $sheet->setCellValue("H17", $data['eventFirstTimingTime']);
        $sheet->setCellValue("H18", '(година)');
        $sheet->setCellValue("B22", $data['eventName']);
        $sheet->setCellValue("B23", '(назва)');
        $sheet->setCellValue("C25", 'серія (номер)');
        $sheet->setCellValue("A10", 'Видав');
        $sheet->setCellValue("A13", 'Одержав');
        $sheet->setCellValue("A17", 'Дата заходу');
        $sheet->setCellValue("A20", 'Підстава');
        $sheet->setCellValue("A22", 'Захід');
        $sheet->setCellValue("A24", 'Квитки(абонементи)');
        $sheet->setCellValue("B4", 'Ідентифікаційний код ЄДРПОУ');
        $sheet->setCellValue("B6", 'Акт приймання - передачі квиткової інформації №');
        $sheet->setCellValue("G17", 'початок заходу');
        $sheet->setCellValue("A27", 'Квитки (абонементи)');
        $sheet->setCellValue("F27", 'Кількість квитків (абонементів)');
        $sheet->setCellValue("G27", 'Ціна квитка (абон.)');
        $sheet->setCellValue("H27", 'Сума, з ПДВ, грн');
//        add dinamic values
        $this->generateDataQuoteInvoice($ticketsData);
//        set styles to cells
        $sheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('B2')->getFont()->setSize(14);
        $sheet->getStyle('B3')->getFont()->setSize(8);
        $sheet->getStyle('B11')->getFont()->setSize(8);
        $sheet->getStyle('B14')->getFont()->setSize(8);
        $sheet->getStyle('B18')->getFont()->setSize(8);
        $sheet->getStyle('H18')->getFont()->setSize(8);
        $sheet->getStyle('B23')->getFont()->setSize(8);
        $sheet->getStyle('C25')->getFont()->setSize(8);

        $sheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('B11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('B14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('B18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('H18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('B23')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('C25')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        $sheet->removeRow(8, 2);
        $sheet->removeRow(10, 1);
        $sheet->removeRow(12, 2);
        $sheet->removeRow(14, 1);

        $objWriter = new PHPExcel_Writer_Excel5($exel);
        $objWriter->save('php://output');
    }

    public function changeSheet($pageNumber=0)
    {
        if(is_integer($pageNumber))
        {
            $exel = $this->document;
            $exel->setActiveSheetIndex(0);
            return $exel->getActiveSheet();
        } else
            throw new CException("page number must be type of integer");
    }

    private function getTicketsData($order_id,$event_id,$cancelStatuses=[Ticket::STATUS_QUOTE_RETURN,Ticket::STATUS_CANCEL])
    {

        $tickets = [];

        $scheme_id= Yii::app()->db->createCommand()
            ->select("scheme_id")
            ->from("{{event}}")
            ->where("id=:id", array(
                ":id"=>$event_id
            ))
            ->queryScalar();
        $sector_ids = Yii::app()->db->createCommand()
            ->select("id")
            ->from("{{sector}}")
            ->where("scheme_id=:scheme_id", array(
                ":scheme_id"=>$scheme_id
            ))
            ->queryColumn();

        $ticketsData = Yii::app()->db->createCommand()
            ->select("t.*")
            ->from(Ticket::model()->tableName()." t")
            ->where("t.event_id=:event_id",array("event_id"=>$event_id))
            ->andWhere("t.order_id=:order_id",array("order_id"=>$order_id))
            ->andWhere(array("in", "sector_id", $sector_ids))
            ->andWhere(array("not in", "status", $cancelStatuses))
            ->queryAll();
        $place_ids = [];
        foreach ($ticketsData as $ticket) {
            $place_ids[] = $ticket["place_id"];
            $tickets[$ticket["place_id"]] = (object)$ticket;
        }

        $placesData = Yii::app()->db->createCommand()
            ->select("t.*")
            ->from(Place::model()->tableName()." t")
            ->andWhere(array("in", "id", $place_ids))
            ->queryAll();
        $places = [];
        foreach ($placesData as $place)
            $places[$place["id"]] = (object)$place;

        $sectors = $this->getSectorNamesWithPrefix($scheme_id);

        foreach ($places as $place) {
            $place->sectorName = $sectors[$place->sector_id];
            $ticket = $tickets[$place->id];
            $ticket->place = $place;
            $tickets[$place->id] = $ticket;
        }
        return $tickets;
    }

    public function getDataFromTickets($order_id,$event_id,$forView=false,$status=[])
    {
        if($forView)
            $tickets = $this->getTicketsData($order_id,$event_id,$status);
        else
            $tickets = $this->getTicketsData($order_id,$event_id);
        $ticketsData = array();
        $ticketFilterByRow = array();
        usort($tickets,function($a,$b) {
            return $a->price - $b->price;
        });
        foreach ($tickets as $ticket) {
            if(array_key_exists($ticket->place->row,$ticketFilterByRow))
                array_push($ticketFilterByRow[$ticket->place->row], $ticket);
            else
                $ticketFilterByRow[$ticket->place->row] = array($ticket);
        }
//todo filter by sector
        foreach ($ticketFilterByRow as $row=>$filteredTickets) {
            $arrBySector = array();
            foreach ($filteredTickets as $ticket)
                $arrBySector[$ticket->place->sector_id][$ticket->price][] = $ticket;


            foreach ($arrBySector as $sector_id=>$data) {
                foreach ($data as $price=>$tickets) {
                    $placeMinMax = $this->getMinMaxPlace($tickets,false);

                    $countByMinMax = $placeMinMax['max'] - $placeMinMax['min'] + 1;
                    $tempPlacesArray = [];
                    $tempCount = count($tickets);

                    if ($countByMinMax != $tempCount) {
                        usort($tickets, function ($a, $b) {
                            return $a->place->place - $b->place->place;
                        });
                        $i = 0;
                        foreach ($tickets as $tTicket) {
                            $i++;
                            if (empty($tempPlacesArray)) {
                                $tempPlacesArray[] = $tTicket;
                            } else {
                                $lastPlace = end($tempPlacesArray);
                                $diffPlace = $lastPlace->place->place - $tTicket->place->place;
                                if ($diffPlace < -1 || $diffPlace > 1) {
                                    $count = count($tempPlacesArray);
                                    $sum = $price * $count;
                                    $placeMinMax = $this->getMinMaxPlace($tempPlacesArray,false);
                                    $sectorName = $tTicket->place->sectorName;
                                    if ($placeMinMax['min'] == $placeMinMax['max'])
                                        $places = $placeMinMax['min'];
                                    else
                                        $places = $placeMinMax['min'] . "-" . $placeMinMax['max'];
                                    $placesName = $sectorName . " р.: " . $row . ", м.: " . $places;
                                    if ($row == 0 && $places == 0)
                                        $placesName = $sectorName;
                                    if(!$forView)
                                        $ticketsData[] = array('placesName' => $placesName, 'count' => $count, 'price' => $price, 'sum' => $sum);
                                    else {
                                        $placeMin = $placeMinMax['min'] != 0 ? $placeMinMax['min'] : "-";
                                        $placeMax = $placeMinMax['max'] != 0 ? $placeMinMax['max'] : "-";
                                        $ticketsData[] = array('sector' => $sectorName, "row"=>$row, "placeFrom"=>$placeMin, "placeTo"=>$placeMax, 'count' => $count, 'price' => $price, 'sum' => $sum);
                                    }
                                    $tempPlacesArray = [];
                                    $tempPlacesArray[] = $tTicket;
                                } else {
                                    $tempPlacesArray[] = $tTicket;
                                }
                            }

                            if ($tempCount == $i && !empty($tempPlacesArray)) {
                                $count = count($tempPlacesArray);
                                $sum = $price * $count;
                                $placeMinMax = $this->getMinMaxPlace($tempPlacesArray,false);
                                $sectorName = $tTicket->place->sectorName;

                                if ($placeMinMax['min'] == $placeMinMax['max'])
                                    $places = $placeMinMax['min'];
                                else
                                    $places = $placeMinMax['min'] . "-" . $placeMinMax['max'];
                                $placesName = $sectorName . " р.: " . $row . ", м.: " . $places;
                                if ($row == 0 && $places == 0)
                                    $placesName = $sectorName;
                                if(!$forView)
                                    $ticketsData[] = array('placesName' => $placesName, 'count' => $count, 'price' => $price, 'sum' => $sum);
                                else {
                                    $placeMin = $placeMinMax['min'] != 0 ? $placeMinMax['min'] : "-";
                                    $placeMax = $placeMinMax['max'] != 0 ? $placeMinMax['max'] : "-";
                                    $ticketsData[] = array('sector' => $sectorName, "row"=>$row, "placeFrom"=>$placeMin, "placeTo"=>$placeMax, 'count' => $count, 'price' => $price, 'sum' => $sum);
                                }
                                $tempPlacesArray = [];
                            }
                        }
                    } else {
                        $ticket = current($tickets);
                        $sectorName = $ticket->place->sectorName;

                        if ($placeMinMax['min'] == $placeMinMax['max'])
                            $places = $placeMinMax['min'];
                        else
                            $places = $placeMinMax['min'] . "-" . $placeMinMax['max'];
                        $count = count($tickets);
                        $sum = $price * $count;
                        $placesName = $sectorName . " р.: " . $row . ", м.: " . $places;
                        if ($row == 0 && $places == 0)
                            $placesName = $sectorName;
                        if(!$forView)
                            $ticketsData[] = array('placesName' => $placesName, 'count' => $count, 'price' => $price, 'sum' => $sum);
                        else {
                            $placeMin = $placeMinMax['min'] != 0 ? $placeMinMax['min'] : "-";
                            $placeMax = $placeMinMax['max'] != 0 ? $placeMinMax['max'] : "-";
                            $ticketsData[] = array('sector' => $sectorName, "row"=>$row, "placeFrom"=>$placeMin, "placeTo"=>$placeMax, 'count' => $count, 'price' => $price, 'sum' => $sum);
                        }
                    }
                }
            }
        }
        usort($ticketsData,function($a,$b) {
            return $a['price'] - $b['price'];
        });
        return $ticketsData;
    }

    private function getMinMaxPlace($models,$isActiveRecord = true)
    {
        $minPlace = PHP_INT_MAX;
        $maxPlace = 0;
        foreach ($models as $model) {
            if($isActiveRecord) {
                if (CHtml::modelName($model) == "Ticket") {
                    if ($maxPlace < $model->place->place)
                        $maxPlace = $model->place->place;
                    if ($minPlace > $model->place->place)
                        $minPlace = $model->place->place;
                } else {
                    if ($maxPlace < $model->place)
                        $maxPlace = $model->place;
                    if ($minPlace > $model->place)
                        $minPlace = $model->place;
                }
            }
            if ($maxPlace < $model->place->place)
                $maxPlace = $model->place->place;
            if ($minPlace > $model->place->place)
                $minPlace = $model->place->place;
        }
        return array('min'=>$minPlace, 'max'=>$maxPlace);
    }

    private function getHeaderDataQuote()
    {
        $quote = $this->instance;
        $roleFrom = Role::model()->findByPk($quote->role_from_id);
        $roleTo = Role::model()->findByPk($quote->role_to_id);
        $event = Event::model()->findByPk($quote->event_id);
		$timing = $event->timings;
		$timing = current($timing);
		$timingTimestamp = strtotime($timing->start_sale);
		$timingDate = Yii::app()->dateFormatter->format('"d" MMMM yyyy року', $timingTimestamp);
		$timingTime = Yii::app()->dateFormatter->format('HH:mm', $timingTimestamp);


        return array("fromCompany"=>$roleFrom->company_name,"fromYerdpou"=>$roleFrom->code_yerdpou, "fromPostAndName"=>$roleFrom->post." ".$roleFrom->real_name,
            "eventFirstTimingDate"=> $timingDate,"eventFirstTimingTime"=>$timingTime, "toPostAndName"=>$roleTo->post." ".$roleTo->real_name, "eventName"=>$event->name);
    }

    private function generateDataQuoteInvoice($ticketsData)
    {
        $data = $ticketsData;
//        if(empty($data) || !is_array($data))
//            throw new CDbException("data cannot be empty and must me an array");
        $sheet = $this->document->getActiveSheet();
        $currentRow = 28;
        $sumTickets = 0;
        $sumPrices = 0;

        foreach ($data as $element){
            $this->generateQuoteRowTable($currentRow);
            $sheet->setCellValue('A'.$currentRow.'', $element['placesName']);
            $sheet->setCellValue('F'.$currentRow.'', $element['count']);
            $sheet->setCellValue('G'.$currentRow.'', $element['price']);
            $sheet->setCellValue('H'.$currentRow.'', $element['sum']);
            $sumTickets += (int)$element['count'];
            $sumPrices += (int)$element['sum'];
            $currentRow++;
        }

        $this->generateQuoteRowTable($currentRow);
        $currentRow++;
        $this->generateQuoteRowTable($currentRow);
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('A'.$currentRow.'', "УСЬОГО");
        $sheet->setCellValue('F'.$currentRow.'', $sumTickets);
        $sheet->setCellValue('H'.$currentRow.'', $sumPrices);

        $this->generateQuoteInvoiceFooter($currentRow,$sumTickets,$sumPrices);
    }

    private function generateQuoteRowTable($currentRow)
    {
        $sheet = $this->document->getActiveSheet();
        $sheet->mergeCells('A'.$currentRow.':E'.$currentRow.'');
        $sheet->mergeCells('H'.$currentRow.':I'.$currentRow.'');
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('F'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }

    private function generateQuoteInvoiceFooter($lastDataRow,$sumTickets,$sumPrices)
    {
        $currentRow = $lastDataRow + 2;
        $sheet = $this->document->getActiveSheet();
        $sheet->setCellValue('A'.$currentRow.'', "Усього кількість");
        $sheet->mergeCells('B'.$currentRow.':I'.$currentRow.'');
        $sheet->setCellValue('B'.$currentRow.'', PriceToTextConverter::convert($sumTickets,false));
        $sheet->getStyle('B'.$currentRow.':I'.$currentRow.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('B'.$currentRow.':I'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $currentRow++;
        $sheet->mergeCells('B'.$currentRow.':I'.$currentRow.'');
        $sheet->setCellValue('B'.$currentRow.'', "(літерами)");
        $sheet->getStyle('B'.$currentRow.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('B'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B'.$currentRow.'')->getFont()->setSize(8);
        $currentRow++;
        $sheet->setCellValue('A'.$currentRow.'', "Усього на сумму");
        $sheet->mergeCells('B'.$currentRow.':I'.$currentRow.'');
        $sheet->setCellValue('B'.$currentRow.'', PriceToTextConverter::convert($sumPrices));
        $sheet->getStyle('B'.$currentRow.':I'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B'.$currentRow.':I'.$currentRow.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->mergeCells('B'.$currentRow.':I'.$currentRow.'');
        $sheet->setCellValue('B'.$currentRow.'', "(літерами)");
        $sheet->getStyle('B'.$currentRow.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('B'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B'.$currentRow.'')->getFont()->setSize(8);
        $currentRow++;
        $sheet->setCellValue('A'.$currentRow.'', "В тому числі ПДВ");
        $sheet->mergeCells('B'.$currentRow.':I'.$currentRow.'');
        $sheet->setCellValue('B'.$currentRow.'', PriceToTextConverter::convert($sumPrices/6));
        $sheet->getStyle('B'.$currentRow.':I'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B'.$currentRow.':I'.$currentRow.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $currentRow++;
        $currentRow++;
        $sheet->setCellValue('A'.$currentRow.'', "Видав");
        $sheet->getStyle('A'.$currentRow.'')->getFont()->setBold(true);
        $sheet->mergeCells('B'.$currentRow.':D'.$currentRow.'');
        $sheet->getStyle('B'.$currentRow.':D'.$currentRow.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->setCellValue('F'.$currentRow.'', "Одержав");
        $sheet->getStyle('F'.$currentRow.'')->getFont()->setBold(true);
        $sheet->mergeCells('G'.$currentRow.':I'.$currentRow.'');
        $sheet->getStyle('G'.$currentRow.':I'.$currentRow.'')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->mergeCells('B'.$currentRow.':D'.$currentRow.'');
        $sheet->mergeCells('G'.$currentRow.':I'.$currentRow.'');
        $sheet->setCellValue('B'.$currentRow.'', "(підпис, ініціали, прізвище)");
        $sheet->setCellValue('G'.$currentRow.'', "(підпис, ініціали, прізвище)");
        $sheet->getStyle('B'.$currentRow.'')->getFont()->setSize(8);
        $sheet->getStyle('G'.$currentRow.'')->getFont()->setSize(8);
        $sheet->getStyle('B'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B'.$currentRow.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle('G'.$currentRow.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

    }

    public function generateEventInvoice()
    {
        throw new CException("is currently under development");
    }

    public function generatePlacePriceInvoice()
    {
        $exel = $this->initExel();
        $sheet = $this->changeSheet(0);
        $sheet->setTitle("Вал по ціні");

        $sheet->getSheetView()->setZoomScale(80);

//        building header structure
        $sheet->getColumnDimension('A')->setWidth(17,29);
        $sheet->getColumnDimension('B')->setWidth(17,29);
        $sheet->getColumnDimension('C')->setWidth(17,29);
        $event = $this->instance;
        $places = $event->places;
        $placesData = $this->getPlacesPriceData($places);
        $currentRow = 1;

        $sheet->getStyle('A'.$currentRow.':C'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->setCellValue('A'.$currentRow.'', "Ціна");
        $sheet->setCellValue('B'.$currentRow.'', "Кількість");
        $sheet->setCellValue('C'.$currentRow.'', "Всього");
        $sheet->getStyle('A'.$currentRow.':C'.$currentRow.'')->getFont()->setBold(true);

        $totalSum = 0;
        $totalCount = 0;
        foreach ($placesData as $dataRow) {
            $currentRow++;
            $sheet->getStyle('A'.$currentRow.':C'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->setCellValue('A'.$currentRow.'', $dataRow['price']);
            $sheet->setCellValue('B'.$currentRow.'', $dataRow['count']);
            $sheet->setCellValue('C'.$currentRow.'', $dataRow['total']);
            $totalCount += $dataRow['count'];
            $totalSum += $dataRow['total'];
        }
        $currentRow++;
        $sheet->getStyle('A'.$currentRow.':C'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->getStyle('A'.$currentRow.':C'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->setCellValue('A'.$currentRow.'', "Усього");
        $sheet->setCellValue('B'.$currentRow.'', $totalCount);
        $sheet->setCellValue('C'.$currentRow.'', $totalSum);
        $sheet->getStyle('A'.$currentRow.':C'.$currentRow.'')->getFont()->setBold(true);
        $sheet->getStyle('A1:C'.$currentRow.'')->getFont()->setSize(14);

        $objWriter = new PHPExcel_Writer_Excel5($exel);
        $objWriter->save('php://output');
    }

    private function getPlacesPriceData($places)
    {
        $placesData = array();
        $placeFilterByPrice = array();
        $price = array();
        foreach ($places as $key => $row)
        {
            $price[$key] = $row['price'];
        }
        array_multisort($price, SORT_ASC, $places);
        foreach ($places as $place) {
            if(array_key_exists($place->price,$placeFilterByPrice))
                array_push($placeFilterByPrice[$place->price], $place);
            else
                $placeFilterByPrice[$place->price] = array($place);
        }

        foreach ($placeFilterByPrice as $key=>$places) {
            $placesCount = count($places);
            $place = current($places);
            $totalPrice = $place->price * $placesCount;
            $placesData[] = array("price"=>$place->price,"count"=>$placesCount,"total"=>$totalPrice);
        }


        return $placesData;
    }

    public function generatePlaceSectorPriceInvoice()
    {
        $exel = $this->initExel();
        $sheet = $this->changeSheet(0);
        $sheet->setTitle("Вал по сектору та ціні");

        $sheet->getSheetView()->setZoomScale(80);

//        building header structure
        $sheet->getColumnDimension('A')->setWidth(21,29);
        $sheet->getStyle('A')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('B')->setWidth(17,29);
        $sheet->getColumnDimension('C')->setWidth(17,29);
        $sheet->getColumnDimension('D')->setWidth(17,29);
        $event = $this->instance;
        $places = $event->places;
        $placesData = $this->getPlacesSectorPriceData($places, $event->scheme_id);
        $currentRow = 1;

        $sheet->getStyle('A'.$currentRow.':D'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->setCellValue('A'.$currentRow.'', "Сектор");
        $sheet->setCellValue('B'.$currentRow.'', "Ціна");
        $sheet->setCellValue('C'.$currentRow.'', "Кількість");
        $sheet->setCellValue('D'.$currentRow.'', "Всього");
        $sheet->getStyle('A'.$currentRow.':D'.$currentRow.'')->getFont()->setBold(true);

        $totalSum = 0;
        $totalCount = 0;
        $currentRow++;
        foreach ($placesData as $key=>$dataRows) {
            $endRow = $currentRow + count($dataRows) - 1;
            $sheet->mergeCells('A'.$currentRow.':A'.$endRow.'');
            $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A'.$currentRow.':A'.$endRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->setCellValue('A'.$currentRow.'', $key);
            foreach ($dataRows as $dataRow) {
                $sheet->getStyle('B'.$currentRow.':D'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->setCellValue('B'.$currentRow.'', $dataRow['price']);
                $sheet->setCellValue('C'.$currentRow.'', $dataRow['count']);
                $sheet->setCellValue('D'.$currentRow.'', $dataRow['total']);
                $totalCount += $dataRow['count'];
                $totalSum += $dataRow['total'];
                $currentRow++;
            }
        }
        $sheet->getStyle('A'.$currentRow.':D'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->getStyle('A'.$currentRow.':D'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->setCellValue('A'.$currentRow.'', "Всього");
        $sheet->setCellValue('C'.$currentRow.'', $totalCount);
        $sheet->setCellValue('D'.$currentRow.'', $totalSum);
        $sheet->getStyle('A'.$currentRow.':D'.$currentRow.'')->getFont()->setBold(true);
        $sheet->getStyle('A1:D'.$currentRow.'')->getFont()->setSize(14);

        $objWriter = new PHPExcel_Writer_Excel5($exel);
        $objWriter->save('php://output');
    }

    private function getPlacesSectorPriceData($places,$scheme_id)
    {
        $placeFilterBySector = array();
        $placeFilterByPrice = array();
        $price = array();
        foreach ($places as $key => $row)
        {
            $price[$key] = $row['price'];
        }
        array_multisort($price, SORT_ASC, $places);

        $sectorFullNames = $this->getSectorNamesWithPrefix($scheme_id);
        foreach ($places as $place) {
            if(array_key_exists($place->price,$placeFilterByPrice))
                array_push($placeFilterByPrice[$place->price], $place);
            else
                $placeFilterByPrice[$place->price] = array($place);
        }

        foreach ($placeFilterByPrice as $price=>$places) {
            $place = current($places);

            $placesCount = count($places);
            $totalPrice = $price * $placesCount;
            $tempPlaceSectors = [];
            $tempPlaceSectors[] = $place->sector_id;
            $placeFilterBySectors = array();

            foreach ($places as $tempPlace) {
                if($tempPlace->sector_id != $place->sector_id){
                    $tempPlaceSectors[] = $tempPlace->sector_id;
                }
            }
            if(count($tempPlaceSectors) > 1){
                foreach ($places as $tempPlace){
                    if (array_key_exists($sectorFullNames[$tempPlace->sector_id], $placeFilterBySectors))
                        array_push($placeFilterBySectors[$sectorFullNames[$tempPlace->sector_id]], $tempPlace);
                    else
                        $placeFilterBySectors[$sectorFullNames[$tempPlace->sector_id]] = array($tempPlace);
                }
                foreach ($placeFilterBySectors as $sector=>$tempPlaces){
                    $count = count($tempPlaces);
                    $sum = $price * $count;
                    if (array_key_exists($sector, $placeFilterBySector))
                        array_push($placeFilterBySector[$sector], array('price' => $price,
                            "count" => $count, "total" => $sum));
                    else
                        $placeFilterBySector[$sector] = array(array('price' => $price,
                            "count" => $count, "total" => $sum));
                }
                continue;
            }

            if (array_key_exists($sectorFullNames[$place->sector_id],$placeFilterBySector))
                array_push($placeFilterBySector[$sectorFullNames[$place->sector_id]], array("price"=>$place->price,"count"=>$placesCount,"total"=>$totalPrice));
            else
                $placeFilterBySector[$sectorFullNames[$place->sector_id]] = array(array("price"=>$place->price,"count"=>$placesCount,"total"=>$totalPrice));
        }
        return $placeFilterBySector;

    }

    private function getSectorNamesWithPrefix($scheme_id=null,$sectors=null)
    {
        if($scheme_id && empty($sectors))
            $sectors = Yii::app()->db->createCommand()
                ->select("t.id,t.name,t.type_sector_id")
                ->from(Sector::model()->tableName()." t")
                ->andWhere("t.scheme_id=:scheme_id",array("scheme_id"=>$scheme_id))
                ->queryAll();

        $type_ids = [];
        foreach ($sectors as $sector) {
            if(!in_array($sector['type_sector_id'],$type_ids))
                $type_ids[] = $sector['type_sector_id'];
        }
        $typeSectors = Yii::app()->db->createCommand()
            ->select("t.id,t.name")
            ->from(TypeSector::model()->tableName()." t")
            ->andWhere(array("in", "t.id", $type_ids))
            ->queryAll();
        $result = [];
        foreach ($sectors as $sector) {
            $prefixName ='';
            foreach ($typeSectors as $prefix)
                if($prefix['id'] == $sector['type_sector_id'])
                    $prefixName = $prefix['name']." ";
            $result[$sector['id']] = $prefixName.$sector['name'];
        }
        return $result;
    }

    public function getPlaceSectorInvoice()
    {
        $exel = $this->initExel();
        $sheet = $this->changeSheet(0);
        $sheet->setTitle("Вал по сектору");

        $sheet->getSheetView()->setZoomScale(80);

//        building header structure
        $sheet->getColumnDimension('A')->setWidth(21,29);
        $sheet->getStyle('A')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('B')->setWidth(17,29);
        $sheet->getColumnDimension('C')->setWidth(17,29);
        $event = $this->instance;
        $places = $event->places;
        $placesData = $this->getPlacesSectorData($places, $event->scheme_id);
        $currentRow = 1;
        $sheet->getStyle('A'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A'.$currentRow.':C'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->setCellValue('A'.$currentRow.'', "Сектор");
        $sheet->setCellValue('B'.$currentRow.'', "Кількість");
        $sheet->setCellValue('C'.$currentRow.'', "Всього (ціна)");
        $sheet->getStyle('A'.$currentRow.':D'.$currentRow.'')->getFont()->setBold(true);
        foreach ($placesData as $sector=>$dataRow) {
            $currentRow++;
            $sheet->getStyle('A'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('A'.$currentRow.':C'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->setCellValue('A'.$currentRow.'', $sector);
            $sheet->setCellValue('B'.$currentRow.'', $dataRow['count']);
            $sheet->setCellValue('C'.$currentRow.'', $dataRow['sum']);
        }
        $sheet->getStyle('A1:C'.$currentRow.'')->getFont()->setSize(14);

        $objWriter = new PHPExcel_Writer_Excel5($exel);
        $objWriter->save('php://output');
    }

    private function getPlacesSectorData($places, $scheme_id)
    {
        $sectorFullNames = $this->getSectorNamesWithPrefix($scheme_id);
        $result = array();
        $filteredBySector = array();
        $rowData = array();
        foreach ($places as $key => $row)
        {
            $rowData[$key] = $row['sector_id'];
        }
        array_multisort($rowData, SORT_ASC, $places);

        foreach ($places as $place) {
            if (array_key_exists($sectorFullNames[$place->sector_id],$filteredBySector))
                array_push($filteredBySector[$sectorFullNames[$place->sector_id]], $place);
            else
                $filteredBySector[$sectorFullNames[$place->sector_id]] = array($place);
        }

        foreach ($filteredBySector as $sector=>$places) {
            $count = count($places);
            $sum = 0;

            foreach ($places as $place)
                $sum += $place->price;

            $result[$sector] = array("count"=>$count, "sum"=>$sum);
        }

        return $result;
    }

    public function getPlacesDetailInvoice()
    {
        $exel = $this->initExel();
        $sheet = $this->changeSheet(0);
        $sheet->setTitle("Деталізований вал");

        $sheet->getSheetView()->setZoomScale(80);

//        building header structure
        $sheet->getColumnDimension('A')->setWidth(19,29);
        $sheet->getStyle('A')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('B')->setWidth(15,29);
        $sheet->getColumnDimension('C')->setWidth(17,29);
        $sheet->getColumnDimension('D')->setWidth(17,29);
        $sheet->getColumnDimension('E')->setWidth(19,29);
        $sheet->getColumnDimension('F')->setWidth(17,29);
        $sheet->getColumnDimension('G')->setWidth(27,29);
        $sheet->getColumnDimension('H')->setWidth(19,29);
        $sheet->getColumnDimension('I')->setWidth(17,29);
        $sheet->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $event = $this->instance;
        $places = $event->places;
        $placesData = $this->getPlacesDetailData($places, $event->scheme_id);
        $currentRow = 1;

        $sheet->getStyle('A'.$currentRow.':I'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->setCellValue('A'.$currentRow.'', "Сектор");
        $sheet->setCellValue('B'.$currentRow.'', "Ряд");
        $sheet->setCellValue('C'.$currentRow.'', "Місце з");
        $sheet->setCellValue('D'.$currentRow.'', "Місце по");
        $sheet->setCellValue('E'.$currentRow.'', "Ціна");
        $sheet->setCellValue('F'.$currentRow.'', "Кількість");
        $sheet->setCellValue('G'.$currentRow.'', "Сума");
        $sheet->setCellValue('H'.$currentRow.'', "Всього кількість");
        $sheet->setCellValue('I'.$currentRow.'', "Всього сума");
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow.'')->getFont()->setBold(true);

        $currentRow++;
        $count = 0;
        $sum = 0;

        foreach ($placesData as $sector => $rows)
        {
            $rowses = array();
            foreach ($rows as $key=>$row){

                $rowses[$key] = $row['row'];
            }
            array_multisort($rowses, SORT_ASC, $placesData[$sector]);
        }

        foreach ($placesData as $sector=>$dataRows) {
            $lastRow = $currentRow + count($dataRows) - 1;
            $sheet->mergeCells('A'.$currentRow.':A'.$lastRow.'');
            $sheet->mergeCells('H'.$currentRow.':H'.$lastRow.'');
            $sheet->mergeCells('I'.$currentRow.':I'.$lastRow.'');
            $sheet->getStyle('A'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$currentRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle('H'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H'.$currentRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle('I'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I'.$currentRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->setCellValue('A'.$currentRow.'', $sector);
            $tempRow = $currentRow;
            $tempCount = 0;
            $tempSum = 0;

            foreach ($dataRows as $dataRow){
                $sheet->getStyle('A'.$currentRow.':I'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $sheet->setCellValue('B'.$currentRow.'', $dataRow['row']);
                $sheet->setCellValue('C'.$currentRow.'', $dataRow['minPlace']);
                $sheet->setCellValue('D'.$currentRow.'', $dataRow['maxPlace']);
                $sheet->setCellValue('E'.$currentRow.'', $dataRow['price']);
                $sheet->setCellValue('F'.$currentRow.'', $dataRow['count']);
                $sheet->setCellValue('G'.$currentRow.'', $dataRow['sum']);
                $count += $dataRow['count'];
                $sum += $dataRow['sum'];
                $tempCount += $dataRow['count'];
                $tempSum += $dataRow['sum'];
                $currentRow++;
            }

            $sheet->setCellValue('H'.$tempRow.'', $tempCount);
            $sheet->setCellValue('I'.$tempRow.'', $tempSum);
        }
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow.'')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('A'.$currentRow.'', "Всього");
        $sheet->setCellValue('F'.$currentRow.'', $count);
        $sheet->setCellValue('G'.$currentRow.'', $sum);
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow.'')->getFont()->setBold(true);
        $sheet->getStyle('A1:I'.$currentRow.'')->getFont()->setSize(14);
        $sheet->freezePane( "A2" );
        $currentRow++;
        $currentRow++;

        $sheet->mergeCells("B".$currentRow.":H".$currentRow);
        $sheet->setCellValue('B'.$currentRow.'', "Разом квитків : ".PriceToTextConverter::convert($count, false)." шт");
        $sheet->getStyle("B".$currentRow.":H".$currentRow)->getFont()->setSize(15);
        $currentRow++;
        $sheet->mergeCells("B".$currentRow.":H".$currentRow);
        $sheet->setCellValue('B'.$currentRow.'', "Разом сумма : ".PriceToTextConverter::convert($sum, false)." грн");
        $sheet->getStyle("B".$currentRow.":H".$currentRow)->getFont()->setSize(15);
        $objWriter = new PHPExcel_Writer_Excel5($exel);
        $objWriter->save('php://output');
    }

    private function getPlacesDetailData($places, $scheme_id)
    {
        $placesData = array();
        $placeFilterByRow = array();

        $sectorFullNames = $this->getSectorNamesWithPrefix($scheme_id);
        $price = array();
        foreach ($places as $key => $row) {
            $price[$key] = $row['price'];
        }
        array_multisort($price, SORT_ASC, $places);
        $newPlaces = [];
        foreach ($places as $place) {
            if (array_key_exists($place->row, $placeFilterByRow))
                array_push($placeFilterByRow[$place->row], $place);
            else
                $placeFilterByRow[$place->row] = array($place);
        }
        foreach ($placeFilterByRow as $row => $filteredPlaces) {
            $pricedArray = [];
            foreach ($filteredPlaces as $place) {
                if (array_key_exists($place->price, $pricedArray))
                    array_push($pricedArray[$place->price], $place);
                else
                    $pricedArray[$place->price] = array($place);
            }
            $newPlaces[$row] = $pricedArray;
        }

        foreach ($newPlaces as $row => $priced) {
            foreach ($priced as $price => $places) {
                $placeMinMax = $this->getMinMaxPlace($places);
                $count = count($places);
                $sum = $price * $count;
                $place = current($places);
                $tempPlaceSectors = [];
                $tempPlaceSectors[] = $place->sector_id;
                $placeFilterBySector = array();

                foreach ($places as $tempPlace) {
                    if ($tempPlace->sector_id != $place->sector_id) {
                        $tempPlaceSectors[] = $tempPlace->sector_id;
                    }
                }
                if (count($tempPlaceSectors) > 1) {
                    foreach ($places as $tempPlace) {
                        if (array_key_exists($sectorFullNames[$tempPlace->sector_id], $placeFilterBySector))
                            array_push($placeFilterBySector[$sectorFullNames[$tempPlace->sector_id]], $tempPlace);
                        else
                            $placeFilterBySector[$sectorFullNames[$tempPlace->sector_id]] = array($tempPlace);
                    }
                    foreach ($placeFilterBySector as $sector => $tempPlaces) {
                        $count = count($tempPlaces);
                        $sum = $price * $count;
                        $place = current($tempPlaces);
                        $placeMinMax = $this->getMinMaxPlace($tempPlaces);
                        $countByMinMax = $placeMinMax['max'] - $placeMinMax['min'] + 1;
                        $tempPlacesArray = [];
                        $tempCount = count($tempPlaces);
                        if ($countByMinMax > $tempCount) {
                            usort($places, function ($a, $b) {
                                return $a->place - $b->place;
                            });
                            $i = 0;
                            foreach ($places as $tPlace) {
                                $i++;
                                if (empty($tempPlacesArray)) {
                                    $tempPlacesArray[] = $tPlace;
                                } else {
                                    $lastPlace = end($tempPlacesArray);
                                    $diffPlace = $lastPlace->place - $tPlace->place;
                                    if ($diffPlace < -1 || $diffPlace > 1) {
                                        $count = count($tempPlacesArray);
                                        $sum = $price * $count;
                                        $placeMinMax = $this->getMinMaxPlace($tempPlacesArray);
                                        $temp = current($tempPlacesArray);
                                        if (array_key_exists($sectorFullNames[$temp->sector_id], $placesData))
                                            array_push($placesData[$sectorFullNames[$temp->sector_id]], array('row' => $temp->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                                "count" => $count, "sum" => $sum));
                                        else
                                            $placesData[$sectorFullNames[$temp->sector_id]] = array(array('row' => $temp->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                                "count" => $count, "sum" => $sum));
                                        $tempPlacesArray = [];
                                        $tempPlacesArray[] = $tPlace;
                                    } else {
                                        $tempPlacesArray[] = $tPlace;
                                    }
                                }

                                if ($tempCount == $i && !empty($tempPlacesArray)) {
                                    $count = count($tempPlacesArray);
                                    $sum = $price * $count;
                                    $placeMinMax = $this->getMinMaxPlace($tempPlacesArray);
                                    $temp = current($tempPlacesArray);
                                    if (array_key_exists($sectorFullNames[$temp->sector_id], $placesData))
                                        array_push($placesData[$sectorFullNames[$temp->sector_id]], array('row' => $temp->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                            "count" => $count, "sum" => $sum));
                                    else
                                        $placesData[$sectorFullNames[$temp->sector_id]] = array(array('row' => $temp->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                            "count" => $count, "sum" => $sum));
                                    $tempPlacesArray = [];
                                }
                            }
                            continue;
                        }

                        if (array_key_exists($sector, $placesData))
                            array_push($placesData[$sector], array('row' => $place->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                "count" => $count, "sum" => $sum));
                        else
                            $placesData[$sector] = array(array('row' => $place->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                "count" => $count, "sum" => $sum));
                    }
                    continue;
                }


                $countByMinMax = $placeMinMax['max'] - $placeMinMax['min'] + 1;
                $tempPlacesArray = [];
                $tempCount = count($places);
                if ($countByMinMax > $tempCount) {
                    usort($places, function ($a, $b) {
                        return $a->place - $b->place;
                    });
                    $i = 0;
                    foreach ($places as $tPlace) {
                        $i++;
                        if (empty($tempPlacesArray)) {
                            $tempPlacesArray[] = $tPlace;
                        } else {
                            $lastPlace = end($tempPlacesArray);
                            $diffPlace = $lastPlace->place - $tPlace->place;
                            if ($diffPlace < -1 || $diffPlace > 1) {
                                $count = count($tempPlacesArray);
                                $sum = $price * $count;
                                $placeMinMax = $this->getMinMaxPlace($tempPlacesArray);
                                $temp = current($tempPlacesArray);
                                if (array_key_exists($sectorFullNames[$temp->sector_id], $placesData))
                                    array_push($placesData[$sectorFullNames[$temp->sector_id]], array('row' => $temp->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                        "count" => $count, "sum" => $sum));
                                else
                                    $placesData[$sectorFullNames[$temp->sector_id]] = array(array('row' => $temp->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                        "count" => $count, "sum" => $sum));
                                $tempPlacesArray = [];
                                $tempPlacesArray[] = $tPlace;
                            } else {
                                $tempPlacesArray[] = $tPlace;
                            }
                        }

                        if ($tempCount == $i && !empty($tempPlacesArray)) {
                            $count = count($tempPlacesArray);
                            $sum = $price * $count;
                            $placeMinMax = $this->getMinMaxPlace($tempPlacesArray);
                            $temp = current($tempPlacesArray);
                            if (array_key_exists($sectorFullNames[$temp->sector_id], $placesData))
                                array_push($placesData[$sectorFullNames[$temp->sector_id]], array('row' => $temp->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                    "count" => $count, "sum" => $sum));
                            else
                                $placesData[$sectorFullNames[$temp->sector_id]] = array(array('row' => $temp->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                                    "count" => $count, "sum" => $sum));
                            $tempPlacesArray = [];
                        }
                    }
                    continue;
                }
                if (array_key_exists($sectorFullNames[$place->sector_id], $placesData))
                    array_push($placesData[$sectorFullNames[$place->sector_id]], array('row' => $place->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                        "count" => $count, "sum" => $sum));
                else
                    $placesData[$sectorFullNames[$place->sector_id]] = array(array('row' => $place->row, 'minPlace' => $placeMinMax['min'], 'maxPlace' => $placeMinMax['max'], 'price' => $price,
                        "count" => $count, "sum" => $sum));
            }
        }


        return $placesData;
    }

    public function generateTicketsInvoice()
    {
        $sheet = $this->changeSheet(0);
        $sheet->setTitle("Звіт по квиткам");

        $sheet->getSheetView()->setZoomScale(100);

        $sheet->getRowDimension(2)->setRowHeight(45);

        $sheet->mergeCells("H1:I1");
        $sheet->mergeCells("M1:N1");
        $sheet->mergeCells("R1:S1");
        $letters = range("A","Z");
        $letters[] = "AA";
        $letters[] = "AB";
        $letters[] = "AC";
        $letters[] = "AD";
        $largeWidthArr = ["A","I","N","S","X"];
        $smallWidthArr = ["C","D"];
        foreach ($letters as $letter) {
            $sheet->getStyle($letter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($letter)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle($letter)->getAlignment()->setWrapText(true);
            $sheet->getStyle($letter)->getFont()->setSize(10);
            if(in_array($letter,$largeWidthArr))
                $sheet->getColumnDimension($letter)->setWidth(29,29);
            elseif (in_array($letter,$smallWidthArr))
                $sheet->getColumnDimension($letter)->setWidth(9,29);
            else
                $sheet->getColumnDimension($letter)->setWidth(15,86);
        }

        $currentRow = 1;
        $sheet->setCellValue('H'.$currentRow.'', "Хто створив замовлення");
        $sheet->getStyle('H'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('M'.$currentRow.'', "Хто прийняв оплату");
        $sheet->getStyle('M'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('R'.$currentRow.'', "Хто роздрукував замовлення");
        $sheet->getStyle('R'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$currentRow.':AD'.$currentRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$currentRow.':AD'.$currentRow)->getFont()->setBold(true);
        $currentRow++;
        $sheet->getStyle('A'.$currentRow)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('A'.$currentRow.'', "Назва/Місто/Дата/Час");
        $sheet->setCellValue('B'.$currentRow.'', "Сектор");
        $sheet->setCellValue('C'.$currentRow.'', "Ряд");
        $sheet->setCellValue('D'.$currentRow.'', "Місце");
        $sheet->setCellValue('E'.$currentRow.'', "Ціна");
        $sheet->setCellValue('F'.$currentRow.'', "Штрих-код");
        $sheet->setCellValue('G'.$currentRow.'', "Замовлення");
        $sheet->setCellValue('H'.$currentRow.'', "Гравець (партнер, каса)");
        $sheet->setCellValue('I'.$currentRow.'', "Користувач (ID касира, ПІБ касира)");
        $sheet->setCellValue('J'.$currentRow.'', "Дата створення");
        $sheet->setCellValue('K'.$currentRow.'', "Спосіб доставки");
        $sheet->setCellValue('L'.$currentRow.'', "Статус доставки");
        $sheet->setCellValue('M'.$currentRow.'', "Гравець (партнер, каса)");
        $sheet->setCellValue('N'.$currentRow.'', "Користувач (ID касира, ПІБ касира)");
        $sheet->setCellValue('O'.$currentRow.'', "Спосіб оплати");
        $sheet->setCellValue('P'.$currentRow.'', "Статус оплати");
        $sheet->setCellValue('Q'.$currentRow.'', "Дата оплати");
        $sheet->setCellValue('R'.$currentRow.'', "Гравець (партнер, каса)");
        $sheet->setCellValue('S'.$currentRow.'', "Користувач (ID касира, ПІБ касира)");
        $sheet->setCellValue('T'.$currentRow.'', "Дата друку");
        $sheet->setCellValue('U'.$currentRow.'', "Статус квитка");
        $sheet->setCellValue('V'.$currentRow.'', "Тип замовлення");
        $sheet->setCellValue('W'.$currentRow.'', "Формат квитка");
        $sheet->setCellValue('X'.$currentRow.'', "ПІБ власника замовлення (або лише прізвище)");
        $sheet->setCellValue('Y'.$currentRow.'', "тел власника");
        $sheet->setCellValue('Z'.$currentRow.'', "e-mail власника");
        $sheet->setCellValue('AA'.$currentRow.'', "Країна");
        $sheet->setCellValue('AB'.$currentRow.'', "Область");
        $sheet->setCellValue('AC'.$currentRow.'', "Місто");
        $sheet->setCellValue('AD'.$currentRow.'', "Теги");
        $sheet->getStyle('A'.$currentRow.':AD'.$currentRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$currentRow.':AD'.$currentRow)->getFont()->setBold(true);

        $count = self::filterAcceptedData(true);
        $currentRow ++;
        $fileUrl = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$this->fileName.".xls";
        for ($i=0; $i<$count/$this->limit; $i++) {
            if (file_exists($fileUrl)) {
                $objReader = new PHPExcel_Reader_Excel5();
                $objPHPExcel = $objReader->load($fileUrl);
                $this->document = $objPHPExcel;
                $sheet = $this->changeSheet();
            }

            $data = self::filterAcceptedData(false, $this->limit, $i*$this->limit);

            foreach ($data as $ticketData) {
                $ticketData = $this->getTicketData($ticketData);
                $letter = "A";
                while (list($key, $val) = each($ticketData)) {
                    if($letter == 'F')
                        $sheet->setCellValueExplicit($letter.$currentRow, $val, PHPExcel_Cell_DataType::TYPE_STRING);
                    else
                        $sheet->setCellValue($letter.$currentRow,$val);

                    $letter++;
                }
                $currentRow ++;
            }
            if ($i+1>=$count/$this->limit)
                $sheet->freezePane( "A3" );
            $objWriter = new PHPExcel_Writer_Excel5($this->document);
            $objWriter->save($fileUrl);
            $this->document->disconnectWorksheets();
            unset($objPHPExcel);
            unset($objWriter);
            unset($this->document);
            unset($data);
        }
        readfile($fileUrl);
    }

    public static function filterAcceptedData($count = false, $limit=false, $offset=false)
    {
        $filter_id = Yii::app()->request->getParam('filter_id');
        $search = false;
        $order = false;
        if ($filter_id) {
            $filter = OrderFilter::model()->findByAttributes(array(
                "id"=>$filter_id,
                "user_id"=>Yii::app()->user->id
            ));
            if ($filter) {
                $settings = json_decode($filter->settings);

                $search = $settings->search;
                $order = (array)$settings->Order;
            }
        }

        $search = $search ? :Yii::app()->request->getParam('search');

        $order = $order? :Yii::app()->request->getParam('Order');

        $model = new Order('searchOrders');

        $model->pay_method = Order::PAY_ALL;
        $model->creator = User::TYPE_ALL;
        $orders = [];
        if ($search) {
            $model->attributes = $order;
            $orders = $model->searchOrders(false, $offset, $limit, true, $count);
        }
        return $orders;
    }

    private function getTicketData($ticket)
    {
        if(isset($ticket["order_type"]))
            $ticket["order_type"] = Order::$orderTypes[$ticket["order_type"]];
        else
            $ticket["order_type"] = '';

        if(isset($ticket['ticket_format']))
            $ticket['ticket_format'] = Ticket::$ticketFormat[$ticket['ticket_format']];
        else
            $ticket["ticket_format"] = '';

        $ticket['fullName'] = $ticket['fullName']." ".$ticket['start_time'];
        $ticket["delivery_status"] = $ticket["delivery_status"] != 0 ? Ticket::$statusDelivery[$ticket["delivery_status"]] : '';
        $ticket["pay_status"] = $ticket["pay_status"] != null ? Ticket::$payStatus[$ticket["pay_status"]] : '';
        $ticket["pay_type"] = $ticket["pay_type"] != null && $ticket["pay_type"] < 3 ? Order::$pay_methods[$ticket["pay_type"]] : '';
        $ticket["ticket_status"] = $ticket["ticket_status"] != null && in_array($ticket["ticket_status"], Ticket::$ticketStatus) ? Ticket::$ticketStatus[$ticket["ticket_status"]] : '';
        $ticket["delivery_type"] = $ticket["delivery_type"] != null ? Order::$delTypes[$ticket["delivery_type"]] : '';


        unset($ticket['id']);
        unset($ticket['event_id']);
        unset($ticket['author_print_id']);
        unset($ticket['cash_user_id']);
        unset($ticket['user_id']);
        unset($ticket['role_id']);
        unset($ticket['cash_role_id']);
        unset($ticket['print_role_id']);
        unset($ticket['start_time']);
        return $ticket;
    }

    private function getSecondaryData($tickets)
    {
        $ordersData = [];
        $rolesData = [];
        $usersData = [];
        $placesData = [];
        $locationData = [];
        $eventsData = [];
        $order_ids = [];
        $user_ids = [];
        $role_ids = [];
        $place_ids = [];
        $sector_ids = [];
        $scheme_ids = [];

        foreach ($tickets as $ticket) {
            if (!in_array($ticket->order_id, $order_ids) && isset($ticket->order_id))
                $order_ids[] = $ticket->order_id;
            if (!in_array($ticket->cash_user_id, $user_ids) && isset($ticket->cash_user_id))
                $user_ids[] = $ticket->cash_user_id;
            if (!in_array($ticket->author_print_id, $user_ids) && isset($ticket->author_print_id))
                $user_ids[] = $ticket->author_print_id;
            if (!in_array($ticket->cash_role_id, $role_ids) && isset($ticket->cash_role_id))
                $role_ids[] = $ticket->cash_role_id;
            if (!in_array($ticket->print_role_id, $role_ids) && isset($ticket->print_role_id))
                $role_ids[] = $ticket->print_role_id;
            if (!in_array($ticket->place_id, $place_ids) && isset($ticket->place_id))
                $place_ids[] = $ticket->place_id;

        }
        $event_ids = [];
        $places = Yii::app()->db->createCommand()
        ->select("t.id,t.place,t.row,t.sector_id, t.event_id")
        ->from(Place::model()->tableName()." t")
        ->andWhere(array("in", "t.id", $place_ids))
        ->queryAll();

        foreach ($places as $place) {
            $placesData[$place["id"]] = $place;
            if (!in_array($place["sector_id"], $sector_ids) && isset($place["sector_id"]))
                $sector_ids[] = $place["sector_id"];
            $event_ids[$place['id']] = $place["event_id"];
        }

        $sectors = Yii::app()->db->createCommand()
            ->select("t.id,t.name,t.type_sector_id,t.scheme_id")
            ->from(Sector::model()->tableName()." t")
            ->andWhere(array("in", "t.id", $sector_ids))
            ->queryAll();
        $sectorScheme = [];
        $sectorPrefixes = $this->getSectorNamesWithPrefix(null,$sectors);
        foreach ($sectors as $sector) {
            if (!in_array($sector["scheme_id"], $scheme_ids) && isset($sector["scheme_id"]))
                $scheme_ids[$sector["id"]] = $sector["scheme_id"];
            $sectorScheme[$sector["id"]] = $sector["scheme_id"];
        }

        $schemes = Scheme::model()->with("location.city.country","location.city.region")->findAllByAttributes(["id"=>$scheme_ids]);
        foreach ($schemes as $scheme) {
            $locationData[$scheme->id] = $scheme;
        }

        $events = Event::getListEvents([],[],$event_ids);
        $events_temp = [];
        foreach ($events as $event){
            $events_temp[$event["id"]] = $event;
        }

        foreach ($places as $place)
            $eventsData[$place["id"]] = $events_temp[$event_ids[$place["id"]]];



        $orders = Yii::app()->db->createCommand()
            ->select("t.id,t.user_id,t.role_id,t.type")
            ->from(Order::model()->tableName()." t")
            ->andWhere(array("in", "t.id", $order_ids))
            ->queryAll();

        foreach ($orders as $order) {
            $ordersData[$order["id"]] = $order;
            if (!in_array($order['user_id'], $user_ids) && isset($order['user_id']))
                $user_ids[] = $order['user_id'];
            if (!in_array($order['role_id'], $role_ids) && isset($order['role_id']))
                $role_ids[] = $order['role_id'];
        }

        $roles = Yii::app()->db->createCommand()
            ->select("t.id,t.name")
            ->from(Role::model()->tableName()." t")
            ->andWhere(array("in", "t.id", $role_ids))
            ->queryAll();

        foreach ($roles as $role)
            $rolesData[$role["id"]] = $role["name"];

        $users = Yii::app()->db->createCommand()
            ->select("t.id,t.name,t.surname")
            ->from(User::model()->tableName()." t")
            ->andWhere(array("in", "t.id", $user_ids))
            ->queryAll();
        foreach ($users as $user)
            $usersData[$user["id"]] = $user;

        $result["order_data"] = $ordersData;
        $result["role_data"] = $rolesData;
        $result["user_data"] = $usersData;
        $result["sector_data"] = $sectorPrefixes;
        $result["place_data"] = $placesData;
        $result["location_data"] = $locationData;
        $result["event_data"] = $eventsData;
        $result["sector_scheme"] = $sectorScheme;
        return $result;
    }


    public function generateKG9Invoice()
    {
        $exel = $this->initExel();
        $sheet = $this->changeSheet(0);
        $sheet->setTitle("KG 9");
        $event = $this->instance;
        $sheet->getSheetView()->setZoomScale(100);

        $sheet->getRowDimension(19)->setRowHeight(35);
        $minTiming =  Yii::app()->db->createCommand()
            ->select("MIN(t.start_sale)")
            ->from(Timing::model()->tableName()." t")
            ->andWhere("t.event_id=:event_id",array("event_id"=>$event->id))
            ->queryScalar();
        $minTiming =  Yii::app()->dateFormatter->format("dd MMMM yyyy року", $minTiming);
        $currDate =  Yii::app()->dateFormatter->format("dd MMMM yyyy року", time());
        $invoiceNumber = 0;
        $query = "INSERT INTO {{invoice}} (invoice_type) VALUES (".self::INVOICE_TYPE_KG9.")";
        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (Yii::app()->db->createCommand($query)->execute()) {
                $invoiceNumber = Yii::app()->db->createCommand()
                    ->select("COUNT(*)")
                    ->from("{{invoice}} t")
                    ->andWhere("t.invoice_type=:type",array("type"=>self::INVOICE_TYPE_KG9))
                    ->queryScalar();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        $letters = range("A","I");
        $largeWidthArr = ["C"];
        $smallWidthArr = ["A"];
        foreach ($letters as $letter) {
            $sheet->getStyle($letter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($letter)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle($letter)->getAlignment()->setWrapText(true);
            $sheet->getStyle($letter)->getFont()->setSize(10);
            if(in_array($letter,$largeWidthArr))
                $sheet->getColumnDimension($letter)->setWidth(20,29);
            elseif (in_array($letter, $smallWidthArr))
                $sheet->getColumnDimension($letter)->setWidth(8,29);
            else
                $sheet->getColumnDimension($letter)->setWidth(12,26);
        }

        $currentRow = 1;
        $sheet->mergeCells("B$currentRow:H$currentRow");
        $sheet->getStyle("B$currentRow")->getFont()->setSize(8);
        $sheet->getStyle("B$currentRow")->getAlignment()->setWrapText(false);
        $sheet->setCellValue('B'.$currentRow, "Додаток 8 до Інструкції з ведення квиткового господарства в театрально-видовищних підприємствах та культурно-освітніх закладах");
        $currentRow++;
        $sheet->setCellValue('I'.$currentRow.'', "Форма № КГ-9");
        $sheet->getStyle("I$currentRow")->getFont()->setSize(8);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("B$currentRow:H$currentRow");
        $sheet->setCellValue('B'.$currentRow.'', "kasa.in.ua");
        $sheet->getStyle('B'.$currentRow)->getFont()->setBold(true);
        $sheet->getStyle('B'.$currentRow)->getFont()->setItalic(true);
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(14);
        $sheet->getStyle("B$currentRow:H$currentRow")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->mergeCells("B$currentRow:H$currentRow");
        $sheet->setCellValue('B'.$currentRow.'', "(найменування підприємства, організації, установи)");
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(8);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("B$currentRow:C$currentRow");
        $sheet->setCellValue('B'.$currentRow.'', "Ідентифікаційний код ЄДРПОУ");
//        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D$currentRow")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("B$currentRow:H$currentRow");
        $sheet->setCellValue('B'.$currentRow.'', "Опис");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(12);
        $sheet->getStyle('B'.$currentRow)->getFont()->setBold(true);
        $currentRow++;
        $sheet->mergeCells("B$currentRow:F$currentRow");
        $sheet->setCellValue('B'.$currentRow.'', "реалізованих та нереалізованих місць за абонементами № ");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(12);
        $sheet->getStyle('B'.$currentRow)->getFont()->setBold(true);
        $sheet->mergeCells("G$currentRow:H$currentRow");
        $sheet->getStyle("G$currentRow:H$currentRow")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('G'.$currentRow)->getFont()->setBold(true);
        $sheet->setCellValue('G'.$currentRow.'', $invoiceNumber);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("D$currentRow:F$currentRow");
        $sheet->getStyle("D$currentRow")->getFont()->setSize(12);
        $sheet->setCellValue('D'.$currentRow.'', $currDate);
        $sheet->getStyle("D$currentRow:F$currentRow")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $currentRow++;
        $sheet->setCellValue("C$currentRow", "Абонемент");
        $sheet->getStyle("C$currentRow")->getFont()->setSize(11);
        $sheet->getStyle('C'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->mergeCells("D$currentRow:F$currentRow");
        $sheet->setCellValue("D$currentRow", "");
        $sheet->getStyle("D$currentRow:F$currentRow")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->mergeCells("D$currentRow:F$currentRow");
        $sheet->setCellValue("D$currentRow", "(серія, номер)");
        $sheet->getStyle("D$currentRow")->getFont()->setSize(8);
        $sheet->getStyle('D'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D'.$currentRow.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("A$currentRow:B$currentRow");
        $sheet->setCellValue("A$currentRow", "Назва заходу :");
        $sheet->getStyle("A$currentRow")->getFont()->setSize(11);
        $sheet->getStyle('A'.$currentRow)->getFont()->setBold(true);
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->mergeCells("C$currentRow:H$currentRow");
        $sheet->getStyle("C$currentRow:H$currentRow")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->setCellValue("C$currentRow", $event->name." на ".$minTiming);
        $sheet->getStyle("C$currentRow")->getFont()->setSize(11);
        $currentRow++;
        $currentRow++;
        $nextRow = $currentRow+1;
        $sheet->mergeCells("A$currentRow:A$nextRow");
        $sheet->getStyle("A$currentRow:I$currentRow")->getFont()->setSize(11);
        $sheet->mergeCells("B$currentRow:C$nextRow");
        $sheet->mergeCells("D$currentRow:E$currentRow");
        $sheet->mergeCells("F$currentRow:G$currentRow");
        $sheet->mergeCells("H$currentRow:I$currentRow");
        $sheet->setCellValue('A'.$currentRow.'', "№ п/п");
        $sheet->setCellValue('B'.$currentRow.'', "Вартість квитка, грн");
        $sheet->getStyle('A'.$currentRow.':B'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$currentRow.':B'.$currentRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A'.$currentRow.':B'.$currentRow)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('D'.$currentRow.'', "Усього для реалізації за абонементами");
        $sheet->getStyle('D'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('F'.$currentRow.'', "Реалізовано за абонементами");
        $sheet->getStyle('F'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('H'.$currentRow.'', "Не реалізовано за абонементами");
        $sheet->getStyle('H'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow)->getFont()->setBold(true);
        $currentRow++;
        $sheet->getStyle('B'.$currentRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle("A$currentRow:I$currentRow")->getFont()->setSize(11);
        $sheet->setCellValue('D'.$currentRow.'', "кільк.");
        $sheet->setCellValue('E'.$currentRow.'', "сума грн.");
        $sheet->setCellValue('F'.$currentRow.'', "кільк.");
        $sheet->setCellValue('G'.$currentRow.'', "сума грн.");
        $sheet->setCellValue('H'.$currentRow.'', "кільк.");
        $sheet->setCellValue('I'.$currentRow.'', "сума грн.");
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow)->getFont()->setBold(true);
        $currentRow++;
        $numLetters = ["A","B","D","E","F","G","H","I"];
        $i = 1;
        $sheet->mergeCells("B$currentRow:C$currentRow");
        foreach ($numLetters as $letter) {
            $sheet->getStyle($letter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($letter)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A'.$currentRow.':I'.$currentRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle($letter)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A'.$currentRow.':I'.$currentRow)->getFont()->setBold(true);
            $sheet->getStyle($letter)->getFont()->setSize(11);
            $sheet->setCellValue($letter.$currentRow, $i);
            $i++;
        }


        $statistics = new Statistic($event);
        $KG9Data = $statistics->getKG9Statistics();
        $currentRow ++;
        $sumRealizedCount = 0;
        $sumRealizedSum = 0;
        $sumNotRealizedCount = 0;
        $sumNotRealizedSum = 0;
        $i = 1;
        foreach ($KG9Data as $price => $data){
            $sumRealizedCount += $data["realizedCount"];
            $sumRealizedSum += $data["realizedSum"];
            $sumNotRealizedCount += $data["notRealizedCount"];
            $sumNotRealizedSum += $data["notRealizedSum"];
            $sheet->mergeCells("B$currentRow:C$currentRow");
            $sheet->setCellValue('A'.$currentRow.'', $i);
            $sheet->setCellValue('B'.$currentRow.'', number_format($price, 2, ',', ' '));
            $sheet->setCellValue('D'.$currentRow.'', $data["realizedCount"]+$data["notRealizedCount"]);
            $sheet->setCellValue('E'.$currentRow.'', number_format($data["realizedSum"]+$data["notRealizedSum"], 2, ',', ' '));
            $sheet->setCellValue('F'.$currentRow.'', $data["realizedCount"]);
            $sheet->setCellValue('G'.$currentRow.'', number_format($data["realizedSum"], 2, ',', ' '));
            $sheet->setCellValue('H'.$currentRow.'', $data["notRealizedCount"]);
            $sheet->setCellValue('I'.$currentRow.'', number_format($data["notRealizedSum"], 2, ',', ' '));
            $sheet->getStyle('A'.$currentRow.':I'.$currentRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle("A$currentRow:I$currentRow")->getFont()->setSize(11);
            $currentRow ++;
            $i++;
        }
        $sheet->mergeCells("B$currentRow:C$currentRow");
        $sheet->setCellValue('B'.$currentRow.'', "Всього");
        $sheet->setCellValue('D'.$currentRow.'', $sumRealizedCount+$sumNotRealizedCount);
        $sheet->setCellValue('E'.$currentRow.'', number_format($sumRealizedSum+$sumNotRealizedSum, 2, ',', ' '));
        $sheet->setCellValue('F'.$currentRow.'', $sumRealizedCount);
        $sheet->setCellValue('G'.$currentRow.'', number_format($sumRealizedSum, 2, ',', ' '));
        $sheet->setCellValue('H'.$currentRow.'', $sumNotRealizedCount);
        $sheet->setCellValue('I'.$currentRow.'', number_format($sumNotRealizedSum, 2, ',', ' '));
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$currentRow.':I'.$currentRow)->getFont()->setBold(true);
        $sheet->getStyle("A$currentRow:I$currentRow")->getFont()->setSize(11);
        $currentRow++;
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("C$currentRow:E$currentRow");
        $sheet->setCellValue('C'.$currentRow.'', "Начальник відділу квиткового господарства");
        $sheet->getStyle('C'.$currentRow)->getFont()->setBold(true);
        $sheet->getStyle("A$currentRow:I$currentRow")->getFont()->setSize(11);
        $sheet->getStyle('A'.$currentRow.':B'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->mergeCells("F$currentRow:I$currentRow");
        $sheet->getStyle("F$currentRow:I$currentRow")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->mergeCells("F$currentRow:I$currentRow");
        $sheet->setCellValue('F'.$currentRow.'', "(підпис, ініціали, прізвище)");
        $sheet->getStyle("F$currentRow")->getFont()->setSize(8);
        $sheet->getStyle('F'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F'.$currentRow.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

//        $sheet->freezePane( "A3" );
        $objWriter = new PHPExcel_Writer_Excel5($exel);
        $objWriter->save('php://output');
    }

    public function generateKG10Invoice()
    {
        $exel = $this->initExel();
        $sheet = $this->changeSheet(0);
        $sheet->setTitle("KG 10");
        $currDate = Yii::app()->dateFormatter->format("\"dd\" MMMM yyyy року", time());
        $event = $this->instance;
        $statistics = new Statistic($event);
        $KG10Data = $statistics->getKG10Statistics();
        $minTiming =  Yii::app()->db->createCommand()
            ->select("MIN(t.start_sale)")
            ->from(Timing::model()->tableName()." t")
            ->andWhere("t.event_id=:event_id",array("event_id"=>$event->id))
            ->queryScalar();

        $invoiceNumber = 0;
        $query = "INSERT INTO {{invoice}} (invoice_type) VALUES (".self::INVOICE_TYPE_KG10.")";
        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (Yii::app()->db->createCommand($query)->execute()) {
                $invoiceNumber = Yii::app()->db->createCommand()
                    ->select("COUNT(*)")
                    ->from("{{invoice}} t")
                    ->andWhere("t.invoice_type=:type",array("type"=>self::INVOICE_TYPE_KG10))
                    ->queryScalar();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        $minTiming =  Yii::app()->dateFormatter->format("\"dd\" MMMM yyyy року", $minTiming);
        $sumPrice = $event->sumPrice;
        $sumCount = $event->countPlacePrice;
        $letters = range("A","J");


        $sheet->getSheetView()->setZoomScale(115);

        $sheet->getRowDimension()->setRowHeight(15);

        $sheet->getColumnDimension("A")->setWidth(15.70);
        $sheet->getColumnDimension("B")->setWidth(9.17);
        $sheet->getColumnDimension("C")->setWidth(11,17);
        $sheet->getColumnDimension("D")->setWidth(9,43);
        $sheet->getColumnDimension("E")->setWidth(12);
        $sheet->getColumnDimension("F")->setWidth(9,43);
        $sheet->getColumnDimension("G")->setWidth(9,43);
        $sheet->getColumnDimension("H")->setWidth(5,43);
        $sheet->getColumnDimension("I")->setWidth(5,43);
        $sheet->getColumnDimension("J")->setWidth(6,57);
        $currentRow = 1;
        $sheet->mergeCells("E$currentRow:J$currentRow");
        $sheet->setCellValue("E$currentRow", "Додаток 9");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $sheet->mergeCells("E$currentRow:J$currentRow");
        $sheet->setCellValue("E$currentRow", "до Інструкції з ведення квиткового");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $sheet->mergeCells("E$currentRow:J$currentRow");
        $sheet->setCellValue("E$currentRow", "господарства в  театрально-видовищних");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $sheet->mergeCells("E$currentRow:J$currentRow");
        $sheet->setCellValue("E$currentRow", "підприємствах та культурно-освітних закладах.");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("H$currentRow:J$currentRow");
        $sheet->setCellValue("H$currentRow", "Форма № КГ-10");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $sheet->mergeCells("E$currentRow:J$currentRow");
        $sheet->setCellValue("E$currentRow", "");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $sheet->mergeCells("E$currentRow:J$currentRow");
        $sheet->setCellValue("E$currentRow", "(найменування підприємства,організації,установи)");
        $sheet->getStyle("E$currentRow")->getFont()->setSize(7);
        $sheet->getStyle("E$currentRow")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E$currentRow")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $currentRow++;
        $sheet->mergeCells("A$currentRow:B$currentRow");
        $sheet->setCellValue("A$currentRow", "Ідентифікаційний код");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $sheet->mergeCells("A$currentRow:B$currentRow");
        $sheet->setCellValue("A$currentRow", "ЄДРПОУ ");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $currentRow++;

        $sheet->mergeCells("B$currentRow:G$currentRow");
        $sheet->setCellValue("B$currentRow", "Зведений звіт про реалізацію  квитків (абонементів) № ".$invoiceNumber);
        $sheet->getStyle("B$currentRow")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("C$currentRow:E$currentRow");
        $sheet->setCellValue("C$currentRow", $currDate);
        $sheet->getStyle("C$currentRow")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("A$currentRow:J$currentRow");
        $sheet->setCellValue("A$currentRow", "Назва заходу ".$event->name." дата заходу ".$minTiming);
        $sheet->getStyle("A$currentRow")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("B$currentRow:G$currentRow");
        $sheet->setCellValue("B$currentRow", "Аншлаговий збір ".number_format($sumPrice, 2 , ",", " ")." грн.");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $sheet->mergeCells("B$currentRow:G$currentRow");
        $sheet->setCellValue("B$currentRow", "Загальна кількість ".number_format($sumCount, 0 , " ", " ")." шт.");
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $currentRow++;
        $currentRow++;
        $nextRow = $currentRow+1;
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $sheet->mergeCells("A$currentRow:A$nextRow");
        $sheet->setCellValue("A$currentRow", "П.І.Б.");
        $sheet->getStyle("A$currentRow:A$nextRow")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->mergeCells("B$currentRow:C$currentRow");
        $sheet->setCellValue("B$currentRow", "Видано");
        $sheet->mergeCells("D$currentRow:E$currentRow");
        $sheet->setCellValue("D$currentRow", "Реалізовано");
        $sheet->mergeCells("F$currentRow:G$currentRow");
        $sheet->setCellValue("F$currentRow", "Повернуто");
        $sheet->mergeCells("H$currentRow:J$currentRow");
        $sheet->setCellValue("H$currentRow", "Відображається");
        $sheet->getStyle("A$currentRow:J$currentRow")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B$currentRow:J$currentRow")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->getStyle("A$currentRow:J$currentRow")->getFont()->setSize(10);
        $sheet->getStyle("B$currentRow:J$currentRow")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B$currentRow:J$currentRow")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue("B$currentRow", "К-ть місць");
        $sheet->setCellValue("C$currentRow", "Сума, грн");
        $sheet->setCellValue("D$currentRow", "К-ть місць");
        $sheet->setCellValue("E$currentRow", "Сума, грн");
        $sheet->setCellValue("F$currentRow", "К-ть місць");
        $sheet->setCellValue("G$currentRow", "Сума, грн");
        $sheet->setCellValue("H$currentRow", "д-т рах.");
        $sheet->setCellValue("I$currentRow", "к-т рах.");
        $sheet->setCellValue("J$currentRow", "сума, грн");
        $sheet->getStyle("B$currentRow:J$currentRow")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $i = 1;
        foreach ($letters as $letter) {
            $sheet->getStyle($letter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($letter)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle($letter)->getAlignment()->setWrapText(true);
            $sheet->getStyle($letter)->getFont()->setSize(10);
            $sheet->setCellValue($letter.$currentRow, $i);
            $i++;
        }
        $sheet->getStyle("A$currentRow:J$currentRow")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sumPublishedCount = 0;
        $sumPublishedSum = 0;
        $sumRealizedCount = 0;
        $sumRealizedSum = 0;
        $sumReturnedCount = 0;
        $sumReturnedSum = 0;
        foreach ($KG10Data as $data) {
            $sumPublishedCount += $data["publishedCount"];
            $sumPublishedSum += $data["publishedSum"];
            $sumRealizedCount += $data["realizedCount"];
            $sumRealizedSum += $data["realizedSum"];
            $sumReturnedCount += $data["returnedCount"];
            $sumReturnedSum += $data["returnedSum"];
            $sheet->setCellValue('A'.$currentRow.'', $data["roleName"]);
            $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('B'.$currentRow.':J'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('B'.$currentRow.'', $data["publishedCount"]);
            $sheet->setCellValue('C'.$currentRow.'', number_format($data["publishedSum"], 2, ',', ' '));
            $sheet->setCellValue('D'.$currentRow.'', $data["realizedCount"]);
            $sheet->setCellValue('E'.$currentRow.'', number_format($data["realizedSum"], 2, ',', ' '));
            $sheet->setCellValue('F'.$currentRow.'', $data["returnedCount"]);
            $sheet->setCellValue('G'.$currentRow.'', number_format($data["returnedSum"], 2, ',', ' '));
            $sheet->getStyle('A'.$currentRow.':J'.$currentRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $currentRow++;
        }
        $sheet->setCellValue('A'.$currentRow.'', "Усього");
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->setCellValue('B'.$currentRow.'', $sumPublishedCount);
        $sheet->setCellValue('C'.$currentRow.'', number_format($sumPublishedSum, 2, ',', ' '));
        $sheet->setCellValue('D'.$currentRow.'', $sumRealizedCount);
        $sheet->setCellValue('E'.$currentRow.'', number_format($sumRealizedSum, 2, ',', ' '));
        $sheet->setCellValue('F'.$currentRow.'', $sumReturnedCount);
        $sheet->setCellValue('G'.$currentRow.'', number_format($sumReturnedSum, 2, ',', ' '));
        $sheet->getStyle('B'.$currentRow.':J'.$currentRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A'.$currentRow.':J'.$currentRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("A$currentRow:J$currentRow");
        $sheet->setCellValue('A'.$currentRow.'', "Додаток: використаний комплект з погашеними нереалізованими квитками (абонементами) на суму:");
        $currentRow++;
        $notSoldSum = $sumPrice - $sumRealizedSum;
        $sheet->mergeCells("A$currentRow:J$currentRow");
        $sheet->setCellValue('A'.$currentRow.'', PriceToTextConverter::convert($notSoldSum));
        $currentRow++;
        $sheet->mergeCells("A$currentRow:J$currentRow");
        $sheet->setCellValue('A'.$currentRow.'', "(сума яка лишилась непроданих квитків)");
        $sheet->getStyle("A$currentRow")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A$currentRow")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle("A$currentRow")->getFont()->setSize(7);
        $currentRow++;
        $sheet->mergeCells("A$currentRow:B$currentRow");
        $sheet->setCellValue('A'.$currentRow.'', "Старший квитковий касир: ");
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->mergeCells("C$currentRow:J$currentRow");
        $sheet->getStyle("C$currentRow:J$currentRow")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->mergeCells("C$currentRow:J$currentRow");
        $sheet->setCellValue('C'.$currentRow.'', "(посада, підпис, ініціали, прізвище)");
        $sheet->getStyle("C$currentRow")->getFont()->setSize(7);
        $sheet->getStyle("C$currentRow")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C$currentRow")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $currentRow++;
        $sheet->mergeCells("A$currentRow:C$currentRow");
        $sheet->setCellValue("A$currentRow", "Звіт перевірив, сума виручки правильна. ");
        $sheet->getStyle("A$currentRow")->getAlignment()->setWrapText(false);
        $currentRow++;
        $sumPercent = ($sumRealizedSum - $sumReturnedSum) / $sumPrice * 100;
        $sumPercent = round($sumPercent,2);
        $sheet->mergeCells("A$currentRow:J$currentRow");
        $sheet->setCellValue("A$currentRow", "% відношення фактичного збору до валового (аншлагу) : $sumPercent%");
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $currentRow++;
        $sumCountPercent = ($sumRealizedCount - $sumReturnedCount) / $sumCount * 100;
        $sumCountPercent = round($sumCountPercent,2);
        $sheet->mergeCells("A$currentRow:J$currentRow");
        $sheet->setCellValue("A$currentRow", "% відношення до загальної кількості місць у реалізації  : $sumCountPercent%");
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $currentRow++;
        $currentRow++;
        $sheet->mergeCells("A$currentRow:B$currentRow");
        $sheet->setCellValue("A$currentRow", "Головний бухгалтер");
        $sheet->getStyle('A'.$currentRow.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->mergeCells("C$currentRow:J$currentRow");
        $sheet->getStyle("C$currentRow:J$currentRow")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $currentRow++;
        $sheet->mergeCells("C$currentRow:J$currentRow");
        $sheet->setCellValue('C'.$currentRow.'', "(посада, підпис, ініціали, прізвище)");
        $sheet->getStyle("C$currentRow")->getFont()->setSize(7);
        $sheet->getStyle("C$currentRow")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C$currentRow")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $sheet->getStyle("A1:J$currentRow")->getFont()->setName("Arial Cyr");


        $objWriter = new PHPExcel_Writer_Excel5($exel);
        $objWriter->save('php://output');
    }
}

