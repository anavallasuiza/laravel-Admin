@section('left-side')

<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <input type="search" placeholder="{{ __('Filter') }}" class="form-control" />
            </li>

            @if ($I->admin)
            <li class="treeview{{ ($MODEL === 'management') ? ' active' : '' }}">
                <a href="#">
                    <i class="fa fa-lock"></i>
                    <span>{{ __('Management') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <li{{ ($ROUTE === 'users') ? ' class="active"' : '' }}>
                        <a href="{{ route('admin::management.users.index') }}">
                            <i class="fa fa-users"></i>
                            {{ __('Users') }}
                        </a>
                    </li>

                    <li{{ ($ROUTE === 'gettext') ? ' class="active"' : '' }}>
                        <a href="{{ route('admin::management.gettext', 'gl') }}">
                            <i class="fa fa-font"></i>
                            {{ __('Gettext') }}
                        </a>
                    </li>

                    <li{{ ($ROUTE === 'uploads') ? ' class="active"' : '' }}>
                        <a href="{{ route('admin::management.uploads') }}">
                            <i class="fa fa-upload"></i>
                            {{ __('Uploads') }}
                        </a>
                    </li>

                    <li{{ ($ROUTE === 'update') ? ' class="active"' : '' }}>
                        <a href="{{ route('admin::management.update') }}">
                            <i class="fa fa-refresh"></i>
                            {{ __('Update') }}
                        </a>
                    </li>

                    <li{{ ($ROUTE === 'logs') ? ' class="active"' : '' }}>
                        <a href="{{ route('admin::management.logs') }}">
                            <i class="fa fa-file-text"></i>
                            {{ __('Logs') }}
                        </a>
                    </li>

                    <li{{ ($ROUTE === 'cache') ? ' class="active"' : '' }}>
                        <a href="{{ route('admin::management.cache.views') }}">
                            <i class="fa fa-floppy-o"></i>
                            {{ __('Cache') }}
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </section>
</aside>
@show