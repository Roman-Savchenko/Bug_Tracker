<?php
namespace Acme\BugBundle\Entity;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\Event\PreUpdateEventArgs;

/**
 * @ORM\Entity
 * @ORM\Table(name="issue")
 * @ORM\HasLifecycleCallbacks
 **/

class Issue implements GenerateCodeInterface
{
    const STATUS_DISABLED = 0;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=300)
     */
    protected $summary;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $code = self::STATUS_DISABLED;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length= 10)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length= 15)
     */
    protected $priority;

    /**
     * @ORM\Column(type="string",length= 15)
     */

    protected $status;

    /**
     * @ORM\Column(type="text", options={"default":0})
     */
    protected $resolution= self::STATUS_DISABLED;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="issue")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="issue")
     */
    public $activitys;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="issues_reporter")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id")
     */
    protected $reporter;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="issues_assignee")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id")
     */
    protected $assignee;

    /**
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="issues")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @var User[] $children
     *
     * @ORM\ManyToMany(targetEntity="Acme\BugBundle\Entity\User", inversedBy="assignedIssue")
     * @ORM\JoinTable(name="issue_collaborator")
     **/
    protected $collaborators;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="issues")
     */
    protected $users;

    public function __construct()
    {
        $this->activitys = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->children = new ArrayCollection();
    }



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
     * Set summary
     *
     * @param string $summary
     *
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Issue
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set priority
     *
     * @param string $priority
     *
     * @return Issue
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Issue
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
     * Set resolution
     *
     * @param string $resolution
     *
     * @return Issue
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Issue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set reporter
     *
     * @param \Acme\BugBundle\Entity\User $reporter
     *
     * @return Issue
     */
    public function setReporter(\Acme\BugBundle\Entity\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \Acme\BugBundle\Entity\User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set assignee
     *
     * @param \Acme\BugBundle\Entity\User $assignee
     *
     * @return Issue
     */
    public function setAssignee(\Acme\BugBundle\Entity\User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \Acme\BugBundle\Entity\User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Set parent
     *
     * @param \Acme\BugBundle\Entity\Issue $parent
     *
     * @return Issue
     */
    public function setParent(\Acme\BugBundle\Entity\Issue $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Acme\BugBundle\Entity\Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Acme\BugBundle\Entity\Issue $child
     *
     * @return Issue
     */
    public function addChild(\Acme\BugBundle\Entity\Issue $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Acme\BugBundle\Entity\Issue $child
     */
    public function removeChild(\Acme\BugBundle\Entity\Issue $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set project
     *
     * @param \Acme\BugBundle\Entity\Project $project
     *
     * @return Issue
     */
    public function setProject(\Acme\BugBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Acme\BugBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add user
     *
     * @param \Acme\BugBundle\Entity\User $user
     *
     * @return Issue
     */
    public function addUser(\Acme\BugBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Acme\BugBundle\Entity\User $user
     */
    public function removeUser(\Acme\BugBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add comment
     *
     * @param \Acme\BugBundle\Entity\Comment $comment
     *
     * @return Issue
     */
    public function addComment(\Acme\BugBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Acme\BugBundle\Entity\Comment $comment
     */
    public function removeComment(\Acme\BugBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add activity
     *
     * @param \Acme\BugBundle\Entity\Activity $activity
     *
     * @return Issue
     */
    public function addActivity(\Acme\BugBundle\Entity\Activity $activity)
    {
        $this->activitys[] = $activity;

        return $this;
    }

    /**
     * Remove activity
     *
     * @param \Acme\BugBundle\Entity\Activity $activity
     */
    public function removeActivity(\Acme\BugBundle\Entity\Activity $activity)
    {
        $this->activitys->removeElement($activity);
    }

    /**
     * Get activitys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivitys()
    {
        return $this->activitys;
    }

    /**
     * Add collaborator
     *
     * @param \Tracker\UserBundle\Entity\User $collaborator
     *
     * @return Issue
     */
    public function addCollaborator(\Tracker\UserBundle\Entity\User $collaborator)
    {
        $this->collaborators[] = $collaborator;

        return $this;
    }

    /**
     * Remove collaborator
     *
     * @param \Tracker\UserBundle\Entity\User $collaborator
     */
    public function removeCollaborator(\Tracker\UserBundle\Entity\User $collaborator)
    {
        $this->collaborators->removeElement($collaborator);
    }

    /**
     * Get collaborators
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }
}
