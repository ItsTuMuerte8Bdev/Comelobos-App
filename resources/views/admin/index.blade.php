@php $activeTab = 'admin_home'; @endphp
@extends('admin.layout')

@section('content')
  <main class="hero home-hero" style="background:var(--color-primary);color:var(--color-primary-contrast);min-height:60vh;">
    <div>
      <h1 style="margin:0;font-weight:700;">¡Bienvenido, administrador!</h1>
      <p style="margin-top:8px;opacity:.95">Este es tu panel — utilízalo sabiamente.</p>
    </div>
  </main>
@endsection
