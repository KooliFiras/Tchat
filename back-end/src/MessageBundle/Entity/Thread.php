<?php


namespace MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Thread as BaseThread;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */
class Thread extends BaseThread
{
    /**
     * @Groups({"message","thread","messageMeta","threadMeta"})
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Groups({"message","thread","messageMeta","threadMeta"})
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $createdBy;

    /**
     * @Groups({"thread","messageMeta","threadMeta"})
     * @ORM\OneToMany(
     *   targetEntity="MessageBundle\Entity\Message",
     *   mappedBy="thread"
     * )
     * @var Message[]|Collection
     */
    protected $messages;

    /**
     * @Groups({"message","thread","messageMeta"})
     * @ORM\OneToMany(
     *   targetEntity="MessageBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @var ThreadMetadata[]|Collection
     */
    protected $metadata;



}