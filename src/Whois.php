<?php

class Whois {
  private $server;

  public function __construct($server) {
    $this->server = $server;
  }

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
