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
    public function __construct(string $server) {
      $this->server = $server;
    }
  
    /**
     * Queries the WHOIS server for the specified domain name.
     *
     * @param string $domain The domain name to query.
     * @return string The response from the WHOIS server.
     * @throws Exception If the connection or query fails.
     */
    public function query(string $domain): string {
      $sanitized_domain = trim($domain, " \t\n\r\0\x0B.");
      if ($sanitized_domain === '') {
        throw new Exception("Invalid domain name: $domain", 400);
      }
  
      $context = stream_context_create([
        'ssl' => [
          'verify_peer' => false,
          'verify_peer_name' => false,
        ],
      ]);
  
      $socket = stream_socket_client("tcp://$this->server:43", $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);
      if (!$socket) {
        throw new Exception("Connection failed: $errstr", $errno);
      }
  
      fwrite($socket, "$sanitized_domain\r\n");
  
      $response = '';
      while (!feof($socket)) {
        $response .= fgets($socket, 128);
      }
      fclose($socket);
  
      if ($response === '' || strpos($response, 'Error: ') === 0) {
        throw new Exception("Query failed: $response", 500);
      }
  
      return $response;
    }
  }