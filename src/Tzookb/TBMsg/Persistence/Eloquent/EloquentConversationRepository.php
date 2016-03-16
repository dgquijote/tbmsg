<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:40
 */

namespace Tzookb\TBMsg\Persistence\Eloquent;


use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Entities\TBMsgable;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;
use Tzookb\TBMsg\Persistence\Eloquent\Models\ConversationUsers;

class EloquentConversationRepository extends EloquentBaseRepository implements ConversationRepository
{
    /**
     * @param Conversation $conversation
     * @return integer
     */
    public function create(Conversation $conversation)
    {
        $eloquentConversation = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Conversation();

        $eloquentConversation->save();

        $participants = array_map(function(TBMsgable $participant) {
            return new ConversationUsers(['user_id'=>$participant->getTbmsgIdentifyId()]);
        }, $conversation->getParticipants());


        $eloquentConversation->conversationUsers()->saveMany($participants);

        return $eloquentConversation->id;
    }

    /**
     * @param $conversationId
     * @return integer[]
     */
    public function allParticipants($conversationId)
    {
        $eloquentConversation = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Conversation();
        $eloquentConversation = $eloquentConversation->findOrFail($conversationId);

        return array_map(function($conversationUser) {
            return $conversationUser['user_id'];
        }, $eloquentConversation->conversationUsers->toArray());
    }

    /**
     * @param $conversationId
     * @param Message $message
     * @return boolean|integer
     */
    public function addMessage($conversationId, Message $message)
    {
        $eloquentMessage = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Message();
        $eloquentMessage->sender_id = $message->getCreator();
        $eloquentMessage->conv_id = $conversationId;
        $eloquentMessage->content = $message->getContent();

        $eloquentMessage->save();
        return $eloquentMessage->id;
    }

    /**
     * @param $conversationId
     */
    public function findById($conversationId)
    {
        $eloquentConversation = new \Tzookb\TBMsg\Persistence\Eloquent\Models\Conversation();
        $conversation = $eloquentConversation->findOrFail($conversationId);
        return $conversation;
    }

}