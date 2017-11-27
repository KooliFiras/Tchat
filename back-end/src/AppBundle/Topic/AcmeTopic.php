<?php

namespace AppBundle\Topic;

use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\PushableTopicInterface;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;

class AcmeTopic implements TopicInterface , PushableTopicInterface
{

    protected $clientManipulator;

    public function __construct(ClientManipulatorInterface $clientManipulator)
    {
        $this->clientManipulator= $clientManipulator;
    }

    /**
     * @param Topic        $topic
     * @param WampRequest  $request
     * @param array|string $data
     * @param string       $provider The name of pusher who push the data
     */
    public function onPush(Topic $topic, WampRequest $request, $data, $provider)
    {
        $topic->broadcast($data);
    }

    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     */

    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {

        $user=$this->clientManipulator->getClient($connection);

      //  $user = $this->clientStorage->getClient($connection->WAMP->clientStorageId);
       // $user= $this->clientManipulator->findByUsername($topic,'test');
        $topic->broadcast(['msg'=> 'hello '. $user]);

    }


//    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
//    {
//
//        //this will broadcast the message to ALL subscribers of this topic.
//        $connection->send('hello '. $connection->resourceId);
//
//      //  $topic->broadcast(['msg' => $connection->resourceId . " has joined " . $topic->getId() .' , all subscribers ' . $topic->count() .' ,RouteNAme: ' . $request->getRouteName() . ' ,Route: ' . $request->getRoute() ]);
//
//       // $connection->event($topic->getId(),['msg'=>'hi '. $connection->resourceId ]);
//
//
//    }

    /**
     * This will receive any UnSubscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast(['msg' => $connection->resourceId . " has left " . $topic->getId()]);
    }

    /**
     * This will receive any Publish requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param $Topic topic
     * @param WampRequest $request
     * @param $event
     * @param array $exclude
     * @param array $eligibles
     * @return mixed|void
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        /*
            $topic->getId() will contain the FULL requested uri, so you can proceed based on that

            if ($topic->getId() == "acme/channel/shout")
               //shout something to all subs.
        */

        $topic->broadcast([
            'msg' => $event
        ]);
    }

    /**
     * Like RPC is will use to prefix the channel
     * @return string
     */
    public function getName()
    {
        return 'acme.topic';
    }


}