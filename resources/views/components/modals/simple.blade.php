<form action="{{ $action ?? '' }}" method="{{ $method ?? 'POST' }}" enctype="{{ $enctype ?? 'application/x-www-form-urlencoded' }}">
    @method($method ?? 'POST')
    @csrf
    <div class="modal modal-blur fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog {{ isset($large) && $large == 'true' ? 'modal-lg' : '' }} modal-dialog-centered modal-dialog-scrollable" role="document">
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
    ajax({
        method: $(target.form).attr('method'),
        url: target.form.action,
        data: new FormData(target.form),
    }, $(target));
}
</script>