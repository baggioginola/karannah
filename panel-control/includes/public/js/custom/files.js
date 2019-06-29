/**
 * Created by mario.cuevas on 7/6/2016.
 */
$(document).ready(function () {
    $("#id_files").fileinput({
        uploadUrl: "files/readXLS",
        allowedFileExtensions: ["xls"],
        maxFileCount: 1,
        minFileCount: 1,
        uploadAsync: false,
        language: "es",
        showUpload: true,
        fileActionSettings: {showUpload: false, showZoom: false},
        purifyHtml: true,
        autoReplace: true
    }).on('filebatchuploadsuccess', function (event, data) {
        bootbox.alert('El archivo se ha subido de forma exitosa.');
    });
});
