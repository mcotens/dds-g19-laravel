<?php

namespace App\Models;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="servicios")
 */
class Servicio {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

    /**
     * @ORM\ManyToMany(targetEntity="Etiqueta")
     * @ORM\JoinTable(name="servicio_etiqueta")
     */
    private $etiquetas;

    public function __construct(array $etiquetas = []) {
        $this->etiquetas = new ArrayCollection($etiquetas);
    }
    public function getNombre(){
        return $this->nombre;
    }

    public function agregarEtiqueta(Etiqueta $etiqueta) {
        $this->etiquetas->add($etiqueta);
    }

    public function eliminarEtiqueta(Etiqueta $etiqueta) {
        $this->eliminarEtiquetaPorID($etiqueta->getId());
    }

    public function getStringEtiquetas() {
        $listaEtiquetas = $this->etiquetas->map(function ($etiqueta) {
            return $etiqueta->getValor();
        })->toArray();

        $texto = $this->nombre . " | " . implode(" - ", $listaEtiquetas);
        return $texto;
    }

    private function eliminarEtiquetaPorID(int $id) {
        $this->etiquetas->filter(function ($etiqueta) use ($id) {
            return $etiqueta->getId() == $id;
        })->forAll(function ($key, $etiqueta) {
            $this->etiquetas->removeElement($etiqueta);
        });
    }
}
