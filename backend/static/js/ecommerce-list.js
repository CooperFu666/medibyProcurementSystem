var EcommerceList = function () {

    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            autoclose: true
        });
    }

    var location = $.localStorage('filter');
    if(location){
        $.each(location,function(n,value) {
            $("input[name='"+n+"']").val(value);
            $("select[name='"+n+"'] option[value='"+value+"']").attr('selected',true);
        });
    }


    var handleList = function(url,token,bSort,showTable,length) {
        if (arguments[2] == undefined || arguments[2] === '') bSort = true;
    	var showTableEm = $("#datatable_list");
    	if(!(showTable==undefined || showTable=='')){
    		showTableEm = $("#"+showTable);
    	}
        var grid = new Datatable();
        grid.setAjaxParam('csrf',token);
        // get all typeable inputs
        $('textarea.form-filter, select.form-filter, input.form-filter:not([type="radio"],[type="checkbox"])').each(function() {
            grid.setAjaxParam($(this).attr("name"), $(this).val());
        });

        //rewrite
        grid.resetFilter =  function(){
            $('textarea.form-filter, select.form-filter, input.form-filter').each(function() {
                $(this).val("");
            });
            $('input.form-filter[type="checkbox"]').each(function() {
                $(this).attr("checked", false);
            });
            grid.clearAjaxParams();
            grid.setAjaxParam('csrf',token);
            grid.addAjaxParam("action", 'filter_cancel');
            grid.submitFilter();
        }
        // get all checkboxes
        $('input.form-filter[type="checkbox"]:checked').each(function() {
            grid.addAjaxParam($(this).attr("name"), $(this).val());
        });

        // get all radio buttons
        $('input.form-filter[type="radio"]:checked').each(function() {
            grid.setAjaxParam($(this).attr("name"), $(this).val());
        });

        grid.init({
            src: showTableEm,
            onSuccess: function (grid) {
                // execute some code after table records loaded

            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                bRetrieve: true,//屏蔽错误提示
                "lengthMenu": [
                    [10, 20, 50, 100, 150],
                    [10, 20, 50, 100, 150] // change per page values here 
                ],
                "pageLength": length ? length : 10, // default record count per page
                "ajax": {
                    "url": url // ajax source
                },
                "bSort": bSort,
//                "order": [
//                    [1, "asc"]
//                ], // set first column as a default sort by asc
                "dom": '<"top">rt<"bottom"lip>',
                "language": {
                    "emptyTable":     "找不到相关数据",
                  //  "info":           "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录",
                    'info': '第 _PAGE_ 页 / 总 _PAGES_ 页',
                    "infoEmpty":      "",
                    "infoFiltered":   "(数据表中共为 _MAX_ 条记录)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                  //  "lengthMenu":     "每页显示 _MENU_ 条记录",
                    "lengthMenu":     "",
                    "loadingRecords": "载入中...",
                    "processing":     "正在加载....",
                    "search":         "搜索:",
                    "zeroRecords":    "对不起，查询不到任何相关数据",
                    "paginate": {
                        "first":      "第一页",
                        "last":       "最后一页",
                        "next":       "下一页",
                        "previous":   "上一页",
                        "page": "",
                        'pageOf':''
                    },
                    "aria": {
                        "sortAscending":  ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                }, //多语言配置
                "fnDrawCallback":function(){
                    if ($(".fancybox-button").size() > 0) {
                        $(".fancybox-button").fancybox({
                            groupAttr: 'data-rel',
                            prevEffect: 'none',
                            nextEffect: 'none',
                            closeBtn: true,
                            helpers: {
                                title: {
                                    type: 'inside'
                                }
                            }
                        });
                    }
                }
            }
        });

         // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());
            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action.val());
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
            } else if (action.val() == "") {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Please select an action',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'No record selected',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });
        return grid;
    }

    return {

        //main function to initiate the module
        init: function (url,token,bSort,showTable,length) {


            initPickers();
            return handleList(url,token,bSort,showTable,length);
            
        }

    };

}();
