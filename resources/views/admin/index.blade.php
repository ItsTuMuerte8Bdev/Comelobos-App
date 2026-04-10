@php $activeTab = 'admin_home'; @endphp
@extends('admin.layout')

@section('content')
  <main class="hero home-hero" style="background:var(--color-primary);color:var(--color-primary-contrast);min-height:60vh;">
    <div>
      <h1 style="margin:0;font-weight:700;">¡Bienvenido {{ Auth::user()->first_name }}!</h1>
    </div>
  </main>
@endsection
