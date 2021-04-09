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

        <ul class="sidebar-menu">

            @switch(Auth::user()->edoUsers())
                @case('office')
                <li>
                    <a href="{{ route('office-create') }}">
                        <i class="glyphicon glyphicon-envelope"></i> <span>@lang('blade.create_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('office-tasks-sent') }}">
                        <i class="glyphicon glyphicon-send"></i> <span> @lang('blade.sent')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('edo-journals') }}">
                        <i class="glyphicon glyphicon-folder-open"></i> <span>@lang('blade.journals')</span>
                    </a>
                </li>

                <li class="header">DEPARTAMENT HUJJATLARI</li>
                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.inbox_doc')</span>
                        <small class="label pull-right bg-red">{{ Auth::user()->countDepInbox()??'' }}</small>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                        <small class="label pull-right bg-yellow">{{ Auth::user()->countDepProcess()??'' }}</small>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                        <small class="label pull-right bg-green">{{ Auth::user()->countDepClosed()??'' }}</small>
                    </a>
                </li>
                @break
                @case('helper')
                <li>
                    <a href="{{ route('guide-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i>
                        <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guide-tasks-resolution') }}">
                        <i class="glyphicon glyphicon-pencil"></i>
                        <span>@lang('blade.to_resolution')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-sent') }}">
                        <i class="glyphicon glyphicon-send"></i> <span>@lang('blade.sent')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-redirect') }}">
                        <i class="glyphicon glyphicon-forward"></i> <span>@lang('blade.forwarded_docs')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-sent-change') }}">
                        <i class="glyphicon glyphicon-edit"></i> <span>@lang('blade.change_sent')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/helper-tasks') }}">
                        <i class="glyphicon glyphicon-tasks"></i> <span>@lang('blade.tasks')</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('/edo/hr-member-protocols/3') }}"><i class="fa fa-reorder"></i>
                        @lang('blade.protocol_management')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->countApparatProtocols() }}</small>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/edo/hr-member-protocols/11') }}"><i class="fa fa-reorder"></i>
                        @lang('blade.hr_orders')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->countHRProtocols() }}</small>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/edo/hr-member-protocols/20') }}"><i class="fa fa-reorder"></i>
                        @lang('blade.kazna_protocols')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->countKaznaProtocols() }}</small>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/edo/hr-member-protocols/24') }}"><i class="fa fa-reorder"></i>
                        @lang('blade.strategy_orders')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->countStrategyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                @break
                @case('guide')
                <li>
                    <a href="{{ route('guide-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i>
                        <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guide-tasks-resolution') }}">
                        <i class="glyphicon glyphicon-pencil"></i>
                        <span>@lang('blade.to_resolution')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-sent') }}">
                        <i class="glyphicon glyphicon-send"></i> <span>@lang('blade.sent')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-redirect') }}">
                        <i class="glyphicon glyphicon-forward"></i> <span>@lang('blade.forwarded_docs')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/edo/hr-member-protocols/3') }}"><i class="fa fa-reorder"></i>
                        @lang('blade.protocol_management')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red">{{ Auth::user()->countApparatProtocols() }}</small>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/edo/hr-member-protocols/11') }}"><i class="fa fa-reorder"></i>
                        @lang('blade.hr_orders')<span class="pull-right-container">
                        <small class="label pull-right bg-red">{{ Auth::user()->countHRProtocols() }}</small></span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/edo/hr-member-protocols/20') }}"><i class="fa fa-reorder"></i>
                        @lang('blade.kazna_protocols')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red">{{ Auth::user()->countKaznaProtocols() }}</small>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/edo/hr-member-protocols/24') }}"><i class="fa fa-reorder"></i>
                        @lang('blade.strategy_orders')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red">{{ Auth::user()->countStrategyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('edo-guide-qr-message-index') }}">
                        <span class="glyphicon glyphicon-qrcode"></span>
                        @lang('blade.qr_documents') 
                        <span class="pull-right-container"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/helper-tasks') }}">
                        <i class="glyphicon glyphicon-tasks"></i> <span>@lang('blade.tasks')</span>
                    </a>
                </li>
                <li class="header">RAHBARIYAT HUJJATLARI</li>
                <li>
                    <a href="{{ route('guide-tasks-g-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i>
                        <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guide-tasks-g-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                @break
                @case('guide_manager')
                <li class="header">RAHBARIYAT MANAGER</li>
                <li>
                    <a href="{{ route('guide-tasks-resolution') }}">
                        <i class="glyphicon glyphicon-pencil"></i>
                        <span>@lang('blade.to_resolution')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-sent') }}">
                        <i class="glyphicon glyphicon-send"></i> <span>@lang('blade.sent')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-redirect') }}">
                        <i class="glyphicon glyphicon-forward"></i> <span>@lang('blade.forwarded_docs')</span>
                    </a>
                </li>
                @if(in_array('main_staff', json_decode(Auth::user()->roles)))
                    <li>
                        <a href="{{ route('edo-staff-protocols') }}">
                            <i class="fa fa-reorder"></i> @lang('blade.hr_orders')
                            <small class="label pull-right bg-red">{{ Auth::user()->hasManyProtocols() }}</small>
                        </a>
                    </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/11') }}"><i class="fa fa-reorder"></i>
                            @lang('blade.hr_orders')<span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->countHRProtocols() }}</small></span>
                        </a>
                    </li>
                    @endif
                @endif


                @if(in_array('bank_apparat', json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-reorder"></i> @lang('blade.protocol_management')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/3') }}">
                            <i class="fa fa-reorder"></i> @lang('blade.protocol_management')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countApparatProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif

                @if(in_array('strategy', json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-check-square-o"></i> <span> @lang('blade.strategy_orders')</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small></span>
                    </a>
                </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/24') }}"><i class="fa fa-reorder"></i>
                            @lang('blade.strategy_orders')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countStrategyProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif
                @if(in_array('kazna', json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-check-square-o"></i> <span> @lang('blade.kazna_protocols')</span>
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/20') }}"><i class="fa fa-reorder"></i>
                            @lang('blade.kazna_protocols')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countKaznaProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif
                <li>
                    <a href="{{ route('edo-qr-message-index') }}"><span class="glyphicon glyphicon-qrcode"></span>
                        @lang('blade.qr_documents') <span class="pull-right-container">
                            <small class="label pull-right bg-red-active">yangi</small></span>
                    </a>
                </li>
                <li class="header">RAHBARIYAT HUJJATLARI</li>
                <li>
                    <a href="{{ route('d-tasks-reg') }}">
                        <i class="glyphicon glyphicon-sort-by-order"></i> <span>@lang('blade.sb_registration')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-download"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-redirect') }}">
                        <i class="glyphicon glyphicon-forward"></i> <span> @lang('blade.forwarded_docs')</span>
                    </a>
                </li>
                <li class="header">DEPARTAMENT HUJJATLARI</li>
                <li>
                    <a href="{{ route('office-create') }}">
                        <i class="glyphicon glyphicon-plus"></i> <span>@lang('blade.create_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('office-tasks-sent') }}">
                        <i class="glyphicon glyphicon-send"></i> <span> @lang('blade.sent')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-users') }}"><i class="fa fa-user"></i>@lang('blade.dep_staff')</a>
                </li>
                <li>
                    <a href="{{ url('/helper-tasks') }}">
                        <i class="glyphicon glyphicon-tasks"></i> <span>@lang('blade.tasks')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('edo-journals') }}">
                        <i class="glyphicon glyphicon-folder-open"></i> <span>@lang('blade.journals')</span>
                    </a>
                </li>
                @break
                @case('director_department')
                <li>
                    <a href="{{ route('d-tasks-reg') }}">
                        <i class="glyphicon glyphicon-sort-by-order"></i> <span>@lang('blade.sb_registration')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-download"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-redirect') }}">
                        <i class="glyphicon glyphicon-forward"></i> <span>@lang('blade.forwarded_docs')</span>
                    </a>
                </li>
                @if(in_array('main_staff', json_decode(Auth::user()->roles)))
                    <li>
                        <a href="{{ route('edo-staff-protocols') }}">
                            <i class="fa fa-reorder"></i> @lang('blade.hr_orders')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red">{{ Auth::user()->hasManyProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/11') }}">
                            <i class="fa fa-reorder"></i> @lang('blade.hr_orders')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countHRProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif


                @if(in_array('bank_apparat', json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-reorder"></i> @lang('blade.protocol_management')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/3') }}">
                            <i class="fa fa-reorder"></i> @lang('blade.protocol_management')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countApparatProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif

                @if(in_array('kazna', json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-check-square-o"></i> <span> @lang('blade.kazna_protocols')</span>
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/20') }}"><i class="fa fa-reorder"></i>
                            @lang('blade.kazna_protocols')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countKaznaProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif
                
                @if(in_array('strategy', json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-check-square-o"></i> <span> @lang('blade.strategy_orders')</span>
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/24') }}"><i class="fa fa-reorder"></i>
                            @lang('blade.strategy_orders')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countStrategyProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif

                
                <li class="header">DEPARTAMENT HUJJATLARI</li>
                <li>
                    <a href="{{ route('edo-qr-message-index') }}"><span class="glyphicon glyphicon-qrcode"></span>
                        @lang('blade.qr_documents') <span class="pull-right-container">
                            <small class="label pull-right bg-red-active">yangi</small></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('office-create') }}">
                        <i class="glyphicon glyphicon-plus"></i> <span>@lang('blade.create_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('office-tasks-sent') }}">
                        <i class="glyphicon glyphicon-send"></i> <span> @lang('blade.sent')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-users') }}"><i class="fa fa-user"></i>@lang('blade.dep_staff')</a>
                </li>
                <li>
                    <a href="{{ url('/helper-tasks') }}">
                        <i class="glyphicon glyphicon-tasks"></i> <span>@lang('blade.tasks')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('edo-journals') }}">
                        <i class="glyphicon glyphicon-folder-open"></i> <span>@lang('blade.journals')</span>
                    </a>
                </li>
                @break
                @case('deputy_of_director')
                <li class="header">RAHBARIYAT HUJJATLARI</li>
                <li>
                    <a href="{{ route('d-tasks-reg') }}">
                        <i class="glyphicon glyphicon-sort-by-order"></i> <span>@lang('blade.sb_registration')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-download"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-redirect') }}">
                        <i class="glyphicon glyphicon-forward"></i> <span>@lang('blade.forwarded_docs')</span>
                    </a>
                </li>

                <li class="header">DEPARTAMENT INBOX</li>

                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                
                @if(in_array('main_staff', json_decode(Auth::user()->roles)))
                    <li>
                        <a href="{{ route('edo-staff-protocols') }}">
                            <i class="fa fa-reorder"></i> @lang('blade.hr_orders')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red">{{ Auth::user()->hasManyProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/11') }}"><i class="fa fa-reorder"></i>
                            @lang('blade.hr_orders')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countHRProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif


                @if(in_array('bank_apparat', json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-reorder"></i> @lang('blade.protocol_management')
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/3') }}">
                            <i class="fa fa-reorder"></i> @lang('blade.protocol_management')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countApparatProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif

                @if(in_array('kazna', json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-check-square-o"></i> <span> @lang('blade.kazna_protocols')</span>
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/20') }}"><i class="fa fa-reorder"></i>
                            @lang('blade.kazna_protocols')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countKaznaProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif

                @if(in_array('strategy', json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-check-square-o"></i> <span> @lang('blade.strategy_orders')</span>
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                        </span>
                    </a>
                </li>
                @else
                    @if(Auth::user()->protocolMember)
                    <li>
                        <a href="{{ url('/edo/hr-member-protocols/24') }}"><i class="fa fa-reorder"></i>
                            @lang('blade.strategy_orders')
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red"> {{ Auth::user()->countStrategyProtocols() }}</small>
                            </span>
                        </a>
                    </li>
                    @endif
                @endif
                <li>
                    <a href="{{ route('edo-qr-message-index') }}"><span class="glyphicon glyphicon-qrcode"></span>
                        @lang('blade.qr_documents') <span class="pull-right-container">
                            <small class="label pull-right bg-red-active">yangi</small></span>
                    </a>
                </li>
                <li class="header">DEPARTAMENT HUJJATLARI</li>
                <li>
                    <a href="{{ route('office-create') }}">
                        <i class="glyphicon glyphicon-plus"></i> <span>@lang('blade.create_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('office-tasks-sent') }}">
                        <i class="glyphicon glyphicon-send"></i> <span> @lang('blade.sent')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-users') }}"><i class="fa fa-user"></i>@lang('blade.dep_staff')</a>
                </li>
                <li>
                    <a href="{{ url('/helper-tasks') }}">
                        <i class="glyphicon glyphicon-tasks"></i> <span>@lang('blade.tasks')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('edo-journals') }}">
                        <i class="glyphicon glyphicon-folder-open"></i> <span>@lang('blade.journals')</span>
                    </a>
                </li>
                @break
                @case('dep_helper')
                <li class="header">DEPARTAMENT HUJJATLARI</li>
                <li>
                    <a href="{{ route('d-tasks-reg') }}">
                        <i class="glyphicon glyphicon-tasks"></i> <span>@lang('blade.sb_registration')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-journal') }}">
                        <i class="glyphicon glyphicon-sort-by-order"></i> <span>Inbox Journal</span>
                    </a>
                </li>
                <li class="header">DEPARTAMENT</li>
                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                <li class="header">KANSELARIYA</li>
                <li>
                    <a href="{{ route('office-create') }}">
                        <i class="glyphicon glyphicon-plus"></i> <span>@lang('blade.create_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('office-tasks-sent') }}">
                        <i class="glyphicon glyphicon-send"></i> <span> @lang('blade.sent')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('edo-journals') }}">
                        <i class="glyphicon glyphicon-folder-open"></i> <span>@lang('blade.journals')</span>
                    </a>
                </li>
                @break
                @case('department_emp')
                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                @break

                @case('control')
                <li class="header">DEPARTAMNET HUJJATLARI</li>
                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>

                <li class="header">IJRO NAZORATI HUJJATLARI</li>
                <li>
                    <a href="{{ route('/edo/tasks/control') }}">
                        <i class="glyphicon glyphicon-check"></i> <span> @lang('blade.sidebar_control')</span>
                    </a>
                </li>
                @break
                @case('admin')
                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-wrench"></i>
                        <span>Configuration</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('/edo-users') }}"><i class="fa fa-users"></i> Users</a></li>
                        <li><a href="{{ url('/edo-user-roles') }}"><i class="fa fa-user"></i> User roles</a></li>
                        <li><a href="{{ url('/edo-type-messages') }}"><i class="fa fa-tasks"></i> Type message</a>
                        </li>
                        <li><a href="{{ url('/edo-management-protocols') }}"><i class="fa fa-user"></i> Management
                                Users</a></li>
                        <li><a href="{{ url('/edo/index-protocols') }}"><i class="fa fa-user"></i> Index
                                Protocol</a></li>
                        <li><a href="{{ url('/edo/member-protocols') }}"><i class="fa fa-user"></i> Protocols</a>
                        </li>
                        <li>
                            <a href="{{ url('/edo/staff-protocols') }}"><i class="fa fa-reorder"></i>
                                @lang('blade.hr_orders')<span class="pull-right-container"></span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="header">DEPARTAMENT HUJJATLARI</li>
                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.inbox_doc')</span>
                        <small class="label pull-right bg-red">{{Auth::user()->countDepInbox()??'' }}</small>

                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                        <small class="label pull-right bg-yellow">{{Auth::user()->countDepProcess()??'' }}</small>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                        <small class="label pull-right bg-green">{{ Auth::user()->countDepClosed()??'' }}</small>
                    </a>
                </li>
                <li>
                    <a href="{{ url('edo-qr-messages') }}"><i class="fa fa-reorder"></i>
                        Qr Messages<span class="pull-right-container">
                            <small class="label pull-right bg-green">new</small></span>
                    </a>
                </li>

                @break
                @case('filial_office')
                <li class="header">RAHBARIYAT HUJJATLARI</li>
                <li>
                    <a href="{{ route('d-tasks-reg') }}">
                        <i class="glyphicon glyphicon-sort-by-order"></i> <span>Registratsiya</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-download"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-redirect') }}">
                        <i class="glyphicon glyphicon-forward"></i> <span> Yo`naltirilgan xatlar</span>
                    </a>
                </li>
                <li class="header">KANSELARIYA</li>
                <li>
                    <a href="{{ route('d-tasks-journal') }}">
                        <i class="glyphicon glyphicon-sort-by-order"></i> <span>Inbox Journal</span>
                    </a>
                </li>
                @break
                @case('filial_manager')
                <li class="header">RAHBARIYAT HUJJATLARI</li>
                <li>
                    <a href="{{ route('d-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-download"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('g-tasks-redirect') }}">
                        <i class="glyphicon glyphicon-forward"></i> <span> Yo`naltirilgan xatlar</span>
                    </a>
                </li>
                @break
                @case('filial_director')
                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                @break
                @case('filial_emp')
                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-hourglass"></i> <span>@lang('blade.on_process')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-closed') }}">
                        <i class="glyphicon glyphicon-folder-close"></i> <span>@lang('blade.closed')</span>
                    </a>
                </li>
                @break
                @case('filial_admin')
                <li>
                    <a href="{{ route('e-tasks-inbox') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.inbox_doc')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('e-tasks-process') }}">
                        <i class="glyphicon glyphicon-inbox"></i> <span>@lang('blade.on_process_docs')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/edo-users') }}">
                        <i class="glyphicon glyphicon-user"></i> <span>EDO Users</span>
                    </a>
                </li>
                @break
            @endswitch

            @if(in_array('bank_apparat_emp',json_decode(Auth::user()->roles)))
            <li>
                <a href="{{ route('edo-staff-protocols') }}">
                    <i class="fa fa-check-square-o"></i> <span>@lang('blade.protocol_management')</span>
                    <span class="pull-right-container">
                    <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                </span>
                </a>
            </li>
            @endif

            @if(in_array('main_staff_emp',json_decode(Auth::user()->roles)))
            <li>
                <a href="{{ route('edo-staff-protocols') }}">
                    <i class="fa fa-check-square-o"></i> <span>@lang('blade.hr_orders')</span>
                    <span class="pull-right-container">
                    <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                </span>
                </a>
            </li>
            @endif

            @if(in_array('strategy_emp',json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-check-square-o"></i> <span> @lang('blade.strategy_orders')</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                    </span>
                    </a>
                </li>
            @endif

            @if(in_array('kazna_emp',json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-staff-protocols') }}">
                        <i class="fa fa-check-square-o"></i> <span> @lang('blade.kazna_protocols')</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-red"> {{ Auth::user()->hasManyProtocols() }}</small>
                    </span>
                    </a>
                </li>
            @endif

            @if(in_array('admin',json_decode(Auth::user()->roles)))
                <li>
                    <a href="{{ route('edo-my-protocols') }}">
                        <i class="fa fa-user"></i> <span>@lang('blade.my_orders')</span>
                        <span class="pull-right-container">
                        <small class="label pull-right bg-green">@lang('blade.new')</small>
                    </span>
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ url('/home') }}" class="text-red">
                    <i class="glyphicon glyphicon-off"></i> <span> @lang('blade.exit')</span>
                </a>
            </li>

        </ul>

    </section>

</aside>
