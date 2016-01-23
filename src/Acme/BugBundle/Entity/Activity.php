<?php

namespace Acme\BugBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="activity")
 **/
class Activity
{
    const STATUS_DISABLED = 0;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $change_status;

    /**
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="activitys")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id")
     */
    protected $issue;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set changeStatus
     *
     * @param \DateTime $changeStatus
     *
     * @return Activity
     */
    public function setChangeStatus($changeStatus)
    {
        $this->change_status = $changeStatus;

        return $this;
    }

    /**
     * Get changeStatus
     *
     * @return \DateTime
     */
    public function getChangeStatus()
    {
        return $this->change_status;
    }

    /**
     * Set issue
     *
     * @param \Acme\BugBundle\Entity\Issue $issue
     *
     * @return Activity
     */
    public function setIssue(\Acme\BugBundle\Entity\Issue $issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return \Acme\BugBundle\Entity\Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }
}
