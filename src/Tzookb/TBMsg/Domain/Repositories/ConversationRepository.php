<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:17
 */

namespace Tzookb\TBMsg\Domain\Repositories;


use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Entities\Message;

interface ConversationRepository extends BaseRepository
{
    /**
     * @param Conversation $conversation
     * @return integer
     */
    public function create(Conversation $conversation);

    /**
     * @param $conversationId
     * @param Message $message
     * @return boolean
     */
    public function addMessage($conversationId, Message $message);

    /**
     * @param $conversationId
     * @return integer[]
     */
    public function allParticipants($conversationId);

    /**
     * @param $conversationId
     */
    public function findById($conversationId);
}