<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url('/admin-lte/dist/img/user.png') }}" class="img-circle" alt="{{Auth::user()->fname}}">
            </div>
            <div class="pull-left info">
                <p>{{Auth::user()->lname}} {{Auth::user()->fname}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> @lang('blade.online')</a>
            </div>
        </div>

        <?php $active = 'active'; $active_s = ''; $active_i = ''; $active_t = ''; $active_a = ''; $active_admin_d = '';
        $active_admin_r = ''; $active_admin_u = ''; $active_admin_i = '';?>
        @if(url()->current() == route('fe-inbox'))
            <?php $active_i = 'active' ?>
        @elseif(url()->current() == route('fe-sent'))
            <?php $active_s = 'active' ?>
        @elseif(url()->current() == route('fe-all-inbox'))
            <?php $active_a = 'active' ?>
        @elseif(url()->current() == url('/admin/departments'))
            <?php $active_admin_d = 'active' ?>
        @elseif(url()->current() == url('/admin/roles'))
            <?php $active_admin_r = 'active' ?>
        @elseif(url()->current() == url('/admin/users'))
            <?php $active_admin_u = 'active' ?>
        @elseif(url()->current() == url('/admin/ip-networks'))
            <?php $active_admin_i = 'active' ?>
        @endif

        <ul class="sidebar-menu">
            <li class="header">@lang('blade.mailbox')</li>
            <li>
                <a href="{{ url('/groups') }}">
                    <i class="fa fa-thumb-tack"></i> <span>@lang('blade.my_group')</span>
                </a>
            </li>
            <li class="treeview {{$active}}">
                <a href="#"><i class="fa fa-pencil-square-o"></i> <span>@lang('blade.write_message')</span><span
                            class="pull-right-container">
            </span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="{{ route('fe-group-compose') }}">
                            <i class="fa fa-user-plus"></i> <span>@lang('blade.write_group')</span>
                            <span class="pull-right-container">
            </span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="{{ route('fe-compose') }}">
                            <i class="fa fa-pencil"></i> <span>@lang('blade.write_message')</span>
                            <span class="pull-right-container">
            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="header">@lang('blade.main_nav')</li>
            <li class="treeview {{$active}}">
                <a href="#"><i class="fa fa-envelope"></i> <span>@lang('blade.webEdo')</span><span
                            class="pull-right-container">
            </span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class=" {{$active_i}}">
                        <a href="{{ route('fe-inbox') }}"><i class="fa fa-eye-slash"></i> @lang('blade.unread_message')
                            <small class="label pull-right bg-yellow">{{$inbox_count}}</small>
                        </a>
                    </li>
                    <li class=" {{$active_s}}">
                        <a href="{{ route('fe-sent') }}"><i class="fa fa-send-o"></i> @lang('blade.sent_message')
                            <small class="label pull-right bg-green">{{$sent_count}}</small>
                        </a>
                    </li>
                    <li class=" {{$active_a}}">
                        <a href="{{ route('fe-all-inbox') }}"><i class="fa fa-inbox"></i> @lang('blade.archive_inbox')
                            <small class="label pull-right bg-blue">{{$all_inbox_count}}</small>
                        </a></li>
                </ul>
            </li>
                
            @if(in_array("admin", json_decode(Auth::user()->roles)))

                <li class="treeview {{$active}}">
                    <a href="#"><i class="fa fa-wrench"></i> <span>Administrator</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">

                        <li class=" {{$active_admin_d}}">
                            <a href="{{ url('/admin/departments') }}">
                                <i class="fa fa-bank"></i> <span> @lang('blade.sidebar_dep')</span>
                            </a>
                        </li>
                        <li class=" {{$active_admin_r}}">
                            <a href="{{ url('/admin/roles') }}">
                                <i class="fa fa-wrench"></i> <span>Role</span>
                            </a>
                        </li>
                        <li class=" {{$active_admin_u}}">
                            <a href="{{ url('/admin/users') }}">
                                <i class="fa fa-users"></i> <span>@lang('blade.sidebar_users')</span>
                            </a>
                        </li>
                        <li class=" {{$active_admin_i}}">
                            <a href="{{ route('it-admin-ip-networks') }}">
                                <i class="fa fa-laptop"></i> <span>Ip networks</span>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-database"></i> <span>Control DB</span><span
                                class="pull-right-container">
                        </span>
                                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ url('/user-message-control') }}">
                                <i class="fa fa-envelope-o"></i> <span> User Mes Control</span>
                                <span class="pull-right-container">
                            <small class="label pull-right bg-green">new</small>
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/monitoring-message') }}">
                                <i class="fa fa-envelope"></i> <span> Message Control</span>
                                <span class="pull-right-container">
                            <small class="label pull-right bg-green">new</small>
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/message-users-delete') }}">
                                <i class="fa fa-users"></i> <span> Message Users</span>
                                <span class="pull-right-container">
                            <small class="label pull-right bg-green">new</small>
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/file-control') }}">
                                <i class="fa fa-file"></i> <span> File Control</span>
                                <span class="pull-right-container">
                            <small class="label pull-right bg-green">new</small>
                            </span>
                            </a>
                        </li>
                    </ul>
                </li>            
            @elseif(in_array("branch_admin", json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ url('/admin/users') }}">
                        <i class="fa fa-users"></i> <span>@lang('blade.sidebar_users')</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-green">new</small>
                    </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/ip-networks') }}">
                        <i class="fa fa-laptop"></i> <span>Ip networks</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-green">new</small>
                    </span>
                    </a>
                </li>
                <li class=" {{$active_admin_d}}">
                    <a href="{{ url('/admin/departments') }}">
                        <i class="fa fa-bank"></i> <span> @lang('blade.sidebar_dep')</span>
                    </a>
                </li>
            @elseif(in_array("it_admin", json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('it-admin-ip-networks') }}">
                        <i class="fa fa-laptop"></i> <span> IP Networks</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-green">new</small>
                    </span>
                    </a>
                </li>
            @elseif(in_array("accountant", json_decode(Auth::user()->roles)))
                <li>
                    <a href="https://online.turonbank.uz:3347/bank-user" target="_blank">
                        <i class="fa fa-user"></i> <span>Online App</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-green">Yangi</small>
                    </span>
                    </a>
                </li>
            @elseif(in_array("control", json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ url('/control') }}">
                        <i class="fa fa-check-square-o"></i> <span>Nazoratdagi xatlar</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-green">yangi</small>
                    </span>
                    </a>
                </li>
            @endif


            @switch(Auth::user()->uwUsers())
                @case('super_admin')
                @case('risk_adminstrator')
                @case('risk_user')
                @case('credit_insp')

                <li class="treeview active">
                    <a href="#"><i class="fa fa-cubes"></i> <span>Anderrayting</span><span
                                class="pull-right-container">
                            </span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="http://172.16.2.13" target="_blank">
                                <i class="fa fa-chrome"></i> <span> 172.16.2.13 (192 lik)</span>
                            </a>
                        </li>
                        <li>
                            <a href="http://10.11.48.7" target="_blank">
                                <i class="fa fa-chrome"></i> <span> 10.11.48.7 (10 lik)</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @break
            @endswitch

            <li>
                <a href="{{ route('edo-home') }}">
                    <i class="fa fa-folder-open"></i> <span>@lang('blade.sidebar_edo')</span>
                </a>
            </li>
            <li>
                <a href="{{ route('fe-deleted') }}">
                    <i class="fa fa-trash text-red"></i> <span class="text-red">@lang('blade.trash_message')</span>
                </a>
            </li>
            <li>
                <a href="{{ url('https://iabs.turonbank.uz:4433/ibs/index.jsp') }}" target="_blank">
                    <i class="fa fa-globe text-blue"></i> <span>@lang('blade.iabs')</span>
                </a>
            </li>
        </ul>
    </section>

</aside>
