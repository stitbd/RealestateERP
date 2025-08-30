const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});


$(function(){
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#dataTable').DataTable();

    $('#dataTableOrder').DataTable({
        "paging"        : true,
        "lengthChange"  : false,
        "searching"     : false,
        "ordering"      : true,
        "info"          : true,
        "autoWidth"     : false,
    });

    $('.alert').delay(5000).slideUp('slow', function(){
        $(this).alert('close');
    });

    if ($(".select2").length > 0) $('.select2').select2();

    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });

    if ($("input[data-bootstrap-switch]").length > 0) {
        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
    }

});

function returnNumber(value){
    value = parseFloat(value);
    return !isNaN(value) ?  value : 0;
}
