<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChildSubject
 *
 * @ORM\Table(name="child_subject")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChildSubjectRepository")
 */
class ChildSubject
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subject_title", type="string", length=255)
     */
    private $subjectTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="out_of", type="string", length=255)
     */
    private $outOf;

    /**
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="childSubjects")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="childSubjects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Exam", mappedBy="childSubject")
     */
    private $exams;

    public function __construct()
    {
        $this->exams = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->subjectTitle;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set subjectTitle
     *
     * @param string $subjectTitle
     *
     * @return ChildSubject
     */
    public function setSubjectTitle($subjectTitle)
    {
        $this->subjectTitle = $subjectTitle;

        return $this;
    }

    /**
     * Get subjectTitle
     *
     * @return string
     */
    public function getSubjectTitle()
    {
        return $this->subjectTitle;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return ChildSubject
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set outOf
     *
     * @param string $outOf
     *
     * @return ChildSubject
     */
    public function setOutOf($outOf)
    {
        $this->outOf = $outOf;

        return $this;
    }

    /**
     * Get outOf
     *
     * @return string
     */
    public function getOutOf()
    {
        return $this->outOf;
    }


    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Subject $parent
     *
     * @return ChildSubject
     */
    public function setParent(\AppBundle\Entity\Subject $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Subject
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return ChildSubject
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add exam
     *
     * @param \AppBundle\Entity\Exam $exam
     *
     * @return ChildSubject
     */
    public function addExam(\AppBundle\Entity\Exam $exam)
    {
        $this->exams[] = $exam;

        return $this;
    }

    /**
     * Remove exam
     *
     * @param \AppBundle\Entity\Exam $exam
     */
    public function removeExam(\AppBundle\Entity\Exam $exam)
    {
        $this->exams->removeElement($exam);
    }

    /**
     * Get exams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExams()
    {
        return $this->exams;
    }
}
