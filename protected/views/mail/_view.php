<div class="mail" style="margin: 0;padding: 0;border: 0;font-family:'Trebuchet MS',Helvetica,sans-serif;font-size: 10pt;vertical-align: baseline;background: #fff;">
    <div class="content" style="margin: 0;padding: 20px;border: 0;vertical-align: baseline;width: 540px;color: #757679;line-height: 1;border: 1px solid #EAEAEA;background: #fdfdfd;">
        <img class="logo" src="[[[cid]]]" alt="kasa.in.ua" title="kasa.in.ua" width="374" height="43" style="display:block; margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 30px;width: 374px;">
        <p class="t1 mb20" style="margin: 0;padding: 0;border: 0;font-size:100%;vertical-align: baseline;margin-bottom: 20px;line-height: 1.3;">Доброго дня, <?=$orderData["owner_name"]?>.<br> <?=$orderData["message"]?></p>
        <p class="t1" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;">В замовленні №<?=$orderData["order_number"]?>:</p>
        <p class="t2 mb10" style="margin: 0;padding: 0;border: 0;font-size: 16pt;vertical-align: baseline;margin-bottom: 10px;text-transform: uppercase;color: #231f20;"><span style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$orderData["ticketsCount"]?> <?=$orderData["ticketsCountLabel"]?></span> на суму <span style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$orderData["ticketsPrice"]?></span> грн.</p>
        <?php
        $headers = $orderData["headers"];
        foreach ($headers as $header) {
            ?>
            <table width="500" cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;border-collapse: collapse;border-spacing: 0;">
                <tr style="margin: 0;padding: 0;border: 0;vertical-align: baseline;">
                    <td width="250" style="font-family:'Trebuchet MS',Helvetica,sans-serif;font-size: 10pt;margin: 0;padding: 0;border: 0;vertical-align: baseline;">
                        <p class="t1" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;">Спосіб оплати</p>
                        <p class="t2 mb10" style="margin: 0;padding: 0;border: 0;font-size: 16pt;vertical-align: baseline;margin-bottom: 10px;text-transform: uppercase;color: #231f20;"><span style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$header["pay_type"]?></span></p>
                        <p class="t1" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;">Статус оплати</p>
                        <p class="t2 mb10" style="margin: 0;padding: 0;border: 0;font-size: 16pt;vertical-align: baseline;margin-bottom: 10px;text-transform: uppercase;color: #231f20;"><span style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$header["pay_status"]?></span></p>
                    </td>
                    <td width="250" style="font-family:'Trebuchet MS',Helvetica,sans-serif;font-size: 10pt;margin: 0;padding: 0;border: 0;vertical-align: baseline;">
                        <p class="t1" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;">Спосіб доставки</p>
                        <p class="t2 mb10" style="margin: 0;padding: 0;border: 0;font-size: 16pt;vertical-align: baseline;margin-bottom: 10px;text-transform: uppercase;color: #231f20;"><span style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$header["delivery_type"]?></span></p>
                        <p class="t1" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;">Статус доставки</p>
                        <p class="t2 mb10" style="margin: 0;padding: 0;border: 0;font-size: 16pt;vertical-align: baseline;margin-bottom: 10px;text-transform: uppercase;color: #231f20;"><span style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$header["delivery_status"]?></span></p>
                    </td>
                </tr>
            </table>
            <p class="mb10 mt20" style="margin: 0;padding: 0;border: 0;font-family: 'Montserrat', sans-serif;vertical-align: baseline;margin-bottom: 10px;margin-top: 20px;">Ваші квитки:</p>
            <?php
            $events = $header["events"];
            foreach ($events as $event) {
                ?>
                <p class="t4 mb5" style="margin: 0;padding: 0;border: 0;font-size: 18pt;vertical-align: baseline;margin-bottom: 5px;color: #231f20;"><?=$event["event_name"]?></p>
                <p class="t5 mb10" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 10px;"><?=$event["event_date"]?><span class="s" style="margin: 0 15px;padding: 0;border: 0;vertical-align: baseline;color: #757679;">|</span><?=$event["event_time"]?><span class="s" style="margin: 0 15px;padding: 0;border: 0;vertical-align: baseline;color: #757679;">|</span><span class="s1" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$event["event_city"]?></span> «<?=$event["event_location"]?>»</p>
                <table class="t6 mb30" width="500" cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;border-collapse: collapse;border-spacing: 0;margin-bottom: 30px;color: #231f20;">
                    <?php
                    $tickets = $event["tickets"];
                    foreach ($tickets as $ticket) {
                        if (intval($ticket["price"]))
                            $price = "<span>".$ticket["price"]."</span> грн.";
                        else
                            $price = "<span style='color: red'>".$ticket["price"]."</span>";
                        ?>
                        <tr style="margin: 0;padding: 0;border: 0;vertical-align: baseline;">
                            <td width="125" style="font-family:'Trebuchet MS',Helvetica,sans-serif;font-size: 10pt;margin: 0;padding: 0;border: 0;vertical-align: baseline;"><p style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 5px;"><span style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$ticket["sector"]?></span></p></td>
                            <td width="125" style="font-family:'Trebuchet MS',Helvetica,sans-serif;font-size: 10pt;margin: 0;padding: 0;border: 0;vertical-align: baseline;"><p style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 5px;">ряд <span style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$ticket["row"]?></span></p></td>
                            <td width="125" style="font-family:'Trebuchet MS',Helvetica,sans-serif;font-size: 10pt;margin: 0;padding: 0;border: 0;vertical-align: baseline;"><p style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 5px;">місце <span style="margin: 0;padding: 0;border: 0;vertical-align: baseline;font-weight: bold;"><?=$ticket["place"]?></span></p></td>
                            <td width="125" style="font-family:'Trebuchet MS',Helvetica,sans-serif;font-size: 10pt;margin: 0;padding: 0;border: 0;vertical-align: baseline;"><p style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 5px;"><?=$price?></p></td>
                        </tr>
                    <?php } ?>
                </table>
                <?php
            }
        }
        ?>
        <p style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;">Дата оформлення замовлення:</p>
        <p class="t7 mb30" style="margin: 0;padding: 0;border: 0;font-size: 16pt;vertical-align: baseline;margin-bottom: 30px;color: #231f20;"><?=$orderData["order_date"]?><span class="s" style="margin: 0 15px;padding: 0;border: 0;vertical-align: baseline;color: #757679;">|</span><?=$orderData["order_time"]?></p>
        <p style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;">Залишена Вами контактна інформація:</p>
        <p class="t8" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;color: #231f20;"><?=$orderData["owner_name"]?></p>
        <p class="t8" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;color: #231f20;"><?=$orderData["owner_phone"]?></p>
        <p class="t8" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;color: #231f20;"><?=$orderData["owner_country"].", ".$orderData["owner_city"]?></p>
        <p class="t8" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;color: #231f20;"><?=$orderData["owner_address"]?></p>
        <p class="mt30" style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;margin-top: 30px;">Дякуємо!</p>
        <p style="margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-bottom: 3px;">Яскравих вражень!</p>
    </div>
</div>