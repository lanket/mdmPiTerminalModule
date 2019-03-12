<?php

// namespace Mpt\JsonRpc;

/**
 * Class Client
 * @package Mpt\JsonRpc
 * isSuccess() - возвращает true если нет ошибок, иначе false
 * getResult() - возвращает ответ сервера
 * getErrorCode() - возвращает код ошибки если isSuccess() = false
 * getErrorMessage() - возвращает описание ошибки если isSuccess() = false
* В момент создания экземпляра класса ожидается 2 аргумента -
* адрес сервера и массив логин/пароль пользователя если требуется авторизация.
* При обращении к любому из методов API стоит вызывать аналогичный метод класса,
* указывая параметры в качестве аргументов.
** Пример взят отсюда https://gtxtymt.xyz/blog/json-rpc-client-php
 */
class mpt_client
{
    const VERSION = '2.0';

    protected $url;

    protected $user;

    protected $password;

    private $method;

    private $params;

    public function __construct(string $url, array $auth = [])
    {
        $this->url = $url;

        if(count($auth) == 2) {
            $this->user = $auth[0];
            $this->password = $auth[1];
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return Response
     * @throws \Exception
     */
    public function __call(string $name, array $arguments = [])
    {
        $this->method = function_exists('snake_case') ? snake_case($name) : $name;
        $this->params = count($arguments) == 1 && is_array($arguments[0]) ? $arguments[0] : $arguments;

        return $this->_curl();
    }

    /**
     * @return Response
     * @throws \Exception
     */
    private function _curl()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            'method' => $this->method,
            'params' => $this->params,
            'id' => microtime(),
            'jsonrpc' => self::VERSION
        ]));

        if(!is_null($this->user)) {
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $this->user.':'.$this->password);
        }

        $result = curl_exec($curl);

        curl_close($curl);

        return new mpt_response($result);
    }
}

class mpt_response
{
    protected $response;

    protected $success;

    public function __construct($response)
    {
        $response = json_decode($response, false); // TODO add JSON_THROW_ON_ERROR when php 7.3 released (https://gtxtymt.xyz/blog/php-73-whats-new-release-date#vozmozhnost-vyzvat-isklyuchenie-pri-rabote-s-json-encode-i-json-decode)

        if(!$response instanceof \stdClass) {
            throw new \Exception('Undefined response data.');
        }

        $this->response = $response;
        $this->success = !isset($this->response->error);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->response->result;
    }

    /**
     * @return integer
     */
    public function getErrorCode()
    {
        return $this->response->error->code;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->response->error->message;
    }
}
