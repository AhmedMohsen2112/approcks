@extends('layouts.backend')

@section('pageTitle', _lang('app.employees'))
@section('breadcrumb')
<li><a href="{{url('admin')}}">{{_lang('app.dashboard')}}</a> <i class="fa fa-circle"></i></li>
<li><a href="{{url('admin/employees')}}">{{_lang('app.employees')}}</a> <i class="fa fa-circle"></i></li>
<li><span> {{_lang('app.view')}}</span></li>

@endsection
@section('js')
@endsection
@section('content')


<div class="row">

    <div class="col-md-8 col-md-offset-2">

        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>{{ _lang('app.info')}}
                </div>

            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-hover">

                        <tbody>
                            <tr>
                                <td>{{ _lang('app.fname')}}</td>
                                <td>{{$employee->fname}}</td>

                            </tr>
                            <tr>
                                <td>{{ _lang('app.lname')}}</td>
                                <td>{{$employee->lname}}</td>

                            </tr>
                            <tr>
                                <td>{{ _lang('app.email')}}</td>
                                <td>{{$employee->email}}</td>

                            </tr>
                            <tr>
                                <td>{{ _lang('app.phone')}}</td>
                                <td>{{$employee->phone}}</td>

                            </tr>

                            <tr>
                                <td>{{ _lang('app.company')}}</td>
                                <td><a class="btn btn-sm" href="{{url('admin/companies/'.$employee->company_id)}}">{{$employee->company_name}}</a></td>

                            </tr>
                           
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->


    </div>


</div>


<script>
var new_lang = {

};

</script>
@endsection
