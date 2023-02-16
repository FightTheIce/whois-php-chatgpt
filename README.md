# whois-php-chatgpt
whois-php-chatgpt

Conversation with ChatGPT to generate a PHP class that queries a whois server.

# Conversation:

### Question: 

can you generate me a php class to query a whois server


### Response: 

Sure, here's an example PHP class you can use to query a WHOIS server:

```php
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
```

To use this class, you would first create an instance of it, passing in the WHOIS server you want to query:
```php
$whois = new Whois('whois.example.com');
```

Then you can call the query method on the instance to query a domain name:

```php
$response = $whois->query('example.com');
echo $response;
```

The query method opens a socket connection to the WHOIS server, sends a query for the specified domain name, reads the response, and returns it as a string. If the connection cannot be opened, the method returns false. Note that different WHOIS servers may have different query formats, so you may need to modify the query method for different servers.