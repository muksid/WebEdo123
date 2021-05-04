<div id="ConfirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
            </div>

            <div class="modal-body">
                <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span>
                    Siz, tanlagan xatlar o`chiriladi!
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

    $('#yesDelete').on('click', function(e) {

        var idsArr = [];

        $(".checkbox:checked").each(function() {

            idsArr.push($(this).attr('data-id'));

        });

        var strIds = idsArr.join(",");

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({

            url: 'deleteAllMessage',
            type: 'POST',
            data: {_token: CSRF_TOKEN, id: strIds},
            dataType: 'JSON',
            beforeSend: function(){
                $("#loading").show();
            },
            success: function(res){
                $('#blade_append').html(res.blade);
                $('#successModal').modal('show');
                $('#res_message').html(res.message);
                setTimeout(function () {
                    location.reload();
                }, 2000);

            },
            complete:function(res){
                $("#loading").hide();
            },

            error: function (data) {
                alert(data);
            }
        });

    });

</script>
