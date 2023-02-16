<?php

/**
 * Class for querying a WHOIS server.
 */
class Whois {
    /**
     * The hostname of the WHOIS server to query.
     *
     * @var string
     */
    private $server;
  
    /**
     * Constructor for the Whois class.
     *
     * @param string $server The hostname of the WHOIS server to query.
     */
    public function __construct($server) {
      $this->server = $server;
    }
  
    /**
     * Queries the WHOIS server for the specified domain name.
     *
     * @param string $domain The domain name to query.
     * @return string|false The response from the WHOIS server, or false on failure.
     */
    public function query($domain) {
      $socket = fsockopen($this->server, 43);
      if (!$socket) {
        return false;
      }
      fwrite($socket, "$domain\r\n");
      $response = '';
      while (!feof($socket)) {
        $response .= fgets($socket, 128);
      }
      fclose($socket);
      return $response;
    }
  }