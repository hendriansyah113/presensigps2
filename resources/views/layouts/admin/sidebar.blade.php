<div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item nav-profile">
                <a href="#" class="nav-link">
                    <div class="nav-profile-image">
                        <img src="{{ asset('assets-purple/images/faces/face1.jpg') }}" alt="profile">
                        <span class="login-status online"></span>
                        <!--change to offline or busy as needed-->
                    </div>
                    <div class="nav-profile-text d-flex flex-column">
                        <span class="font-weight-bold mb-2">{{ Auth::guard('user')->user()->name }}</span>
                        <span class="text-secondary text-small">Administrator</span>
                    </div>
                    <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                </a>
            </li>
            <li class="nav-item {{ request()->is('dashboardadmin') ? 'active' : '' }}">
                <a class="nav-link" href="/dashboardadmin">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                    aria-controls="ui-basic">
                    <span class="menu-title">Data Master</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Karyawan</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item {{ request()->is('monitoring') ? 'active' : '' }}">
                <a class="nav-link" href="/monitoring">
                    <span class="menu-title">Data Presensi</span>
                    <i class="mdi mdi-fingerprint menu-icon"></i>
                </a>
            </li>
            <li class="nav-item {{ request()->is('presensi/laporan') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#laporan" aria-expanded="false"
                    aria-controls="laporan">
                    <span class="menu-title">Laporan</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-file-outlines menu-icon"></i>
                </a>
                <div class="collapse" id="laporan">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="/presensi/laporan">Presensi</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
