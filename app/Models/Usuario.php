<?php

namespace App\Models;
use Doctrine\ORM\Mapping as ORM;
use Lombok\Getter;
use Lombok\Setter;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuarios")
 */
#[Setter, Getter]
class Usuario extends \Lombok\Helper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Persona")
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     */
    private $personaAsociada;

    public function __construct($username, $password)
    {
        parent::__construct();
        $this->username = $username;
        $this->password = $password;
    }

    public function getPersonaAsociada(){
        return $this->personaAsociada;
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}
