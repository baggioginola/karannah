/**
 * Created by mario.cuevas on 7/6/2016.
 */
$(document).ready(function () {
    $("#id_banner").fileinput({
        uploadUrl: "imagenes/add",
        allowedFileExtensions: ["jpg", "png", "jpeg"],
        maxFileCount: 5,
        minFileCount: 1,
        uploadAsync: false,
        language: "es",
        showUpload: true,
        fileActionSettings: {showUpload: false, showZoom: false},
        previewSettings: {image: {width: "auto", height: "100px"}},
        purifyHtml: true,
        autoReplace: true,
        uploadExtraData: function (previewId, index) {
            var info = {
                "id": 1,
                "type": "banner_big",
                "name": "banner",
                'num_imagenes': $('.file-initial-thumbs > div').length + $('.file-live-thumbs > div').length
            };
            return info;
        }
    }).on('filebatchuploadsuccess', function (event, data) {
        bootbox.alert('Las imágenes se han subido correctamente');
    }).on('fileloaded', function (event, file, previewId, index, reader) {
        $('#upload_images').val('1');
    });
});
