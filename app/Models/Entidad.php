<?php

namespace App\Models;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="entidades")
 */
class Entidad
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Establecimiento", mappedBy="entidad", cascade={"persist", "remove"})
     */
    protected $establecimientos;

    public function getId(){
        return $this->id;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function esAdministradaPor($usuario)
    {
        return $this->prestadora->getPersonaAInformar()->getId() == $usuario->getPersonaAsociada()->getId() ||
            $this->prestadora->getOrganismoControl()->getPersonaAInformar()->getId() == $usuario->getPersonaAsociada()->getId();
    }

    public function agregarEstablecimiento($establecimiento)
    {
        $establecimiento->setEntidad($this);
        $this->establecimientos[] = $establecimiento;
    }

    public function eliminarEstablecimiento($establecimiento)
    {
        $this->eliminarEstablecimientoPorID($establecimiento->getId());
    }

    public function eliminarEstablecimientoPorID($id)
    {
        $this->establecimientos = array_filter(
            $this->establecimientos,
            function ($est) use ($id) {
                return $est->getId() != $id;
            }
        );
    }

    public function agregarEstablecimientoEnPosicion($establecimiento, $posicion)
    {
        array_splice($this->establecimientos, $posicion, 0, $establecimiento);
    }

    public function getPrimero()
    {
        return $this->establecimientos[0];
    }

    public function getUltimo()
    {
        return $this->establecimientos[count($this->establecimientos) - 1];
    }

    private function getEstablecimientosEnLocacion($ubicacion)
    {
        $listaRetornar = [];

        if ($ubicacion->getMetadato()->getProvincia() !== null) {
            $provinciaId = $ubicacion->getMetadato()->getProvincia()->getId();
            $establecimientosProvincia = array_filter(
                $this->establecimientos,
                function ($est) use ($provinciaId) {
                    return $est->getUbicacion()->getMetadato()->getProvincia()->getId() == $provinciaId;
                }
            );
            $listaRetornar = array_merge($listaRetornar, $establecimientosProvincia);
        }

        if ($ubicacion->getMetadato()->getLocalidad() !== null) {
            $localidadId = $ubicacion->getMetadato()->getLocalidad()->getId();
            $establecimientosLocalidad = array_filter(
                $this->establecimientos,
                function ($est) use ($localidadId) {
                    return $est->getUbicacion()->getMetadato()->getLocalidad()->getId() == $localidadId;
                }
            );
            $listaRetornar = array_merge($listaRetornar, $establecimientosLocalidad);
        }

        return $listaRetornar;
    }
}
