<?php

namespace MessageBundle\Controller;



use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\MessageBundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use MessageBundle\Entity\Message;
use MessageBundle\Entity\MessageMetadata;
use MessageBundle\Entity\Thread;
use MessageBundle\Entity\ThreadMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Entity\User;

class MessageController extends FOSRestController implements ContainerAwareInterface, ClassResourceInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * Displays the authenticated participant inbox.
     * @Annotations\Get("/inbox")
     */
    public function getInboxAction()
    {

        $currentUserId= $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            '
            SELECT  tm2.id
            FROM MessageBundle:ThreadMetadata tm2
            WHERE tm2.id NOT IN
            (
            SELECT tm1.id
            FROM MessageBundle:ThreadMetadata tm1
            WHERE tm1.participant = :id  AND tm1.isDeleted =0) AND tm2.participant <> :id AND (tm2.lastParticipantMessageDate IS NOT NULL )
            GROUP BY tm2.participant
            ORDER BY tm2.participant desc
            
           ')->setParameter('id', $currentUserId);

        $threadMetadataId = $query->getResult();

        //   $threads = $this->getProvider()->getInboxThreads();
        // $serializer = $this->container->get('jms_serializer');
        //return $threads;
        $ids =array();
        foreach ($threadMetadataId as $param){
            $ids[]= $param['id'];
        }

        $query = $em->createQuery(
            '
            SELECT  u.id As participant_id, u.username AS username, tm2.lastParticipantMessageDate AS last_participant_message_date, m.body AS last_message, mm.isRead AS is_read
            FROM MessageBundle:ThreadMetadata tm2
            JOIN MessageBundle:Thread t with t.id = tm2.thread
            JOIN UserBundle:User u with u.id = tm2.participant
            JOIN MessageBundle:Message m with m.thread= t.id AND m.createdAt= tm2.lastParticipantMessageDate
            JOIN MessageBundle:MessageMetadata mm with mm.message =m.id AND mm.participant <> :id
            WHERE tm2.id IN
            (:ids)
            ORDER BY tm2.lastParticipantMessageDate DESC 
            
           ')->setParameter('ids', $ids)
            ->setParameter('id', $currentUserId);

        $threadMetadata = $query->getResult();

        $result =array();
        foreach ($threadMetadata as $param){

            $result[]= array(
                                        'participant_id' =>$param['participant_id'],
                                        'username' =>$param['username'],
                                        'last_participant_message_date' =>$param['last_participant_message_date'],
                                        'last_message' =>json_decode($param['last_message']),
                                        'is_read' =>$param['is_read']

                                        );


            }


        return $result;
    }



    /**
     * Displays the authenticated participant inbox.
     * @Annotations\Get("/discussion/{participantId}")
     * @param $participantId
     * @return array
     */
    public function getDiscussionAction($participantId){

        $em1 = $this->getDoctrine()->getManager();
        $em2 = $this->getDoctrine()->getManager();

        $query = $em1->createQuery(
            '
            SELECT u
            FROM  UserBundle:User u
            WHERE u.id= :participant_id
            
           ')->setParameter('participant_id',$participantId);

        $participant = $query->getOneOrNullResult();
        $currentUser =$this->getUser();

        $query = $em2->createQuery(
            '
            SELECT u.id As user_id ,u.username,m.body, m.createdAt AS created_at
            FROM  MessageBundle:Message m
            JOIN  MessageBundle:MessageMetadata mm WITH mm.message = m.id
            JOIN UserBundle:user u WITH u.id = m.sender
            WHERE ((m.sender = :participant and mm.participant = :current_user ) OR (m.sender = :current_user and mm.participant = :participant))
            ORDER BY m.createdAt
            
           ')
             ->setParameter('participant',$participant)
             ->setParameter('current_user',$currentUser);

        $messages = $query->getResult();

        $result=array();
        foreach ($messages as $param){

            $result[]= array(
                'user_id' =>$param['user_id'],
                'username' =>$param['username'],
                'username' =>$param['username'],
                'body' =>json_decode($param['body']),
                'created_at' =>$param['created_at']

            );

        }

        return $result;
    }



    /**
     * Displays the authenticated participant inbox.
     * @Annotations\Post("/send")
     * @param Request $request
     * @return Message
     */
    public function sendMessageAction(Request $request){

        $em1 = $this->getDoctrine()->getManager();
        $em2 = $this->getDoctrine()->getManager();

        $date = new \DateTime();

        $participantId= $request->get('participant_id');

        $query = $em1->createQuery(
            '
            SELECT u
            FROM  UserBundle:User u
            WHERE u.id= :participant_id
            
           ')->setParameter('participant_id',$participantId);

        $participant = $query->getOneOrNullResult();

        $thread = new Thread();
        $thread->setCreatedBy($this->getUser());
        $thread->setSubject("");
        $thread->setCreatedAt($date);

        $message= new Message();
        $message->setThread($thread);
        $message->setSender($this->getUser());

        if ($request->get('body')!= null)
        {
            $body= json_encode($request->get('body'));
            $message->setBody($body);
        }else{
            $message->setBody("");
        }

        if($request->get('filename') != null && $request->get('file') != null)
        {
            $filename = $request->get('filename');
            $message->setFilepath($filename);
            $file = base64_decode($request->get('file'));
            $dir = __DIR__.'/../../../web/images/messages/';
            file_put_contents($dir.$filename, $file);
        }else{
            $message->setFilepath("");
        }

        $message->setCreatedAt();

        $messageMetadata1 = new MessageMetadata();
        $messageMetadata1->setMessage($message);
        $messageMetadata1->setParticipant($this->getUser());
        $messageMetadata1->setIsRead(true);


        $messageMetadata2 = new MessageMetadata();
        $messageMetadata2->setMessage($message);
        $messageMetadata2->setParticipant($participant);
        $messageMetadata2->setIsRead(false);

        $message->addMetadata($messageMetadata1);
        $message->addMetadata($messageMetadata2);


        $threadMetadata1 = new ThreadMetadata();
        $threadMetadata1->setThread($thread);
        $threadMetadata1->setParticipant($this->getUser());
        $threadMetadata1->setLastMessageDate($date);

        $threadMetadata2 = new ThreadMetadata();
        $threadMetadata2->setThread($thread);
        $threadMetadata2->setParticipant($participant);
        $threadMetadata2->setLastParticipantMessageDate($date);

        $thread->addMetadata($threadMetadata1);
        $thread->addMetadata($threadMetadata2);


        $messageMetadata1 = new MessageMetadata();
        $messageMetadata1->setMessage($message);
        $messageMetadata1->setParticipant($this->getUser());
        $messageMetadata1->setIsRead(false);


        $messageMetadata2 = new MessageMetadata();
        $messageMetadata2->setMessage($message);
        $messageMetadata2->setParticipant($participant);
        $messageMetadata2->setIsRead(false);




        $em2->persist($thread);
        $em2->flush();



        $em2->persist($message);
        $em2->flush();


        return $message;

    }



    /**
     * Deletes a thread.
     * @Annotations\Delete("/thread/delete/{threadId}")
     *
     * @param $threadId
     */
    public function deleteAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $this->container->get('fos_message.deleter')->markAsDeleted($thread);
        $this->container->get('fos_message.thread_manager')->saveThread($thread);


    }



    /**
     * Gets the provider service.
     *
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        return $this->container->get('fos_message.provider');
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }



}
