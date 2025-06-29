<div class="col-lg-3 col-md-4">
    <div class="sidebar" data-aos="fade-right">
        <div class="admin-profile">
            <div class="admin-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <h5 class="admin-name">Admin User</h5>
            <p class="admin-role">System Administrator</p>
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
                    <i class="fas fa-shopping-cart"></i>
                    Kategori Management
                </a>
            </li>
            <li>
                <a class="{{ (request()->is('admin/produk') || request()->is('admin/tambahproduk') || request()->is('admin/editproduk/*')) ? 'active' : '' }}" href="{{ url('admin/produk') }}">
                    <i class="fas fa-box"></i>
                    Produk Management
                </a>
            </li>
            <li>
                <a class="{{ request()->is('admin/pengguna') ? 'active' : '' }}" href="{{ url('admin/pengguna') }}">
                    <i class="fas fa-users"></i>
                    Users Management
                </a>
            </li>
            <li>
                <a class="{{ request()->is('admin/pengaturan') ? 'active' : '' }}" href="{{ url('admin/pengaturan') }}">
                    <i class="fas fa-chart-line"></i>
                    Settings
                </a>
            </li>
            <li>
                <form action="{{ url('logout') }}" id="logout_admin" method="POST">
                    @csrf
                </form>
                <a href="javascript:void(0);" onclick="document.getElementById('logout_admin').submit();">
                    <i class="fas fa-users"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
