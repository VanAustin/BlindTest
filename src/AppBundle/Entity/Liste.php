<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *@ORM\Entity
 *@ORM\Table(name="liste")
 */
class Liste
{
    /**
     *@ORM\Column(type="integer")
     *@ORM\Id
     *@ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="listes")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $category;
    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="listes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $task;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="listes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Get the value of Category
     *
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Set the value of Category
     *
     * @param mixed category
     *
     * @return self
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }
    /**
     * Get the value of Task
     *
     * @return mixed
     */
    public function getTask()
    {
        return $this->task;
    }
    /**
     * Set the value of task
     *
     * @param mixed task
     *
     * @return self
     */
    public function setTask($task)
    {
        $this->task = $task;
        return $this;
    }
    /**
     * Get the value of User
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of User
     *
     * @param mixed user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}
