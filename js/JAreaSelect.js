/**
 * 地区选择器
 * @version 1.1.0
 * @author <yangjian102621@gmail.com>
 */
(function($) {

	$.fn.JAreaSelect = function(options) {

		var obj = {};
		var $container = $(this);
		var areaData = __AREADATA__;  //地区数据
		//初始化参数
		var defaults = {
			prov : 0, //省
			city : 0, //市
			dist : 0, //区
			name : {
				prov : 'province',
				city : 'city',
				dist : 'dist'
			},

			selectClassName : 'form-control', //select class名称

		};

		/* 合并参数 */
		options = $.extend(defaults, options);

		//创建元素
		obj.create = function() {

			obj.province = $('<select class="'+options.selectClassName+'" name="'+options.name.prov+'"></select>');
			//加载所有省级
			$.each(areaData.prov, function(id, name) {
				if ( id == options.prov ) {
					obj.province.append('<option value="'+id+'" selected>'+name+'</option>');
				} else {
					obj.province.append('<option value="'+id+'">'+name+'</option>');
				}
			});

			//绑定选中省级事件
			obj.province.on('change', function() {

				//删除元素
				try {
					obj.city.remove();
					obj.dist.remove();
				} catch (e) {}

				var pid = $(this).val(); //获取省份id

				if ( areaData.city[pid] && areaData.city[pid].length > 0 ) {

					obj.city = $('<select class="'+options.selectClassName+'" name="'+options.name.city+'"></select>');
					$.each(areaData.city[pid], function(i, item) {
						if ( item.id == options.city ) {
							obj.city.append('<option value="'+item.id+'" selected>'+item.name+'</option>');
						} else {
							obj.city.append('<option value="'+item.id+'">'+item.name+'</option>');
						}
					});

					//切换城市的时候加载地区
					obj.city.on("change", function() {

						try {obj.dist.remove();} catch (e) {}
						//console.log(obj.getAreaString());

						var cid = $(this).val();
						if ( areaData.dist[cid] && areaData.dist[cid].length > 0 ) {
							obj.dist = $('<select class="'+options.selectClassName+'" name="'+options.name.dist+'"></select>');
							$.each(areaData.dist[cid], function(i, item) {
								if ( item.id == options.dist ) {
									obj.dist.append('<option value="'+item.id+'" selected>'+item.name+'</option>');
								} else {
									obj.dist.append('<option value="'+item.id+'">'+item.name+'</option>');
								}
							});
							$container.append(obj.dist);
						}
					});
					$container.append(obj.city);
					obj.city.trigger("change"); //自动触发事件
				}

			});
			$container.append(obj.province);
			obj.province.trigger("change"); //自动触发事件
		}

		//获取区域id
		obj.getAreaId = function() {
			return {
				prov : obj.province.val(),
				city : obj.city ? obj.city.val() : 0,
				dist : obj.dist ? obj.dist.val() : 0
			};
		}

		//获取区域字符串
		obj.getAreaString = function() {
			var html = obj.province.find("option:selected").html();
			try {
				html += obj.city.find("option:selected").html();
				html += obj.dist.find("option:selected").html();
			} catch (e) {}
			return html;
		}

		obj.create();
		return obj;

	}
})(jQuery);
