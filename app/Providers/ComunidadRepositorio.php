<?php

namespace App\Providers;
use App\Models\Comunidad;
use App\Models\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

class ComunidadRepositorio
{

    public function __construct()
    {
    }

    public function guardar(Comunidad $comunidad)
    {
        EntityManager::persist($comunidad);
        EntityManager::flush();
    }

    public function buscarPorId(int $id) : ?Comunidad
    {
        return EntityManager::getRepository(Comunidad::class)->find($id);
    }

    public function actualizar(Usuario $usuario)
    {
        EntityManager::merge($usuario);
        EntityManager::flush();
    }

    public function eliminar(Usuario $usuario)
    {
        $usuario->setActivo(false);
        $this->actualizar($usuario);
    }

    public function buscarTodos()
    {
        return EntityManager::getRepository(Usuario::class)->findBy(['activo' => 1]);
    }

    public function buscarPorUsername($username)
    {
        return EntityManager::createQuery("SELECT u FROM App\Models\Usuario u WHERE u.activo = true AND u.username LIKE :usuarioBuscado")->setParameter('usuarioBuscado', '%' . $username . '%')->getResult();
    }
}
