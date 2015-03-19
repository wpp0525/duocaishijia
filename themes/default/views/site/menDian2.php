
<div class="container">
    <div class="form" style="margin-bottom:60px">
        <div class="area_li">
            <div class="area-link">
                <p align="center"><strong>已开通服务城市：</strong>          </p>
                <table class="table_mendian" style="border: 1px solid #000000;">
<!--	                <tr style="height: 36px">-->
<!--		                <td width="146" align="left" valign="middle">大区</td>-->
<!--		                <td width="140" align="left" valign="middle">城市</td>-->
<!--		                <td width="723" align="left" valign="middle">地址</td>-->
<!--	                </tr>-->
	                <?php
		                foreach($sectionsArea as $key=>$section){

			                if(count($section)){
				                $sections = Section::model()->findByPk($key);?>
				                <tr style="height: 36px;border-bottom:1px solid #000000; ">
					                <td class=tr1 align="left" valign="middle" style="border-right:1px solid #000000;"><strong><?php echo $sections->section;?></strong></td>
					                <td align="left" valign="middle" style="border-right:1px solid #000000;"><strong>城市<strong></td>
					                <td align="left" valign="middle" style="border-right:1px solid #000000;"><strong>地址<strong></td>
				                </tr>
				                <?php
				                foreach($section as $areaId){
					                $area = Area::model()->findByPk($areaId);
					                $dcstores = DcStore::model()->findAllByAttributes(array(
						                'city' => $area->area_id,
					                ));
					                foreach($dcstores as $dcstore){
						                ?>
						                <tr style="height: 36px;border-bottom:1px solid #000000;">
							                <td width="146" align="left" valign="middle" style="border-right:1px solid #000000;"></td>
							                <td width="140" align="left" valign="middle" style="border-right:1px solid #000000;"><?php echo $area->name;?></td>
							                <td width="723" align="left" valign="middle" style="border-right:1px solid #000000;"><?php echo $dcstore->address; ?></td>
						                </tr>
					                <?php
					                }


				                } }
		                }?>
<!--                    <tr>-->
<!--                        <td class=tr1 align="left" valign="middle"><strong>华北</strong></td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td width="146" align="left" valign="middle"></td>-->
<!--                        <td width="140" align="left" valign="middle">北京</td>-->
<!--                        <td width="723" align="left" valign="middle">北京市丰台区马家堡西路26号院3号楼底商8号</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">北京市海淀区厂洼街5号院底商3号</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">沈阳</td>-->
<!--                        <td align="left" valign="middle">沈阳市铁西区兴顺街5号</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">邢台</td>-->
<!--                        <td align="left" valign="middle">邢台市桥西区中华大街与滨江路交叉口红星美凯龙负一层9188号</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">邯郸</td>-->
<!--                        <td align="left" valign="middle">邯郸市西环康业建材城</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">涉县</td>-->
<!--                        <td align="left" valign="middle">涉县宏信建材城4楼D12</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">忻州</td>-->
<!--                        <td align="left" valign="middle">忻州市云中路新欣三利建材市场西区</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle"><strong>华东</strong></td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">上海</td>-->
<!--                        <td align="left" valign="middle">上海市嘉定区沪宜公路城中路</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle"><strong>华中</strong></td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">长沙</td>-->
<!--                        <td align="left" valign="middle">长沙市开福区月湖大市场C区2栋</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">抚州</td>-->
<!--                        <td align="left" valign="middle">抚州市凤凰城建材城B1-8号</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">周口</td>-->
<!--                        <td align="left" valign="middle">周口市项城市欧蓓莎商城一期116号</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle"><strong>西南</strong></td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">贵阳</td>-->
<!--                        <td align="left" valign="middle">贵阳市南明区宝山南路462号</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>&nbsp;</td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>&nbsp;</td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                        <td align="left" valign="middle">&nbsp;</td>-->
<!--                    </tr>-->
                </table>
            </div>
        </div>
        </form></div></div>
