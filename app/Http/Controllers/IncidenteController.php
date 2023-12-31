<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Providers\ComunidadRepositorio;
use App\Providers\IncidentePorComunidadRepositorio;
use App\Providers\IncidenteRepositorio;
use App\Providers\UsuarioRepositorio;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\Setup;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Doctrine\ORM\EntityManager;
use App\Models\Incidente;

class IncidenteController extends Controller
{
    protected UsuarioRepositorio $repoUsuario;
    protected IncidenteRepositorio $repoIncidente;
    protected IncidentePorComunidadRepositorio $repoIncidenteComunidad;
    protected ComunidadRepositorio $repoComunidad;

    public function __construct() {
        $this->repoIncidenteComunidad = new IncidentePorComunidadRepositorio();
        $this->repoIncidente = new IncidenteRepositorio();
        $this->repoUsuario = new UsuarioRepositorio();
        $this->repoComunidad = new ComunidadRepositorio();
    }

    public function index(Request $request)
    {
        $model = [];

        if($request->input('tipo') != null){
            $model['msg'] = ['tipo' => $request->input('tipo'),
                'texto' => $request->input('msg')];
        }

        if($request->input('adminPlataforma') != null){
            $model['adminPlataforma'] = true;
        }

        $paramEstado = $request->input("estado");
        $usuario = $this->repoUsuario->buscarPorId(intval($request->input('idUsuario')));
        $comunidad = $this->repoComunidad->buscarPorId(intval($request->input('idComunidad')));

        if($usuario != null){
            $model['userActual'] = $usuario;
        }

        if($usuario != null) {
            if ($paramEstado !== null) {
                if($paramEstado == "paraRevision"){
                    return "paraRevision";
                }else{
                    $incidentesPorComunidad = $this->repoIncidente->incidentesDeEstado($paramEstado, $comunidad->getIncidentes());
                }
            } else {
                $incidentesPorComunidad = $comunidad->getIncidentes();
            }
        }else{
            $incidentesPorComunidad = array();
        }

        $model['incidentesPorComunidad'] = $incidentesPorComunidad;

        return view('listaIncidentes', $model);
    }
}
