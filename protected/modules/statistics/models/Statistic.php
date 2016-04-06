<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 14.10.2015
 * Time: 12:31
 */
class Statistic
{
    private $places;
    private $allPlaces;
    private $date_from;
    private $date_to;
    private $storage;

    /**
     * @param $event
     * @param null string $date_from
     * @param null string $date_to
     */
    public function __construct($event, $date_from=null, $date_to=null, $filter=true)
    {
        $this->storage = $event;
        $this->date_from = $date_from;
        $this->date_to = $date_to;
        if ($filter)
            $this->initFilter();
    }

    /**
     * @description filtering incoming places|tickets
     */
    private function initFilter()
    {
        $this->places = [];
        $from = $this->date_from;
        $to = $this->date_to;
        $event = $this->storage;
//        $tempPlaces = $event->places ? $event->places : [];
        $tempPlaces = $this->generateDataArray($event);


        if(!empty($tempPlaces)) {
            if ($from || $to) {
                if ($from)
                    $fromDate = strtotime($from);
                else
                    $fromDate = null;
                if ($to)
                    $toDate = strtotime($to);
                else
                    $toDate = null;
                foreach ($tempPlaces as $place) {
                    if ($place->ticket) {
                        if ($place->ticket->status != Ticket::STATUS_CANCEL) {
                            $ticketDate = strtotime($place->ticket->date_add);
                            if ($fromDate && $toDate) {
                                if ($ticketDate >= $fromDate && $ticketDate <= $toDate && ($place->ticket->order->type != Order::TYPE_QUOTE || $place->ticket->status == Ticket::STATUS_QUOTE_SOLD)) {
                                    $this->places[] = $place;
                                    $this->allPlaces[] = $place;
                                }elseif ($ticketDate >= $fromDate && $ticketDate <= $toDate)
                                    $this->allPlaces[] = $place;
                            } elseif ($fromDate) {
                                if ($ticketDate >= $fromDate && ($place->ticket->order->type != Order::TYPE_QUOTE || $place->ticket->status == Ticket::STATUS_QUOTE_SOLD)) {
                                    $this->places[] = $place;
                                    $this->allPlaces[] = $place;
                                }elseif ($ticketDate >= $fromDate)
                                    $this->allPlaces[] = $place;
                            } elseif ($toDate) {
                                if ($ticketDate <= $toDate && ($place->ticket->order->type != Order::TYPE_QUOTE || $place->ticket->status == Ticket::STATUS_QUOTE_SOLD)) {
                                    $this->places[] = $place;
                                    $this->allPlaces[] = $place;
                                }elseif ($ticketDate <= $toDate)
                                    $this->allPlaces[] = $place;
                            }
                        }
                    } else
                        $this->allPlaces[] = $place;
                }
            } else {
                foreach ($tempPlaces as $place) {
                    if ($place->ticket) {
                        if ($place->ticket->status != Ticket::STATUS_CANCEL  && ($place->ticket->order->type != Order::TYPE_QUOTE || $place->ticket->status == Ticket::STATUS_QUOTE_SOLD))
                            $this->places[] = $place;
                    }
                    $this->allPlaces[] = $place;
                }
            }
        }
    }

    private function generateDataArray($event)
    {
        $places = [];
        if($event->id)
        {
            ini_set("memory_limit", "768M");
            $tickets = [];
            $roles = [];
            $orders = [];
            $sector_ids = Yii::app()->db->createCommand()
                ->select("id")
                ->from("{{sector}}")
                ->where("scheme_id=:scheme_id", array(
                    ":scheme_id"=>$event->scheme_id
                ))
                ->queryColumn();

            $placesData = Yii::app()->db->createCommand()
                ->select("t.*")
                ->from(Place::model()->tableName()." t")
                ->where("t.event_id=:event_id",array("event_id"=>$event->id))
                ->andWhere(array("in", "sector_id", $sector_ids))
                ->queryAll();

            foreach ($placesData as $place) {
                $places[$place["id"]] = (object)$place;
            }

            $dependency = new CDbCacheDependency("SELECT MAX(date_update), MAX(date_add) FROM tbl_ticket WHERE event_id=".$event->id." AND status!=".Ticket::STATUS_CANCEL);
            $ticketsData = Yii::app()->db->cache(60*60*24, $dependency)->createCommand()
                ->select("t.*")
                ->from(Ticket::model()->tableName()." t")
                ->where("t.event_id=:event_id",array("event_id"=>$event->id))
                ->andWhere(array("in", "sector_id", $sector_ids))
                ->andWhere(array("not in", "status", [Ticket::STATUS_QUOTE_RETURN,Ticket::STATUS_CANCEL]))
//                ->andWhere("t.status!=:status",array("status"=>Ticket::STATUS_QUOTE_RETURN))
//                ->andWhere("t.status!=:status",array("status"=>Ticket::STATUS_CANCEL))
                ->queryAll();



            $role_ids = [];
            $order_ids = [];
            foreach ($ticketsData as $ticket) {
                if (isset($tickets[$ticket["place_id"]])) {
//                    throw new CHttpException("500","two or more active tickets on same place error ".$ticket['place_id']);
                }
                $tickets[$ticket["place_id"]] = (object)$ticket;
                if(!in_array($ticket["role_id"],$role_ids))
                    $role_ids[] = $ticket["role_id"];
                if(!in_array($ticket["order_id"],$order_ids))
                    $order_ids[] = $ticket["order_id"];
            }

            $rolesData = Yii::app()->db->createCommand()
                ->select("t.*")
                ->from(Role::model()->tableName()." t")
                ->andWhere(array("in", "t.id", $role_ids))
                ->queryAll();

            foreach ($rolesData as $role)
                $roles[$role["id"]] = (object)$role;
            if (empty($order_ids))
                return array();
            $dependency = new CDbCacheDependency("SELECT MAX(date_add) FROM tbl_order WHERE id IN (".implode(",", $order_ids).")");
            $ordersData = Yii::app()->db->cache(60*60*24, $dependency)->createCommand()
                ->select("t.*")
                ->from(Order::model()->tableName()." t")
                ->andWhere(array("in", "t.id", $order_ids))
                ->queryAll();

            foreach ($ordersData as $order)
                $orders[$order["id"]] = (object)$order;

            foreach ($tickets as $ticket) {
                $ticket->role = $roles[$ticket->role_id];
                $ticket->order = $orders[$ticket->order_id];
            }

            foreach ($places as $place) {
                if(isset($tickets[$place->id]))
                    $place->ticket = $tickets[$place->id];
                else
                    $place->ticket = false;
            }
        }
        return $places;
    }

