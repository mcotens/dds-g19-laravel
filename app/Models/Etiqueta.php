<?php

namespace App\Models;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="etiquetas")
 */
class Etiqueta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @ORM\Column(name="valor", type="string", length=255)
     */
    private $valor;

    public function __construct($tipo, $valor)
    {
        $this->tipo = $tipo;
        $this->valor = $valor;
    }
}
