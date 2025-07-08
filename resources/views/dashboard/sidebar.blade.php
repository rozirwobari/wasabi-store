<div class="col-lg-3 col-md-4">
    <div class="sidebar" data-aos="fade-right">
        <div class="admin-profile">
            <div class="admin-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <h5 class="admin-name">{{ Auth::user()->name }}</h5>
            <p class="admin-role">{{ Auth::user()->email }}</p>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a class="{{ request()->is('admin') ? 'active' : '' }}" href="{{ url('admin') }}" data-tab="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a class="{{ request()->is('admin/kategori') ? 'active' : '' }}" href="{{ url('admin/kategori') }}">
                    <i class="fa-solid fa-list"></i>
                    Kategori Management
                </a>
            </li>
            <li>
                <a class="{{ (request()->is('admin/produk') || request()->is('admin/tambahproduk') || request()->is('admin/editproduk/*')) ? 'active' : '' }}" href="{{ url('admin/produk') }}">
                    <i class="fa-solid fa-box"></i>
                    Produk Management
                </a>
            </li>
            <li>
                <a class="{{ (request()->is('admin/orders') || request()->is('admin/show-orders?*')) ? 'active' : '' }}" href="{{ url('admin/orders') }}">
                    <i class="fa-solid fa-cart-flatbed"></i>
                    Orders Management
                </a>
            </li>
            <li>
                <a class="{{ request()->is('admin/pengguna') || request()->is('admin/editpengguna/*') || request()->is('admin/tambahpengguna') ? 'active' : '' }}" href="{{ url('admin/pengguna') }}">
                    <i class="fas fa-users"></i>
                    Users Management
                </a>
            </li>
            <li>
                <a class="{{ request()->is('admin/setting') ? 'active' : '' }}" href="{{ url('admin/setting') }}">
                    <i class="fa-solid fa-gear"></i>
                    Settings
                </a>
            </li>
            <li>
                <form action="{{ url('logout') }}" id="logout_admin" method="POST">
                    @csrf
                </form>
                <a href="javascript:void(0);" onclick="document.getElementById('logout_admin').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