    /**
     * @return array
     * @description data for preview basic statistics window
     */
    public function getPlacesPreviewStatisticsData()
    {
        $event = $this->storage;
        if($this->places) {
            $places = $this->allPlaces;

            $withPrice = [];
            $sold = [];
            $onSell = [];
            $reserved = [];
            $reservedQuote = [];
            $invite = [];
            foreach ($places as $place) {
                if ($place->ticket && $this->inDateFilterRange($place)) {
                    if($place->ticket->status != Ticket::STATUS_CANCEL && $place->ticket->status != Ticket::STATUS_QUOTE_RETURN) {
                        if ($place->ticket->pay_status == Ticket::PAY_INVITE)
                            $invite[] = $place;
                        elseif ($place->ticket->pay_status == Ticket::PAY_PAY || $place->ticket->status == Ticket::STATUS_QUOTE_SOLD)
                            $sold[] = $place;
                        elseif ($place->ticket->order->type == Order::TYPE_QUOTE)
                            $reservedQuote[] = $place;
                        elseif ($place->ticket->pay_status == Ticket::PAY_NOT_PAY)
                            $reserved[] = $place;
                    } else {
                        $onSell[] = $place;
                    }
                } elseif (!$place->ticket)
                    $onSell[] = $place;

                if ($place->price)
                    $withPrice[] = $place;
            }
            $withPriceData = [];
            $soldData = [];
            $onSellData = [];
            $reservedData = [];
            $inviteData = [];
            $total = count($withPrice);
            $withPriceData["sum"] = number_format($this->sumPrices($withPrice), 0, ' ', ' ');
            $withPriceData["count"] = count($withPrice);
            $soldData["sum"] = number_format($this->sumPrices($sold), 0, ' ', ' ');
            $soldData["count"] = count($sold);
            $onSellData["sum"] = number_format($this->sumPrices($onSell), 0, ' ', ' ');
            $onSellData["count"] = count($onSell);
            $reservedData["sum"] = number_format($this->sumPrices($reserved), 0, ' ', ' ');
            $reservedData["count"] = count($reserved);
            $reservedQuoteData["sum"] = number_format($this->sumPrices($reservedQuote), 0, ' ', ' ');
            $reservedQuoteData["count"] = count($reservedQuote);
            $inviteData["sum"] = number_format($this->sumPrices($invite), 0, ' ', ' ');
            $inviteData["count"] = count($invite);

            if ($total != 0) {
                $withPriceData["percent"] = round(($withPriceData["count"] / $total) * 100, 1);
                $soldData["percent"] = round(($soldData["count"] / $total) * 100, 1);
                $onSellData["percent"] = round(($onSellData["count"] / $total) * 100, 1);
                $reservedData["percent"] = round(($reservedData["count"] / $total) * 100, 1);
                $reservedQuoteData["percent"] = round(($reservedQuoteData["count"] / $total) * 100, 1);
                $inviteData["percent"] = round(($inviteData["count"] / $total) * 100, 1);
            } else {
                $withPriceData["percent"] = 0;
                $soldData["percent"] = 0;
                $onSellData["percent"] = 0;
                $reservedData["percent"] = 0;
                $reservedQuoteData["percent"] = 0;
                $inviteData["percent"] = 0;
            }
//todo sale control
            $closedFromSaleData["count"] = 0;
            $closedFromSaleData["sum"] = 0;
            $closedFromSaleData["percent"] = 0;

            $funCount = Place::getCountFunWithPrice($event->id);
            $countAll = $event->scheme->getCountPlaces() + $funCount;
            $withNoPrice = $countAll - $withPriceData["count"];
        } else {
            $params = ["sum", "count", "percent"];
            foreach ($params as $param) {
                $withPriceData[$param] = 0;
                $soldData[$param] = 0;
                $onSellData[$param] = 0;
                $reservedData[$param] = 0;
                $reservedQuoteData[$param] = 0;
                $inviteData[$param] = 0;
                $closedFromSaleData[$param] = 0;
            }
            $withNoPrice = 0;
        }

        return ["placesWithPrice"=>$withPriceData,"placesSold"=>$soldData, "withNoPriceCount" => $withNoPrice, "placesOnSale"=>$onSellData, "placesReserved"=>$reservedData,
                "placesInvite"=>$inviteData, "placesClosedFromSale"=>$closedFromSaleData, "placesReservedQuote"=>$reservedQuoteData];
    }

