<?php

namespace MyApp;
// session_start();
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $userids = [];
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "Server Started";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        // echo sprintf(
        //     'Connection %d sending message "%s" to %d other connection%s' . "\n",
        //     $from->resourceId,
        //     $msg,
        //     $numRecv,
        //     $numRecv == 1 ? '' : 's'
        // );

        $data = json_decode($msg, true);

        if ($data['type'] === 'login') {
            $this->userids[$from->resourceId] = $data['userid'];
            $payload = [
                'type' => 'login',
                'userid' => $data['userid'],
            ];
        } elseif ($data['type'] === 'message') {
            $payload = [
                'type' => 'message',
                'userid' => $this->userids[$from->resourceId] ?? 'Anónimo',
                'message' => $data['message'],
            ];
        }

        foreach ($this->clients as $client) {
            $client->send(json_encode($payload));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
        $userid = $this->userids[$conn->resourceId] ?? null;
        $this->clients->detach($conn);

        if ($userid) {
            $payload = [
                'type' => 'logout',
                'userid' => $userid,
            ];

            foreach ($this->clients as $client) {
                $client->send(json_encode($payload));
            }

            unset($this->userids[$conn->resourceId]);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
