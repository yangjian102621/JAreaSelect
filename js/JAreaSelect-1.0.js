/**

 * 地区选择器

 * @import AreaData.js 需要导入数据包，地域可以在数据库中自由添加，

 * 更新生成地域数据包就ok了，可扩展性很强。要扩展的可以下载数据库

 * @verion	1.2

 * @author	yangjian<yangjian102621@gmail.com>

 */

var Area = function( __data, __boxs, __ids ) {

	if ( typeof __ids != 'object' ) __ids = [0,0,0,0];

	var __key = Array('country', 'province', 'city', 'district' );

	var _tipArray = {

		'country'  : '-请选择国家-',

		'province' : '-请选择省份-',

		'city'	   : '-请选择城市-',

		'district' : '-请选择地区-'	

	};

	/* select name */

	var _selectName = {

		'country'  : 'country_id',

		'province' : 'province_id',

		'city'	   : 'city_id',

		'district' : 'district_id'	

	};

	/* select id */

	var _selectId = {

		'country'  : 'country_select_id',

		'province' : 'province_select_id',

		'city'	   : 'city_select_id',

		'district' : 'district_select_id'	

	};

	/* 各个select对应的父级地区select的id */

	var parentAreaId = {

		'country'  : '',

		'province' : 'country_select_id',

		'city'	   : 'province_select_id',

		'district' : 'city_select_id'	

	}

	

	/* 父级地区联动的子级地区的id */

	var childAreaId = {

		'country'  : 'province_select_id',

		'province' : 'city_select_id',

		'city'	   : 'district_select_id',

		'district' : ''	

	}

	

	/* 创建元素并添加属性 */

	Area.prototype.createEle = function( tag, attr ) {

		var ele = document.createElement(tag);

		for ( name in attr ) {

			if ( attr.hasOwnProperty(name) ) {

				ele[name] = attr[name];	

			} 

		}

		return ele;

	}

	

	/**

	 * 初始化select

	 * @param	name 分别指代select的名称，country, province, city, district

	 * @param	_box 相对应select外的span

	 * @param	_id 对应元素数据库中的Id

	 */

	Area.prototype.initSelect = function( name, _box, _id ) {

		_id = _id || 0;

		var that = this;

		var _select = this.createEle('select', {

			name : _selectName[name],

			id : _selectId[name],

			onchange : function() {

				that.changeOption(name, this.value);

			}	

		});

		if ( _id == 0 ) {

			_select.appendChild(this.createEle('option', {

				value : 0,

				innerHTML : _tipArray[name]	

			}));	

		} 

		//获取对应的父级Id的子类元素

		var listData;

		if ( name == 'country' ) {

			listData = __data[name];

		} else {

			var _key = document.getElementById(parentAreaId[name]).value;

			listData = __data[name][_key]

		}

		if ( listData ) {

			for ( var i = 0; i < listData.length; i++ ) {

				_select.appendChild(this.createEle('option', {

					value : listData[i][0],

					innerHTML : listData[i][1],

					selected : listData[i][0]==_id?'selected':''

				}));

			}	

		}

		_box = typeof _box === 'string' ? document.getElementById(_box) : _box;

		_box.appendChild(_select);

	},

	

	/**

	 * 联动更改

	 * @param				name		当前联动的select的name属性名称

	 * @param				parent_id	当前地域的父级的地域的ID（注意：是数据库中的Id而不是DOM的id）

	 */

	Area.prototype.changeOption = function( name, parent_id ) {

		 if ( parent_id == '' || name == '' ) return;

		 var child = document.getElementById(childAreaId[name]);

		 if ( child == null ) return;

		 child.innerHTML = '';

		 var _name = __key[this.indexOf(__key,name)+1]

		 child.appendChild(this.createEle('option', {

			  value : 0,

			  innerHTML : _tipArray[_name]

			  		 

		 }));

		 var listData = __data[_name][parent_id];

		 if ( listData ) {

			for ( var i = 0; i < listData.length; i++ ) {

				child.appendChild(this.createEle('option', {

					value : listData[i][0],

					innerHTML : listData[i][1]	

				}));	

			}	 

		 }

		 

	}

	

	/* 初始化所有select */

	Area.prototype.start = function() {

		for ( var i = 0; i < __key.length; i++ ) {

			this.initSelect(__key[i], __boxs[i], __ids[i]);

		}

	}

	

	/**

	 * 查找元素在数组中的下标

	 * @param	 	arr		目标数组

	 * @param		val		需要查找的数组元素

	 * @return		index	返回数组中需要查找元素的下标

	 */

	Area.prototype.indexOf = function( arr, val ) {

		if ( typeof arr != 'object' ) return;

		for ( var i = 0; i < arr.length; i++ ) {

			if ( arr[i] == val ) return i;

		}

		return -1;

	}

	

};