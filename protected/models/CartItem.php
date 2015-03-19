<?php

/**
 * @author Lujie.Zhou(gao_lujie@live.cn, qq:821293064).
 */
class CartItem extends Item implements IECartPosition
{
    public $cartProps = '';

    public $sku;

    public function getId()
    {
        if (empty($this->skus)) {
            return 'Item' . $this->item_id;
        } else {
	        $skus = array();
	        foreach ($this->skus as $sku) {
		        $skus[$sku->props] = $sku;
	        }
	        if (is_array($this->cartProps)) {
		        foreach ($this->cartProps as $cartProp) {
			        $pvids = explode(';', $cartProp);
			        $props = array();
			        foreach ($pvids as $pvid) {
				        $ids = explode(':', $pvid);
				        $props[$ids[0]] = $pvid;
			        }
			        $props = json_encode($props);
			        if (isset($skus[$props])) {
				        $this->sku[] = $skus[$props];
				        $key[] = 'Sku'.$skus[$props]->sku_id;
			        }
		        }
	        } else {
		        $props = array();
		        $pvids = explode(';', $this->cartProps);
		        foreach ($pvids as $pvid) {
			        $ids = explode(':', $pvid);
			        $props[$ids[0]] = $pvid;
		        }
		        $props = json_encode($props);
			    $this->sku = $skus[$props];
		        $key = 'Sku'.$skus[$props]->sku_id;
	        }

	        if ($key) {
		        return $key;
	        } else {
		        return false;
	        }

        }
    }

	public function getPrice ()
	{
		if (is_array($this->cartProps)) {
			$price = array();
			foreach ($this->cartProps as $carProp) {
				if (!empty($this->sku)) {
					foreach ($this->sku as $sku) {
						$skuProps = implode(';', json_decode($sku->props, true));

						if ($skuProps == $carProp) {
							$price[$carProp] = $sku->price;
						}
					}
				}
			}
//			if (!empty($this->sku)) {
//				foreach ($this->sku as $sku) {
//					$price[] = $sku->price;
//				}
//			} else {
//				$price = empty($this->sku);
//			}
		} else {
			if (!empty($this->sku)) {
				$price = $this->sku->price;
			} else {
				$price = empty($this->sku);
			}
		}
		return $price;
		//        return empty($this->sku) ? $this->price : $this->sku->price;
	}

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Item the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}