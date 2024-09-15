<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('assets/image/online-pharmacy.png') }}" alt="SIMPO Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light"><b>S I M P O</b></span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/image/user.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link @if(request()->is('dashboard*')) active @endif">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @canany(['MasterMedicine.read', 'MasterPatient.read'])
                    <li class="nav-item @if(request()->is('medicines*') OR request()->is('patients*')) menu-open @endif">
                        <a href="#" class="nav-link @if(request()->is('medicines*') OR request()->is('patients*')) active @endif">
                            <i class="nav-icon fas fa-database"></i>
                            <p>
                                Master
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        @canany(['MasterMedicine.read', 'MasterPatient.read'])
                            <ul class="nav nav-treeview">
                                @can('MasterMedicine.read')
                                    <li class="nav-item">
                                        <a href="{{ route('medicines.index') }}" class="nav-link @if(request()->is('medicines*')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Medicine</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('MasterPatient.read')
                                    <li class="nav-item">
                                        <a href="{{ route('patients.index') }}" class="nav-link @if(request()->is('patients*')) active @endif">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Patient</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        @endcanany
                    </li>
                @endcanany
                @canany(['TransactionMedicine.read', 'TransactionPatient.read'])
                    <li class="nav-item @if(request()->is('transactions/medicines*') OR request()->is('transactions/patients*')) menu-open @endif">
                        <a href="#" class="nav-link @if(request()->is('transactions/medicines*') OR request()->is('transactions/patients*')) active @endif">
                            <i class="nav-icon fas fa-book-medical"></i>
                            <p>
                                Transaction
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        @canany(['TransactionMedicine.read', 'TransactionPatient.read'])
                        <ul class="nav nav-treeview">
                            @can('TransactionMedicine.read')
                                <li class="nav-item">
                                    <a href="{{ route('transactions.medicines.index') }}" class="nav-link @if(request()->is('transactions/medicines*')) active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Medicine</p>
                                    </a>
                                </li>
                            @endcan
                            @can('TransactionPatient.read')
                                <li class="nav-item">
                                    <a href="{{ route('transactions.patients.index') }}" class="nav-link @if(request()->is('transactions/patients*')) active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Patient</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                        @endcanany
                    </li>
                @endcanany
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="nav-link text-left btn" style="color: #c2c7d0;">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
