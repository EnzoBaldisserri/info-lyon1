<?php

namespace App\Entity\Schedule;

class Lesson {
    /**
     * Name of the lesson
     * @var string
     */
    private $name;

    /**
     * Beginning of the lesson
     * @var DateTime
     */
    private $startDate;

    /**
     * End of the lesson
     * @var DateTime
     */
    private $endDate;

    /**
     * Groups that must attend the lesson
     * @var array
     */
    private $groups;

    /**
     * Teachers teaching the lesson
     * @var array
     */
    private $teachers;

    /**
     * Rooms where the lesson is happening
     * @var array
     */
    private $rooms;

    /**
     * Get the name of the lesson
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of the lesson
     *
     * @param string name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the beginning of the lesson
     *
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set the beginning of the lesson
     *
     * @param DateTime startDate
     *
     * @return self
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get the end of the lesson
     *
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the end of the lesson
     *
     * @param DateTime endDate
     *
     * @return self
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get the groups that must attend the lesson
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set the groups that must attend the lesson
     *
     * @param array groups
     *
     * @return self
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get the teachers teaching the lesson
     *
     * @return array
     */
    public function getTeachers()
    {
        return $this->teachers;
    }

    /**
     * Set the teachers teaching the lesson
     *
     * @param array teachers
     *
     * @return self
     */
    public function setTeachers(array $teachers)
    {
        $this->teachers = $teachers;

        return $this;
    }

    /**
     * Get the rooms where the lesson is happening
     *
     * @return array
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set the rooms where the lesson is happening
     *
     * @param array rooms
     *
     * @return self
     */
    public function setRooms(array $rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

}
