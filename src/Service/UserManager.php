<?php

namespace Service;

use Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

class UserManager 
{
    // On va avoir besoin de $_SESSION
    private $session;
    
    public function __construct(Session $session) 
    {
        $this->session = $session;
    }
    
    public function encodePassword($plainPassword) 
    {
        // PASSWORD_BCRYPT est l'algorithme de hashage (PASSWORD_DEFAULT revient au même car il utilise PASSWORD_BCRYPT)
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }
    
    /**
     * 
     * @param string $plainPassword, le mot de passe saisi par l'utilisateur dans le formulaire sur login.html
     * @param string $encodedPassword, le mdp encodé dans la BDD
     * @return bool
     * 
     */
    public function verifyPassword($plainPassword, $encodedPassword) 
    {
        return password_verify($plainPassword, $encodedPassword);
    }
    
    /**
     * 
     * @param User $user
     */
    public function login(User $user) 
    {
        // $_SESSION['user'] = $user
        $this->session->set('user', $user);
    }
    
    public function logout() 
    {
        // equivalent de unset($_SESSION['user'])
        $this->session->remove('user');
    }
    
    /**
     * 
     * @return User|null, si user n'est pas trouvé la métohde renvoie null, équivalent de utilisateur_connecte() en procédural
     */
    public function getUser() 
    {
        return $this->session->get('user');
    }
    
    public function getUserName() 
    {
        if($this->session->has('user'))
        {
            return $this->session->get('user')->getFullName();
        }
        
        return '';
    }
    
    
    
    public function isAdmin() 
    {
        // méthode isAdmin() de Entity\User
        return $this->session->has('user') && $this->session->get('user')->isAdmin();
    }
    
    
}
