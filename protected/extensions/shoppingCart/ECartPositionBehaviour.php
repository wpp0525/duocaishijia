<?php

/**
 * position in the cart
 *
 * @author pirrat <mrakobesov@gmail.com>
 * @version 0.9
 * @package ShoppingCart
 *
 * Can be used with non-AR models.
 */
class ECartPositionBehaviour extends CActiveRecordBehavior {

	/**
	 * Positions number
	 * @var array
	 */
	private $quantity = array();
    /**
     * Update model on session restore?
     * @var boolean
     */
    private $refresh = true;

    /**
     * Position discount sum
     * @var float
     */
    private $discountPrice = 0.0;


	/**
	 * Returns total price for all units of the position
	 * @param bool $withDiscount
	 * @param null $prop
	 * @return float|int
	 */
	public function getSumPrice($withDiscount = true, $prop = null, $keyId=null) {
	    $prices = $this->getOwner()->getPrice();
	    $qtys = $this->quantity;

	    $fullSum = 0;

	    if(is_array($this->quantity)){
		    if(!$keyId){
		    $i = 0;
		    foreach ($prices as $price) {
			    $fullSum += $price * $qtys[0][$i];
			    $i++;
		    }
		    } else {
			    if (!is_array($prices)) {
				    $fullSum = $prices * $qtys[$keyId];
			    } else {
				    $fullSum = $prices[$prop] * $qtys[$keyId];
			    }
		    }

		    if($withDiscount) {
			    $fullSum -=  $this->discountPrice;
		    }

	    }else{
		    if (isset($prop)) {
			    $propPrices = $this->getOwner()->getPrice();
			    $propPrice = $propPrices[$prop];
			    $fullSum = $propPrice * $this->quantity;
		    } else {
			    $fullSum = $this->getOwner()->getPrice() * $this->quantity;
		    }
	    }
        return $fullSum;
    }

    /**
     * Returns quantity.
     * @return int
     */
    public function getQuantity($key = 0) {
	    $quantity = $this->quantity;
        return $quantity[$key];
    }

    /**
     * Updates quantity.
     *
     * @param int quantity
     */
    public function setQuantity($newVal, $key = 0) {
	    $quantity = $this->quantity;
	    $quantity[$key] = $newVal;
	    $this->quantity = $quantity;
    }

    /**
     * Magic method. Called on session restore.
     */
    public function __wakeup() {
        if ($this->refresh === true)
            $this->getOwner()->refresh();
    }

    /**
     * If we need to refresh model on restoring session.
     * Default is true.
     * @param boolean $refresh
     */
    public function setRefresh($refresh) {
        $this->refresh = $refresh;
    }

    /**
     * Add $price to position discount sum
     * @param float $price
     * @return void
     */
    public function addDiscountPrice($price) {
        $this->discountPrice += $price;
    }

    /**
     * Set position discount sum
     * @param float $price
     * @return void
     */
    public function setDiscountPrice($price) {
        $this->discountPrice = $price;
    }
}
