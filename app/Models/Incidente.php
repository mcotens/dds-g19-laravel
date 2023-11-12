<?php

namespace App\Models;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Lombok\Getter;
use Lombok\Setter;

/**
 * @ORM\Entity
 * @ORM\Table(name="incidentes")
 */
#[Setter, Getter]
class Incidente extends \Lombok\Helper {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToMany(targetEntity="ServicioPrestado")
     * @ORM\JoinTable (name="incidentes_servicios_prestados",
     *     joinColumns={@ORM\JoinColumn(name="incidente_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="serviciosafectados_id", referencedColumnName="id")})
     */
    private $serviciosAfectados;

    /**
     * @ORM\Column(name="observaciones", type="string")
     */
    private string $observaciones;

    /**
     * @ORM\Column(name="fecha_hora_apertura", type="datetime", columnDefinition="TIMESTAMP")
     */
    private DateTime $fechaHoraApertura;

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="autor_apertura_id", referencedColumnName="id")
     */
    private Persona $autorApertura;

    /**
     * @ORM\Column(name="estaCerrado", type="boolean")
     */
    private bool $estaCerrado;

    public function __construct()
    {
        $this->estaCerrado = false;
        parent::__construct();
    }
    public function __destruct()
    {
        parent::__destruct();
    }

    public function getId(){
        return $this->id;
    }

    public function isEstaCerrado(){
        return $this->estaCerrado;
    }
    public function getServiciosAfectados(){
        return $this->serviciosAfectados;
    }

    public function actualizarEstado($incidentesPorComunidad){

        foreach ($incidentesPorComunidad as $i){
            if(!$i->estaCerrado()){
                return;
            }
        }
        $this->estaCerrado = true;
    }
    public function obtenerEntidad(){
        return $this->serviciosAfectados[0].getEstablecimiento().getEntidad();
    }
    public function agregarServiciosPrestados(array $serviciosPrestados){
        $this->serviciosAfectados = array_merge($this->serviciosAfectados, $serviciosPrestados);
    }
}
