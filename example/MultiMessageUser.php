<?php
/**
 * Created by PhpStorm.
 * User: mypc
 * Date: 26.08.18
 * Time: 13:49
 */

namespace app\modules\chats\models;


use app\components\MultiTableQuery;
use app\components\MultiTableRecord;
use app\modules\admin\models\PublicContent;
use app\modules\anime\models\Anime;
use app\modules\chats\models\ChatMessage;
use app\modules\series\models\Series;
use app\modules\users\models\User;
use yii\helpers\VarDumper;

/**
 * Class MultiUserWallPublication
 * @package app\modules\main\models
 *
 * @property ChatMessage $message
 * @property User $user

 */
class MultiMessageUser extends MultiTableRecord
{

    /**
     * {@inheritdoc}
     */
    static function models()
    {
        return [
            'chat_messages' => ChatMessage::class,
            'users' => User::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields()
    {

    }

    /**
     * {@inheritdoc}
     */
    static function join(MultiTableQuery $query)
    {
        return $query->from('chat_messages')
            ->leftJoin('users', 'chat_messages.user_id = users.id');
    }
}