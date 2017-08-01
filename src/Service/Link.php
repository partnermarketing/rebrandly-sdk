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

    // Just a simple wrapper around the full creation method - intended to save
    // the user from having to actually create a full Link object and pass it
    // back. Effectively just creates a link objects, runs a full create and
    // only returns the resulting short URL, the thing a user will probably
    // most often care about.
    public function quickCreate($url)
    {
        $linkModel = new LinkModel($url);

        $this->fullCreate($linkModel);

        return $linkModel->getShortUrl();
    }

    public function fullCreate(LinkModel $linkModel)
    {
        // We could actually GET here, as there's a shorthand endpoint that
        // takes a query string to generate one link. But really this method
        // should eventually expand to support taking an array of links to
        // generate so we may as well use the POST endpoint from the get go.
        $method = 'POST';
        $target = 'links';

        $response = $this->http->get($target, $linkModel);

        // The link creation endpoint supports creating an arbitrary number of
        // links at once, so the response is a numeric array. Currently we only
        // support creating one link at a time, so we just handle the 'first'
        // response.
        foreach ($response[0] as $key => $value) {
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
    public function destroyLink(LinkModel $linkModel)
    {
        $method = 'DELETE';
        $target = 'links/' . $linkModel->getId();

        // TODO: Check what the trash flag actually does. This is currently just
        // set to false because that's what the example on the API demonstration
        // used.
        $params = [
            'trash' => false,
        ];

        $response = $this->http->delete($target, $params);

        return $reponse;
    }
}
