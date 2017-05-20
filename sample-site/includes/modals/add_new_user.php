<!-- add new user. -->
<div class="modal fade" id="addNewUserModal" tabindex="-1" role="dialog" aria-labelledby="addNewUserModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add new user</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST">
                    <div class="form-group">
                        <label for="inputUser" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputNewUser" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputNewPassword" name="password" placeholder="Password">
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
                <button type="button" class="btn btn-primary" id="addNewUserModalSaveChanges" data-loading-text="Loading...">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    /* delete user */
    $('#addNewUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var username = button.data('username');
        var modal = $(this);
        modal.find('#inputNewUser').val('');
        modal.find('#inputNewPassword').val('');
        modal.find('#deleteUserModalUser').text(username);
    });
    $('#addNewUserModalSaveChanges').click(function () {
        $.ajax({
            type: "POST",
            url: 'manage-users.php',
            data: {
                'newuser': true,
                'username': $('#inputNewUser').val(),
                'password': $('#inputNewPassword').val(),
                'pwalgorithm': $('#inputPasswordAlgorithm').val()
            },
            success: function (data) {
                $('#messageswrapper').append(data);
                $('#userListTable').append("<tr id=\"username"+$('#inputNewUser').val()+"\"><td>"+$('#inputNewUser').val()+"</td><td>hidden</td><td><button type=\"button\" data-username=\""+$('#inputNewUser').val()+"\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#deleteUserModal\">Delete</button><button data-username=\""+$('#inputNewUser').val()+"\" type=\"button\" class=\"btn btn-default\" data-toggle=\"modal\" data-target=\"#changePasswordModal\">Change Password</button></td></tr>");
                $('#addNewUserModal').modal('hide');
                $('#addNewUserModalSaveChanges').button('reset');
            },
            dataType: 'html'
        });
    });
</script>