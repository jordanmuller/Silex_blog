<?php

namespace Repository;

use Entity\Article;
use Entity\User;

class UserRepository extends RepositoryAbstract 
{
    protected function getTable() 
    {
        return 'user';
    }
    
    public function findByEmail($email) 
    {
        $dbUser = $this->db->fetchAssoc(
            "SELECT * FROM user WHERE email = :email",
            [
                ':email' => $email
            ]    
        );
        
        if(!empty($dbUser))
        {
            return $this->buildEntity($dbUser);
        }
    }
    
    public function save(User $user) 
    {
        // $ data, les données à enregister en BDD récupérer depuis les getters de l'objet Category
        $data = [
            'lastname' => $user->getLastname(),
            'firstname' => $user->getFirstname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
               ];
                
        
        // Si category a un id, on est en update
        $where = !empty($user->getId())
                 ? ['id' => $user->getId()]
                 // Sinon on insert
                 : null
            ;
        
        // Appel à la méthode de RepositoryAbstract qui EXECUTERA L'INSERTION OU LA MODIFICATION
        $this->persist($data, $where);
        
        if(empty($user->getId())) 
        {
            // On set l'id récupéré dans la BDD à l'objet category dont on a récupérer le name
            $user->setId($this->db->lastInsertId());
        }
    }    

    private function buildEntity(array $data)
    {
        $user = new User();
        
        $user 
            ->setId($data['id'])
            ->setLastname($data['lastname'])
            ->setFirstname($data['firstname'])
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setRole($data['role'])
        ;
        
        // Après on renvoie l'objet $user et ses valeurs
        return $user;
    }
}
