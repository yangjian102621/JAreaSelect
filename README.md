JAreaSelect
========
>url:    /template/default/script/JAreaSelect.js;<br>
接口开发：yangjian102621@gmail.com<br>
文档编写：yangjian

插件描述:
--------
JAreaSelect区域选择插件是一个区域选择的js插件。提供1到4级的区域选择

插件依赖:
-------
* 纯js代码实现，无需引进第三方类库

示例代码:
-------
```html
    <!--html代码-->
    <span id=”area_select_box”></span>
```
```javascript
    //js code
   var select = new JAreaSelect({
        data : AREA_DATA,
        container : "area-select-box",
        maxAreaLevel : 3,
        showCountry : false,
        ids : [1,0,0,0]
    });

    select.initAreas();
    
    //auto init
    new JAreaSelect({
        data : AREA_DATA,
        container : "area-select-box",
        maxAreaLevel : 3,
        showCountry : false,
        ids : [1,0,0,0],
        autoInit : true
    });
    
```
option参数说明:
-------------

Key  | 类型   | 说明
---|--- | ---
data | Object | 地区数据数组
suffix| string  | select name属性前缀,默认为0，表示第1个区域选择器
ids  | Array | 区域的初始化id
container  | String | 区域选择器的容器,default area_select_box
maxAreaLevel  | int | 选择器的最大选择级别，默认为第4级
showCountry | boolean | 是否显示国家，默认为false不显示
autoInit | boolean | 是否自动初始化

效果截图:
------------
![Alt text](images/area_select_img_1.png) <br/>

####将 MaxmaxAreaLevel : 4 则会出现四级可选择

![Alt text](images/area_select_img_2.png) <br/>