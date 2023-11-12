<?php

namespace App\Models;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="servicios_prestados")
 */
class ServicioPrestado {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Servicio")
     * @ORM\JoinColumn(name="servicio_id", referencedColumnName="id")
     */
    private $servicio;

    /**
     * @ORM\ManyToOne(targetEntity="Establecimiento")
     * @ORM\JoinColumn(name="establecimiento_id", referencedColumnName="id")
     */
    private $establecimiento;

    public function __construct(Servicio $servicio) {
        $this->servicio = $servicio;
    }
    public function getServicio(){
        return $this->servicio;
    }

    public function getEstablecimiento(){
        return $this->establecimiento;
    }
}
