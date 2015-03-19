
<META http-equiv="content-type" content="text/html; charset=utf-8">
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=A4749739227af1618f7b0d1b588c0e85"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
<div id="l-map" style="height: 100%;"></div>

<script type="text/javascript">
	// 百度地图API功能.
	var map = new BMap.Map("l-map");

	map.centerAndZoom(new BMap.Point(110.269945,37.86713), 5);
	map.enableScrollWheelZoom(true);
	var index = 0;
	var myGeo = new BMap.Geocoder();//创建一个地址解析器的实例
	var adds = [];
	var markers = [];
	var storeInfo = {};
	var stores = <?php echo json_encode($storesInfo); ?>;

	function setAdds () {
		$.each(stores, function(index, store){
			if (index == "address") {
				adds = store;
			} else {
				storeInfo[index] = {
					"name": store["storeName"],
					"phone":store["phone"]
				}
			}
		});
		addStores();
	}
	/**
	 * 循环门店地址，并添加到百度地图中
	 */
	function addStores(){
		var add = adds[index];
		geocodeSearch(add);
		index++;
	}
    /**
     * ，在百度地图中解析门店地址，并添加到地图中
     */
	function geocodeSearch(add){
		if(index < adds.length){
			setTimeout(window.addStores,400);
		}
		//将地址解析结果显示在地图上
		myGeo.getPoint(add, function(point){
			var opts = {
				width : 200,     // 信息窗口宽度    如果指定的宽度为0，则信息窗口的宽度将按照其内容自动调整
				height: 0,     // 信息窗口高度  如果指定的高度为0，则信息窗口的高度将按照其内容自动调整
				title : storeInfo[add].name, // 信息窗口标题
				enableMessage: false//设置允许信息窗发送短息
			}
			var myStyles = [{
				url: '../../../images/btn.jpg',
				size: new BMap.Size(32, 32),
				opt_anchor: [16, 0],
				textColor: 'red',
				opt_textSize: 20
			}];
			if (point) {
				var myIcon = new BMap.Icon("../../../images/flag_zh_cn.png", new BMap.Size(16,12));
				var point1 = new BMap.Point(point.lng, point.lat);
				var marker = new BMap.Marker(point1, {icon:myIcon});

				var infoWindow = new BMap.InfoWindow("地址：" + add+"<br/>电话：" + storeInfo[add].phone, opts);
				marker.addEventListener("click", function(){this.openInfoWindow(infoWindow);});

				markers.push(marker);
				var markerClusterer = new BMapLib.MarkerClusterer(map, {markers:markers});
				markerClusterer.setStyles(myStyles);
			}
		}, "全国");
	}
	setAdds();
</script>