    /**
     * @param $place
     * @return bool
     * @description for filtering incoming places|tickets
     */
    private function inDateFilterRange($place) {
        $from = $this->date_from;
        $to = $this->date_to;
        if ($from || $to) {
            if ($from)
                $fromDate = strtotime($from);
            else
                $fromDate = null;
            if ($to)
                $toDate = strtotime($to);
            else
                $toDate = null;
            if ($place->ticket) {
                $ticketDate = strtotime($place->ticket->date_add);
                if ($fromDate && $toDate) {
                    if ($ticketDate >= $fromDate && $ticketDate <= $toDate)
                        return true;
                    else
                        return false;
                }
                elseif ($fromDate) {
                    if ($ticketDate >= $fromDate)
                        return true;
                    else
                        return false;
                }
                elseif ($toDate) {
                    if ($ticketDate <= $toDate)
                        return true;
                    else
                        return false;
                }
            } else
                return false;
        }
        return true;
    }

    private function sumPrices ($places) {
        $result = 0;
        if(!empty($places)) {
            foreach ($places as $place){
                if($place->ticket)
                    $result += $place->ticket->price;
                else
                    $result += $place->price;
            }
        }
        return $result;
    }

    /**
     * @return array
     * @description statistics of tickets soled|invited|reserved by contragents
     */
    public function getRolesStatistics() {
        $result = [];
        $places = $this->places;
        if(!empty($places)) {
            $ticketsByRole = [];
            foreach ($places as $place) {
                if($place->ticket){
                    $ticket = $place->ticket;
//                    $roleName = $ticket->role->name;
                    if (array_key_exists($ticket->role_id, $ticketsByRole))
                        array_push($ticketsByRole[$ticket->role_id], $ticket);
                    else
                        $ticketsByRole[$ticket->role_id] = array($ticket);
                }
            }

            foreach ($ticketsByRole as $role_id => $tickets) {
                $roleName = current($tickets)->role->name;
                $reserved = 0;
                $sold = 0;
                $invite = 0;
                $sum = 0;
                $withPricePlacesSum = $this->sumPrices($places);
                foreach ($tickets as $ticket) {

                    if($ticket->pay_status == Ticket::PAY_PAY)
                        $sold++;
                    elseif($ticket->pay_status == Ticket::PAY_INVITE)
                        $invite++;
                    else
                        $reserved++;

                    if($ticket->pay_status != Ticket::PAY_INVITE)
                        $sum += $ticket->price;

                }
                $val = round(($sum / $withPricePlacesSum) * 100, 1);
                $result[$roleName] = ["reserved" => $reserved, "sold" => $sold, "invite" => $invite, "sum" => $sum,
                    "val" => $val];
            }
        }
        return $result;
    }

    public function getRolesSpecialStatistic() {
        set_time_limit(0);
        $result = [];
        $places = $this->allPlaces;
        $placesWithTickets = [];
        $cashier_ids = [423,424];
        $specialRoles = [];

        if(!empty($places)) {
            $ticketsByRole = [];
            $ticketByQuoteRole = [];
            $quoteOrders = [];
            foreach ($places as $place) {
                if($place->ticket){
                    $placesWithTickets[] = $place;
                    $ticket = $place->ticket;


//                    $roleName = $ticket->role->name;
                    if($ticket->author_print_id && in_array($ticket->author_print_id,$cashier_ids)) {
                        if (array_key_exists($ticket->author_print_id, $specialRoles))
                            array_push($specialRoles[$ticket->author_print_id], $ticket);
                        else
                            $specialRoles[$ticket->author_print_id] = array($ticket);
                        continue;
                    } elseif (!$ticket->author_print_id && $ticket->cash_user_id && in_array($ticket->cash_user_id,$cashier_ids)) {
                        if (array_key_exists($ticket->cash_user_id, $specialRoles))
                            array_push($specialRoles[$ticket->cash_user_id], $ticket);
                        else
                            $specialRoles[$ticket->cash_user_id] = array($ticket);
                        continue;
                    } elseif (!$ticket->author_print_id && !$ticket->cash_user_id && in_array($ticket->user_id,$cashier_ids)) {
                        if (array_key_exists($ticket->user_id, $specialRoles))
                            array_push($specialRoles[$ticket->user_id], $ticket);
                        else
                            $specialRoles[$ticket->user_id] = array($ticket);
                        continue;
                    }

                    if($ticket->order->type == Order::TYPE_QUOTE) {
                        $ticketByQuoteRole[$ticket->order->id][] = $ticket;
                        $quoteOrders[] = $ticket->order->id;
                        continue;
                    }

//                    kasa in ua key = 11
                    if (array_key_exists(11, $ticketsByRole))
                        array_push($ticketsByRole[11], $ticket);
                    else
                        $ticketsByRole[11] = array($ticket);
                }
            }
            $withPricePlacesSum = $this->sumPrices($placesWithTickets);

            $quotesData = Yii::app()->db->createCommand()
                ->select("t.*")
                ->from(Quote::model()->tableName()." t")
                ->andWhere(array("in", "order_id", $quoteOrders))
                ->queryAll();
            $role_ids = [];
            foreach($quotesData as $quote) {
                $quotes[$quote["order_id"]] = (object)$quote;
                $role_ids[] = $quote["role_to_id"];
            }
            $rolesData = Yii::app()->db->createCommand()
                ->select("t.*")
                ->from(Role::model()->tableName()." t")
                ->andWhere(array("in", "id", $role_ids))
                ->queryAll();
            $roles = [];
            foreach($rolesData as $role)
                $roles[$role["id"]] = (object)$role;


            foreach ($ticketByQuoteRole as $order_id=>$tickets) {
                $quote = $quotes[$order_id];
                $role_id = $quote->role_to_id;
                $role = $roles[$role_id];
                $roleName = $role->name;
                if(isset($result[$roleName]))
                    $this->calculateSpecialRolesRow($tickets,$withPricePlacesSum,$result[$roleName]);
                else
                    $result[$roleName] = $this->calculateSpecialRolesRow($tickets,$withPricePlacesSum);
            }

            foreach ($ticketsByRole as $role_id => $tickets) {
                $roleName = 'kasa.in.ua';
                if(isset($result[$roleName]))
                    $this->calculateSpecialRolesRow($tickets,$withPricePlacesSum,$result[$roleName]);
                else
                    $result[$roleName] = $this->calculateSpecialRolesRow($tickets,$withPricePlacesSum);
            }
            foreach ($specialRoles as $user_id => $tickets) {
                $roleName = "№ ".$user_id." (".User::getNameById($user_id).")";
                if(isset($result[$roleName]))
                    $this->calculateSpecialRolesRow($tickets,$withPricePlacesSum,$result[$roleName]);
                else
                    $result[$roleName] = $this->calculateSpecialRolesRow($tickets,$withPricePlacesSum);
            }
        }
        return $result;
    }

