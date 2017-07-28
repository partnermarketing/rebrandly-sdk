<?php

namespace Rebrandly\Service;

use Rebrandly\Model\Link as LinkModel;
use Rebrandly\Service\Http;

class Link
{
    private $http;

    public function __construct($apiKey)
    {
        $this->http = new Http($apiKey);
    }

    public function quickCreate($url)
    {
        $linkModel = new LinkModel($url);

        $this->fullCreate($linkModel);

        return $linkModel->shortUrl;
    }

    public function fullCreate(LinkModel $linkModel)
    {
        $target = 'links';

        $response = $this->http->send($target, $linkModel);

        foreach ($response as $key => $value) {
            // I don't like this.
            $setter = "set" . $key;
            if (method_exists($linkModel, $setter)) {
                $linkModel->$setter($value);
            }
        }

        return $linkModel;
    }
}
