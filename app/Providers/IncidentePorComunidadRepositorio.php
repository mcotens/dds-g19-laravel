<?php

namespace App\Providers;
use App\Models\IncidentePorComunidad;
use App\Models\Usuario;
use LaravelDoctrine\ORM\Facades\EntityManager;

class IncidentePorComunidadRepositorio
{
    public function __construct()
    {
    }

    public function guardar(IncidentePorComunidad $incidentePorComunidad)
    {
        EntityManager::persist($incidentePorComunidad);
        EntityManager::flush();
    }

    public function buscarPorId(int $id)
    {
        return EntityManager::find(IncidentePorComunidad::class, $id);
    }

    public function actualizar(IncidentePorComunidad $incidentePorComunidad)
    {
        EntityManager::merge($incidentePorComunidad);
        EntityManager::flush();
    }

    public function eliminar(IncidentePorComunidad $incidentePorComunidad)
    {
        EntityManager::remove($incidentePorComunidad);
        EntityManager::flush();
    }

    public function buscarTodos()
    {
        return EntityManager::getRepository(IncidentePorComunidad::class)->findAll();
    }

    public function buscarPorIncidente(string $idIncidente)
    {
        $query = EntityManager::createQuery(
            "SELECT ipc FROM App\Models\IncidentePorComunidad ipc WHERE ipc.incidente = :idBuscado"
        )->setParameter("idBuscado", (int)$idIncidente);

        return $query->getResult();
    }

    public function incidentesComunidadDe(Usuario $usuario, $incidentes)
    {
        $comunidadesPersona = array_map(function ($membresia) {
            return $membresia->getComunidad();
        }, $usuario->getPersonaAsociada()->getMembresias()->getValues());

        $incidentesFiltrados = [];

        foreach ($comunidadesPersona as $comunidad) {
            foreach ($incidentes as $incidente) {
                if ($comunidad->tieneIncidente($incidente) && !array_reduce($incidentesFiltrados, function ($carry, $ipc) use ($incidente) {
                        return $carry || $ipc->getIncidente()->getId() === $incidente->getId();
                    }, false)) {
                    $incidentesFiltrados[] = $comunidad->obtenerIncidenteComunidad($incidente);
                }
            }
        }

        return $incidentesFiltrados;
    }
}