    private function calculateSpecialRolesRow($tickets, $withPricePlacesSum, &$array=false)
    {
        $reserved = 0;
        $sold = 0;
        $invite = 0;
        $quoteRealization = 0;
        $sum = 0;
        foreach ($tickets as $ticket) {

            if ($ticket->pay_status == Ticket::PAY_INVITE)
                $invite++;
            elseif ($ticket->pay_status == Ticket::PAY_PAY || $ticket->status == Ticket::STATUS_QUOTE_SOLD)
                $sold++;
            elseif ($ticket->order->type == Order::TYPE_QUOTE)
                $quoteRealization++;
            elseif ($ticket->pay_status == Ticket::PAY_NOT_PAY)
                $reserved++;

            if($ticket->pay_status == Ticket::PAY_PAY || $ticket->status == Ticket::STATUS_QUOTE_SOLD)
                $sum += $ticket->price;

        }
        $val = round(($sum / $withPricePlacesSum) * 100, 1);
        if($array) {
            $array["reserved"] += $reserved;
            $array["sold"] += $sold;
            $array["quoteRealization"] += $quoteRealization;
            $array["invite"] += $invite;
            $array["sum"] += $sum;
            $array["val"] += $val;
        } else {
            return ["reserved" => $reserved, "sold" => $sold, "quoteRealization" => $quoteRealization, "invite" => $invite, "sum" => $sum,
                "val" => $val];
        }
    }

    /**
     * @param bool|false $withPrices
     * @return array
     * @description statistics of places sorted by sectors
     */
    public function getSectorsStatistics($withPrices = false)
    {
        $result = [];
        $places = $this->allPlaces;
        if(!empty($places)) {
            $sectorNames = Yii::app()->db->createCommand()
                ->select("CONCAT(IFNULL(t.name,''), ' ', s.name) as sector_name, s.id, t.name")
                ->from(Sector::model()->tableName()." s")
                ->leftJoin(TypeSector::model()->tableName()." t", "t.id=s.type_sector_id")
                ->where("s.scheme_id=:scheme_id", array(
                    ":scheme_id"=>$this->storage->scheme_id
                ))
                ->queryAll();

            $sectors = [];
            if (!empty($sectorNames))
                foreach ($sectorNames as $sectorName) {
                    $sectors[$sectorName['id']] = $sectorName;
                }
            $newPlaces = [];
            foreach ($places as $place) {
                if ($place->ticket) {
                    $sector = $sectors[$place->sector_id];
                    if (!isset($newPlaces[$sector['sector_name']])) {
                        $newPlaces[$sector['sector_name']][] = $place;
                        continue;
                    }
                    array_push($newPlaces[$sector['sector_name']], $place);
                }
            }
            ksort($newPlaces, SORT_NATURAL);
            if($withPrices) {
                foreach ($newPlaces as $sectorName => $tempPlaces)
                    $result[$sectorName] = $this->getSectorsWithPriceData($tempPlaces);

                return $result;
            }
            $withPricePlacesSum = $this->sumPrices($places);

            foreach ($newPlaces as $sectorName => $tempPlaces)
                $result[$sectorName] = $this->getSectorsDataArray($tempPlaces,$withPricePlacesSum);
        }
        return $result;
    }

