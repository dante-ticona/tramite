<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
  <script src="{{asset('/js/util/vue2.js')}}"></script>
  <script src="{{asset('/js/util/eventBus.js')}}"></script>
  <script src="{{asset('/js/util/sweetalert/sweetalert2.all.min.js')}}"></script>
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
  <script>
    window.Laravel = <?php echo json_encode(
  array(
                        'csrf_token' => csrf_token(),
                        'usr_id' => Auth::user()->id,
                        'usr_name' => Auth::user()->name,
                        'usr_user' => Auth::user()->email,
                        'rol_id' => Auth::user()->role_id,
                        'emp_id' => Auth::user()->branch_id,
                        'id_regional' => Auth::user()->id_regional,
                        'id_departamento' => Auth::user()->id_departamento,
                        'id_agencia' => Auth::user()->id_agencia,
                        'es_atc' => Auth::user()->es_atc,
                        'es_supervisor' => Auth::user()->es_supervisor,
                        'es_jefe' => Auth::user()->es_jefe,
                        'access_token' => Session::get('access_token'),
                        'url_gestora' => urlGestora(),
                        'url_gestora_sgg' => urlsggTest(),
                        'url_gestora_sgg1' => urlsggGestora1(),
                        'url_personas' => urlPersonas(),
                        'credenciales' => obtenerCredenciales(),
                        'nombre_regional' => getNameRegional(Auth::user()->id_regional),
                      )); ?>

    async function controlRefreshToken(expAt) {
      let expiresAt = expAt;
      let currentTime = Math.round(Date.now() / 1000);
      if (expiresAt - currentTime < 10) {
        let response = await fetch('/refresh-token', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        });
        //expiresAt = await response.json();
        let data = await response.json();
        expiresAt = data.expires_at;
        window.Laravel.access_token = data.access_token;
      }
      let t = setTimeout(function() {
        controlRefreshToken(expiresAt);
      }, 10000);
    }
    controlRefreshToken("{{ Session::get('expires_at') }}");


  </script>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper" id="app">
    <notifi-consultas></notifi-consultas>

    @if (entorno() == 'DESARROLLO')
        <aside class="main-sidebar {{ colorTema() }} elevation-4" style="background-color:#85BDC5;">
    @elseif (entorno() == 'PRODUCCION')
        <aside class="main-sidebar {{ colorTema() }} elevation-4">
    @else
        <aside class="main-sidebar {{ colorTema() }} elevation-4" style="background-color:#ffffff;">
    @endif

      <!-- Brand Logo -->
      <a href="/home" class="brand-link">
        <img src="{{ asset(rutaLogoTramiteSIP()) }}" alt="The Logo" class="brand-image elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light">&nbsp;</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{ asset('/img/profile.png') }}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/profile')">
                <strong>{{ strtoupper(Auth::user()->email) }}</strong>
            </a>
          </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if (Auth::user()->es_atc || Auth::user()->es_supervisor || Auth::user()->role_id == 3 || Auth::user()->role_id == 6)
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/crearCaso')">
                    <i class="nav-icon fas fa-plus blue"></i>
                    <p>Crear Caso</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/buscar_1582')">
                    <i class="nav-icon fa fa-list blue" aria-hidden="true"></i>
                    <p>LEY 1582</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/misCasos')">
                    <i class="nav-icon fas fa-inbox blue"></i>
                    <p> Mis Pendientes </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/buscarCasos')">
                    <i class="nav-icon fas fa-search blue"></i>
                    <p>Buscar Casos </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/registroPasaporte')">
                    <i class="nav-icon fas fa-user blue"></i>
                    <p>Registro de Pasaporte </p>
                </a>
            </li>
            @endif
            @if (Auth::user()->es_jefe)
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/cierreTramites')">
                    <i class="nav-icon fas fa-user blue"></i>
                    <p>Adm. Cierres</p>
                </a>
            </li>
            <li class="nav-item">
                  <router-link to="/cambioAgenciaUsers" class="nav-link">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                    <p>
                      Cambio Agencia Usuarios
                    </p>
                  </router-link>
                </li>
            @endif
            @if (Auth::user()->es_supervisor || Auth::user()->role_id == 2 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4 || Auth::user()->role_id == 6)
            <!-- *** Escritorio *** -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link animated-link">
                <i class="nav-icon fas fa-inbox blue"></i>
                <p>
                  Escritorio
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>

              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/dashboard')">
                        &nbsp; &nbsp; &nbsp;<i class="nav-icon fas fa-tachometer-alt blue"></i>
                        <p>KPIs</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/nodosTrabajos')">
                        &nbsp; &nbsp; &nbsp;<i class="nav-icon fas fa-inbox blue"></i>
                        <p>Nodos Trabajos</p>
                    </a>
                </li>
              </ul>
            </li>
            @endif
            @if (Auth::user()->es_supervisor || Auth::user()->role_id == 3 || Auth::user()->role_id == 4 || Auth::user()->role_id == 2 || Auth::user()->role_id == 6)
            <li class="nav-item has-treview">
              <a href="#" class="nav-link">
                <i class="nav -icon fas fa-cog green"></i>
                <p>Reportes
                <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li>
                    <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/reporteProceso')">
                        &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-copy green"></i>
                        <p>Reporte Procesos</p>
                    </a>
                </li>
              </ul>

              <ul class="nav nav-treeview">
                <li>
                    <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/reporte-legal')">
                        &nbsp; &nbsp; &nbsp;<i class="nav-icon fas fa-file-alt" style="color: #B06218;"></i>
                        <p>Reporte Legal</p>
                    </a>
                </li>
              </ul>

               <!-- <ul class="nav nav-treeview">
                <li>
                    <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/reporteJubilacion')">
                        &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-copy green"></i>
                        <p>Reporte jubilación</p>
                    </a>
                </li>
              </ul> -->
            </li>
            @endif
            @if (Auth::user()->role_id == 4)
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/buscarCasos')">
                    <i class="nav-icon fas fa-search blue"></i>
                    <p>Buscar Casos </p>
                </a>
            </li>
            @endif
            @if (Auth::user()->role_id == 2)
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/buscarCasos')">
                    <i class="nav-icon fas fa-search blue"></i>
                    <p>Buscar Casos </p>
                </a>
            </li>
            <!-- *** Diseño *** -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog green"></i>
                <p>
                  Diseño
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/procesos')">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-copy green"></i>
                    <p>Procesos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/actividades')">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon fas fa-list green"></i>
                    <p>Actividades</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/formularios')">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-file green"></i>
                    <p>Formularios</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/impresiones')">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon fa fa-print green"></i>
                    <p>Impresiones</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/modeladoFormularios')">
                    &nbsp; &nbsp; &nbsp;<i class="nav-icon fa fa-file green"></i>
                    <p>Diseñador Formularios</p>
                </a>
              </li>
              </ul>
            </li>
            <!-- *** Administracion *** -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog green"></i>
                <p>
                  Admin. Procesos
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
              <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/catalogos')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>>
                <p>Catálogos</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/nodos')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                <p>Nodos</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/users')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                <p>Usuarios</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/usersNodos')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                <p>Usuarios Nodos</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/nodosProcesos')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                <p>Nodos Procesos <small>(Creadores)</small></p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle green"></i>
                <p>Reasignar Casos</p>
            </a>
          </li>
              </ul>
            </li>
            <!-- *** Parametros *** -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog orange"></i>
                <p>
                  Parametros Procesos
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
              <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/actuaciones')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                <p>Actuaciones</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/tactividades')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                <p>Tipos Actividades</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/tformularios')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                <p>Tipos Formularios</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/estadosAvance')">
                &nbsp; &nbsp; &nbsp;<i class="nav-icon far fa-circle orange"></i>
                <p>Estados Avance</p>
            </a>
          </li>
              </ul>
            </li>
            @endif
            @if (Auth::user()->role_id == 3 || Auth::user()->role_id == 6)
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/notificacionFechas')">
                    <i class="nav-icon fa fa-calendar blue"></i>
                    <p>Notificación Fechas</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/notificacionComplementarios')">
                    <i class="nav-icon fa fa-paperclip blue"></i>
                    <p>Notificación Adjuntos</p>
                </a>
            </li>

            <!-- NOTIFICACIONES GENERAL -->
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/notificaciones')">
                    <i class="nav-icon fa fa-bell blue"></i>
                    <p>Bandeja de Notificaciones</p>
                </a>
            </li>

            <!-- BANDEJA DE NOTIFICACIONES UCPP -->
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/notificaciones_consultas')">
                    <i class="nav-icon fa fa-comments blue"></i>
                    <p>Bandeja de Consultas UCPP</p>
                </a>
            </li>
            @endif
            @if (Auth::user()->role_id == 5)
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/buscarCasos')">
                    <i class="nav-icon fas fa-search blue"></i>
                    <p>Buscar Casos </p>
                </a>
            </li>
            @endif
            @if (Auth::user()->role_id == 6)
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link animated-link" @click="closeModalAndRedirect('/buscarDocumentos')">
                    <i class="nav-icon fa fa-archive blue"></i>
                    <p>Buscar Documentos</p>
                </a>
            </li>
            @endif

            <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link">
                <i class="nav-icon fas fa-power-off red"></i>
                <p>{{ __('Logout') }}</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->

        <div style="position: absolute; bottom: 10px; width: 60%; font-size: 0.7em; text-align: center;">
            <span class="right badge badge-warning">
            @if (entorno() == 'CALIDAD')
                <strong style="animation: blink 1s infinite;"> {{ entorno() }}</strong>
            @elseif (entorno() == 'DESARROLLO')
                <span style="animation: blink 1s infinite;"> {{ entorno() }}</span>
            @elseif (entorno() == 'PRODUCCION')
                <span> {{ entorno() }}</span>
            @endif
            <br>
            <b>Versión</b> {{ version() }}
            </span>
            <p style="color:#ffffff;">{{ urlsggTest() }}</p>
        </div>
      </div>

      <!-- /.sidebar -->
    </aside>
    {{-- Content Wrapper. Contains page content --}}
    <div class="content-wrapper">
      {{-- Main content --}}
        <div class="content-header">
      <div class="content">
                <div class="row">
          <router-view></router-view>
                </div>
        </div>
      </div>
      {{-- /.content --}}
    </div>
    {{-- /.content-wrapper --}}
    {{-- Main Footer --}}
    <footer class="main-footer">
      {{-- To the right --}}
      <div class="float-right d-none d-sm-inline">
        TramiteSIP
      </div>
      {{-- Default to the left --}}
      <strong>GNT</strong>
    </footer>
  </div>
  {{-- ./wrapper --}}
  <script src="{{ asset('/js/app.js') }}"></script>

  <style scoped>
    .animated-link {
        transition: transform 0.3s ease, color 0.3s ease;
    }
    .animated-link:hover {
        transform: scale(1.1);
        color: #007bff;
    }
    .animated-link:hover .nav-icon {
        color: #007bff;
    }
    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
  </style>

</body>
</html>
