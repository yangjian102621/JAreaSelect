/**
 * 地区选择器
 * @import AreaData.js 需要导入数据包，地域可以在数据库中自由添加，
 * 更新生成地域数据包就ok了，可扩展性很强。要扩展的可以下载数据库，自己开发添加程序。开发程序在php文件中
 * @verion	1.3
 * @author	yangjian<yangjian102621@gmail.com>	qq:906388445
 * @since  2013.06.15
 * 版本更新 ： 1. 重新封装了JArea对象，修复了在一个页面不能创建多个区域选择对象。
			   2. 更新了js对象数组的生成的php程序和area数据库的表结构，精简了字段。
			   3. 提供了数据录入的PHP程序接口。
 */

var JAreaSelect = function( options ) {

	var obj = {};
	//初始化参数
	var defaults = {
		data : __AREADATA__,      /* 地区数据数组 */
		prov : 0, //省
		city : 0, //市
		dist : 0, //区
		container : 'area_select_box',
		//提示内容
		tipArray : {
			'province' : '-请选择省份-',
			'city'	   : '-请选择城市-',
			'district' : '-请选择地区-'
		},
		//每个select标签的父级容器ID
		boxId : {
			'province' : 'province_box_'+__suffix,
			'city'	   : 'city_box_'+__suffix,
			'district' : 'district_box_'+__suffix
		},

		//select name
		selectName : {
			'province' : 'province_id_'+__suffix,
			'city'	   : 'city_id_'+__suffix,
			'district' : 'district_id_'+__suffix
		},
		//select id
		selectId : {
			'province' : 'province_select_id_'+__suffix,
			'city'	   : 'city_select_id_'+__suffix,
			'district' : 'district_select_id_'+__suffix
		},
		/* 各个select对应的父级地区select的id */
		parentAreaId : {
			'province' : 'country_select_id_'+__suffix,
			'city'	   : 'province_select_id_'+__suffix,
			'district' : 'city_select_id_'+__suffix
		},
		/* 父级地区联动的子级地区的id */
		childAreaId : {
			'province' : 'city_select_id_'+__suffix,
			'city'	   : 'district_select_id_'+__suffix,
			'district' : ''
		},
        /*地址字符串*/
        address : '' ,
		selectClassName : 'form-control', //select class名称
        /* 最多加载的区域级别, 最多为3级 */
        loadLevel : 3

	};

    /* 合并参数 */
    for ( var key in options ) {
	    defaults[key] = options[key];
    }
	obj.options = defaults;

    if ( obj.options.loadLevel > 3 ) {
	    obj.options.loadLevel = 3;
    }

	//创建元素
	obj.create = function( tag, attr ) {
		var ele = document.createElement(tag);
		for ( var name in attr ) {
			if ( attr.hasOwnProperty(name) ) {
				ele[name] = attr[name];
			}
            if( name == 'selected' ){
                if( attr.selected=='selected' ){
                    obj.options.address += ele.innerHTML;
                }
            }
		}
		return ele;
	};

	/**
     * 初始化select
     * @param    name    分别指代select的名称，country, province, city, district
     * @param    _box_id 相对应select外的span的id
     * @param    _id    对应元素数据库中的Id
     * @param   show    是否显示该元素
     */
    obj.initSelect = function (name, _box_id, _id, show) {
        _id = _id || 0;
        var that = this;
        //创建select
        var _select = this.createEle('select', {
            name: this.options.selectName[name],
            id: this.options.selectId[name],
            className: 'form-control input-sm yj_width_130 dinline',
            style: 'margin:0px 5px;',
            onchange: function () {
                that.changeOption(name, this.value);
            }
        });
        if (_id == 0) {
            _select.appendChild(this.createEle('option', {
                value: 0,
                innerHTML: this.options.tipArray[name]
            }));
        }
        //获取对应的父级Id的子类元素
        var listData;
        if ( name == 'country' ) {
            listData = this.options.data[name];
        } else {
            var _key = document.getElementById(this.options.parentAreaId[name]).value;
            listData = this.options.data[name][_key]
        }
        if (listData) {
            for (var i = 0; i < listData.length; i++) {
                _select.appendChild(this.createEle('option', {
                    value: listData[i][0],
                    innerHTML: listData[i][1],
                    selected: listData[i][0] == _id ? 'selected' : ''
                }));
            }
        }
        //创建span容器
        var _box = this.createEle("span", {id: _box_id, className: 'select_span'});
        //如果不显示国家则隐藏box
        if ( !show ) {
            _box.style.display = 'none';
        }
        _box.appendChild(_select);
        var container = typeof this.options.container == "object" ? this.options.container : document.getElementById(this.options.container);
        container.appendChild(_box);
    };

	/**
	 * 联动更改
	 * @param				name		当前联动的select的name属性名称
	 * @param				parent_id	当前地域的父级的地域的ID（注意：是数据库中的Id而不是DOM的id）
	 */
	JAreaSelect.prototype.changeOption = function( name, parent_id ) {

        var index = this.indexOf(this.options.keys,name) + 1;
        if ( index >= this.options.maxAreaLevel ) return;    /* 加载到最大级别就不加载了 */
        if ( parent_id == '' || name == '' ) return;
        var child = document.getElementById(this.options.childAreaId[name]);
        if ( child == null ) return;
        child.innerHTML = '';
        var _name = this.options.keys[index]
        child.appendChild(this.createEle('option', {
             value : 0,
             innerHTML : this.options.tipArray[_name]

        }));
        var listData = this.options.data[_name][parent_id];
        if ( listData ) {
           for ( var i = 0; i < listData.length; i++ ) {
               child.appendChild(this.createEle('option', {
                   value : listData[i][0],
                   innerHTML : listData[i][1]
               }));
           }
        }

	};

	/* 初始化所有select */
	JAreaSelect.prototype.initAreas = function() {

		for ( var i = 0; i < this.options.maxAreaLevel; i++ ) {
            var show = true;
            if ( !this.options.showCountry && i == 0 ) show = false;    //不显示国家
            this.initSelect(this.options.keys[i], this.options.boxs_id[this.options.keys[i]], this.options.ids[i], show);
		}
	};

	/**
	 * 查找元素在数组中的下标
	 * @param	 	arr		目标数组
	 * @param		val		需要查找的数组元素
	 * @return		index	返回数组中需要查找元素的下标
	 */
	JAreaSelect.prototype.indexOf = function( arr, val ) {
		if ( typeof arr != 'object' ) return false;
		for ( var i = 0; i < arr.length; i++ ) {
			if ( arr[i] == val ) return i;
		}
		return -1;
	};

    /* 获取地址*/
    JAreaSelect.prototype.getAddress = function(){
        return this.options.address;
    }
    if ( this.options.autoInit ) this.initAreas();

	return obj;
};
