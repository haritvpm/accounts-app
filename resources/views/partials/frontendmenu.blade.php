<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('*home') ? "active" : "" }} " href=" {{ route("frontend.home")
                        }} ">
                        <i class=" fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
               
               

                <li class="nav-item">
                    <a href="{{ route("frontend.tax-entries.index") }}"
                     class="nav-link {{ request()->is("*tax-entries") || request()->is("*/tax-entries/*") ? "active" : "" }}">
                        <i class="fa-fw nav-icon fas fa-sack-dollar">

                        </i>
                        <p>
                           <span>{{ trans('cruds.taxEntry.title') }}</span>
                        </p>
                    </a>
                </li>
              <!--   <li class="nav-item">
                    <a href="{{ route("frontend.tds.index") }}"
                     class="nav-link {{ request()->is("tds") || request()->is("tds/*") ? "active" : "" }}">
                        <i class="fa-fw nav-icon fas fa-table">

                        </i>
                        <p>
                        <span>{{ trans('cruds.td.title') }}</span>
                        </p>
                    </a>
                </li>
 -->
                @can('salary_bill_detail_create')
                <li class="nav-item">
                    <a href="{{ route("frontend.salary-bill-details.create") }}"
                    class="nav-link {{ request()->is("*salary-bill-details/create") ? "active" : "" }}">
                        <i class="fa-fw nav-icon fas fa-dollar-sign">

                        </i>
                        <p>
                            Salary Bill Entry
                        </p>
                    </a>
                </li>
                @endcan

                @can('salary_bill_detail_access')
                <li class="nav-item">
                    <a href="{{ route("frontend.salary-bill-details.index") }}"
                     class="nav-link {{ request()->is("*salary-bill-details") ? "active" : "" }}">
                        <i class="fa-fw nav-icon fas fa-table ">

                        </i>
                        <p>
                            {{ trans('cruds.salaryBillDetail.title') }}
                        </p>
                    </a>
                </li>
                @endcan


                <li class="nav-item">
                        <a href="{{ route("frontend.employees.index") }}" class="nav-link {{ request()->is("frontend/employees") || request()->is("frontend/employees/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.employee.title') }}
                            </p>
                        </a>
                    </li>

                @can('profile_password_edit')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('*frontend/profile') || request()->is('*frontend/profile./*') ? 'active' : '' }}"
                        href="{{ route('frontend.profile.index') }}">
                        <i class="fa-fw fas fa-key nav-icon">
                        </i>
                        <p>
                            Change Password
                        </p>
                    </a>
                </li>
                @endcan

                <li class="nav-item">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>