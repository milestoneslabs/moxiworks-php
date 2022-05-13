<?php


namespace MoxiworksPlatform;

use GuzzleHttp\Tests\Psr7\Str;
use MoxiworksPlatform\Exception\ArgumentException;
use MoxiworksPlatform\Exception\InvalidResponseException;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Translation\Tests\StringClass;


class Agent extends Resource {
    /**
     * @var string the Moxi Works Platform ID of the agent
     *   moxi_works_agent_id is the Moxi Works Platform ID of the agent
     *
     *   this must be set for any Moxi Works Platform transaction
     *
     */
    public $moxi_works_agent_id;

    /**
     * @var string the UUID of the office which the Agent is associated
     */
    public $moxi_works_office_id;

    /**
     * @var string the id of the agent used by the company of the agent
     */
    public $client_agent_id;

    /**
     * @var string the id office used by the company of the agent
     */
    public $client_office_id;

    /**
     * @var string the id of the company used by the company of the agent
     */
    public $client_company_id;

    /**
     * @var string the agent's MLS ID
     */
    public $mls_agent_id;

    /**
     * @var string the agent's license number
     */
    public $license;

    /**
     * @var string agent's primary MLS name
     */
    public $mls_name;

    /**
     * @var string the agent's primary MLS abbreviation
     */
    public $mls_abbreviation;


    /**
     * @var string the agent's ID
     */
    public $agent_id;


    /**
     * @var string the agent's Office ID
     */
    public $office_id;


    /**
     * @var string the agent's Company ID
     */
    public $company_id;


    /**
     * @var string the agent's alternate phone number
     */
    public $alt_phone_number;


    /**
     * @var string the agent's lead routing email address
     */
    public $lead_routing_email_address;


    /**
     * @var string the agent's GCI goal
     */
    public $gc_goal;


    /**
     * @var string the agent's office address, street and number
     */
    public $office_address_street;

    /**
     * @var string the agent's office address, suite or office number
     */
    public $office_address_street2;

    /**
     * @var string the agent's office address, city
     */
    public $office_address_city;

    /**
     * @var string the agent's office address, state
     */
    public $office_address_state;

    /**
     * @var string the agent's office address, zip code
     */
    public $office_address_zip;

    /**
     * @var string the agent's name
     */
    public $name;

    /**
     * @var string agent's mobile phone number
     */
    public $mobile_phone_number;

    /**
     * @var string agent's home phone number
     */
    public $home_phone_number;

    /**
     * @var string agent's fax phone number
     */
    public $fax_phone_number;

    /**
     * @var string agent's main phone number
     */
    public $main_phone_number;

    /**
     * @var string agent's office phone number
     */
    public $office_phone_number;

    /**
     * @var string agent's primary email address
     */
    public $primary_email_address;

    /**
     * @var string agent's secondary email address
     */
    public $secondary_email_address;

    /**
     * @var string agent's twitter handel
     */
    public $twitter;

    /**
     * @var string agent's google plus page url
     */
    public $google_plus;

    /**
     * @var string agent's facebook page url
     */
    public $facebook;

    /**
     * @var string agent's home page url
     */
    public $home_page;

    /**
     * @var string url of a full sized profile image of the agent
     */
    public $profile_image_url;

    /**
     * @var string url of a thumb sized profile image of the agent
     */
    public $profile_thumb_url;

    /**
     * @var string title -- any business titles associated with the agent
     */
    public $title;

    /**
     * @var string uuid -- uuid of the agent
     */
    public $uuid;

    /**
     * @var boolean has_engage_access -- whether the agent has access to MoxiWorks Engage
     */
    public $has_engage_access;


    /**
     * @var Array available_mls -- available MLSs for this agent
     */
    public $available_mls;


    /**
     * @var Array alternate_offices -- alternate offices for this agent
     */
    public $alternate_offices;

    /**
     * @var Integer gci_goal -- GCI goal for this agent
     */
    public $gci_goal;


    /**
     * @var String region -- region for this agent
     */
    public $region;


    /**
     * @var Float buyer_commission_rate -- buyer commission rate for this agent
     */
    public $buyer_commission_rate;


