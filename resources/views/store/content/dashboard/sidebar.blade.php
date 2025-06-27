            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                <div class="sidebar" data-aos="fade-right">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="user-name">{{ Auth::user()->name }}</h5>
                        <p class="user-email">{{ Auth::user()->email }}</p>
                    </div>

                    <ul class="sidebar-menu">
                        <li>
                            <a href="{{ url('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}" data-tab="dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}" data-tab="profile">
                                <i class="fas fa-user"></i>
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('dataplayers') }}" class="{{ (request()->is('dataplayers') || request()->is('dataplayers/*')) ? 'active' : '' }}" data-tab="orders">
                                <i class="fas fa-box"></i>
                                Data Player
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('orders') }}" class="{{ (request()->is('orders') || request()->is('order-details/*')) ? 'active' : '' }}" data-tab="orders">
                                <i class="fas fa-box"></i>
                                My Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('settings') }}" class="{{ request()->is('settings') ? 'active' : '' }}"
                                data-tab="settings">
                                <i class="fas fa-cog"></i>
                                Settings
                            </a>
                        </li>
                        <li>
                            <form id="logout-form" action="{{ url('logout') }}" method="post">
                                @csrf
                                <a href="#"
                                    onclick="document.getElementById('logout-form').submit(); return false;">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
