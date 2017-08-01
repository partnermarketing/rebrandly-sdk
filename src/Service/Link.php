<?php

namespace Rebrandly\Service;

use Rebrandly\Model\Domain as DomainModel;
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

        return $linkModel->getShortUrl();
    }

    public function fullCreate(LinkModel $linkModel)
    {
        $method = 'POST';
        $target = 'links';

        $response = $this->http->get($target, $linkModel);

        //die(var_dump($response));

        foreach ($response[0] as $key => $value) {
            if ($key == 'domain') {
                $tmpDomain = new DomainModel();
                $tmpDomain->setId($value->id);
                $tmpDomain->setFullName($value->fullName);

                $value = $tmpDomain;
            }

            $setter = 'set' . $key;
            if (method_exists($linkModel, $setter)) {
                $linkModel->$setter($value);
            }
        }

        return $linkModel;
    }

    public function destroyLink(LinkModel $linkModel)
    {
        $method = 'DELETE';
        $target = 'links/' . $linkModel->getId();

        $params = [
            'trash' => false,
        ];

        $response = $this->http->delete($target, $params);

        return $reponse;
    }
}
