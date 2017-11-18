<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *@ORM\Entity
 *@ORM\Table(name="task")
 */
class Task
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
    private $title;

    /**
    * @var string
    *
    * @ORM\Column(name="status", type="string", length=6, options={"default"="undone"})
    */
    private $status = 'undone';

    /**
     * @ORM\OneToMany(targetEntity="Liste", mappedBy="task", cascade={"persist"})
     */
    private $listes;

    /**
    * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
    */
    private $author;

    public function __construct()
    {
        $this->listes = new ArrayCollection();
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
     * Get the value of Title
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of Title
     *
     * @param mixed title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the value of Author
     *
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * Set the value of Author
     *
     * @param mixed author
     *
     * @return self
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get the value of Listes
     *
     * @return mixed
     */
    public function getListes()
    {
        return $this->listes;
    }
    public function addListe(Liste $liste)
    {
        $liste->setTask($this);
        $this->listes[] = $liste;
    }
    public function removeListe(Liste $liste)
    {
        $this->listes->removeElement($liste);
    }
}
