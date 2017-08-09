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

    private function prepareRequestBody($action, linkModel $linkModel)
    {
        $requiredParams = [
            'create' => ['destination'],
            'update' => ['destination', 'slashtag', 'title', 'domain', 'favourite'],
        ];

        $optionalParams = [
            'create' => ['slashtag', 'title', 'domain', 'description', 'favourite'],
            'update' => ['description']
        ];

        foreach($requiredParams[$action] as $param) {
            $getter = 'get' . $param;
            if (!$linkModel->$getter()) {
                die('Required parameter not set: '. $key . PHP_EOL);
            }
        }

        $body = [];

        foreach($requiredParams[$action] + $optionalParams[$action] as $param) {
            $getter = 'get' . $param;
            $value = $linkModel->$getter();
            if ($value) {
                $body[$param] = $value;
            }
        }

        return $body;
    }

    // Just a simple wrapper around the full creation method - intended to save
    // the user from having to actually create a full Link object and pass it
    // back. Effectively just creates a link objects, runs a full create and
    // only returns the resulting short URL, the thing a user will probably
    // most often care about.
    public function quickCreate($url)
    {
        $linkModel = new LinkModel($url);

        $createdLink = $this->fullCreate($linkModel);

        return $linkModel->getShortUrl();
    }

    public function fullCreate(LinkModel $linkModel)
    {
        $target = 'links';

        $body = $this->prepareRequestBody('create', $linkModel);

        $response = $this->http->post($target, $body);

        foreach ($response as $key => $value) {
            if ($key == 'domain') {
                $tmpDomain = new DomainModel();
                $tmpDomain->setId($value->id);
                $tmpDomain->setFullName($value->fullName);

                $value = $tmpDomain;
            }

            // The API returns a fair bit of stuff that we simply don't care
            // about, so we keep what we care about and bin the rest. The stuff
            // we care about is everything detailed in the Rebrandly docs under
            // the Link model section.
            $setter = 'set' . $key;
            if (method_exists($linkModel, $setter)) {
                $linkModel->$setter($value);
            }
        }

        return $linkModel;
    }

    // Untested, use at your own peril.
    // May or may not do what it says on the tin.
    public function destroy($link, $permanent = true)
    {
        $method = 'DELETE';

        // Because deletion only requires an ID it's fine for us to accept just
        // the numeric link ID. If the user gives us a full linkModel, extract
        // only the ID and continue.
        if ($link instanceof linkModel) {
            $target = 'links/' . $linkModel->getId();
        } elseif (is_int($link)) {
            $target = $link;
        }

        // Trashing is really tombstoning with a worse name. The link still
        // exists, it's just not flagged as visible any more.
        $params = [
            'trash' => !$permanent,
        ];

        $response = $this->http->delete($target, $params);

        return $reponse;
    }
}
