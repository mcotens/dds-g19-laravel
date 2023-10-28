<?php

namespace App\Models;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comunidades")
 */
class Comunidad {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(name="nombre", type="string", length=1000)
     */
    private $nombre;

    /**
     * @ORM\Column(name="detalle", type="string", length=1500)
     */
    private $detalle;

    /**
     * @ORM\ManyToMany(targetEntity="ServicioPrestado")
     */
    private $serviciosPrestados;

    /**
     * @ORM\OneToMany(mappedBy="comunidad", targetEntity="Membresia", cascade={"ALL"})
     */
    private $membresias;

    /**
     * @ORM\OneToMany(mappedBy="comunidad", targetEntity="IncidentePorComunidad", cascade={"PERSIST", "MERGE", "REMOVE"})
     * @ORM\JoinColumn(name="comunidad_id", referencedColumnName="id")
     */
    private $incidentes;

    public function __construct() {
        $this->serviciosPrestados = new ArrayCollection();
        $this->membresias = new ArrayCollection();
        $this->incidentes = new ArrayCollection();
    }

    public function getMembresia(Persona $persona) {
        $filteredMembresias = $this->membresias->filter(function ($m) use ($persona) {
            return $m->getPersona()->getId() == $persona->getId();
        });
        return $filteredMembresias->isEmpty() ? null : $filteredMembresias->first();
    }

    public function cerrarIncidente(Incidente $incidente, Persona $persona) {
        $matchingIncidentes = $this->incidentes->filter(function ($ipc) use ($incidente) {
            return $ipc->getIncidente() == $incidente;
        });

        $matchingIncidentes->forAll(function ($key, $ipc) use ($persona) {
            $ipc->setEstaCerrado(true);
            $ipc->setAutorCierre($persona);
            $ipc->setFechaHoraCierre(new \DateTime());
        });

        $this->notificarMiembros(new IncidenteCerrado($incidente));
    }
    public function tieneIncidente(Incidente $incidenteRec) {
        foreach ($this->incidentes as $incidente){
            if($incidente->getId() == $incidenteRec->getId()){
                return true;
            }
        }

        return false;
    }

    public function obtenerIncidenteComunidad(Incidente $incidente) {
        $matchingIncidentes = $this->incidentes->filter(function($ipc) use ($incidente) {
            return $ipc->getIncidente()->getId() == $incidente->getId();
        });

        return $matchingIncidentes->isEmpty() ? null : $matchingIncidentes->first();
    }
}
