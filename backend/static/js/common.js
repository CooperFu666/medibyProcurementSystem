/**
 * Created by Administrator on 15-7-29.
 */
function check_is_all(obj)
{
    var objTr = $(obj).parents('tr');
    if( objTr.find(".node_item:checked").length!=objTr.find(".node_item").length)
    {
        objTr.find(".access_left .checker span").removeClass("checked");
        objTr.find(".check_all").attr("checked",false);
    }
    else{
        objTr.find(".access_left .checker span").addClass("checked");
        objTr.find(".check_all").attr("checked",true);
    }

}
function check_module(obj)
{
    if($(obj).attr("checked"))
    {
        $(obj).parents('tr').find(".checker span").addClass("checked");
        $(obj).parents('tr').find(".node_item").attr("checked","checked");
    }
    else
    {
        $(obj).parents('tr').find(".checker span").removeClass("checked");
        $(obj).parents('tr').find(".node_item").attr("checked",false);
    }
}