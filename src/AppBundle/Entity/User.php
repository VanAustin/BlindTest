<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    /**
     * @ORM\OneToMany(targetEntity="Liste", mappedBy="user", cascade={"persist"})
     */
    private $listes;

    /**
   * @ORM\OneToMany(targetEntity="Task", mappedBy="author")
   */
    private $tasks;

    public function __construct()
    {
        parent::__construct();
        $this->listes = new ArrayCollection;
        $this->tasks = new ArrayCollection;
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
        $liste->setUser($this);
        $this->listes[] = $liste;
    }

    public function removeListe(Liste $liste)
    {
        $this->listes->removeElement($liste);
    }

    public function __toString() {
        return $this->getUsername();
    }

    /**
     * Add Task
     *
     * @param \AppBundle\Entity\Task $task
     *
     * @return User
     */
    public function addTask(Task $task)
    {
        $this->tasks[] = $task;
        return $this;
    }

    /**
     * Remove Task
     *
     * @param \AppBundle\Entity\Task $task
     */
    public function removeTask(Task $task)
    {
        $this->tasks->removeElement($task);
    }
    
    /**
     * Get the value of Questions
     *
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
