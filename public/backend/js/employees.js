var Employees_grid;
var Employees = function () {
    var init = function () {
        $.extend(lang, new_lang);
        handleRecords();
        handleSubmit();

    };
    var add_rules = function () {
        
    }
    var edit_rules = function () {
        
    }

    var handleRecords = function () {

        Employees_grid = $('.dataTable').dataTable({
            //"processing": true,
            "serverSide": true,
            "ajax": {
                "url": config.admin_url + "/employees/data",
                "type": "POST",
                data: {_token: $('input[name="_token"]').val()},
            },
            "columns": [
//                    {"data": "user_input", orderable: false, "class": "text-center"},
                {"data": "fname", name: "employees.fname"},
                {"data": "lname", name: "employees.lname"},
                {"data": "email", name: "employees.email"},
                {"data": "phone", name: "employees.phone"},
                {"data": "company", name: "companies.name"},
                {"data": "options", orderable: false, searchable: false}
            ],
            "order": [
                [2, "desc"]
            ],
            "oLanguage": {"sUrl": config.url + '/datatable-lang-' + config.lang_code + '.json'}

        });
    }
    var handleSubmit = function () {

        $('#addEditEmployeesForm').validate({
            rules: {
                fname: {
                    required: true
                },
                lname: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                },
                phone: {
                    required: true
                },
                company: {
                    required: true
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

        $('#addEditEmployees .submit-form').click(function () {
            if ($('#addEditEmployeesForm').validate().form()) {
                $('#addEditEmployees .submit-form').prop('disabled', true);
                $('#addEditEmployees .submit-form').html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');
                setTimeout(function () {
                    $('#addEditEmployeesForm').submit();
                }, 1000);

            }
            return false;
        });
        $('#addEditEmployeesForm input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#addEditEmployeesForm').validate().form()) {
                    $('#addEditEmployees .submit-form').prop('disabled', true);
                    $('#addEditEmployees .submit-form').html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');
                    setTimeout(function () {
                        $('#addEditEmployeesForm').submit();
                    }, 1000);
                }
                return false;
            }
        });



        $('#addEditEmployeesForm').submit(function () {
            var id = $('#id').val();
            var formData = new FormData($(this)[0]);
            var action = config.admin_url + '/employees';
            if (id != 0) {
                formData.append('_method', 'PATCH');
                action = config.admin_url + '/employees/' + id;
            }


            $.ajax({
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#addEditEmployees .submit-form').prop('disabled', false);
                    $('#addEditEmployees .submit-form').html(lang.save);

                    if (data.type == 'success')
                    {
                        My.toast(data.message);
                        Employees_grid.api().ajax.reload();

                        if (id != 0) {
                            $('#addEditEmployees').modal('hide');
                        } else {
                            Employees.empty();
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
                    $('#addEditEmployees .submit-form').prop('disabled', false);
                    $('#addEditEmployees .submit-form').html(lang.save);
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
        edit: function (t) {
            edit_rules();
            var id = $(t).attr("data-id");
            My.editForm({
                element: t,
                url: config.admin_url + '/employees/' + id + '/edit',
                success: function (data)
                {
                    console.log(data);

                    Employees.empty();
                    My.setModalTitle('#addEditEmployees', lang.edit);

                    for (i in data.message)
                    {
                        if(i == 'company_id'){
                            $('#company').val(data.message[i]);
                        }else{
                              $('#' + i).val(data.message[i]);
                        }

                      
                    }
                    $('#addEditEmployees').modal('show');
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
                                url: config.admin_url + '/employees/' + id,
                                data: {_method: 'DELETE', _token: $('input[name="_token"]').val()},
                                success: function (data)
                                {
                                    Employees_grid.api().ajax.reload();
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
            add_rules();
            Employees.empty();
            My.setModalTitle('#addEditEmployees', lang.add);
            $('#addEditEmployees').modal('show');
        },
        empty: function () {
            $('#id').val(0);
            $('#active').find('option').eq(0).prop('selected', true);
            $('#work_type').find('option').eq(0).prop('selected', true);
            $('#department').find('option').eq(0).prop('selected', true);
            $('#category').find('option').eq(0).prop('selected', true);
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
    Employees.init();
});