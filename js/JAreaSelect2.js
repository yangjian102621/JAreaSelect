/**
 * 地区选择器高级版本
 * @version 1.0.0
 * @author <yangjian102621@gmail.com>
 */
(function($) {

	$.fn.JAreaSelect2 = function(options) {
		//dom UI 变量定义
		var $dom = {};
		var $container = $(this);

		var areaData = __AREADATA__;  //地区数据
		//初始化参数
		var defaults = {
			prov : 0, //省
			city : 0, //市
			dist : 0, //区

		};

		/* 合并参数 */
		options = $.extend(defaults, options);

		//创建元素
		function createElement() {
			$dom.areaBox = $('<div class="jarea-select-box"></div>');
			$dom.tabul = $('<ul class="tab-ul"></ul>');
			//省份
			var id = 0, name = "请选择";
			if ( options.prov > 0 ) {
				id = options.prov;
				name = areaData.prov[options.prov];
			}
			$dom.prov = $('<li class="tab-item active"><a href="#prov" data-id="'+id+'">'+name+'</a></li>');
			$dom.tabul.append($dom.prov)

			//城市
			if ( options.city > 0 ) {
				id = options.city;
				name = getCityName(options.prov, options.city);
				$dom.city = $('<li class="tab-item"><a href="#city" data-id="'+id+'">'+name+'</a></li>');
				$dom.tabul.append($dom.city);

				$dom.prov.removeClass('active');
				$dom.city.addClass('active');
			}

			//地区
			if ( options.dist > 0 ) {
				id = options.dist;
				name = getDistName(options.city, options.dist);
				$dom.dist = $('<li class="tab-item"><a href="#dist" data-id="'+id+'">'+name+'</a></li>');
				$dom.tabul.append($dom.dist);

				$dom.city.removeClass('active');
				$dom.dist.addClass('active');
			}
			$dom.areaBox.append($dom.tabul);

			//创建tab panel
			var $tabContent = $('<div class="tab-content"></div>');
			$dom.provTab = $('<div class="tab-panel active"></div>');
			$dom.cityTab = $('<div class="tab-panel"></div>');
			$dom.distTab = $('<div class="tab-panel"></div>');
			$tabContent.append($dom.provTab);
			$tabContent.append($dom.cityTab);
			$tabContent.append($dom.distTab);

			//追加省份
			var $ul = $('<ul></ul>');
			$.each(areaData.prov, function(id, name) {
				$ul.append('<li><a href="javascript:void(0);" data-id="'+id+'">'+name+'</a></li>');
			});
			$dom.provTab.append($ul);

			$dom.areaBox.append($tabContent);
			$container.append($dom.areaBox);

		}

		//根据城市ID查找城市名称
		function getCityName(provId, cityId) {
			var str = '';
			$.each(areaData.city[provId], function(i, item) {
				if ( item.id == cityId ) {
					str = item.name;
					return;
				}
			});
			return str;
		}

		//根据地区ID查找地区名称
		function getDistName(cityId, distId) {
			var str = '';
			$.each(areaData.dist[cityId], function(i, item) {
				if ( item.id == distId ) {
					str = item.name;
					return;
				}
			});
			return str;
		}

		createElement(); //创建元素
	}

})(jQuery);
