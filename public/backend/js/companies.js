var Companies_grid;
var Companies = function () {
    var init = function () {
        $.extend(lang, new_lang);
        handleRecords();
        handleSubmit();
        handlePasswordActions();
        My.readImageMulti('image');

    };
    var add_rules = function () {
        $('input[name="password"]').rules('add', {
            required: true
        });
    }
    var edit_rules = function () {
        $('input[name="password"]').rules('remove', 'required');
    }
    var handlePasswordActions = function (string_length) {
        $('#show-password').click(function () {
            if ($('#password').val() != '') {
                $("#password").attr("type", "text");

            } else {
                $("#password").attr("type", "password");

            }
        });
        $('#random-password').click(function () {
            $('[id^="password"]').closest('.form-group').removeClass('has-error').addClass('has-success');
            $('[id^="password"]').closest('.form-group').find('.help-block').html('').css('opacity', 0);
            $('[id^="password"]').val(randomPassword(8));
        });
    }
    var randomPassword = function (string_length) {
        var chars = "0123456789!@#$%^&*abcdefghijklmnopqrstuvwxtzABCDEFGHIJKLMNOPQRSTUVWXTZ!@#$%^&*";
        var myrnd = [], pos;
        while (string_length--) {
            pos = Math.floor(Math.random() * chars.length);
            myrnd += chars.substr(pos, 1);
        }
        return myrnd;
    }
    var handleRecords = function () {

        Companies_grid = $('.dataTable').dataTable({
            //"processing": true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/companies/data",
                "type": "POST",
                data: {_token: $('input[name="_token"]').val()},
            },
            "columns": [
//                    {"data": "user_input", orderable: false, "class": "text-center"},
                {"data": "name", name: "name"},
                {"data": "logo", orderable: false, searchable: false},
                {"data": "options", orderable: false, searchable: false}
            ],
            "order": [
                [0, "desc"]
            ],
            "oLanguage": {"sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json'}

        });
    }
    var handleSubmit = function () {

        $('#addEditCompaniesForm').validate({
            rules: {
                name: {
                    required: true
                },
                website: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
            },
            //messages: lang.messages,
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');

            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                $(element).closest('.form-group').find('.help-block').html('').css('opacity', 0);

            },
            errorPlacement: function (error, element) {
                $(element).closest('.form-group').find('.help-block').html($(error).html()).css('opacity', 1);
            }
        });

        $('#addEditCompanies .submit-form').click(function () {
            if ($('#addEditCompaniesForm').validate().form()) {
                $('#addEditCompanies .submit-form').prop('disabled', true);
                $('#addEditCompanies .submit-form').html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');
                setTimeout(function () {
                    $('#addEditCompaniesForm').submit();
                }, 1000);

            }
            return false;
        });
        $('#addEditCompaniesForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#addEditCompaniesForm').validate().form()) {
                    $('#addEditCompanies .submit-form').prop('disabled', true);
                    $('#addEditCompanies .submit-form').html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');
                    setTimeout(function () {
                        $('#addEditCompaniesForm').submit();
                    }, 1000);
                }
                return false;
            }
        });



        $('#addEditCompaniesForm').submit(function () {
            var id = $('#id').val();
            var formData = new FormData($(this)[0]);
            var action = config.admin_url + '/companies';
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/companies/' + id;
            }


            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#addEditCompanies .submit-form').prop('disabled', false);
                    $('#addEditCompanies .submit-form').html(lang.save);

                    if (data.type == 'success')
                    {
                        My.toast(data.message);
                        Companies_grid.api().ajax.reload();

                        if (id != 0) {
                            $('#addEditCompanies').modal('hide');
                        } else {
                            Companies.empty();
                        }

                    } else {
                        console.log(data)
                        if (typeof data.errors === 'object') {
                            for (i in data.errors)
                            {
                                $('[name="' + i + '"]')
                                        .closest('.form-group').addClass('has-error');
                                $('#' + i).closest('.form-group').find(".help-block").html(data.errors[i][0]).css('opacity', 1)
                            }
                        } else {
                            //alert('here');
                            $.confirm({
                                title: lang.error,
                                content: data.message,
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    tryAgain: {
                                        text: lang.try_again,
                                        btnClass: 'btn-red',
                                        action: function () {
                                        }
                                    }
                                }
                            });
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#addEditCompanies .submit-form').prop('disabled', false);
                    $('#addEditCompanies .submit-form').html(lang.save);
                    My.ajax_error_message(xhr);
                },
                dataType: "json",
                type: "POST"
            });

            return false;

        })




    }



    return{
        init: function () {
            init();
        },
        status: function (t) {
            var user_id = $(t).data("id");
            $(t).prop('disabled', true);
            $(t).html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');

            $.ajax({
                url: config.admin_url + '/users/status/' + user_id,
                success: function (data) {
                    Companies_grid.api().ajax.reload();
                },
                error: function (xhr, textStatus, errorThrown) {
                    My.ajax_error_message(xhr);
                },
            });

        },
        edit: function (t) {
            var id = $(t).attr("data-id");
            My.editForm({
                element: t,
                url: config.admin_url + '/companies/' + id + '/edit',
                success: function (data)
                {
                    console.log(data);

                    Companies.empty();
                    My.setModalTitle('#addEditCompanies', lang.edit);

                    for (i in data.message)
                    {
                        if (i == 'logo') {
                            $('.image_box').html('<img src="' + data.message[i] + '" class="image" width="150" height="80" />');
                        } else {
                            $('#' + i).val(data.message[i]);
                        }


                    }
                    $('#addEditCompanies').modal('show');
                }
            });

        },
        delete: function (t) {
            var id = $(t).attr("data-id");
            var del = bootbox.dialog({
                animate: false,
                message: lang.confirm_message,
                title: lang.attention_message,
                buttons: {
                    cancel: {
                        label: lang.no,
                        className: 'btn-default'
                    },
                    danger: {
                        label: lang.yes,
                        className: "btn-primary",
                        callback: function (ele) {
                            //console.log(ele);

                            ele.target.setAttribute("disabled", true);
                            ele.target.innerHTML = '<i class="fa fa-spin fa-spinner"></i>';

                            My.deleteForm({
                                element: t,
                                url: config.admin_url + '/companies/' + id,
                                data: {_method: 'DELETE', _token: $('input[name="_token"]').val()},
                                success: function (data)
                                {
                                    Companies_grid.api().ajax.reload();
                                    del.modal('hide')
                                }
                            });
                            return false;
                        }
                    }
                }
            });

        },

        add: function () {
            Companies.empty();
            My.setModalTitle('#addEditCompanies', lang.add);
            $('#addEditCompanies').modal('show');
        },
        empty: function () {
            $('#id').val(0);
            $('.image_box').html('<img src="' + config.url + '/no-image.png" class="image" width="150" height="80" />');
            $('#image').val('');
            $('.has-error').removeClass('has-error');
            $('.has-success').removeClass('has-success');
            $('.help-block').html('');
            My.emptyForm();
        },
    };
}();
$(document).ready(function () {
    Companies.init();
});