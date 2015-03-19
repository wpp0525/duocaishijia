<?php

class CartController extends YController
{

    public function actionIndex()
    {
        $this->render('index', array('cart' => Yii::app()->cart));
    }

    public function actionAdd()
    {
	    $status = "";
	    $props = $_POST['props'];
	    if(is_array($props)){
		    foreach($props as $prop){
			    if($prop == ''){
				    $status = "props";
			    }
		    }
	    }
	    if($status == "props"){
		    echo json_encode(array(
			    'status' => $status,
		    ));
	    }else{
        $item = $this->loadItem();
	    if (!empty($_POST['qtys'])) {
		    try {
			    $total = $i = 0;
			    foreach ($_POST['qtys'] as $qty) {
				    Yii::app()->cart->put($item, $qty, $i);
				    $total ++;
				    $i++;
			    }
			    echo json_encode(array(
				    'status' => 'success',
				    'total' => $total,
			    ));
		    } catch (CException $e) {
			    echo json_encode(array(
				    'status' => 'false',
				    'msg' => $e->getMessage(),
			    ));
		    }
	    } else {

		    Yii::app()->cart->put($item);

	    }
	}
//        $quantity = empty($_POST['qty']) ? 1 : intval($_POST['qty']);
//        if(Yii::app()->cart->put($item, $quantity))
//            echo json_encode(array('status' => 'success','number' => Yii::app()->cart->getItemsCount()));
//        else
//            echo json_encode(array('status' => 'success'));
    }

    public function actionUpdate()
    {
	    $sku = Sku::model()->findByPk(substr($_POST['sku_id'], 3));
	    //        if($sku->stock<$_POST['qty']){
	    //            echo  '<div id="error-message" style="color:red">库存数量不足。</div>';
	    //        }else{
	    $item = CartItem::model()->with('skus')->findByPk(intval($_POST['item_id']));
	    $props[] = $_POST['props'];
	    $qtys[$_POST['sku_id']] = $_POST['qty'];
	    $item->cartProps = empty($_POST['props']) ? array() : $props;
	    $quantity = empty($_POST['qty']) ? 1 : $qtys[$_POST['sku_id']] = $_POST['qty'];
	    Yii::app()->cart->update($item, $quantity, 0);
	    //        }
    }

    public function actionRemove($key)
    {
        Yii::app()->cart->remove($key);
        $this->redirect('index');
    }

    public function actionClear()
    {
        Yii::app()->cart->clear();
        $this->redirect('index');
    }

    public function loadItem()
    {
        if (empty($_POST['item_id'])) {
            throw new CHttpException(400, 'Bad Request!.');
        }
        $item = CartItem::model()->with('skus')->findByPk(intval($_POST['item_id']));
        if (empty($item)) {
            throw new CHttpException(400, 'Bad Request!.');
        }
        $item->cartProps = empty($_POST['props']) ? '' : $_POST['props'];
        return $item;
    }

    public function actionGetPrice()
    {

        $positions = isset($_POST['positions']) ? $_POST['positions'] : array();
        $cart = Yii::app()->cart;

        $totalPrice = 0;
        foreach ($positions as $key) {
            $item = $cart->itemAt($key);
	        foreach($item->sku as $sku){
		        $skuId = 'Sku'.$sku->sku_id;
		        if($skuId == $key) {
			        $props = $sku->props;
		        }
	        }
	        $props = json_decode($props, true);
	        $props = implode(';',$props);
            $totalPrice += $item->getSumPrice(null,$props,$key);
        }
        echo json_encode(array('total' => $totalPrice));
    }

	public function actionAddToUser ()
	{
		/**
		 * @var UserModule    $webUser
		 * @var EShoppingCart $cart
		 */
		$webUser = Yii::app()->user;
		$cart = Yii::app()->cart;
		$items = $cart->getPositions();

		$profile = Profile::model()->findByAttributes(array('user_id' => $webUser->id));
		if ($profile) {
			$profile->cart = serialize($items);
			if ($profile->save()) {
				echo json_encode(array(
					'status' => 'success',
				));
			} else {
				echo json_encode(array(
					'status' => 'false',
				));
			}
		} else {
			echo json_encode(array(
				'status' => 'false',
			));
		}
	}
}