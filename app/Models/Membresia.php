<?php

namespace App\Models;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Lombok\Getter;
use Lombok\Setter;
/**
 * @ORM\Entity
 * @ORM\Table(name="membresias")
 */

class Membresia extends \Lombok\Helper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Persona")
     */
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="Comunidad")
     */
    private $comunidad;

    /**
     * @ORM\ManyToMany(targetEntity="ServicioPrestado")
     */
    private $serviciosObservados;

    public function __construct()
    {
        parent::__construct();
        $this->serviciosObservados = new ArrayCollection();
    }
    public function __destruct()
    {
        parent::__destruct();
    }

    public function getComunidad(){
        return $this->comunidad;
    }

    public function agregarServicioObservado($servicioPrestado)
    {
        $this->serviciosObservados[] = $servicioPrestado;
    }

    public function agregarServicioAfectado($servicioPrestado)
    {
        $key = array_search($servicioPrestado, $this->serviciosObservados->toArray(), true);
        if ($key !== false) {
            unset($this->serviciosObservados[$key]);
        }
    }

    public function estaAfectado($servicioPrestado)
    {
        return !in_array($servicioPrestado, $this->serviciosObservados->toArray(), true);
    }
}
