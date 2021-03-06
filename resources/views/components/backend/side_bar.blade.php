<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu page-sidebar-menu-light" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <!-- END SIDEBAR TOGGLER BUTTON -->
         
            <li class="nav-item start {{!$page_link_name?'active':''}}">
                <a href="{{url('admin')}}" class="nav-link nav-toggle">
                    <i class="icon-home"></i><span class="title">{{_lang('app.dashboard')}}</span>
                </a>
            </li>
            <li class="nav-item start {{$page_link_name&&$page_link_name=='employees'?'active':''}}">
                <a href="{{url('admin/employees')}}" class="nav-link nav-toggle">
                    <i class="icon-home"></i><span class="title">{{_lang('app.employees')}}</span>
                </a>
            </li>
            <li class="nav-item start {{$page_link_name&&$page_link_name=='companies'?'active':''}}">
                <a href="{{url('admin/companies')}}" class="nav-link nav-toggle">
                    <i class="icon-home"></i><span class="title">{{_lang('app.companies')}}</span>
                </a>
            </li>
       
           


        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>