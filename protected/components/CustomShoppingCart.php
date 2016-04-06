 <?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 06.05.15
 * Time: 14:07
 */

class CustomShoppingCart extends EShoppingCart {


    public function init($test=false){
        $this->restoreFromSession();
    }

    /**
     * Restores the shopping cart from the session
     */
    public function restoreFromSession() {
        $data = unserialize(Yii::app()->getUser()->getState(Yii::app()->user->getState("currentCartId")));
        if (is_array($data) || $data instanceof Traversable)
            foreach ($data as $key => $product)
                parent::add($key, $product);

    }

    /**
     * Add item to the shopping cart
     * If the position was previously added to the cart,
     * then information about it is updated, and count increases by $quantity
     * @param IECartPosition $position
     * @param int count of elements positions
     */
    public function put(IECartPosition $position, $quantity = 1, $cartId = false) {
        $key = $position->getId();
        if ($cartId)
            $this->cartId = $cartId;

        if ($this->itemAt($key) instanceof IECartPosition) {
            $position = $this->itemAt($key);
            $oldQuantity = $position->getQuantity();
            $quantity += $oldQuantity;
        }

        $this->update($position, $quantity);
    }

    public function update(IECartPosition $position, $quantity) {
        if (!($position instanceof CComponent))
            throw new InvalidArgumentException('invalid argument 1, product must implement CComponent interface');

        $key = $position->getId();

        $this->cartId = Yii::app()->user->getState("currentCartId");
        $position->detachBehavior("CartPosition");
        $position->attachBehavior("CartPosition", new ECartPositionBehaviour());
        $position->setRefresh($this->refresh);

        $position->setQuantity($quantity);

        if ($position->getQuantity() < 1)
            $this->remove($key);
        else
            parent::add($key, $position);

        $this->applyDiscounts();
        $this->onUpdatePosition(new CEvent($this));
        $this->saveState();
    }

    /**
     * Removes position from the shopping cart of key
     * @param mixed $key
     */
    public function remove($key) {
        parent::remove($key);
        $this->cartId = Yii::app()->user->getState("currentCartId");
        $this->applyDiscounts();
        $this->onRemovePosition(new CEvent($this));
        $this->saveState();
    }

    public function clear()
    {
        parent::clear();
        $this->cartId = Yii::app()->user->getState("currentCartId");
        $this->saveState();
    }

}