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

    private static function vincentyGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    public function incidentesDeEstado(string $estado, int $idPersona)
    {
        $incidentePorComunidadRepositorio = new IncidentePorComunidadRepositorio();

        $incidentesTotales = $this->buscarTodos();

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
