/**
 * Created by Administrator on 2015/6/9.
 */

UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
UE.Editor.prototype.getActionUrl = function(action) {
    if (action == 'uploadimage' || action == 'uploadscrawl' || action == 'uploadimage' ||action == 'uploadvideo'||action == 'uploadfile') {
        return uploadUrl;
    }else {
        return this._bkGetActionUrl.call(this, action);
    }
}