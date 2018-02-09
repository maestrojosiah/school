<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 */
class Student
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
     * @ORM\Column(name="f_name", type="string", length=255)
     */
    private $fName;

    /**
     * @var string
     *
     * @ORM\Column(name="l_name", type="string", length=255)
     */
    private $lName;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255)
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="age", type="string", length=255)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="students")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Classs", inversedBy="students")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $classs;

    /**
     * @ORM\OneToMany(targetEntity="Exam", mappedBy="student")
     */
    private $exams;

    /**
     * @ORM\OneToMany(targetEntity="Attendance", mappedBy="student")
     */
    private $attendances;

    /**
     * @ORM\OneToMany(targetEntity="BookMovement", mappedBy="owner")
     */
    private $bookMovements;

    public function __toString() {
        return $this->fName . " " . $this->lName;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attendances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->exams = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bookMovements = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fName
     *
     * @param string $fName
     *
     * @return Student
     */
    public function setFName($fName)
    {
        $this->fName = $fName;

        return $this;
    }

    /**
     * Get fName
     *
     * @return string
     */
    public function getFName()
    {
        return $this->fName;
    }

    /**
     * Set lName
     *
     * @param string $lName
     *
     * @return Student
     */
    public function setLName($lName)
    {
        $this->lName = $lName;

        return $this;
    }

    /**
     * Get lName
     *
     * @return string
     */
    public function getLName()
    {
        return $this->lName;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Student
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set age
     *
     * @param string $age
     *
     * @return Student
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return string
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Student
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
     * Set classs
     *
     * @param string $classs
     *
     * @return Student
     */
    public function setClasss($classs)
    {
        $this->classs = $classs;

        return $this;
    }

    /**
     * Get classs
     *
     * @return string
     */
    public function getClasss()
    {
        return $this->classs;
    }

    /**
     * Add attendance
     *
     * @param \AppBundle\Entity\Attendance $attendance
     *
     * @return Student
     */
    public function addAttendance(\AppBundle\Entity\Attendance $attendance)
    {
        $this->attendances[] = $attendance;

        return $this;
    }

    /**
     * Remove attendance
     *
     * @param \AppBundle\Entity\Attendance $attendance
     */
    public function removeAttendance(\AppBundle\Entity\Attendance $attendance)
    {
        $this->attendances->removeElement($attendance);
    }

    /**
     * Get attendances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttendances()
    {
        return $this->attendances;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Student
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Add exam
     *
     * @param \AppBundle\Entity\Exam $exam
     *
     * @return Student
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

    /**
     * Add bookMovement
     *
     * @param \AppBundle\Entity\BookMovement $bookMovement
     *
     * @return Student
     */
    public function addBookMovement(\AppBundle\Entity\BookMovement $bookMovement)
    {
        $this->bookMovements[] = $bookMovement;

        return $this;
    }

    /**
     * Remove bookMovement
     *
     * @param \AppBundle\Entity\BookMovement $bookMovement
     */
    public function removeBookMovement(\AppBundle\Entity\BookMovement $bookMovement)
    {
        $this->bookMovements->removeElement($bookMovement);
    }

    /**
     * Get bookMovements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookMovements()
    {
        return $this->bookMovements;
    }
}
