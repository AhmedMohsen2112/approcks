@extends('layouts.backend')

@section('pageTitle',_lang('app.profile'))
@section('breadcrumb')
<li><a href="{{url('admin')}}">{{_lang('app.dashboard')}}</a> <i class="fa fa-circle"></i></li>
<li><span> {{_lang('app.profile')}}</span></li>

@endsection

@section('js')
<script src="{{url('public/backend/js')}}/profile.js" type="text/javascript"></script>
@endsection
@section('content')
<form role="form"  id="addEditProfileForm"  enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{$User->id}}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{_lang('app.profile') }}</h3>
        </div>
        <div class="panel-body">


            <div class="form-body">
                <div class="form-group form-md-line-input">
                    <input type="text" class="form-control" id="username" name="username" value="{{$User->username}}">
                    <label for="username">{{_lang('app.username')}}</label>
                    <span class="help-block"></span>
                </div>
                 <div class="form-group form-md-line-input">
                    <input type="password" class="form-control" id="password" name="password">
                    <label for="password">{{_lang('app.password')}}</label>
                    <span class="help-block"></span>
                </div>
                <div class="form-group form-md-line-input">
                    <input type="text" class="form-control" id="email" name="email" value="{{$User->email}}">
                    <label for="email">{{_lang('app.email')}}</label>
                    <span class="help-block"></span>
                </div>

           

            

       
            </div>




            <!--Table Wrapper Finish-->
        </div>
        <div class="panel-footer text-center">
            <button type="button" class="btn btn-info submit-form"
                    >{{_lang('app.save') }}</button>
        </div>

    </div>







</form>
@endsection