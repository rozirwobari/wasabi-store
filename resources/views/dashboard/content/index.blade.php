@extends('dashboard.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="col-lg-9 col-md-8">
        <div class="main-content" data-aos="fade-up">
            <div id="dashboard" class="tab-content active">
                <h2 class="section-title">
                    Admin Dashboard Overview
                </h2>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-plus me-2"></i>Add Product
                    </a>
                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-truck me-2"></i>Process Orders
                    </a>
                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-user-plus me-2"></i>Add User
                    </a>
                    <a href="#" class="quick-action-btn">
                        <i class="fas fa-chart-bar me-2"></i>View Analytics
                    </a>
                </div>

                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card revenue">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-value">Rp 15.5M</div>
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +12.5% from last month
                        </div>
                    </div>

                    <div class="stat-card orders">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-value">1,247</div>
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +8.3% from last month
                        </div>
                    </div>

                    <div class="stat-card users">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-value">3,856</div>
                        <div class="stat-label">Active Users</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +15.2% from last month
                        </div>
                    </div>

                    <div class="stat-card products">
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-value">289</div>
                        <div class="stat-label">Total Products</div>
                        <div class="stat-change negative">
                            <i class="fas fa-arrow-down"></i> -2.1% from last month
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <h3 class="section-title">Recent Orders</h3>
                <div class="data-table">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#12345</td>
                                <td>John Doe</td>
                                <td>28 May 2025</td>
                                <td>Rp 150.000</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-secondary-custom me-1">View</button>
                                    <button class="btn btn-sm btn-primary-custom">Process</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#12344</td>
                                <td>Jane Smith</td>
                                <td>27 May 2025</td>
                                <td>Rp 280.000</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-secondary-custom me-1">View</button>
                                    <button class="btn btn-sm btn-outline-secondary">Archive</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#12343</td>
                                <td>Mike Johnson</td>
                                <td>26 May 2025</td>
                                <td>Rp 95.000</td>
                                <td><span class="status-badge status-cancelled">Cancelled</span></td>
                                <td>
                                    <button class="btn btn-sm btn-secondary-custom me-1">View</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
