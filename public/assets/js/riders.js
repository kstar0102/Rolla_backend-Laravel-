$(function () {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-gray'
        },
        buttonsStyling: false
    });
    

    $('.rider-del-btn').click(function() {
        var id = $(this).attr('data-id');

        swalWithBootstrapButtons.fire({
            title: 'Are you sure you want to delete?',
            showCancelButton: true,
            confirmButtonClass: "btn-danger me-2",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then((res) => {
            if(res.isConfirmed) {
                // delete
            }
        });
    });

    $('.rider-edit-btn').click(function () {
        var id = $(this).data('data-id');

        $('#modal-rider-edit').modal();
    });
});
