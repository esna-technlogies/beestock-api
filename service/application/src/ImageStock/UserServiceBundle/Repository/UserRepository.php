<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 24/02/2017
 * Time: 12:06 AM
 */

namespace ImageStock\UserServiceBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use ImageStock\UserServiceBundle\Document\User;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserRepository
 * @package UserServiceBundle\Repository
*/
class UserRepository extends DocumentRepository
{
    /**
     * @param int $limit
     * @return mixed
     */
    public function findAllOrderedByName($limit = 10)
    {
        return $this->createQueryBuilder()
            ->sort('firstName', 'ASC')
            ->limit($limit)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param $name
     * @param int $limit
     * @return mixed
     */
    public function findAllByNameOrderedByName($name, $limit = 10)
    {
        return $this->createQueryBuilder()
            ->field('fullName')->equals(new \MongoDB\BSON\Regex($name, 'i'))
            ->sort('fullName', 'ASC')
            ->limit($limit)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param $id
     * @return null|object
     */
    public function findById($id)
    {
        $user = parent::find($id);

        if(is_null($user)){
            $errorMessage = sprintf(
                'No user was found with id: %s ',
                $id
            );

            throw new Exception($errorMessage, Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @param $name
     * @return null|object
     */
    public function findOneByName($name)
    {
        $user = parent::findOneBy(['firstName' => $name]);

        if(is_null($user)){
            $errorMessage = sprintf(
                'No user was found with name: %s ',
                $name
            );

            throw new Exception($errorMessage, Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user)
    {
        $this->dm->persist($user);
        $this->dm->flush();
    }

    /**
     * @param User $user
     */
    public function delete(User $user)
    {
        $this->dm->remove($user);
        $this->dm->flush();
    }
}