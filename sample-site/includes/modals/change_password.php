<!-- change password. -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change password for user: <span id="changePasswordModalUser"></span></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST">
                    <div class="form-group">
                        <label for="inputUser" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" readonly="readonly" id="inputUser" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPasswordAlgorithm" class="col-sm-2 control-label">Password Algorithm</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="inputPasswordAlgorithm" name="pwalgorithm">
                                <option value="apr1-md5">APR1-MD5</option>
                                <option value="bcrypt">BCRYPT</option>
                                <option value="sha">SHA</option>
                                <option value="plain">Plain</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="changePasswordModalSaveChanges">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    /* change password */
    $('#changePasswordModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var username = button.data('username');
        var modal = $(this);
        modal.find('#inputUser').val(username);
        modal.find('#inputPassword').val('');
        modal.find('#changePasswordModalUser').text(username);
    });
    $('#changePasswordModalSaveChanges').click(function () {
        $.ajax({
            type: "POST",
            url: 'manage-users.php',
            data: {
                username: $('#inputUser').val(),
                password: $('#inputPassword').val(),
                pwalgorithm: $('#inputPasswordAlgorithm').val()
            },
            success: function (data) {
                $('#messageswrapper').append(data);
                $('#changePasswordModal').modal('hide');
            },
            dataType: 'html'
        });
    });
</script>