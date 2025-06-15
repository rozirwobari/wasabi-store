<!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>AdminPanel</h3>
            <div class="subtitle">Dashboard</div>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link  {{ request()->is('dashboards') ? 'active' : '' }}" href="{{ url('dashboards') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboards/kategori') ? 'active' : '' }}" href="{{ url('dashboards/kategori') }}">
                    <i class="fa-solid fa-list"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ (request()->is('dashboards/produk') || request()->is('dashboards/tambahproduk') || request()->is('dashboards/editproduk/*')) ? 'active' : '' }}" href="{{ url('dashboards/produk') }}">
                    <i class="fas fa-box"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboards/pengguna') ? 'active' : '' }}" href="{{ url('dashboards/pengguna') }}">
                    <i class="fas fa-users"></i>
                    <span>Pengguna</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboards/pengaturan') ? 'active' : '' }}" href="{{ url('dashboards/pengaturan') }}">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item">
                <form action="{{ url('logout') }}" id="logout_admin" method="POST">
                    @csrf
                </form>
                <a href="javascript:void(0);" onclick="document.getElementById('logout_admin').submit();" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Logout</span>
                </a>
            </li>

        </ul>
    </nav>