<?php

namespace Entity;

class Category {
    /**
     *
     * @var int
     */
    private $id;
    
    /**
     *
     * @var string
     */
    private $name;
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
        // setter fluent permet de chaîner les appels grâce au return $this;
        // On pourra écrire $objet->setName()->setId(), impossible si l'on a pas flécher les appels
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }
}

