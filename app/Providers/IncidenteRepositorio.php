<?php

namespace App\Providers;
use App\Models\Incidente;
use App\Models\Persona;
use Doctrine\ORM\EntityManagerInterface;
use LaravelDoctrine\ORM\Facades\EntityManager;

class IncidenteRepositorio
{

    public function __construct()
    {
    }

    public function guardar(Incidente $incidente)
    {
        EntityManager::persist($incidente);
        EntityManager::flush();
    }

    public function buscarPorId(int $id)
    {
        return EntityManager::find(Incidente::class, $id);
    }

    public function actualizar(Incidente $incidente)
    {
        EntityManager::merge($incidente);
        EntityManager::flush();
    }

    public function eliminar(Incidente $incidente)
    {
        EntityManager::remove($incidente);
        EntityManager::flush();
    }

    public function buscarTodos()
    {
        return EntityManager::getRepository(Incidente::class)->findAll();
    }

    public function incidentesDeEstado(string $estado, int $idPersona)
    {
        $incidentePorComunidadRepositorio = new IncidentePorComunidadRepositorio();
        $personaRepositorio = EntityManager::getRepository(Persona::class);

        $incidentesTotales = $this->buscarTodos();

        if ($estado === "paraRevision") {
            $persona = $personaRepositorio->find($idPersona);
            $evaluadorSolicitudRevision = new EvaluadorSolicitudRevision(new CalculadoraDistanciaEnMetros());
            $incidentesPorComunidadTotales = $evaluadorSolicitudRevision->obtenerIncidentesCercanos($persona);

            $incidentesRepetidos = array_map(function ($ipc) {
                return $ipc->getIncidente();
            }, $incidentesPorComunidadTotales);

            $incidentesFinal = array_unique($incidentesRepetidos);

            return $incidentesFinal;
        }

        $incidentesResultado = [];

        switch ($estado) {
            case "abierto":
                foreach ($incidentesTotales as $incidente) {
                    $incidentesPorComunidad = $incidentePorComunidadRepositorio->buscarPorIncidente((string)$incidente->getId());
                    if (array_reduce($incidentesPorComunidad, function ($carry, $ipc) {
                        return $carry || !$ipc->isEstaCerrado();
                    }, false)) {
                        $incidentesResultado[] = $incidente;
                    }
                }
                break;
            case "cerrado":
                foreach ($incidentesTotales as $incidente) {
                    $incidentesPorComunidad = $incidentePorComunidadRepositorio->buscarPorIncidente((string)$incidente->getId());
                    if (array_reduce($incidentesPorComunidad, function ($carry, $ipc) {
                        return $carry && $ipc->isEstaCerrado();
                    }, true)) {
                        $incidentesResultado[] = $incidente;
                    }
                }
                break;
        }

        return $incidentesResultado;
    }
}
