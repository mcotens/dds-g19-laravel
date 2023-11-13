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

    public function incidentesDeEstado(string $estado, $incidentesPorComunidad)
    {
        // Convert the PersistentCollection to an array
        $incidentesArray = $incidentesPorComunidad->toArray();

        switch ($estado) {
            case "cerrado":
                return array_filter($incidentesArray, function ($ipc) {
                    return $ipc->isEstaCerrado();
                });
            default:
                return array_filter($incidentesArray, function ($ipc) {
                    return !$ipc->isEstaCerrado();
                });
        }
    }

}