    /**
     * @var Float seller_commission_rate -- seller commission rate for this agent
     */
    public $seller_commission_rate;

    /**
     * @var string agent's first name
     */
    public $first_name;

    /**
     * @var string agent's last name
     */
    public $last_name;
    /**
     * @var timestamp $created_timestamp
     */
    public $created_timestamp;
    /**
     * @var $deactivated_timestamp
     */
    public $deactivated_timestamp;
    /**
     * @var timestamp last updated
     */
    public $last_updated;



    /**
     * Agent constructor.
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
     * Find an Agent on Moxi Works Platform.
     *
     * find can be performed including the Moxi Works Agent ID in a parameter array
     *  <code>
     *  \MoxiworksPlatform\Agent::find([moxi_works_agent_id: 'abc123'])
     *  </code>
     * @param array $attributes
     *       <br><b>moxi_works_agent_id *REQUIRED* </b>The Moxi Works Agent ID for the agent
     *       <br><b>agent_uuid *REQUIRED* </b>The Moxi Works UUID for the agent -- either
     *
     *
     * @return Agent|null
     *
     * @throws ArgumentException if required parameters are not included
     * @throws RemoteRequestFailureException
     */
    public static function find($attributes=[]) {
        $agent_identifier = $attributes['agent_uuid'] ? : $attributes['moxi_works_agent_id'];
        if(!$agent_identifier)
            throw new ArgumentException("either agent_uuid or moxi_works_agent_id is required");
        $url = Config::getUrl() . "/api/agents/" . $agent_identifier;
        return Agent::sendRequest('GET', $attributes, $url);
    }

    /**
     * Search for Agents by Company on Moxi Works Platform.
     *
     * search can be performed by including moxi_works_company_id and updated_since in a parameter array
     *  <code>
     *  \MoxiworksPlatform\Agent::search([moxi_works_company_id: 'abc123', updated_since: 1463595006])
     *  </code>
     * @param array $attributes
     *       <br><b>moxi_works_company_id *REQUIRED* </b> string The Moxi Works Company ID for the company in which we are searching for agents
     *
     *       <h2>
     *     optional Task search parameters
     * </h2>
     *       <br><b>updated_since </b> integer  Unix timestamp representing the start time for the search. If no <i>updated_since</i> parameter is included in the request, only agents updated in the last seven days will be included in the response.
     *
     * @return Array paged response array with the format:
     *   [
     *     page_number: [Integer],
     *     total_pages: [Integer],
     *     agents:  [Array] containing MoxiworkPlatform\Agent objects
     *   ]
     *
     * @throws ArgumentException if required parameters are not included
     * @throws ArgumentException if at least one search parameter is not defined
     * @throws RemoteRequestFailureException
     */
    public static function search($attributes=[]) {
        $method = 'GET';
        $url = Config::getUrl() . "/api/agents";
        $agents = array();

        $required_opts = array('moxi_works_company_id');

        if(count(array_intersect(array_keys($attributes), $required_opts)) != count($required_opts))
            throw new ArgumentException(implode(',', $required_opts) . " are required");

        $json = Resource::apiConnection($method, $url, $attributes);

        if(!isset($json) || empty($json))
            return null;

        foreach ($json['agents'] as $c) {
            $agent = new Agent($c);
            array_push($agents, $agent);
        }
        $json['agents'] = $agents;
        return $json;
    }


    /**
     * @param $method
     * @param array $opts
     * @param null $url
     *
     * @return Agent|null
     *
     * @throws ArgumentException if required parameters are not included
     * @throws RemoteRequestFailureException
     */
    private static function sendRequest($method, $opts=[], $url=null) {
        if($url == null) {
            $url = Config::getUrl() . "/api/agents";
        }
        $required_opts = array('moxi_works_company_id');
        if(count(array_intersect(array_keys($opts), $required_opts)) != count($required_opts))
            throw new ArgumentException(implode(',', $required_opts) . " required");
        $agent = null;
        $json = Resource::apiConnection($method, $url, $opts);
        $agent = (!isset($json) || empty($json)) ? null : new Agent($json);
        return $agent;
    }
}