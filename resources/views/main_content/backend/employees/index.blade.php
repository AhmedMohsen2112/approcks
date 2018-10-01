@extends('layouts.backend')

@section('pageTitle', _lang('app.employees'))
@section('breadcrumb')
<li><a href="{{url('admin')}}">{{_lang('app.dashboard')}}</a> <i class="fa fa-circle"></i></li>
<li><span> {{_lang('app.employees')}}</span></li>

@endsection
@section('js')
<script src="{{url('public/backend/js')}}/employees.js" type="text/javascript"></script>
@endsection
@section('content')
<div class="modal fade" id="addEditEmployees" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="addEditEmployeesLabel"></h4>
            </div>

            <div class="modal-body">


                <form role="form"  id="addEditEmployeesForm"  enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="0">
                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            <select class="form-control" id="company" name="company">
                                <option value = "">{{_lang('app.choose')}}</option>
                                @foreach ($companies as $key => $value)
                                <option value = "{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                            <label for = "company">{{_lang('app.company')}}</label>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="{{_lang('app.fname')}}">
                            <label for="fname">{{_lang('app.fname')}}</label>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            <input type="text" class="form-control" id="lname" name="lname" placeholder="{{_lang('app.lname')}}">
                            <label for="lname">{{_lang('app.lname')}}</label>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group form-md-line-input">
                            <input type="text" class="form-control" id="email" name="email" placeholder="{{_lang('app.email')}}">
                            <label for="email">{{_lang('app.email')}}</label>
                            <span class="help-block"></span>
                        </div>

                        <div class="form-group form-md-line-input">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="{{_lang('app.phone')}}">
                            <label for="phone">{{_lang('app.phone')}}</label>
                            <span class="help-block"></span>
                        </div>




                    </div>


                </form>

            </div>

            <div class = "modal-footer">
                <span class = "margin-right-10 loading hide"><i class = "fa fa-spin fa-spinner"></i></span>
                <button type = "button" class = "btn btn-info submit-form"
                        >{{_lang("app.save")}}</button>
                <button type = "button" class = "btn btn-white"
                        data-dismiss = "modal">{{_lang("app.close")}}</button>
            </div>
        </div>
    </div>
</div>
<div class = "panel panel-default">

    <div class = "panel-body">
        <!--Table Wrapper Start-->
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group">
                        <a class="btn green" style="margin-bottom: 40px;" href="" onclick="Employees.add(); return false;">{{ _lang('app.add_new')}}<i class="fa fa-plus"></i> </a>
                    </div>
                </div>
            </div>
        </div>

        <table class = "table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer">
            <thead>
                <tr>
                    <th>{{_lang('app.fname')}}</th>
                    <th>{{_lang('app.lname')}}</th>
                    <th>{{_lang('app.email')}}</th>
                    <th>{{_lang('app.phone')}}</th>
                    <th>{{_lang('app.company')}}</th>
                    <th>{{_lang('app.options')}}</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <!--Table Wrapper Finish-->
    </div>
</div>
<script>
    var new_lang = {

    };
</script>
@endsection