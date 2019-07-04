/**
 * Created by mario.cuevas on 5/12/2016.
 */
$(document).ready(function () {
    tinymce.init({
        selector: "textarea#id_contenido",
        menubar: "edit",

        theme: "modern",
        toolbar: " undo redo |  bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
        plugins: [
            "advlist autolink link image lists charmap hr anchor pagebreak spellchecker",
            "searchreplace visualblocks visualchars  fullscreen insertdatetime  nonbreaking",
            "save table contextmenu directionality template paste "
        ]

    });

    $("#id_video").fileinput({
        uploadUrl: "videosUpload/add",
        allowedFileExtensions: ["mp4"],
        maxFileCount: 1,
        minFileCount: 1,
        uploadAsync: false,
        language: "es",
        showUpload: false,
        fileActionSettings: {showUpload: false, showZoom: false},
        previewSettings: {image: {width: "auto", height: "100px"}},
        purifyHtml: true,
        autoReplace: true,
        validateInitialCount: true,
        uploadExtraData: function (previewId, index) {
            var info = {
                "type": "videos",
                "name": $('#submit_id').val(),
                'num_videos': $('.file-initial-thumbs > div').length + $('.file-live-thumbs > div').length
            };
            return info;
        }
    }).on('filebatchuploadsuccess', function (event, data) {
        bootbox.alert('Los videos se han subido correctamente');
    }).on('fileloaded', function (event, file, previewId, index, reader) {
        $('#upload_videos').val('1');
    });

    $('#reset_button').click(function () {
        $('#form_global').trigger("reset");
        $('#submit_type').val('videos/add');
        $('#submit_id').val('');

        return false;
    });

    var url = 'videos/getAll';
    var columns = [{data: 'titulo'}];
    var table = masterDatatable(url, columns);

    var url_last_id = 'videos/getLastId';

    $.ajax({
        url: url_last_id,
        type: "POST",
        cache: false,
        data: {},
        dataType: 'json',
        success: function (data) {
            $('#submit_id').val(parseInt(data.id) + 1);
        }
    });

    $('#datatable tbody').on('click', '#btn_edit', function () {

        $("#form_alert").slideUp();
        var id = table.row($(this).parents('tr')).data().id;

        var data = {id: id};
        var url = 'videos/getById';

        $('#submit_type').val('videos/edit');

        $.post(url, data, function (response, status) {
            if (status === 'success') {
                $.each(response, function (key, val) {
                    if (key === 'contenido') {
                        tinyMCE.get('id_contenido').setContent(val);
                    }
                    $("input[name=" + key + "]").val(val);
                    $("textarea[name=" + key + "]").val(val);
                    $("select[name=" + key + "]").val(val);
                });

                var images = [];
                var initialPreviewConfigObj = [];
                var j = 0;
                var i = 1;

                var dataImage = getVideo(VIDEOS, response.id, i);
                if (dataImage.status === 200) {
                    images[j] = '<img src="' + dataImage.url + '" class="file-preview-image" alt="Desert" title="Desert" style="width:auto; height:100px;">';
                    images[j] = '<video class="kv-preview-data"  controls="" style="width:213px; height:160px;"><source src="' + dataImage.url + '" type="video/mp4" > </video>';

                    var initialPreviewConfigItem = {};
                    initialPreviewConfigItem['caption'] = dataImage.name;
                    initialPreviewConfigItem['key'] = j;
                    initialPreviewConfigObj.push(initialPreviewConfigItem);
                    j++;
                }

                $('#id_video').fileinput('refresh', {
                    uploadUrl: "videosUpload/add",
                    allowedFileExtensions: ["mp4"],
                    initialPreview: images,
                    initialPreviewFileType: 'image',
                    initialPreviewShowDelete: false,
                    initialPreviewConfig: initialPreviewConfigObj,
                    validateInitialCount: true,
                    fileActionSettings: {showDrag: false},
                    append: true,
                    showUploadedThumbs: false,
                    uploadExtraData: function (previewId, index) {
                        var info = {
                            "type": "videos",
                            "name": $('#submit_id').val(),
                            'num_videos': $('.file-initial-thumbs > div').length + $('.file-live-thumbs > div').length
                        };
                        return info;
                    }
                });

                $('#upload_videos').val('0');
            }
            $('#submit_id').val(response.id);
        }, 'json');
        return false;
    });

    $('#datatable tbody').on('click', '#btn_delete', function () {
        var id = table.row($(this).parents('tr')).data().id;
        bootbox.confirm("Eliminar elemento?", function (result) {
            if (result === true) {
                var data = {id: id, active: 0};
                var url = 'videos/delete';
                $.post(url, data, function (response, status) {
                    if (status === 'success') {
                        bootbox.alert(response.message);
                        table.ajax.reload();
                    }
                }, 'json');
            }
        });
        return false;
    });

    var form = $('#form_global').submit(function () {

        if ($('#id_submit').hasClass('disabled')) {
            return false;
        }

        var type = $('#submit_type').val();

        if ($('#id_video').fileinput('upload') == null && $('#upload_videos').val() == 1) {
            return false;
        }

        var live_count = $('.file-initial-thumbs > div').length;
        var initial_count = $('.file-live-thumbs > div').length;

        var fileStack = live_count + initial_count;

        var data = $(this).serialize();

        if (type === 'videos/edit') {
            var id = $('#submit_id').val();
            data = data + '&' + $.param({'id': id});
        }
        data = data + '&' + $.param({'contenido': tinyMCE.get('id_contenido').getContent()});
        $.ajax({
            url: type,
            type: "POST",
            cache: false,
            data: data,
            dataType: 'json',
            async: false,
            success: function (data) {
                table.ajax.reload();
                submit_responseVideo(form, data, 'videos/add', 'videos');
            }
        });
        return false;
    });
});