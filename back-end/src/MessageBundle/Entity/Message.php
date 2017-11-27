<?php


namespace MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Message as BaseMessage;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @Groups({"message","thread","messageMeta","threadMeta"})
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Groups({"message","messageMeta","threadMeta"})
     * @ORM\ManyToOne(
     *   targetEntity="MessageBundle\Entity\Thread",
     *   inversedBy="messages"
     * )
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;

    /**
     * @Groups({"message","thread","messageMeta","threadMeta"})
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $sender;



    /**
     * @Groups({"message","thread","threadMeta"})
     * @ORM\OneToMany(
     *   targetEntity="MessageBundle\Entity\MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * @var MessageMetadata[]|Collection
     */
    protected $metadata;

    /**
     * @ORM\Column(name="filepath",type="string",length=255)
     */
    protected $filepath;

    /**
     * @var File
     */
    protected $file;


    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    public function setCreatedAt()
    {
        $this->createdAt = new \ DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \FOS\MessageBundle\Model\ThreadInterface
     */
    public function getThread(): \FOS\MessageBundle\Model\ThreadInterface
    {
        return $this->thread;
    }

    /**
     * @param \FOS\MessageBundle\Model\ThreadInterface $thread
     */
    public function setThread(\FOS\MessageBundle\Model\ThreadInterface $thread)
    {
        $this->thread = $thread;
    }

    /**
     * @return \FOS\MessageBundle\Model\ParticipantInterface
     */
    public function getSender(): \FOS\MessageBundle\Model\ParticipantInterface
    {
        return $this->sender;
    }

    /**
     * @param \FOS\MessageBundle\Model\ParticipantInterface $sender
     */
    public function setSender(\FOS\MessageBundle\Model\ParticipantInterface $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return Collection|MessageMetadata[]
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param Collection|MessageMetadata[] $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * @param string $filepath
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }



}