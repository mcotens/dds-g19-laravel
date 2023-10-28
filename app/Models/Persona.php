<?php

namespace App\Models;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Lombok\Getter;
use Lombok\Setter;
/**
 * @ORM\Entity
 * @ORM\Table(name="personas")
 */
#[Setter, Getter]
class Persona extends \Lombok\Helper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="apellido", type="string", length=255)
     */
    private $apellido;

    /**
     * @ORM\OneToMany(targetEntity="Membresia", mappedBy="persona", cascade={"all"})
     */
    private $membresias;

    public function __construct()
    {
        parent::__construct();
        $this->membresias = new ArrayCollection();
        $this->fechas = new ArrayCollection();
    }
    public function __destruct()
    {
        parent::__destruct();
    }

    public function getId(){
        return $this->id;
    }

    public function getMembresias(){
        return $this->membresias;
    }

    public function agregarFechaDeNotificacion($fecha)
    {
        $this->fechas[] = $fecha;
    }

    public function eliminarFechaDeNotificacion($fecha)
    {
        $this->fechas->removeElement($fecha);
    }

    public function agregarMembresia($membresia)
    {
        $this->membresias[] = $membresia;
        $membresia->getComunidad()->agregarMembresiaDirecto($membresia);
    }

    public function eliminarMembresia($membresia)
    {
        $this->membresias->removeElement($membresia);
        $membresia->getComunidad()->eliminarMembresiaDirecto($membresia);
    }
}
