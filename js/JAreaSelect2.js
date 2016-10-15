/**
 * 地区选择器高级版本
 * @version 1.0.0
 * @author <yangjian102621@gmail.com>
 */
(function($) {

	$.fn.JAreaSelect2 = function(options) {

		var obj = {};
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



	}

})(jQuery);
