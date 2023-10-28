<?php

namespace App\Models;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="establecimientos")
 */
class Establecimiento
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="ServicioPrestado", mappedBy="establecimiento", cascade={"all"})
     */
    private $serviciosPrestados;

    /**
     * @ORM\ManyToOne(targetEntity="Entidad")
     */
    private $entidad;


    public function __construct()
    {
        $this->serviciosPrestados = new ArrayCollection();
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getEntidad(){
        return $this->entidad;
    }

    public function agregarServicio($servicio)
    {
        $servicioPrestado = new ServicioPrestado($servicio);
        $servicioPrestado->setEstablecimiento($this);
        $this->serviciosPrestados[] = $servicioPrestado;
        return $servicioPrestado;
    }

    public function eliminarServicioPrestado($servicioPrestado)
    {
        $this->serviciosPrestados->removeElement($servicioPrestado);
    }

    public function eliminarServicio($servicio)
    {
        foreach ($this->serviciosPrestados as $servicioPrestado) {
            if ($servicioPrestado->getServicio()->getId() == $servicio->getId()) {
                $this->serviciosPrestados->removeElement($servicioPrestado);
            }
        }
    }

    public function serviciosPrestadosDelServicio($servicio)
    {
        return $this->serviciosPrestados->filter(function ($servicioPrestado) use ($servicio) {
            return $servicioPrestado->getServicio()->getId() == $servicio->getId();
        });
    }

}
