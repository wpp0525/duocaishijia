<?php

class DefaultController extends Controller
{
    //进入支付页  这个界面不使用了
	public function actionIndex()
	{
        $orderId = $_GET['order_id'];
        $amount = $_GET['amount'];
        $pay_method_id = $_GET['pay_method_id'];
        $WIDsubject="多彩服务";

        $this->render('index', array(
            'orderId'       => $orderId,
            'amount'        => $amount,
            'pay_method_id' => $pay_method_id,
            'WIDsubject'        => $WIDsubject,
        ));
	}

    //这个界面也不使用了
//    public function  actionApi(){
//        //商户订单号
//        $out_trade_no = $_POST['WIDout_trade_no'];
//        //商户网站订单系统中唯一订单号，必填
//        //订单名称
//        $subject = $_POST['WIDsubject'];
//        //付款金额
//        $total_fee = $_POST['WIDtotal_fee'];
//        //订单描述
//        $body = $_POST['WIDbody'];
//
//        $orderId = $_GET['order_id'];
//        $amount = $_GET['amount'];
//        $pay_method_id = $_GET['pay_method_id'];
//        $this->render('alipayapi', array(
//            'WIDout_trade_no'=>$out_trade_no,
//            'WIDsubject'=>$subject,
//            'WIDtotal_fee'=>$total_fee,
//            'WIDbody'=>$body
//        ));
//    }
     // 去支付界面
    public function  actionAlipayapi(){
	    //商户订单号
       // $orderId = $_GET['order_id']+'1';//这个方法是get的
	   
		$order_id=$_GET['order_id'];		
		$orderId = Payment::model()->findByAttributes(array(
				'order_id' => $order_id,
			));

		//var_dump(isset($orderId));
	    if(count($order_id) ==14){ //如果是单笔订单，才会执行加1的命令
			if(isset($orderId)){//查询单笔订单，多次支付的情况后
	          $orderId = $_GET['order_id']+'1'; //如果设置了值，则加一,
			}else{
			  $orderId = $_GET['order_id'];
			}
	    }else{//多笔订单直接赋值
		    $orderId = $_GET['order_id'];
	    }

        $amount = $_GET['amount'];
        $pay_method_id = $_GET['pay_method_id'];
        $WIDsubject="多彩服务";
    //  echo('WIDsubject='.$WIDsubject);

        $this->render('alipayapi', array(
            'WIDout_trade_no'=>$orderId,
            'WIDsubject'=>$WIDsubject,
            'WIDtotal_fee'=>$amount,
            'WIDbody'=>$$pay_method_id
        ));
    }
     /*支付成功返回的数据*/
    public function  actionReturn(){
        //商户订单号
		//$out_trade_no=$_REQUEST['out_trade_no'];

       if(count($_REQUEST['out_trade_no']) ==14){ // 如果是单笔订单，才会执行减1的命令
		  $order_id=$_REQUEST['out_trade_no']-'1';
       }else{  //多笔直接赋值
	      $order_id=$_REQUEST['out_trade_no'];
	   }
		$orderId = Payment::model()->findByAttributes(array(
				'order_id' => $order_id,
			));

	    if(count($_REQUEST['out_trade_no']) ==14){
			if(isset($orderId)){
	          $out_trade_no = $_REQUEST['out_trade_no']-'1'; //如果设置了值，则减一
			}else{
			  $out_trade_no = $_REQUEST['out_trade_no'];
			}
	    }else{
		      $out_trade_no = $_REQUEST['out_trade_no'];
	    }
        //支付宝交易号
        $trade_no = $_REQUEST['trade_no'];
		//付款账号
	   // $buyer_email = $_REQUEST['buyer_email'];
		
        //交易状态
        $trade_status = $_REQUEST['trade_status'];
        $total_fee = $_REQUEST['total_fee'];

        $this->render('return_url', array(
            'out_trade_no'=>$out_trade_no, //商城的订单号
            'trade_no'=>$trade_no,         //支付宝的流水号
            'trade_status'=>$trade_status,
            'total_fee'=>$total_fee,

        ));
    }

	 public function  actionNotify(){
        //商户订单号
        $out_trade_no = $_POST['out_trade_no'];
        //支付宝交易号
        $trade_no = $_POST['trade_no'];
        //交易状态
        $trade_status = $_POST['trade_status'];
        $this->render('notify_url', array(
            'out_trade_no'=>$out_trade_no,
            'trade_no'=>$trade_no,
            'trade_status'=>$trade_status,
        ));
    }

//    public function actionDoConfirm()
//    {
//        $orderId = $_GET['order_id'];
//        $this->redirect(array('alipay/default/index',
//            'order_id'      => $model->order_id,
//            'amount'        => $payFirst,
//            'pay_method_id' => $_POST['payment_method_id'],
//        ));
//    }


}