/**

 * 此专门为聚客商品搜索提供的区域选择插件

 * @author yangjian

 * @version 1.0

 * @depend jQuery

 */

var JAreaList = function(options) {



    this.options = {

        data : {},    /* 区域数据 */

        area : [],  /* 片区数据 */

        url : null,     /* 链接地址的基础初始url */

        container : null,   /* 容器 */

        direct : [],     /* 直辖市 */

		subfix : '.shtml' 	/* url后缀 */

    }



    $.extend(this.options, options);



    JAreaList.prototype.init = function() {



        this.createAreaBox();

    }



    /**

     * 创建元素

     */

    JAreaList.prototype.createAreaBox = function() {



        var areaBox = $('<div class="sh-area-box"></div>');

        var areaBody = $('<div class="sh-area-body"></div>');

        var areaAll = $('<div class="sh-area-all"></div>');

        areaAll.append('<a href="'+this.options.url+'" class="sh-area-link">所有地区</a>');

        areaBody.append(areaAll);

        areaBody.append('<div class="sh-area-direct">产品片区</div>');

        var direct = $('<ul class="sh-direct-box"></ul>');



        //创建片区

        for ( var i= 0; i < this.options.area.length; i++) {

            direct.append(this.createDirect(this.options.area[i]));

        }



        //创建省份

        var province = $('<ul class="sh-province-box"></ul>');

        var provinceData = this.options.data.province[1];

        for ( var i = 0; i < provinceData.length; i++ ) {

            if ( this.isDirect(provinceData[i]) ) continue;

            province.append(this.createProvince(provinceData[i]));

        }



        areaBody.append(direct);

        areaBody.append(province);

        areaBox.append(areaBody);

        $(this.options.container).append(areaBox);

    }



    /**

     * 创建片区和直辖市

     * @param string name 片区名称

     */

    JAreaList.prototype.createDirect = function( name ) {



        var item = $('<li class="sh-list-item"></li>');

        var url = this.options.url+"-v-"+name+this.options.subfix;

        var link = $('<a href="'+url+'" class="sh-area-link">'+name+'</a>');

        item.append(link);

        return item;



    }



    /**

     * 创建省份

     * @param object province 省份信息

     */

    JAreaList.prototype.createProvince = function ( province ) {



        var item = $('<li class="sh-list-item"></li>');

        var url = this.options.url+"-p-"+province[0]+this.options.subfix;;

        var link = $('<a href="'+url+'" class="sh-area-link">'+province[1]+'</a>');

        item.append(link);



        //创建子级地区

        var citys = this.options.data.city[province[0]];

        var cityBox = $('<ul class="sh-area-city" id="province-'+province[0]+'"></ul>');

        for ( var i = 0; i < citys.length; i++ ) {

            var __item = $('<li class="sh-list-item"></li>');

            var __url = this.options.url+"-c-"+citys[i][0]+this.options.subfix;;

            var __link = $('<a href="'+__url+'" class="sh-area-link">'+citys[i][1].substr(0, 4)+'</a>');

            __item.append(__link);

            cityBox.append(__item);

        }

        item.append(cityBox);



        var self = this;

        item.mouseover(function() {



            self.options.showHandler = setTimeout(function() {

                item.find('ul').show();

            }, 200)



        }).mouseout(function() {



            if ( self.options.showHandler ) {

                clearTimeout(self.options.showHandler);

            }



            self.options.hideHandler = setTimeout(function() {

                item.find('ul').hide();

            }, 200)



        });

        cityBox.mouseover(function(event) {



            event.stopPropagation();    //阻止事件冒泡

            if ( self.options.hideHandler ) {

                clearTimeout(self.options.hideHandler);

            }



        }).mouseout(function(event) {



            event.stopPropagation();    //阻止事件冒泡

            self.options.hideHandler = setTimeout(function() {

                item.find('ul').hide();

            }, 200)

        });

        return item;

    }



    /**

	 * 判断一个省份是否是直辖市

	 * @param object province 省份信息

	 */

    JAreaList.prototype.isDirect = function( province ) {

        for ( var i= 0; i < this.options.direct.length; i++ ) {

            if ( this.options.direct[i] == province[1] ) {

                return true;

            }

        }

        return false;

    }



    this.init();

}