<!-- delete user. -->
<div class="modal fade alert alert-danger" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete user: <span id="deleteUserModalUser"></span></h4>
            </div>
            <div class="modal-body"><strong>Are you sure you want to delete this user?</strong></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="deleteUserModalSaveChanges">Delete</button>
            </div>
        </div>
    </div>
</div>
<script>
    /* delete user */
    $('#deleteUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var username = button.data('username');
        var modal = $(this);
        modal.find('#deleteUserModalUser').text(username);
    });
    $('#deleteUserModalSaveChanges').click(function () {
        $.ajax({
            type: "POST",
            url: 'manage-users.php',
            data: {
                'delete': $('#deleteUserModalUser').text()
            },
            success: function (data) {
                $('#messageswrapper').append(data);
                $('#username' + $('#deleteUserModalUser').text()).remove();
                $('#deleteUserModal').modal('hide');
            },
            dataType: 'html'
        });
    });
</script>