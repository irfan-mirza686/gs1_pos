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
                <div class="parent-icon"><img src="{{asset('assets/uploads/sidebar_icons/pos.png')}}" alt="pos"
                        height="30" width="30">
                </div>
                <div class="menu-title">POS</div>
            </a>
        </li>

        <li class="menu-label">Elements</li>

        <li>
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><img src="{{asset('assets/uploads/sidebar_icons/user_management.png')}}"
                        alt="inventory" height="30" width="30">
                </div>
                <div class="menu-title">User Management</div>
            </a>
            <ul>
                <li> <a href="{{ route('users') }}"><i class="bx bx-right-arrow-alt"></i>Users</a>
                </li>
                <li> <a href="{{ route('roles') }}"><i class="bx bx-right-arrow-alt"></i>Roles & Permissions</a>
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
                <div class="parent-icon"><img src="{{asset('assets/uploads/sidebar_icons/inventory.png')}}"
                        alt="inventory" height="30" width="30">
                </div>
                <div class="menu-title">Inventory</div>
            </a>
            <ul>
                <li> <a href="{{ route('products') }}"><i class="bx bx-right-arrow-alt"></i>Products</a>
                </li>
                <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Create Product</a>
                </li>
                <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Expired Products</a>
                </li>
                <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Categories</a>
                </li>
                <li> <a href="{{route('brands')}}"><i class="bx bx-right-arrow-alt"></i>Brands</a>
                </li>
                <li> <a href="{{route('units')}}"><i class="bx bx-right-arrow-alt"></i>Units</a>
                </li>
                <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Print Barcodes</a>
                </li>
                <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Print QR Codes</a>
                </li>

            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><img src="{{asset('assets/uploads/sidebar_icons/stock.png')}}" alt="stock"
                        height="30" width="30">
                </div>
                <div class="menu-title">Stock</div>
            </a>
            <ul>
                <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Manage Stock</a>
                </li>
                <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Stock Adjustment</a>
                </li>
                <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Stock Transfer</a>
                </li>

            </ul>
        </li>

        <li>
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><img src="{{asset('assets/uploads/sidebar_icons/sales.png')}}" alt="sales"
                        height="30" width="30">
                </div>
                <div class="menu-title">Sales</div>
            </a>
            <ul>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Sales</a>
                </li>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Invoices</a>
                </li>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Sales Returns</a>
                </li>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>POS</a>
                </li>
                <li> <a href="{{route('customers')}}"><i class="bx bx-right-arrow-alt"></i>Customers</a>
                </li>

            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><img src="{{asset('assets/uploads/sidebar_icons/settings.png')}}"
                        alt="inventory" height="30" width="30">
                </div>
                <div class="menu-title">Settings</div>
            </a>
            <ul>
                <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>General Settings</a>
                    <ul>

                        <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Profile</a>
                        </li>

                    </ul>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><img src="{{asset('assets/uploads/sidebar_icons/reports.png')}}"
                        alt="inventory" height="30" width="30">
                </div>
                <div class="menu-title">Reports</div>
            </a>
            <ul>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Sales Report</a>
                </li>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Inventory Report</a>
                </li>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Invoice Report</a>
                </li>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Customer Report</a>
                </li>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Tax Report</a>
                </li>
                <li> <a href="{{route('sales')}}"><i class="bx bx-right-arrow-alt"></i>Profile & Loss</a>
                </li>

            </ul>
        </li>

    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
