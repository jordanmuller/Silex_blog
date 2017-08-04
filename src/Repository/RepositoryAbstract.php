<?php
namespace Repository;

use Silex\Application;

abstract class RepositoryAbstract 
{
    /**
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;
    
    //On a besoin de l'objet $app (notre apllication) car c'est lui qui contient l'indice ['db'] qui permet la connexion à la BDD
    // On passe cette connexion dans la probiété $db, initiée dans le constructeur et héritée par la classe CategoryRepository et toutes les classes dans Repository
    public function __construct(Application $app) 
    {
        $this->db = $app['db'];
    }
    
    //$data équivaut aux données insérées
    public function persist(array $data, array $where = null) 
    {
        if(is_null($where))
        {
            //insertion
            $this->db->insert($this->getTable(), $data);
        } else {
            //modification
            $this->db->update($this->getTable(), $data, $where);
        }
    }
    /**
     * Oblige les classes filles à définir cette méthode
     * qui renvoie le nom de la table à laquelle elles font référence
     */
    abstract protected function getTable();
}
