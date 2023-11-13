<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidentes</title>

    <!-- Bootstrap CSS Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS file -->
    <link href="/css/generales.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/resources/favicon.ico">
    <script src="/noAuth/obtenerUbicacion.js"></script>
</head>
<body class="d-flex flex-column">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/home"><b>MESTRAPEST</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="/home">Inicio</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdownIncidentes" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Incidentes
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownIncidentes">
                        <a class="dropdown-item" href="/incidentes">Listado</a>
                        <a class="dropdown-item" href="/aperturaIncidente">Apertura</a>
                        <a class "dropdown-item" href="/cierreIncidente">Cierre</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownEntidades" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Entidades
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownEntidades">
                        <a class="dropdown-item" href="/entidadesPrestadoras">Administracion</a>
                        <a class="dropdown-item" href="/rankings">Rankings</a>
                        <a class="dropdown-item" href="/cargaMasiva">Carga masiva</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/comunidades">Comunidades</a>
                </li>
                @if(isset($adminPlataforma))
                    <li class="nav-item">
                        <a class="nav-link" href="/usuarios">Usuarios</a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSesion" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Mi sesión
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownSesion">
                        @if(isset($userActual))
                            <a class="dropdown-item" href="/usuarios/{{ $userActual->getId() }}/edit">Mi perfil</a>
                            <a class="dropdown-item" href="/misComunidades">Mis comunidades</a>
                            <a class="dropdown-item" href="/usuarios/{{ $userActual->getId() }}/interes">Mis intereses</a>
                            <a class="dropdown-item" href="/logout">Cerrar sesión</a>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

@if(isset($msg))
    <div class="container mt-3">
        <div class="row justify-content-center d-flex">
            <div class="col-8">
                <div class="alert alert-{{ $msg['tipo'] }} alert-dismissible fade show" role="alert">
                    {{ $msg['texto'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Contenido principal -->
<div id="main-content">
    <div class="container mt-4 mb-4 altura-minima">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <h2 class="mb-3">Listado de Incidentes</h2>
                <form method="GET" action="/incidentes">
                    @csrf
                    <div class="row">
                        <label for="estado" class="form-label">Buscar incidente por estado</label>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6 col-sm-8">
                            <select class="form-select mb-3" name="estado" id="estado" required>
                                <option>Estado</option>
                                <option value="abierto">Abierto</option>
                                <option value="cerrado">Cerrado</option>
                                <option value="paraRevision">Para revisión</option>
                            </select>
                        </div>
                        <input type="hidden" name="latitud" id="latitud" value="">
                        <input type="hidden" name="longitud" id="longitud" value="">
                        <div class="col-sm-2">
                            <button class="btn btn-primary">Buscar</button>
                        </div>
                        @if(isset($userActual))
                            @unless(count($userActual->getPersonaAsociada()->getMembresias()) === 0)
                                <div class="col-sm-2">
                                    <a href="/aperturaIncidente" class="btn btn-success">Abrir incidente</a>
                                </div>
                            @endunless
                        @endif
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table align-middle table-sm table-striped table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col">Servicios</th>
                            <th scope="col">Entidad</th>
                            <th scope="col">Establecimiento</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($incidentesPorComunidad as $incidentePorComunidad)
                            @foreach($incidentePorComunidad->getIncidente()->getServiciosAfectados() as $servicio)
                                <tr>
                                    <td>{{ $servicio->getServicio()->getNombre() }}</td>
                                    @if($loop->first)
                                        <td rowspan="{{ count($incidentePorComunidad->getIncidente()->getServiciosAfectados()) }}">{{ $incidentePorComunidad->getIncidente()->getServiciosAfectados()[0]->getEstablecimiento()->getEntidad()->getNombre() }}</td>
                                        <td rowspan="{{ count($incidentePorComunidad->getIncidente()->getServiciosAfectados()) }}">{{ $incidentePorComunidad->getIncidente()->getServiciosAfectados()[0]->getEstablecimiento()->getNombre()}}</td>
                                        <td rowspan="{{ count($incidentePorComunidad->getIncidente()->getServiciosAfectados()) }}">
                                            @if($incidentePorComunidad->isEstaCerrado())
                                                <span class="badge bg-danger">Cerrado</span>
                                            @else
                                                <span class="badge bg-success">Abierto</span>
                                            @endif
                                        </td>
                                        <td rowspan="{{ count($incidentePorComunidad->getIncidente()->getServiciosAfectados()) }}" class="text-center">
                                            <form action="/cierreIncidente" method="post">
                                                <input type="hidden" name="incidente" value="{{ $incidentePorComunidad->getIncidente()->getId() }}">
                                                @unless($incidentePorComunidad->getIncidente()->isEstaCerrado())
                                                    <button type="submit" class="btn btn-sm btn-warning">Cerrar</button>
                                                @endunless
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-secondary text-white text-center py-3">
    <div class="container">
        2023 Grupo 19 - UTN FRBA, DDS Miércoles Noche.
    </div>
</footer>

<!-- Bootstrap JavaScript y Popper.js Links -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
