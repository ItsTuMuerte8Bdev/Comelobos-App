<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos | Contactos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero app-header">
                <div class="header-inner">
                    <a href="{{ route('cuenta') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
                    <h2>Contactos</h2>
                </div>
            </main>

            <section class="px-3 py-3 content-section">
                <div class="padded container-max text-primary-dark">
                    <h1 class="page-heading">Directorio — Comedor Universitario CU</h1>

                    <h5 class="section-heading">DIRECCIÓN</h5>
                    <p class="contact-line contact-strong">Coordinación General: Mtra. Laura Pérez Hernández</p>
                    <p class="contact-line"><i class="bi bi-envelope-fill"></i> direccion.comedorcu@correo.buap.mx</p>
                    <p class="contact-line"><i class="bi bi-geo-alt-fill"></i> Edificio Comedor Universitario, Ciudad Universitaria, Puebla, Puebla.</p>
                    <p class="contact-line"><i class="bi bi-telephone-fill"></i> (222) 229-55-00 Ext. 7010 — Código: COU-101</p>

                    <hr>

                    <h5 class="section-heading">SUBDIRECCIÓN</h5>
                    <p class="contact-line contact-strong">Subdirección Operativa: Ing. Carlos Méndez García</p>
                    <p class="contact-line"><i class="bi bi-envelope-fill"></i> subdireccion.comedorcu@correo.buap.mx</p>
                    <p class="contact-line"><i class="bi bi-telephone-fill"></i> (222) 229-55-00 Ext. 7012 — Código: COU-102</p>

                    <hr>

                    <h5 class="section-heading">DEPARTAMENTOS</h5>

                    <h6 class="contact-strong">Administración</h6>
                    <p class="contact-line"><i class="bi bi-person-badge-fill"></i> Lic. Ana Castillo López</p>
                    <p class="contact-line"><i class="bi bi-envelope-fill"></i> administracion.comedorcu@correo.buap.mx</p>
                    <p class="contact-line"><i class="bi bi-telephone-fill"></i> (222) 229-55-00 Ext. 7020 — Código: COU-103</p>

                    <h6 class="contact-strong">Atención al Público</h6>
                    <p class="contact-line"><i class="bi bi-person-lines-fill"></i> Mtro. Juan Carlos Rojas</p>
                    <p class="contact-line"><i class="bi bi-envelope-fill"></i> atencion.comedorcu@correo.buap.mx</p>
                    <p class="contact-line"><i class="bi bi-telephone-fill"></i> (222) 229-55-00 Ext. 7021 — Código: COU-104</p>

                    <h6 class="contact-strong">Nutrición y Calidad</h6>
                    <p class="contact-line"><i class="bi bi-heart-pulse-fill"></i> Dra. Gabriela Torres Mendoza</p>
                    <p class="contact-line"><i class="bi bi-envelope-fill"></i> nutricion.comedorcu@correo.buap.mx</p>
                    <p class="contact-line"><i class="bi bi-telephone-fill"></i> (222) 229-55-00 Ext. 7031 — Código: COU-202</p>

                    <h6 class="contact-strong">Mantenimiento y Servicios</h6>
                    <p class="contact-line"><i class="bi bi-tools"></i> Jefe de Mantenimiento: C. Raúl Herrera</p>
                    <p class="contact-line"><i class="bi bi-envelope-fill"></i> mantenimiento.comedorcu@correo.buap.mx</p>
                    <p class="contact-line"><i class="bi bi-telephone-fill"></i> (222) 229-55-00 Ext. 7050 — Código: COU-105</p>

                    <h6 class="contact-strong">Seguridad y Control</h6>
                    <p class="contact-line"><i class="bi bi-shield-lock-fill"></i> Coordinador: C. Miguel Ángel López</p>
                    <p class="contact-line"><i class="bi bi-envelope-fill"></i> seguridad.comedorcu@correo.buap.mx</p>
                    <p class="contact-line"><i class="bi bi-telephone-fill"></i> (222) 229-55-00 Ext. 7060 — Código: COU-105</p>

                    <hr>

                 </div>
            </section>

            @include('partials.navbar', ['activeTab' => 'cuenta'])
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
