<div class="cart-widget">
    <div class="header"><i class="fa fa-shopping-basket"></i>кошик<div class="total"><span class="count"></span> <span class="sum"></span></div></div>
    <div id="cart-content" class="content">
        <?php

            $this->render('_tickets', array(
                "items" => $items,
                "event_id" => $this->event_id,
                "model_fake" => $this->model_fake
            ));
        ?>
    </div>
</div>
