<?php
/**
 * Отправка SMS.
 * Пакет содержит классы для отправки SMS с сайта.
 *
 * @package App.Models.Sms
 */

namespace App\Models\Sms;

use Storage;
use Config;
use App\Models\Contracts\Sms;
use App\Models\Exceptions\ResponseException;

/**
 * Классы драйвер для отправки СМС сообщений с сайта с использованием СмсЦентр - www.smsc.ru.
 */
class SmsCenter extends Sms
{
    /**
     * Отправка СМС сообщения.
     *
     * @param  string  $phone  Номер телефона.
     * @param  string  $message  Сообщение для отправки.
     * @param  string  $sender  Отправитель.
     * @param  bool  $isTranslit  Если указать true, то нужно транслитерировать сообщение в латиницу.
     *
     * @return string|null Вернет идентификатор сообщения, если сообщение было отправлено.
     * @throws ResponseException
     */
    public function send(string $phone, string $message, string $sender, bool $isTranslit = false): ?string
    {
        $msg = iconv('utf-8', 'windows-1251', $message);
        $url = 'http://smsc.ru/sys/send.php?login='.Config::get('sms.center.login').'&psw='.Config::get(
                'sms.center.password'
            ).'&phones='.$phone.'&mes='.$msg.'&sender='.$sender.'&fmt=3';

        $response = Storage::url($url);
        $result = json_decode($response, true);

        if (isset($result['error_code'])) {
            switch ($result['error_code']) {
                case 1:
                    throw new ResponseException(trans('models.sms.smsCenter.params'));
                case 2:
                    throw new ResponseException(trans('models.sms.smsCenter.access'));
                case 3:
                    throw new ResponseException(trans('models.sms.smsCenter.balance'));
                case 4:
                    throw new ResponseException(trans('models.sms.smsCenter.block'));
                case 5:
                    throw new ResponseException(trans('models.sms.smsCenter.format'));
                case 6:
                    throw new ResponseException(trans('models.sms.smsCenter.prohibited'));
                case 7:
                    throw new ResponseException(trans('models.sms.smsCenter.invalid'));
                case 8:
                    throw new ResponseException(trans('models.sms.smsCenter.unreached'));
                case 9:
                    throw new ResponseException(trans('models.sms.smsCenter.limit'));
            }

            throw new ResponseException(trans('models.sms.smsCenter.undefined'));
        } else {
            return $result['id'];
        }
    }

    /**
     * Проверки статуса отправки сообщения.
     *
     * @param  string  $index  Индекс отправленного сообщения.
     * @param  string  $phone  Номер телефона.
     *
     * @return bool Вернет true, если сообщение было отправлено.
     * @throws ResponseException
     */
    public function check(string $index, string $phone): bool
    {
        $url = 'http://smsc.ru/sys/status.php?login='.Config::get('sms.center.login').'&psw='.Config::get(
                'sms.center.password'
            ).'&phone='.$phone.'&id='.$index.'&fmt=3';

        $response = Storage::url($url);
        $result = json_decode($response, true);

        if (isset($result['error_code'])) {
            throw new ResponseException($result['error']);
        } elseif (isset($result['err'])) {
            throw new ResponseException($result['err']);
        } elseif (isset($result['status'])) {
            switch (isset($result['status'])) {
                case -3:
                    throw new ResponseException(trans('models.sms.smsCenter.not_exist'));
                case -1:
                    throw new ResponseException(trans('models.sms.smsCenter.processing'));
                case 0:
                    throw new ResponseException(trans('models.sms.smsCenter.transmitted'));
                case 3:
                    throw new ResponseException(trans('models.sms.smsCenter.expired'));
                case 20:
                    throw new ResponseException(trans('models.sms.smsCenter.undelivered'));
                case 22:
                    throw new ResponseException(trans('models.sms.smsCenter.invalid'));
                case 23:
                    throw new ResponseException(trans('models.sms.smsCenter.prohibited'));
                case 24:
                    throw new ResponseException(trans('models.sms.smsCenter.balance'));
                case 25:
                    throw new ResponseException(trans('models.sms.smsCenter.unavailable'));
                case 1:
                    return true;
            }
        }

        return false;
    }
}
