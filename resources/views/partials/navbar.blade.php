@php
    $activeTab = $activeTab ?? 'home';

    $items = [
        ['key' => 'home', 'label' => 'Inicio', 'icon' => 'bi-house', 'href' => url('/index')],
        ['key' => 'reservas', 'label' => 'Reservas', 'icon' => 'bi-calendar2-event', 'href' => url('/reservas')],
        ['key' => 'checkin', 'label' => 'Check-In', 'icon' => 'bi-qr-code', 'href' => url('/checkin')],
        ['key' => 'menu', 'label' => 'Menú', 'icon' => 'bi-list', 'href' => url('/menu')],
        ['key' => 'cuenta', 'label' => 'Cuenta', 'icon' => 'bi-person', 'href' => url('/account')],
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
