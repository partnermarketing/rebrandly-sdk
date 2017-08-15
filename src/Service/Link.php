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

    // TODO: Rename this
    // Some basic validation to handle compulsory fields when prepping a request
    // for feeding out to the HTTP handler. Basically just here to check that
    // all required fields are present, and to strip out any unnecessary ones.
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

    // Helper function to take an array like the JSON output from the Rebrandly
    // API and turn it into a useful linkModel.
    private function buildModelFromArray($link)
    {
        $return = new LinkModel('');
        foreach ($link as $key => $value) {
            // The API returns a fair bit of stuff that we simply don't care
            // about, so we keep what we care about and bin the rest. The stuff
            // we care about is everything detailed in the Rebrandly docs under
            // the Link model section.
            $setter = 'set' . $key;
            if (method_exists($return, $setter)) {
                $return->$setter($value);
            }
        }

        return $return;
    }

    // This is just a simple wrapper around the full creation method.
    // As far as the user is concerned, its purpose is to present a minimal
    // interface for supplying a destination URL and getting back a shortened
    // URL. Unfortunately the resulting short URL isn't the unique key for a
    // link so the user needs to be given the whole linkModel objects - this
    // complicates the simple case but is necessary so that the user has a link
    // ID to later read, update and delete with.
    public function quickCreate($url)
    {
        $linkModel = new LinkModel($url);

        return $this->fullCreate($linkModel);
    }

    public function fullCreate(LinkModel $linkModel)
    {
        $target = 'links';

        $body = $this->prepareRequestBody('create', $linkModel);

        $response = $this->http->post($target, $body);

        $createdLink = $this->buildModelFromArray($response);

        return $createdLink;
    }

    // Gets full details of a single link given either a link ID or a full link
    // model. If given a link model, it simply extracts the link ID and
    // continues, as the link ID is the only unique key by which to search links
    public function getOne($link)
    {
        if ($link instanceof linkModel) {
            $target = 'links/' . $link->getId();
        } elseif (is_int($link)) {
            $target = 'links/' . $link;
        }

        $response = $this->http->get($target);

        $linkModel = $this->buildModelFromArray($response);

        return $linkModel;
    }

    // Link deletion in the API is handled one link at a time by DELETEing on
    // the link ID. As above, we can accept a full link model and extract the
    // link ID, or the user can provide it directly.
    public function delete($link, $permanent = true)
    {
        if ($link instanceof LinkModel) {
            $target = 'links/' . $link->getId();
        } elseif (is_string($link)) {
            $target = 'links/' . $link;
        }

        // Trashing is really tombstoning with a worse name. The link still
        // exists, it's just not flagged as visible any more. Amazingly, the
        // default behaviour of the API is to permanently delete, and it only
        // tombstones if prompted. Whilst that is stupid, we're adhering to that
        // behaviour for the sake of consistency.
        $params = [
            'trash' => !$permanent,
        ];

        $response = $this->http->delete($target, $params);

        return $response;
    }

    // Very few parameters are actually filterable - you can only filter a
    // search based on domain, favourite status, or active/trashed status.
    // There is no functionality to filter by other fields, so while we could
    // implement this client-side by simply crawling the API its slow response
    // time makes that an exercise in insanity.
    public function search($filters)
    {
        $target = 'links/';

        $links = $this->http->get($target, $filters);

        return $links;
    }
}
