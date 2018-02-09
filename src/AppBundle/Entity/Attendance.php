<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attendance
 *
 * @ORM\Table(name="attendance")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttendanceRepository")
 */
class Attendance
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
     * @var \DateTime
     *
     * @ORM\Column(name="on_date", type="date")
     */
    private $onDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="morning", type="boolean")
     */
    private $morning;

    /**
     * @var boolean
     *
     * @ORM\Column(name="afternoon", type="boolean")
     */
    private $afternoon;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="attendances")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $student;


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
     * Set onDate
     *
     * @param \DateTime $onDate
     *
     * @return Attendance
     */
    public function setOnDate($onDate)
    {
        $this->onDate = $onDate;

        return $this;
    }

    /**
     * Get onDate
     *
     * @return \DateTime
     */
    public function getOnDate()
    {
        return $this->onDate;
    }

    /**
     * Set morning
     *
     * @param integer $morning
     *
     * @return Attendance
     */
    public function setMorning($morning)
    {
        $this->morning = $morning;

        return $this;
    }

    /**
     * Get morning
     *
     * @return int
     */
    public function getMorning()
    {
        return $this->morning;
    }

    /**
     * Set afternoon
     *
     * @param integer $afternoon
     *
     * @return Attendance
     */
    public function setAfternoon($afternoon)
    {
        $this->afternoon = $afternoon;

        return $this;
    }

    /**
     * Get afternoon
     *
     * @return int
     */
    public function getAfternoon()
    {
        return $this->afternoon;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Attendance
     */
    public function setStudent(\AppBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
