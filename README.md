# yii2-multitable-query

В ближайшее время оформлю как библиотеку


К примеру MultiMessageUser::find()->where(['chat_messages.id' => $chat_message->id])->one();
Выдаст объект у в котором есть запись из таблицы users и из таблицы chat_messages
