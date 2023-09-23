<form action="{{ $action ?? '' }}" method="{{ $method ?? 'POST' }}">
    @method($method ?? 'POST')
    @csrf
    <div class="modal modal-blur fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ $slot ?? '' }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" onclick="{{ $onSubmit ?? 'onSimpleModalFormSubmit(this)' }}">提交</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// 简单模态框的表单提交事件
function onSimpleModalFormSubmit(target) {
    $(target).addClass('btn-loading');
    let data = new FormData(target.form);
    $.ajax({
        method: target.form.method,
        url: target.form.action,
        data: data,
        processData: false,
        contentType: false,
        success: function(res){
            if (res) {
                if (res.code == 200) {
                    window.location.reload();
                } else {
                    toastr.warning(res.message);
                }
            } else {
                console.log('success', res);
            }
        },
        error: function(req, status, ex) {
            if (req.status == 422) {
                toastr.warning(req.responseJSON.message);
            } else {
                toastr.error(req.status + ' - ' + req.statusText);
            }
        },
        complete: function(){
            $(target).removeClass('btn-loading');
        }
    });
}
</script>