@section('left-side')

<section class="sidebar">
    <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
            <input type="search" placeholder="{{ __('Filter') }}" class="form-control" />

            <span class="input-group-btn">
                <button type="submit" name="seach" id="search-btn" class="btn btn-flat">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </div>
    </form>

    <ul class="sidebar-menu">
        <li class="treeview{{ strstr($ROUTE, 'management') ? ' active' : '' }}">
            <a href="#">
                <i class="fa fa-lock"></i>
                <span>{{ __('Management') }}</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>

            <ul class="treeview-menu">
                <li{!! strstr($ROUTE, 'management.users') ? ' class="active"' : '' !!}>
                    <a href="{{ route('admin.management.users.index') }}">
                        <i class="fa fa-users"></i>
                        {{ __('Users') }}
                    </a>
                </li>

                <li class="treeview{{ strstr($ROUTE, 'management.gettext') ? ' active' : '' }}">
                    <a href="#">
                        <i class="fa fa-font"></i>
                        <span>{{ __('Gettext') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">
                        <li{!! strstr($ROUTE, 'management.gettext.app') ? ' class="active"' : '' !!}>
                            <a href="{{ route('admin.management.gettext.app') }}">
                                <i class="fa fa-font"></i>
                                {{ __('App Gettext') }}
                            </a>
                        </li>

                        <li{!! strstr($ROUTE, 'management.gettext.admin') ? ' class="active"' : '' !!}>
                            <a href="{{ route('admin.management.gettext.admin') }}">
                                <i class="fa fa-font"></i>
                                {{ __('Admin Gettext') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <li{!! strstr($ROUTE, 'management.uploads') ? ' class="active"' : '' !!}>
                    <a href="{{ route('admin.management.uploads.index') }}">
                        <i class="fa fa-upload"></i>
                        {{ __('Uploads') }}
                    </a>
                </li>

                <li{!! strstr($ROUTE, 'management.update') ? ' class="active"' : '' !!}>
                    <a href="{{ route('admin.management.update.index') }}">
                        <i class="fa fa-refresh"></i>
                        {{ __('Update') }}
                    </a>
                </li>

                <li{!! strstr($ROUTE, 'management.logs') ? ' class="active"' : '' !!}>
                    <a href="{{ route('admin.management.logs.index') }}">
                        <i class="fa fa-file-text"></i>
                        {{ __('Logs') }}
                    </a>
                </li>

                <li{!! strstr($ROUTE, 'management.cache') ? ' class="active"' : '' !!}>
                    <a href="{{ route('admin.management.cache.views') }}">
                        <i class="fa fa-floppy-o"></i>
                        {{ __('Cache') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</section>

@show
