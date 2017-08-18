<?php

namespace Rebrandly\Service;

use Rebrandly\Model\Domain as DomainModel;
use Rebrandly\Model\Link as LinkModel;
use Rebrandly\Service\Http;

class Link
{
    const REQUIREDFIELDS = [
        'create' => ['destination'],
        'update' => ['destination', 'slashtag', 'title', 'domain', 'favourite'],
    ];

    const OPTIONALFIELDS = [
        'create' => ['slashtag', 'title', 'domain', 'description', 'favourite'],
        'update' => ['description']
    ];

    private $http;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->http = new Http($apiKey);
    }

    /**
     * Ensures all required fields for the requested action exist on a $link
     *
     * While the link model includes its own validation of fields on assignment
     * and hence we can trust that any set data is of the correct type etc, the
     * link model doesn't know about the API's requirements.
     *
     * @param string $action The action being performed, used to look up which
     *    fields are required
     *
     * @param array $linkArray Array describing the link being validated
     */
    private function validate($action, $linkArray)
    {
        $missing = array_flip(array_diff_key(array_flip(self::REQUIREDFIELDS[$action]), $linkArray));

        if (count($missing) > 0) {
            throw new \InvalidArgumentException("Missing required fields: " . join(' ', $missing));
        }
    }

    /**
     * Shorthand to shorten a link given nothing but the destination
     *
     * @param string $destination The target URL that the shortened link should
     *    resolve to.
     *
     * @return LinkModel $link A link populated with the response from the
     *    Rebrandly API.
     */
    public function quickCreate($url)
    {
        $link = new LinkModel();
        $link->setDestination($url);

        return $this->fullCreate($link);
    }

    /**
     * Creates a link given LinkModel with any desired details
     *
     * @param array $link Any fields the user wishes to set on the link before
     *    creation
     *
     * @return LinkModel $link A link populated with the response from the
     *    Rebrandly API.
     */
    public function fullCreate(LinkModel $link)
    {
        $target = 'links';
        $linkArray = $link->export();
        $this->validate('create', $linkArray);

        $response = $this->http->post($target, $linkArray);

        $link = new LinkModel();
        $link->import($response);

        return $link;
    }

    /**
     * Updates a link given all existing and any new data
     *
     * @param LinkModel $link A new link to update with
     *
     * @return LinkModel $link An updated link as returned from the API
     */
    public function update($link)
    {
        $target = $link->getId();
        $linkArray = $link->export();
        $this->validate('update', $linkArray);

        $response = $this->http->post($target, $linkArray);

        $link = new LinkModel();
        $link->import($response);

        return $link;
    }

    /**
     * Gets full details of a single link given its ID
     *
     * @param string $linkId the ID of the link, as provided on creation by
     *    Rebrandly
     *
     * @return LinkModel $link A populated link as returned from the API
     */
    public function getOne($linkId)
    {
        $target = 'links/' . $linkId;

        $response = $this->http->get($target);

        $link = new LinkModel;
        $link->import($response);

        return $link;
    }

    /**
     * Deletes (optionally: permanently) a link
     *
     * @param string|object $link The link object or link ID to delete
     *
     * @param boolean $permanent Permanently deletes the link, rather than
     *    marking it as inactive.
     *
     * TODO: Check what this response actually is
     * @return array $response Whatever response the API gives us.
     */
    public function delete($link, $permanent = true)
    {
        if ($link instanceof LinkModel) {
            $target = 'links/' . $link->getId();
        } elseif (is_string($link) || is_integer($link)) {
            $target = 'links/' . $link;
        }

        $params = [
            'trash' => !$permanent,
        ];

        $response = $this->http->delete($target, $params);

        return $response;
    }

    /**
     * Search for links meeting some criteria, with sorting controls
     *
     * @param array $filters A list of parameters to filter and sort by
     *
     * @return LinkModel[] $links A list of links that meet the given criteria
     */
    public function search($filters)
    {
        $target = 'links/';

        $response = $this->http->get($target, $filters);

        $links = [];
        foreach ($response as $linkArray) {
            $link = new LinkModel;
            $link->import($linkArray);
            array_push($links, $link);
        }

        return $links;
    }

    /**
     * Count links meeting some criteria
     *
     * @param array $filters A list of parameters to filter by
     *
     * @return integer $count A count of links that meet the given criteria
     */
    public function count($filters)
    {
        $target = 'links/count';

        $response = $this->http->get($target, $filters);

        return $response;
    }
}
