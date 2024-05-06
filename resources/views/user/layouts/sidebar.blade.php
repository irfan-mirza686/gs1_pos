<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{asset('assets/uploads/logo/gs_logo.png')}}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">GS1 POS</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="{{ route('pos') }}" target="_blank">
                <div class="parent-icon">🖥️
                </div>
                <div class="menu-title">POS</div>
            </a>
        </li>

        <li class="menu-label">Elements</li>

        <li>
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon">🔗
                </div>
                <div class="menu-title">User Management</div>
            </a>
            <ul>
                <li> <a href="{{ route('users') }}"><i class="bx bx-right-arrow-alt"></i>Users</a>
                </li>
                <li> <a href="{{ route('roles') }}"><i class="bx bx-right-arrow-alt"></i>Roles</a>
                </li>
            </ul>
        </li>
        <!-- <li>
            <a href="{{ route('products') }}">
                <div class="parent-icon">🛍️
                </div>
                <div class="menu-title">Products</div>
            </a>
        </li> -->

        <li>
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon">📁
                </div>
                <div class="menu-title">Master Data</div>
            </a>
            <ul>
                <li> <a href="{{ route('products') }}"><i class="bx bx-right-arrow-alt"></i>Products</a>
                </li>

            </ul>
        </li>

        <li>
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon">🛒
                </div>
                <div class="menu-title">Sales</div>
            </a>
            <ul>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Sale</a>
                </li>

            </ul>
        </li>

    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
