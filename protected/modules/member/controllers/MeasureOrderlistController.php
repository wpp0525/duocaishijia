        <?php
/**
 * Created by PhpStorm.
 * User: Jeffery
 * Date: 14-12-9
 * Time: 上午11:12
 */

class MeasureOrderlistController extends CController {

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/member';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('detail','admin','create','update'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($index = 1)
	{
		$pageItemNum = 5;
		$id = Yii::app()->user->id;
		$allMeasureOrders = ArOrderMeasure::model()->findAll(array(
			'order'=>'create_time desc',
			'condition'=>'user_id=:user_id',
			'params'=>array(':user_id'=>$id),
		));
		$length = count($allMeasureOrders, 0);
		$count = ceil($length/$pageItemNum);
		$measureOrders = $this->getPairMeasureOrder($allMeasureOrders, $count, $index, $pageItemNum);
		$this->render('admin',array(
			'measureOrders'=>$measureOrders,
			'count'=>$count,
			'currentPage'=>$index
		));
	}



	public function getPairMeasureOrder($model = array(), $count, $index, $num){
		if($index <= $count)
			return array_slice($model, ($index-1)*$num, $num);
		else
			return array_slice($model, ($index-1)*$num);
	}

    public function actionView($measureId) {
        $this->layout = '//layouts/main';
        $model = ArOrderMeasure::model();
        $measureOrders = $model->findByPk($measureId);
//        dump($measureOrders);
        $webUser = Yii::app()->user;
        if(!$webUser->isGuest)
            $this->render('view', array(
                'measureOrders' => $measureOrders,'model'=>$model
            ));
        else
            $this->redirect(array('/user/login/redirectLogin', 'url'=>'/member/measureOrderlist/view'));

    }
    public function actionUpdate()
    {
//        $this->layout = '//layouts/main';
        $model = ArOrderMeasure::model();

        //单号和类型
        $measure_id = $_POST['cel_num'];
        $meas_type = $_POST['meas_type'];

        $count = 0 ;
        switch($meas_type) {
            case "cate":
                $itemsmodel = ArOrderMeasureItem::model();
                $category_ids = $_POST['category_ids'];
                $rst_areas = isset($_POST['rst_areas'])? 0:$_POST['rst_areas'];
                $icount = $itemsmodel -> deleteAll('measure_id=:measure_id',
                        array(':measure_id'=>$measure_id));
                //判断是否更新成功
                $count = $icount;
                $category_id = explode(',',$category_ids);
                $rst_area = explode(',',$rst_areas);
                for($index=0;$index<count($category_id);$index++){
                    $itemmodel = new ArOrderMeasureItem();
                    $itemmodel -> category_item_id = $category_id[$index];
                    $itemmodel -> rst_area = $rst_area[$index];
                    $itemmodel -> measure_id = $measure_id;
                    $itemmodel -> create_time = date('Y-m-d H:i:s', time());
//                    $itemmodel -> category_item_id = $category_ids;
                    //更新测量单明细表
                    if ($itemmodel->validate()) {
                        if (!$itemmodel->save()) {
                            Yii::log('Fail to save order measure item.', 'error');
                            throw new DbSaveException(array(
                                'errorInfo' => $this->modelError($itemmodel),
                            ));
                            }
                        }
                        else {
                        throw new InvalidDataException(array(
                            'errorInfo' => $this->modelError($itemmodel),
                        ));
                    }
                }
                break;
            case "area":
                //地址
                $client_phone = $_POST['client_phone'];
                $client_mobile = $_POST['client_mobile'];
                $client_zip = $_POST['client_zip'];
                $client_address = $_POST['client_address'];
                $client_name = $_POST['client_name'];
                $client_state = $_POST['client_state'];
                $client_city = $_POST['client_city'];
                $client_district = $_POST['client_district'];


                $count = $model -> updateByPk($measure_id,array(
                    'client_phone'=>$client_phone,
                    'client_mobile'=>$client_mobile,
                    'client_zip'=>$client_zip,
                    'client_address'=>$client_address,
                    'client_name'=>$client_name,
                    'client_state'=>$client_state,
                    'client_city'=>$client_city,
                    'client_district'=>$client_district
                ));
                break;
            case "time":
                //时间
                $measure_time = $_POST['measure_time'];
                $measure_time = date('Y-m-d H:i:s', time());
//                $measure_time = Yii::app()->format->formatDateTime($measure_time);
//                dump($measure_time);
//                dump($measure_time);die;
                $count = $model -> updateByPk($measure_id,array('create_time'=>$measure_time));
                break;
            case "memo":
                //备注
                $client_memo = $_POST['client_memo'];
                $count = $model -> updateByPk($measure_id,array('memo'=>$client_memo));
                break;
            default:
                break;
        }



        if($count>0){
            echo json_encode(array(
                'status' => true,'measure_id'=>$measure_id
            ));
        }else{
            echo json_encode(array(
                'status' => false
            ));
        }

    }
} 