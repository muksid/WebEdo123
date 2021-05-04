<div id="ConfirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
            </div>

            <div class="modal-body">
                <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span>
                    Siz, tanlagan xat o`chiriladi!
                </h4>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('blade.cancel')</button>
                <button type="button" class="btn btn-danger" id="yesDelete">Ha, O`chirish</button>
            </div>
        </div>
    </div>
</div>

<script>

    $('#yesDelete').click(function () {

        let id = $('#ConfirmModal').data('id');

        $.ajax({
            url: '/fe/deleteInboxMessage',
            type: 'GET',
            data: {id: id},
            dataType: 'json',
            beforeSend: function(){
                $("#loading").show();
            },
            success: function(res){
                $('#blade_append').html(res.blade);
                $('#successModal').modal('show');
                $('#res_message').html(res.message);
                setTimeout(function () {
                    window.location.href = "/fe/inbox";
                }, 2000);

            },
            complete:function(res){
                $("#loading").hide();
            }

        });

        $('#ConfirmModal').modal('hide');
    });

</script>
