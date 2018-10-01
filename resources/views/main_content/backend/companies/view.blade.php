@extends('layouts.backend')

@section('pageTitle', _lang('app.companies'))
@section('breadcrumb')
<li><a href="{{url('admin')}}">{{_lang('app.dashboard')}}</a> <i class="fa fa-circle"></i></li>
<li><a href="{{url('admin/companies')}}">{{_lang('app.companies')}}</a> <i class="fa fa-circle"></i></li>
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
                                <td>{{ _lang('app.name')}}</td>
                                <td>{{$company->name}}</td>

                            </tr>

                            <tr>
                                <td>{{ _lang('app.email')}}</td>
                                <td>{{$company->email}}</td>

                            </tr>
                            <tr>
                                <td>{{ _lang('app.website')}}</td>
                                <td><a class="btn btn-sm" href="{{$company->website}}">{{_lang('app.click_here')}}</a></td>

                            </tr>
                           
                            <tr>
                                <td>{{ _lang('app.logo')}}</td>
                                <td><img style="width: 100px;height: 100px;" alt="" src="{{$company->logo}}"></td>
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