    /**
     * @param $places
     * @return array
     */
    private function getSectorsWithPriceData($places)
    {
        $pricedArray = [];
        $result = [];
        foreach ($places as $place) {
            if ($place->ticket) {
                if (array_key_exists($place->ticket->price, $pricedArray))
                    array_push($pricedArray[$place->ticket->price], $place);
                else
                    $pricedArray[$place->ticket->price] = array($place);
            }
        }

        foreach ($pricedArray as $price => $places) {
            $reserved = 0;
            $sold = 0;
            $invite = 0;
            $sum = 0;
            $placesCount = count($places);
            foreach ($places as $place) {
                if ($place->ticket) {
                    if ($place->ticket->pay_status == Ticket::PAY_INVITE)
                        $invite++;
                    elseif ($place->ticket->pay_status == Ticket::PAY_PAY || $place->ticket->status == Ticket::STATUS_QUOTE_SOLD)
                        $sold++;
                    elseif ($place->ticket->order->type != Order::TYPE_QUOTE)
                        $reserved++;

                    if ($place->ticket->pay_status != Ticket::PAY_INVITE && ($place->ticket->order->type != Order::TYPE_QUOTE || $place->ticket->status == Ticket::STATUS_QUOTE_SOLD))
                        $sum += $place->ticket->price;
                }
            }
            $result[$price] = ["price"=>$price, "reserved" => $reserved, "sold" => $sold, "invited" => $invite, "sum" => $sum, "placesCount"=>$placesCount];

        }
        return $result;
    }

    /**
     * @param $places
     * @param int $sumPlaces
     * @return array
     */
    private function getSectorsDataArray($places, $sumPlaces) {
        $reserved = 0;
        $sold = 0;
        $invite = 0;
        $sum = 0;
        foreach ($places as $place) {
            if($place->ticket) {

                if($place->ticket->pay_status == Ticket::PAY_PAY)
                    $sold++;
                elseif($place->ticket->pay_status == Ticket::PAY_INVITE)
                    $invite++;
                else
                    $reserved++;
                if($place->ticket->pay_status != Ticket::PAY_INVITE)
                    $sum += $place->ticket->price;
            }
        }
        $val = round(($sum / $sumPlaces) * 100, 2);
        return ["reserved" => $reserved, "sold" => $sold, "invite" => $invite, "sum" => $sum,
            "val" => $val];
    }

    /**
     * @return array
     * @description returns data for high-chart statistics
     */
    public function getChartData()
    {
        $places = $this->places;
        $reserved = [];
        $sold = [];
        $dateExist = [];
        $soldResult = [0];
        $reservedResult = [0];
        $dateY = 0;
        $dateM = 0;
        $dateD = 0;
        if (!empty($places)) {
            foreach ($places as $place) {
                if ($place->ticket) {
                    if($place->ticket->order->type != Order::TYPE_QUOTE) {
                        $date_add = Yii::app()->dateFormatter->format('yyyy-MM-dd', $place->ticket->date_add);
                        if ($place->ticket->pay_status == Ticket::PAY_PAY) {
                            if (array_key_exists($date_add, $sold))
                                array_push($sold[$date_add], $place->ticket);
                            else
                                $sold[$date_add] = array($place->ticket);
                        } elseif ($place->ticket->pay_status == Ticket::PAY_NOT_PAY) {
                            if (array_key_exists($date_add, $reserved))
                                array_push($reserved[$date_add], $place->ticket);
                            else
                                $reserved[$date_add] = array($place->ticket);
                        }
                        $dateExist[] = Yii::app()->dateFormatter->format('yyyy-MM-dd', $place->ticket->date_add);
                    }
                }
            }
            if (!empty($dateExist)) {
                $dateExist = array_unique($dateExist);
                $minDate = min($dateExist);
                $dates = $this->dateRange(min($dateExist), max($dateExist));
                $soldResult = [];
                $reservedResult = [];
                foreach ($dates as $date) {
                    $dateFormat = Yii::app()->dateFormatter->format('yyyy-MM-dd', $date);
                    if (array_key_exists($dateFormat, $sold))
                        $soldResult[] = count($sold[$dateFormat]);
                    else
                        $soldResult[] = 0;

                    if (array_key_exists($dateFormat, $reserved))
                        $reservedResult[] = count($reserved[$dateFormat]);
                    else
                        $reservedResult[] = 0;

                }

                $dateY = Yii::app()->dateFormatter->format("yyyy", $minDate);
                $dateM = Yii::app()->dateFormatter->format("M", $minDate);
                $dateD = Yii::app()->dateFormatter->format("d", $minDate);
            }
        }
        return ["sold"=>$soldResult, "reserved"=>$reservedResult, "dateY"=>$dateY, "dateM"=>$dateM, "dateD"=>$dateD];

    }

    /**
     * @param $first
     * @param $last
     * @param string $step
     * @param string $format
     * @return array
     * @description function for calculation range between first date and last date
     */
    private function dateRange($first, $last, $step="+1 day", $format="d.m.Y") {
        $dates = array();
        $current = strtotime( $first );
        $last = strtotime( $last );

        while ( $current <= $last ) {

            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }

        return $dates;
    }

