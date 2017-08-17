<?php

namespace Rebrandly\Service;

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

    public function __construct($apiKey)
    {
        $this->http = new Http($apiKey);
    }

    /*
     * Ensures all $required fields exist on a $link
     *
     * @param array $required A list of fields which must exist on the $link
     *
     * @param array $link Information describing the link being validated
     *
     * @return array $missingFields Any fields which are required but missing
     */
    private function validate($required, $link)
    {
        $missing = array_diff_key(array_flip($required), $link);

        if (count($missing)) {
            throw new InvalidArgumentException("Missing required fields: " . join(' ', $missing));
        }
    }

    /*
     * Shorthand to shorten a link given nothing but the destination
     *
     * @param string $destination The target URL that the shortened link should
     *    resolve to.
     *
     * @return array $link An array populated with the response from the
     *    Rebrandly API.
     */
    public function quickCreate($url)
    {
        $link = [
            'destination' => $url,
        ];

        return $this->fullCreate($link);
    }

    /*
     * Shortens a link given an array with any desired details included
     *
     * @param array $link Any fields the user wishes to set on the link before
     *    creation
     *
     * @return array $link An array populated with the response from the
     *    Rebrandly API.
     */
    public function fullCreate($link)
    {
        $target = 'links';

        try {
            $this->validate(self::REQUIREDFIELDS['create'], $link);
        } catch (InvalidArgumentException $e) {
            return $e;
        }

        $response = $this->http->post($target, $link);

        return $response;
    }

    /*
     * Updates a link, given an array containing all existing and any new data
     *
     * @param array $link An array of all link fields, including those which are
     *    unchanged.
     *
     * @return array $link An updated link as returned from the API
     */
    public function update($link)
    {
        $target = $link['id'];

        try {
            $this->validate(self::REQUIREDFIELDS['update'], $link);
        } catch (InvalidArgumentException $e) {
            return $e;
        }

        $response = $this->http->post($target, $link);

        return $response;
    }

    /*
     * Gets full details of a single link given its ID
     *
     * @param string $linkId the ID of the link, as provided by Rebrandly
     *
     * @return array $link A populated link as returned from the API
     */
    public function getOne($linkId)
    {
        $target = 'links/' . $linkId;

        $response = $this->http->get($target);

        return $response;
    }

    /*
     * Deletes (optionally: permanently) a link given its ID
     *
     * @param string $linkId the ID of the link to delete, as provided by Rebrandly
     *
     * @param boolean $permanent Permanently deletes the link, rather than
     *    marking it as inactive.
     *
     * TODO: Check what this response actually is
     * @return array $response Whatever response the API gives us.
     */
    public function delete($linkId, $permanent = true)
    {
        $target = 'links/' . $linkId;

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

    /*
     * Search for links meeting some criteria, with sorting controls
     *
     * @param array $filters A list of parameters to filter and sort by
     *
     * @return array $links A list of links that meet the given criteria
     */
    public function search($filters)
    {
        $target = 'links/';

        $links = $this->http->get($target, $filters);

        return $links;
    }
}
