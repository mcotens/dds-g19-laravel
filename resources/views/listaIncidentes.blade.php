<!-- Contenido principal -->
<div id="main-content">
    <div class="container mt-4 mb-4 altura-minima">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <h2 class="mb-3">Listado de Incidentes</h2>
                <form method="GET" action="https://dds-g19-laravel.onrender.com/incidentes">
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
                                    <a href="https://dds-tpa-g19-main-app.onrender.com/aperturaIncidente" class="btn btn-success">Abrir incidente</a>
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
                                            <form action="https://dds-tpa-g19-main-app.onrender.com/cierreIncidente" method="post">
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
