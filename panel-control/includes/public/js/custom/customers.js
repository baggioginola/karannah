/**
 * Created by mario.cuevas on 5/12/2016.
 */
$(document).ready(function () {

    var url = 'reportes/getCustomers';
    var columns = [{data: 'nombre'},{data:'apellidos'},{data:'e_mail'},{data:'telefono'}];

    var table = masterDatatableCustomers(url, columns);

    $('#datatable tbody').on('click', '#btn_edit', function () {

        var id = table.row($(this).parents('tr')).data().id_cliente;

        var data = {id_cliente: id};
        var url = 'reportes/getCustomerById';

        $.post(url, data, function (response, status) {
            if (status == 'success') {
                $.each(response, function (key, val) {
                    $("input[name=" + key + "]").val(val);
                    $("textarea[name=" + key + "]").val(val);
                });
            }

        }, 'json');
        return false;
    });
});