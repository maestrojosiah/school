<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class
 *
 * @ORM\Table(name="class")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClassRepository")
 */
class Classs
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
     * @ORM\Column(name="class_number", type="string", length=10)
     */
    private $classNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="class_teacher", type="string", length=255)
     */
    private $classTeacher;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="classses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Student", mappedBy="classs")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity="Exam", mappedBy="classs")
     */
    private $exams;

    public function __toString() {
        return $this->classNumber." (".$this->classTeacher.")";
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
        $this->exams = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set classNumber
     *
     * @param string $classNumber
     *
     * @return Class
     */
    public function setClassNumber($classNumber)
    {
        $this->classNumber = $classNumber;

        return $this;
    }

    /**
     * Get classNumber
     *
     * @return string
     */
    public function getClassNumber()
    {
        return $this->classNumber;
    }

    /**
     * Set classTeacher
     *
     * @param string $classTeacher
     *
     * @return Class
     */
    public function setClassTeacher($classTeacher)
    {
        $this->classTeacher = $classTeacher;

        return $this;
    }

    /**
     * Get classTeacher
     *
     * @return string
     */
    public function getClassTeacher()
    {
        return $this->classTeacher;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Classs
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
     * Add student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Classs
     */
    public function addStudent(\AppBundle\Entity\Student $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \AppBundle\Entity\Student $student
     */
    public function removeStudent(\AppBundle\Entity\Student $student)
    {
        $this->students->removeElement($student);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Add exam
     *
     * @param \AppBundle\Entity\Exam $exam
     *
     * @return Classs
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
