<?php


namespace MoxiworksPlatform;

use GuzzleHttp\Tests\Psr7\Str;
use MoxiworksPlatform\Exception\ArgumentException;
use MoxiworksPlatform\Exception\InvalidResponseException;
use Symfony\Component\Translation\Tests\StringClass;


class ActionLog extends Resource {
    /**
     * @var string the Moxi Works Platform ID of the agent
     *   moxi_works_agent_id is the Moxi Works Platform ID of the agent which an action log entry is
     *   or is to be associated with.
     *
     *   this must be set for any ActionLog create transaction
     *
     */
    public $moxi_works_agent_id;

    /**
     * @var string your system's unique ID for the contact
     *   *your system's* unique ID for the Contact
     *
     *   this must be set for any ActionLog create transaction
     *
     */
    public $partner_contact_id;

    /**
     * @var string
     *   the title of this ActionLog entry
     *
     *   this must be set for any ActionLog create transaction
     *
     */
    public $title;

    /**
     * @var string
     *   the body of this ActionLog entry
     *
     *   this must be set for any ActionLog create transaction
     *
     */
    public $body;

    /**
     * @var string
     *   the type of this ActionLog entry
     *
     */
    public $type;

    /**
     * @var integer
     *   the timestamp of this ActionLog entry
     *
     */
    public $timestamp;

    /**
     * @var array of image associative arrays associated with the listing in the format
     *
     * [
     *      "moxi_works_action_log_id" => "(string) unique identifier for the Moxi Works Platform ActionLog entry",
     *      "type" => "(string) the type of ActionLog entry this is. The string should be formatted in lowercase with an underscore between each word",
     *      "timestamp" => "(Integer) Unix timestamp for the creation time of the ActionLog entry",
     *      "log_data" => "(Dictionary) the payload data of the ActionLog entry. The structure returned is dependent on the kind of ActionLog entry this is"
     * ]
     */
    public $actions;

    /**
     * ActionLog constructor.
     * @param array $data
     */
    function __construct(array $data) {
        foreach($data as $key => $val) {
            if(property_exists(__CLASS__,$key)) {
                $this->$key = $val;
            }
        }
    }

    /**
     *  Create an ActionLog entry on The Moxi Works Platform
     * <code>
     *   MoxiworksPlatform\ActionLog::create([
     *     moxi_works_agent_id: '123abc',
     *     partner_contact_id: '1234',
     *     title: 'Client Picked Up House Keys!',
     *     body: 'Firstname Lastname came by the office to pick up their keys']);
     * </code>
     *
     * @param array $attributes
     *       <br><b>moxi_works_agent_id *REQUIRED* </b>The Moxi Works Agent ID for the agent to which this ActionLog entry is to be associated
     *       <br><b>partner_contact_id *REQUIRED* </b>Your system's unique ID for the contact for whom the ActionLog entry regards.
     *      <br><b>title</b>  string  string a short description of the ActionLog entry (should be 85 characters or less)
     *      <br><b>body</b>  string the body of the ActionLog entry (should be 255 characters or less)
     *
     * @return ActionLog|null
     *
     * @throws ArgumentException if required parameters are not included
     * @throws RemoteRequestFailureException
     */
    public static function create($attributes=[]) {
        return ActionLog::sendRequest('POST', $attributes);
    }

    /**
     * Search ActionLogs for a specific Contact on Moxi Works Platform.
     *
     * search can be performed by including date_start and date_end in a parameter array
     *  <code>
     *  \MoxiworksPlatform\Contact::search([moxi_works_agent_id: 'abc123', partner_contact_id: 'abc123'])
     *  </code>
     * @param array $attributes
     *       <br><b>moxi_works_agent_id *REQUIRED* </b> string The Moxi Works Agent ID for the agent to which this ActionLog entry is associated
     *       <br><b>partner_contact_id *REQUIRED* </b>Your system's unique ID for the contact for whom the ActionLog entry regards.
     *
     *
     *
     * @return Array of ActionLog objects
     *
     * @throws ArgumentException if required parameters are not included
     * @throws ArgumentException if at least one search parameter is not defined
     * @throws RemoteRequestFailureException
     */
    public static function search($attributes=[]) {
        $method = 'GET';
        $url = Config::getUrl() . "/api/action_logs";
        $actions = array();

        $required_opts = array('moxi_works_agent_id', 'partner_contact_id');

        if(count(array_intersect(array_keys($attributes), $required_opts)) != count($required_opts))
            throw new ArgumentException(implode(',', $required_opts) . " are required");

        $json = Resource::apiConnection($method, $url, $attributes);

        if(!isset($json) || empty($json))
            return null;

        foreach ($json['actions'] as $c) {
            $action = new ActionLog($c);
            array_push($actions, $action);
        }
        $json['actions'] = $actions;
        return $json;
    }

    /**
     * @param $method
     * @param array $opts
     * @param null $url
     *
     * @return ActionLog|null
     *
     * @throws ArgumentException if required parameters are not included
     * @throws RemoteRequestFailureException
     */
    private static function sendRequest($method, $opts=[], $url=null) {
        if($url == null) {
            $url = Config::getUrl() . "/api/action_logs";
        }

        $action_log = null;
        $json = Resource::apiConnection($method, $url, $opts);
        $action_log = (!isset($json) || empty($json)) ? null : new ActionLog($json);
        return $action_log;
    }


}
