<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 *@ORM\Entity
 *@ORM\Table(name="category")
 */
class Category
{
    /**
     *@ORM\Column(type="integer")
     *@ORM\Id
     *@ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $categoryName;

    /**
     * @ORM\OneToMany(targetEntity="Liste", mappedBy="category")
     */
    private $listes;


    public function __construct()
    {
        $this->listes = new ArrayCollection;
    }

    /**
     * Get the value of Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Get the value of Category Name
     *
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }
    /**
     * Set the value of Category Name
     *
     * @param mixed categoryName
     *
     * @return self
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
        return $this;
    }
    /**
     * Get the value of Collections
     *
     * @return mixed
     */
    public function getListes()
    {
        return $this->listes;
    }
    public function addCollection(Liste $liste)
    {
        $this->listes[] = $liste;
        return $this;
    }
    public function removeListe(Liste $liste)
    {
        $this->listes->removeElement($liste);
    }
    public function __toString() {
        return $this->getCategoryName();
    }
}
