@php
    $activeTab = $activeTab ?? 'admin_home';

    $items = [
        ['key' => 'admin_home', 'label' => 'Inicio', 'icon' => 'bi-house', 'href' => url('/admin')],
        ['key' => 'admin_checkin', 'label' => 'Check-In', 'icon' => 'bi-people', 'href' => url('/admin/checkin')],
        ['key' => 'admin_creditos', 'label' => 'Créditos', 'icon' => 'bi-credit-card', 'href' => url('/admin/creditos')],
        ['key' => 'admin_cuenta', 'label' => 'Cuenta', 'icon' => 'bi-person', 'href' => url('/admin/cuenta')],
    ];
@endphp

<nav class="navbar navbar-expand bg-white border-top">
    <div class="container-fluid justify-content-around">
        <ul class="navbar-nav d-flex flex-row w-100 justify-content-between">
            @foreach ($items as $it)
                @php $isActive = ($activeTab === $it['key']); @endphp
                <li class="nav-item text-center w-100">
                    <a class="nav-link {{ $isActive ? 'active text-dark d-flex flex-column align-items-center' : 'text-dark d-flex flex-column align-items-center' }}" href="{{ $it['href'] }}" {!! $isActive ? 'aria-current="page"' : '' !!}>
                        <i class="bi {{ $it['icon'] }} nav-icon"></i>
                        <span class="nav-label">{{ $it['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
