<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="/" class="text-nowrap logo-img my-2 d-flex align-items-center">
                <img src="/assets/images/auth/logo.png" alt="Logo" style="max-height:50px; margin-right: 10px;">
                <h3 style="margin: 0;">PAMSIMAS</h3>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        @if (auth()->user()->role->role === 'admin')
            <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti t  i-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Home</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/dashboard" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                </ul>

                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Data Master</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/periode" aria-expanded="false">
                            <span>
                                <i class="ti ti-droplet-filled"></i>
                            </span>
                            <span class="hide-menu">Periode Pemakaian</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/pelanggan" aria-expanded="false">
                            <span>
                                <i class="ti ti-users"></i>
                            </span>
                            <span class="hide-menu">Pelanggan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/tarif" aria-expanded="false">
                            <span>
                                <i class="ti ti-brand-cashapp"></i>
                            </span>
                            <span class="hide-menu">Tarif </span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/tahun" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar-stats"></i>
                            </span>
                            <span class="hide-menu">Tahun</span>
                        </a>
                    </li>
                </ul>

                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Pemakaian</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/lihat-pemakaian" aria-expanded="false">
                            <span>
                                <i class="ti ti-brand-tinder"></i>
                            </span>
                            <span class="hide-menu">Lihat Pemakaian</span>
                        </a>
                    </li>
                </ul>

                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Transaksi</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/pembayaran" aria-expanded="false">
                            <span>
                                <i class="ti ti-cash"></i>
                            </span>
                            <span class="hide-menu">Pembayaran</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/riwayat-pembayaran" aria-expanded="false">
                            <span>
                                <i class="ti ti-history"></i>
                            </span>
                            <span class="hide-menu">Riwayat</span>
                        </a>
                    </li>
                </ul>

                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Keuangan</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/saldo" aria-expanded="false">
                            <span>
                                <i class="ti ti-credit-card"></i>
                            </span>
                            <span class="hide-menu">Saldo Kas</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/saldo-masuk" aria-expanded="false">
                            <span>
                                <i class="ti ti-file-arrow-right"></i>
                            </span>
                            <span class="hide-menu">Saldo Masuk</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/saldo-keluar" aria-expanded="false">
                            <span>
                                <i class="ti ti-file-arrow-left"></i>
                            </span>
                            <span class="hide-menu">Saldo Keluar</span>
                        </a>
                    </li>
                </ul>

                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Laporan</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/laporan-pembayaran" aria-expanded="false">
                            <span>
                                <i class="ti ti-checkup-list"></i>
                            </span>
                            <span class="hide-menu">Laporan Pembayaran</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/laporan-keuangan" aria-expanded="false">
                            <span>
                                <i class="ti ti-report-money"></i>
                            </span>
                            <span class="hide-menu">Laporan Keuangan</span>
                        </a>
                    </li>
                </ul>

                {{-- <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Settings</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/settings-midtrans" aria-expanded="false">
                            <span>
                                <i class="ti ti-wallet"></i>
                            </span>
                            <span class="hide-menu">Midtrans</span>
                        </a>
                    </li>
                </ul> --}}

            </nav>
        @elseif(auth()->user()->role->role === 'petugas')
            <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti t  i-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Home</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/dashboard" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                </ul>

                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Pemakaian</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/lihat-pemakaian" aria-expanded="false">
                            <span>
                                <i class="ti ti-brand-tinder"></i>
                            </span>
                            <span class="hide-menu">Lihat Pemakaian</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/catat-pemakaian" aria-expanded="false">
                            <span>
                                <i class="ti ti-file-pencil"></i>
                            </span>
                            <span class="hide-menu">Catat Pemakaian</span>
                        </a>
                    </li>
                </ul>
            </nav>
        @else
            <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Home</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/dashboard" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                </ul>

                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Pemakaian</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/pemakaian-pelanggan" aria-expanded="false">
                            <span>
                                <i class="ti ti-droplet-filled"></i>
                            </span>
                            <span class="hide-menu">Lihat Pemakaian</span>
                        </a>
                    </li>
                </ul>

                @php
                    $notificationCount = \App\Models\Pemakaian::where('user_id', auth()->user()->id)
                        ->where('status', 'belum dibayar')
                        ->count();
                @endphp


                <ul id="sidebarnav">
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Tagihan</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/cek-tagihan" aria-expanded="false">
                            <span>
                                <i class="ti ti-cash"></i>
                            </span>
                            <span class="hide-menu">Cek Tagihan</span>
                            @if ($notificationCount)
                                <span id="notification-badge"
                                    class="badge text-bg-warning">{{ $notificationCount }}</span>
                            @else
                            @endif

                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/tagihan-terbayar" aria-expanded="false">
                            <span>
                                <i class="ti ti-discount-check"></i>
                            </span>
                            <span class="hide-menu">Tagihan Terbayar</span>
                        </a>
                    </li>
                </ul>

            </nav>
        @endif
    </div>
    <!-- End Sidebar scroll-->
</aside>
