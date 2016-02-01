<?php namespace M44rt3np44uw\OPSkins;

use GuzzleHttp\Client;

/**
 * Class OPSkins
 * @package M44rt3np44uw\LaravelOPSkins
 */
class OPSkins
{
    /**
     * API URL
     */
    CONST API_URL = "https://opskins.com/api/user_api.php";

    /**
     * @var Client
     */
    private $client;

    /**
     * @var
     */
    private $api_token;

    /**
     * Different request methods.
     *
     * @var array
     */
    private $requests = [
        "test",
        "GetActiveSales",
        "GetStaleSales",
        "ResendTrade",
        "GetOP",
        "Cashout",
        "BumpItem",
        "EditItem",
        "SellItem",
        "GetPriceHistory"
    ];

    /**
     * Different payout amounts.
     *
     * @var array
     */
    private $amounts = [
        500,
        1000,
        2000,
        3500,
        5000,
        10000,
        25000,
        50000,
        100000,
        200000,
        299900,
        400000,
        500000
    ];

    /**
     * OPSkins constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        // Set the client.
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->api_token;
    }

    /**
     * @param mixed $api_token
     */
    public function setApiToken($api_token)
    {
        $this->api_token = $api_token;
    }

    /**
     * @param $request
     * @param $arguments
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __call($request, $arguments)
    {
        // Uppercace first character.
        $request = ucfirst($request);

        // Check if it is a valid call.
        if(in_array($request, $this->requests))
        {
            // Make API call.
            return $this->_APICall($request, $arguments);
        }
    }

    /**
     * @param $request_params
     * @return string
     */
    private function _getRequestParams($request_params)
    {
        // Add key.
        $params = [
            'key' => $this->getApiToken()
        ];

        // Add each param in the add_param array.
        if(is_array($request_params))
        {
            foreach($request_params as $key => $value)
            {
                // Check if the key is amount
                if($key == 'amount')
                {
                    // If the value is not in the amount array.
                    if(!in_array($value, $this->amounts))
                    {
                        // Set the value to the first amount.
                        $value = $this->amounts[0];
                    }
                }

                // Add each params.
                array_add($params, $key, $value);
            }
        }

        // Return query.
        return http_build_query($params);
    }

    /**
     * @param $request
     * @param array $params
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function _APICall($request, $params = [])
    {
        // Default request params.
        $request_params = [
            'request' => $request
        ];

        // Check if the params array isset and not empty.
        if(isset($params) && !empty($params))
        {
            // Merge both arrays.
            $request_params = array_merge($request_params, $params);
        }

        // Return response.
        return $this->client->get(OPSkins::API_URL . "?" . $this->_getRequestParams($request_params));
    }
}