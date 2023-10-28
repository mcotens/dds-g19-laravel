<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Lombok\Getter;
use Lombok\Setter;

/**
 * @ORM\Entity
 * @ORM\Table(name="incidentes_por_comunidad")
 */
#[Setter, Getter]
class IncidentePorComunidad extends \Lombok\Helper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Incidente")
     * @ORM\JoinColumn(name="incidente_id", referencedColumnName="id")
     */
    private $incidente;

    /**
     * @ORM\Column(name="esta_cerrado", type="boolean")
     */
    private $estaCerrado = false;

    /**
     * @ORM\Column(name="fecha_hora_cierre", type="datetime")
     */
    private $fechaHoraCierre;

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="autor_cierre_id", referencedColumnName="id")
     */
    private $autorCierre;

    /**
     * @ORM\ManyToOne(targetEntity="Comunidad")
     */
    private $comunidad;

    public function __construct($incidente)
    {
        parent::__construct();
        $this->incidente = $incidente;
    }
    public function __destruct()
    {
        parent::__destruct();
    }

    public function getIncidente(){
        return $this->incidente;
    }

    public function isEstaCerrado(){
        return $this->estaCerrado;
    }
}