    /**
     * @return array
     * @description returns delivery and pay statistics
     */
    public function getDeliveryAndPayStatistics()
    {
        $places = $this->places;
        $filteredByDelivery = [];
        $result = [];
        foreach ($places as $place) {
            if ($place->ticket) {
                $ticket = $place->ticket;
                if (isset($ticket->delivery_type) && isset($ticket->pay_type) && $ticket->pay_status == Ticket::PAY_PAY && $ticket->status != Ticket::STATUS_CANCEL) {
                    $delName = Order::$delTypes[$ticket->delivery_type];
                    if (array_key_exists($delName, $filteredByDelivery))
                        array_push($filteredByDelivery[$delName], $ticket);
                    else
                        $filteredByDelivery[$delName] = array($ticket);
                }
            }
        }

        foreach ($filteredByDelivery as $delName=>$tickets) {
            $byCardCount = 0;
            $byCashCount = 0;
            $byCardSum = 0;
            $byCashSum = 0;
            foreach ($tickets as $ticket) {
                if ($ticket->pay_type == Order::PAY_CARD) {
                    $byCardCount += 1;
                    $byCardSum += $ticket->price;
                }
                if ($ticket->pay_type == Order::PAY_CASH) {
                    $byCashCount += 1;
                    $byCashSum += $ticket->price;
                }
            }

            $result[$delName] = ["byCardCount"=>$byCardCount,"byCashCount"=>$byCashCount,"byCardSum"=>$byCardSum,"byCashSum"=>$byCashSum];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getKG9Statistics()
    {
        $places = $this->places;
        $pricedArray = [];
        foreach ($places as $place) {
            if ($place->ticket) {
                if ($place->ticket->pay_status == Ticket::PAY_INVITE)
                    $place->price = $place->ticket->price = 0;

                if (array_key_exists($place->ticket->price, $pricedArray))
                    array_push($pricedArray[$place->ticket->price], $place);
                else
                    $pricedArray[$place->ticket->price] = array($place);
            }else {
                if (array_key_exists($place->price, $pricedArray))
                    array_push($pricedArray[$place->price], $place);
                else
                    $pricedArray[$place->price] = array($place);
            }
        }
        $result = [];
        foreach ($pricedArray as $price => $places) {
            $realizedCount = 0;
            $realizedSum = 0;
            $notRealizedCount = 0;
            $notRealizedSum = 0;
            foreach ($places as $place) {
                if ($place->ticket) {
                    $ticket = $place->ticket;
                    if ($ticket->pay_status == Ticket::PAY_PAY || $ticket->pay_status == Ticket::PAY_INVITE) {
                        if($ticket->status != Ticket::STATUS_CANCEL) {
                            $realizedCount += 1;
                            $realizedSum += $place->ticket->price;
                        }
                    } elseif ($ticket->pay_status == Ticket::PAY_NOT_PAY) {
                        $notRealizedCount += 1;
                        $notRealizedSum += $place->ticket->price;
                    }
                } else {
                    $notRealizedCount += 1;
                    $notRealizedSum += $place->price;
                }
            }
            $result[$price] = ["realizedCount"=>$realizedCount, "realizedSum"=>$realizedSum,
                                "notRealizedCount"=>$notRealizedCount, "notRealizedSum"=>$notRealizedSum
            ];
        }
        ksort($result);
        return $result;
    }

    public function getKG10Statistics()
    {
        $places = $this->places;
        $result = [];
        $rolesArray = [];
        $role_ids = [];
        $role = new Role();
        $role_ids_by_parent = [];
        foreach ($places as $place) {
            if ($place->ticket) {
                if (!in_array($place->ticket->role_id, $role_ids))
                    $role_ids[] = $place->ticket->role_id;
            }
        }

        foreach ($role_ids as $id) {
            $role_ids_by_parent[$id] = $role->getAbsoluteParentRecursively($id);
        }
        $role_ids = [];
        foreach ($places as $place) {
            if ($place->ticket) {
                if (array_key_exists($role_ids_by_parent[$place->ticket->role_id], $rolesArray))
                    array_push($rolesArray[$role_ids_by_parent[$place->ticket->role_id]], $place->ticket);
                else
                    $rolesArray[$role_ids_by_parent[$place->ticket->role_id]] = array($place->ticket);

                if (!in_array($role_ids_by_parent[$place->ticket->role_id], $role_ids))
                    $role_ids[] = $role_ids_by_parent[$place->ticket->role_id];
            }
        }
        $roleNames = $this->getRolesName($role_ids);

        foreach ($rolesArray as $role=>$places) {

            $realizedCount = 0;
            $realizedSum = 0;
            $returnedCount = 0;
            $returnedSum = 0;

            foreach ($places as $ticket) {

                if($ticket->delivery_status == Ticket::STATUS_QUOTE_RETURN && $ticket->status == Ticket::STATUS_CANCEL && $ticket->pay_status == Ticket::PAY_PAY) {
                    $returnedCount += 1;
                    $returnedSum += $ticket->price;
                } elseif ($ticket->pay_status == Ticket::PAY_PAY && $ticket->status != Ticket::STATUS_CANCEL) {
                    $realizedCount += 1;
                    $realizedSum += $ticket->price;
                }

            }

            $result[$role] = [
                'roleName' => $roleNames[$role],'publishedCount' => $realizedCount+$returnedCount, 'publishedSum' => $realizedSum+$returnedSum,
                'realizedCount' => $realizedCount, 'realizedSum' => $realizedSum, 'returnedCount' => $returnedCount,
                'returnedSum' => $returnedSum
            ];
        }
        return $result;
    }

    private function getRolesName($ids)
    {
        $result = Yii::app()->db->createCommand()
            ->select("t.id,t.name")
            ->from(Role::model()->tableName()." t")
            ->andWhere(array("in", "t.id", $ids))
            ->queryAll();
        $names = [];
        if ($result) {
            foreach ($result as $data) {
                $names[$data["id"]] = $data["name"];
            }
        }
        return $names;
    }

    public function getCashierStatistic()
    {
        $data = $this->storage;
        $filteredArrByEvent = [];
        $user_ids = [];
        $role_ids = [];
        $userPercentArr = [];
        $percentArr = [];
        $event_ids = [];
        $result = [];


        if (empty($data))
            return [];
        foreach ($data as $ticket) {
            if (array_key_exists($ticket["event_id"], $filteredArrByEvent))
                array_push($filteredArrByEvent[$ticket["event_id"]], (object)$ticket);
            else
                $filteredArrByEvent[$ticket["event_id"]] = array((object)$ticket);

            if (!in_array($ticket["user_id"], $user_ids) && $ticket["user_id"])
                $user_ids[] = $ticket["user_id"];

            if (!in_array($ticket["author_print_id"], $user_ids) && $ticket["author_print_id"])
                $user_ids[] = $ticket["author_print_id"];

            if (!in_array($ticket["cash_user_id"], $user_ids) && $ticket["cash_user_id"])
                $user_ids[] = $ticket["cash_user_id"];

            if (!in_array($ticket["role_id"], $role_ids) && $ticket["role_id"])
                $role_ids[] = $ticket["role_id"];

            if (!in_array($ticket["cash_role_id"], $role_ids) && $ticket["cash_role_id"])
                $role_ids[] = $ticket["cash_role_id"];

            if (!in_array($ticket["print_role_id"], $role_ids) && $ticket["print_role_id"])
                $role_ids[] = $ticket["print_role_id"];

            if (!in_array($ticket["event_id"], $event_ids) && $ticket["event_id"])
                $event_ids[] = $ticket["event_id"];
        }

        $percentData = Yii::app()->db->createCommand()
            ->select("t.*")
            ->from(CashierPercent::model()->tableName()." t")
            ->orWhere(array("in", "t.user_id", $user_ids))
            ->orWhere(array("in", "t.event_id", $event_ids))
            ->orWhere(array("in", "t.role_id", $role_ids))
            ->queryAll();

        foreach ($percentData as $data) {
            $percents = ["fullSalePercent"=>$data["order_cash_print_percent"], "cashSalePercent"=>$data["cash_print_percent"],
                "printSalePercent"=>$data["print_percent"]];
            if ($data["event_id"] != CashierPercent::NO_EVENT)
                $percentArr[$data["role_id"]][$data["user_id"]][$data["event_id"]] = $percents;
            elseif ($data["user_id"] != CashierPercent::NO_USER)
                $percentArr[$data["role_id"]][$data["user_id"]][CashierPercent::NO_EVENT] = $percents;
            elseif ($data["role_id"] != CashierPercent::NO_ROLE)
                $percentArr[$data["role_id"]][CashierPercent::NO_USER][CashierPercent::NO_EVENT] = $percents;
        }

        $usersData = Yii::app()->db->createCommand()
            ->select("t.id,t.name,t.surname,t.email")
            ->from(User::model()->tableName()." t")
            ->andWhere(array("in", "t.id", $user_ids))
            ->queryAll();
        $users = [];
        foreach ($usersData as $user) {
            $userName = $user["id"]." - ".$user["name"]." ".$user["surname"];
            if (!($user["name"]) && !$user["surname"])
                $userName = $user["id"]." - ".$user["email"];
            $users[$user["id"]] = $userName;
        }
        $eventsData = Event::getListEvents([],[],$event_ids);
        $event = [];
        foreach ($eventsData as $eventData)
            $event[$eventData["id"]] = $eventData["name"]." / ".$eventData["city_name"]." / ".$eventData["timing"];

        foreach ($filteredArrByEvent as $event_id => $tickets) {
            $fullSaleArr = [];
            $cashSaleArr = [];
            $printSaleArr = [];
            $inviteSaleArr = [];

            $event_ids[] = $event_id;

            foreach ($tickets as $ticket) {
                if ($ticket->pay_status == Ticket::PAY_INVITE) {
                    if (array_key_exists($ticket->user_id, $inviteSaleArr))
                        array_push($inviteSaleArr[$ticket->author_print_id], $ticket);
                    else
                        $inviteSaleArr[$ticket->author_print_id] = array($ticket);
                } elseif ($ticket->user_id == $ticket->author_print_id && $ticket->user_id == $ticket->cash_user_id && $ticket->user_id) {
                    if (array_key_exists($ticket->user_id, $fullSaleArr))
                        array_push($fullSaleArr[$ticket->author_print_id], $ticket);
                    else
                        $fullSaleArr[$ticket->author_print_id] = array($ticket);
                } elseif ($ticket->author_print_id == $ticket->cash_user_id && $ticket->cash_user_id) {
                    if (array_key_exists($ticket->cash_user_id, $cashSaleArr))
                        array_push($cashSaleArr[$ticket->author_print_id], $ticket);
                    else
                        $cashSaleArr[$ticket->author_print_id] = array($ticket);
                } elseif ($ticket->author_print_id) {
                    if (array_key_exists($ticket->author_print_id, $printSaleArr))
                        array_push($printSaleArr[$ticket->author_print_id], $ticket);
                    else
                        $printSaleArr[$ticket->author_print_id] = array($ticket);
                }

            }
            $example = ["fullSaleCount"=>0,"fullSaleSum"=>0,"fullSalePercent"=>0,
                        "cashSaleCount"=>0,"cashSaleSum"=>0,"cashSalePercent"=>0,
                        "printSaleCount"=>0,"printSaleSum"=>0,"printSalePercent"=>0, "inviteCount"=>0
            ];

            foreach ($fullSaleArr as $user_id=>$nTickets) {
                $this->addCashierStatistic("full",$result,$nTickets,$event[$event_id],$users[$user_id],$example,$percentArr,$event_id);
            }

            foreach ($cashSaleArr as $user_id=>$nTickets) {
                $this->addCashierStatistic("cash",$result,$nTickets,$event[$event_id],$users[$user_id],$example,$percentArr,$event_id);
            }

            foreach ($printSaleArr as $user_id=>$nTickets) {
                $this->addCashierStatistic("print",$result,$nTickets,$event[$event_id],$users[$user_id],$example,$percentArr,$event_id);
            }

            foreach ($inviteSaleArr as $user_id=>$nTickets) {
                $this->addCashierStatistic("invite",$result,$nTickets,$event[$event_id],$users[$user_id],$example,$percentArr,$event_id);
            }
        }
        return $result;
    }

    private function addCashierStatistic($action,&$result,$tickets,$event_id,$user_id,$example,$percentArr,$numericEventId)
    {

        foreach ($tickets as $ticket) {

            $cashierMoney = 0;
            if ($action != "invite") {
                $percent_event = $numericEventId;
                $percent_role = $ticket->print_role_id;
                $percent_user = $ticket->author_print_id;

                $percent = $this->calculatePercent($action, $percentArr, $percent_role, $percent_user, $percent_event);
                $cashierMoney = $ticket->price / 100 * $percent;
            }
            if (isset($result[$event_id])) {
                if ($action == "invite") {
                    if (isset($result[$event_id][$user_id])) {
                        if(isset($result[$event_id][$user_id][$action."Count"]))
                            $result[$event_id][$user_id][$action."Count"] += 1;
                        else
                            $result[$event_id][$user_id][$action."Count"] = 1;

                    } else {
                        $result[$event_id][$user_id] = $example;
                        $result[$event_id][$user_id][$action."Count"] += 1;
                    }
                    continue;
                }
                if(!isset($result[$event_id][$user_id]))
                    $result[$event_id][$user_id] = $example;
                if(!isset($result[$event_id][$user_id]["intviteCount"]))
                    $result[$event_id][$user_id]["intviteCount"] = 0;

                if(isset($result[$event_id][$user_id][$action."SaleCount"]))
                    $result[$event_id][$user_id][$action."SaleCount"] += 1;
                else
                    $result[$event_id][$user_id][$action."SaleCount"] = 1;
                if(isset($result[$event_id][$user_id][$action."SaleSum"]))
                    $result[$event_id][$user_id][$action."SaleSum"] += $ticket->price;
                else
                    $result[$event_id][$user_id][$action."SaleSum"] = $ticket->price;
                if(isset($result[$event_id][$user_id][$action."SalePercent"]))
                    $result[$event_id][$user_id][$action."SalePercent"] += $cashierMoney;
                else
                    $result[$event_id][$user_id][$action."SalePercent"] = $cashierMoney;
            } else {
                if ($action == "invite") {
                    $result[$event_id][$user_id] = $example;
                    if(isset($result[$event_id][$user_id][$action."Count"]))
                        $result[$event_id][$user_id][$action."Count"] += 1;
                    else
                        $result[$event_id][$user_id][$action."Count"] = 1;
                } else {
                    $result[$event_id][$user_id] = $example;
                    $result[$event_id][$user_id][$action."SaleCount"] += 1;
                    $result[$event_id][$user_id][$action."SaleSum"] += $ticket->price;
                    $result[$event_id][$user_id][$action."SalePercent"] += $cashierMoney;
                }
            }

        }
    }

    private function calculatePercent($action,$percentArr, $percent_role, $percent_user, $percent_event)
    {
        $percent = 0;
        if(isset($percentArr[$percent_role][$percent_user][$percent_event]))
            return $percentArr[$percent_role][$percent_user][$percent_event][$action."SalePercent"];
        if(isset($percentArr[$percent_role][$percent_user][CashierPercent::NO_EVENT]))
            return $percentArr[$percent_role][$percent_user][CashierPercent::NO_EVENT][$action."SalePercent"];
        if(isset($percentArr[$percent_role][CashierPercent::NO_USER][CashierPercent::NO_EVENT]))
            return $percentArr[$percent_role][CashierPercent::NO_USER][CashierPercent::NO_EVENT][$action."SalePercent"];

        return $percent;
    }

}