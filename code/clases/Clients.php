<?php
namespace clases;
use GuzzleHttp\Client;

class Clients
{
    protected $usernameToken;
    protected $base_uri;
    protected $password = "X";
    protected $guzzle;
    protected $method = "GET";

    public function __construct($base_uri, $usernameToken)
    {
        $this->guzzle = new Client();
        $this->base_uri = $base_uri;
        $this->usernameToken = $usernameToken;
    }

    public function makeRequest($method, $endpoint, array $params = [])
    {
        $url = $this->base_uri . $endpoint;
        $response = $this->guzzle->request($method, $url, array_merge(['query' => $params], ['auth' => [$this->usernameToken, $this->password]]));
        $body = $response->getBody();
        $body = json_decode($body, true);
        return $body;
    }

    /**
     * @return mixed
     */
    public function getTickets()
    {
        $page = 1;
        $tickets = [];
          $response = $this->makeRequest($this->method, '/api/v2/tickets',[]);
          $tickets = array_merge($tickets, $response);
//        do {
//        $response = $this->makeRequest($this->method, '/api/v2/tickets',[]);
//        $tickets = array_merge($tickets, $response);
//            if (true === empty($response)) {
//                break;
//            }
//            $page++;
//        } while(true);
        return $tickets;
    }

    protected function getGroup($groupId)
    {
        return $this->makeRequest($this->method, '/api/v2/groups/' . $groupId);
    }

    protected function getAgent_Id($agentId)
    {
        return $this->makeRequest($this->method, ' /api/v2/agents/' . $agentId);
    }

    protected function getCompany_id($company_id)
    {
        return $this->makeRequest($this->method, '/api/v2/companies/' . $company_id);
    }

    protected function getContact_Id($conId)
    {
        return $this->makeRequest($this->method, '/api/v2/contacts/' . $conId);
    }

    public function createCsv()
    {
        $group = [];
        $comp = [];
        $cont = [];
        $agent = [];
        $line = [];
        $tickets = $this->getTickets();
        $headers = array("id-TICKET", "TICKET_STATUS", "SUBJECT-TICKET", "GROUP-NAME", "COMP-NAME", "CONTACTS-NAME", "AGENT-NAME");
        $file = fopen('Headers.csv', 'w+');
        fputcsv($file, $headers);
        foreach ($tickets as $ticket) {
            $group = $this->getGroup($ticket['group_id']);
            $comp = $this->getCompany_id($ticket['company_id']);
            $cont = $this->getContact_Id($ticket['requester_id']);
            if($ticket["responder_id"]!=null) {
                $agent = $this->getAgent_Id($ticket["responder_id"]);
                $line = [$ticket['id'], $ticket['status'], $ticket['subject'], $group['name'], $comp['name'], $cont['name'],$agent['contact']['name']];
                fputcsv($file, $line);
            }else continue;
        }
        fclose($file);
    }
}