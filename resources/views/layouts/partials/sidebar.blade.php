<!-- Main Sidebar Container -->


<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="./img/vimeo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">ORDER</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="./img/boy.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <router-link to="/home" class="nav-link">
                        <i class="nav-icon fa fa-th"></i>
                        <p>
                            Home
                        </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/dinein" class="nav-link">
                        <i class="nav-icon fa fa-utensils"></i>
                        <p>
                            Dine In
                        </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/order" class="nav-link">
                        <i class="nav-icon fa fa-cart-plus"></i>
                        <p>
                            Orders
                        </p>
                    </router-link>
                </li>
                @can('browse', $permission['product'])
                    <li class="nav-item">
                        <a href="/admin/products" class="nav-link">
                            <i class="nav-icon fa fa-shopping-bag"></i>
                            <p>
                                Products
                            </p>
                        </a>
                    </li>
                @endcan
               

                @can('browse', $permission['table'])
                    <li class="nav-item">
                        <a href="/admin/tables" class="nav-link">
                            <i class="nav-icon fa fa-chair"></i>
                            <p>
                                Tables
                            </p>
                        </a>
                    </li>
                @endcan
                @can('browse', $permission['user'])
                    <li class="nav-item">
                        <a href="/admin/users" class="nav-link">
                            <i class="nav-icon fa fa-user"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                @endcan
                
                <li class="nav-item">
                    <router-link to="/report" class="nav-link">
                        <i class="nav-icon fa fa-book"></i>
                        <p>
                            Reports
                        </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                        
                        <i class="nav-icon fa fa-power-off"></i>
                        <p>{{ __('Logout') }}</p>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                {{-- <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class=" nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Starter Pages
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Active Page</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Inactive Page</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
