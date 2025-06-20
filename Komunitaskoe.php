<?php

// README
// ======
// Ini adalah sebuah aplikasi komunitas untuk saling bertukar pesan.
// Misi Anda adalah melengkapi potongan kode yang disediakan sehingga aplikasi dapat berjalan sesuai kebutuhan.
//
// OUTPUT YANG DIHARAPKAN:
/* 
List channel terurut abjad:
Cerdaskan Bangsa
Gila Bola
Politik Cerdas

Lutvi bergabung ke semua channel
Daftar channel dimana Lutvi terdaftar:
Gila Bola
Politik Cerdas
Cerdaskan Bangsa

Ricky bergabung ke channel Gila Bola
Lutvi mencoba bergabung ke channel Gila Bola
Jumlah anggota Gila Bola: 2

Rizal mencari channel yang mengandung kata "cerdas", dan bergabung ke channel hasil pencarian
Politik Cerdas
Cerdaskan Bangsa

Ricky bergabung ke channel Politik Cerdas
Daftar anggota channel Politik Cerdas:
Lut
Rizal
Ricky

List channel terurut jumlah anggota:
Politik Cerdas (3)
Gila Bola (2)
Cerdaskan Bangsa (2)

Lutvi: Selamat bergabung Ricky & Rizal
Rizal: Hai
Ricky: Hai Lutvi
Lutvi: Halo semuanyaa
 */

$app = new Komunitaskoe();
$app->run();

class Komunitaskoe
{
    // Code class Komonitaskoe tidak untuk di ganti

    public function run() {
        // Lut, Ricky, Rizal bergabung ke aplikasi Komunitaskoe
        $lut = new Person("Lut");
        $ricky = new Person("Ricky");
        $rizal = new Person("Rizal");

        // Saat ini, ada 3 channel yang tersedia
        $gilaBola = Channel::create("Gila Bola");
        $politikCerdas = Channel::create("Politik Cerdas");
        $cerdaskanBangsa = Channel::create("Cerdaskan Bangsa");

        // Tampilkan semua channel secara alfabetis
        debug('List channel terurut abjad:');
        foreach (Channel::getListByName() as $availableChannel) {
            debug($availableChannel->getName());
        }
        debug('');

        debug("Lutvi bergabung ke semua channel");
        $lut->joinChannel($gilaBola);
        $lut->joinChannel($politikCerdas);
        $lut->joinChannel($cerdaskanBangsa);

        debug('Daftar channel dimana Lutvi terdaftar:');
        foreach ($lut->getChannels() as $channel) {
            debug($channel->getName());
        }
        debug('');

        debug('Ricky bergabung ke channel Gila Bola');
        $ricky->joinChannel($gilaBola);
        debug("Lutvi mencoba bergabung ke channel Gila Bola");
        $lut->joinChannel($gilaBola);
        // Jumlah anggota gila bola seharusnya tetap 2
        debug('Jumlah anggota Gila Bola: ' . $gilaBola->getMemberCount());
        debug('');

        debug('Rizal mencari channel yang mengandung kata "cerdas", dan bergabung ke channel hasil pencarian');
        $channelCerdas = Channel::search("cerdas");
        foreach ($channelCerdas as $channel) {
            debug($channel->getName());
            $rizal->joinChannel($channel);
        }
        debug('');

        debug('Ricky bergabung ke channel Politik Cerdas');
        $ricky->joinChannel($politikCerdas);
        debug('Daftar anggota channel Politik Cerdas:');
        foreach ($politikCerdas->getMembers() as $member) {
            debug($member->getName());
        }
        debug('');

        debug('List channel terurut jumlah anggota:');
        foreach (Channel::getListByMemberCount() as $availableChannel) {
            debug($availableChannel->getName() . "(" . $availableChannel->getMemberCount() . ")");
        }
        debug('');

        // Lutvi mengirim chat di chanel Politik Cerdas
        $politikCerdas->addMessage(new Message($lut, 'Selamat bergabung Ricky & Rizal'));
        // Rizal dan ricky membalas
        $politikCerdas->addMessage(new Message($rizal, 'Hai'));
        $politikCerdas->addMessage(new Message($ricky, 'Hai Lutvi'));
        // Lutvi membalas lagi
        $politikCerdas->addMessage(new Message($lut, 'Halo semuanyaa'));
        // Tampilkan pesan dalam urutan pesan baru ada di bawah
        foreach ($politikCerdas->getMessages() as $message) {
            debug($message->getInfo());
        }
        debug('');
    }
}

class Message
{
    // <YOUR CODE HERE>
    private $person;
    private $message;

    public function __construct(Person $person, string $message)
    {
        $this->person = $person;
        $this->message = $message;
    }
    
    public function getInfo() : string
    {
        return $this->person->getName() . ': ' . $this->message;
    }
}

class Person
{
    // <YOUR CODE HERE>
    private $name;
    private $channels = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function joinChannel(Channel $channel)
    {
        if (!in_array($channel, $this->channels, true)) {
            $this->channels[] = $channel;
            $channel->addMember($this);
        }
    }

    public function getChannels() : array
    {
        return $this->channels;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

class Channel
{
    // <YOUR CODE HERE>
    private static $channels = [];
    private $name;
    private $members = [];
    private $messages = [];

    public static function create(string $name) : Channel
    {
        $channel = new Channel($name);
        self::$channels[] = $channel;
        return $channel;
    }
    public static function search(string $keyword): array
    {
        $result = [];
        foreach (self::$channels as $channel) {
            if (stripos($channel->name, $keyword) !== false) {
                $result[] = $channel;
            }
        }
        return $result;
    }

    public static function getListByName() : array
    {
        $sorted = self::$channels;
        usort($sorted, function ($a, $b) {
            return strcmp($a->name, $a->name);
        });
        return $sorted;
    }

    public static function getListByMemberCount() : array
    {
        $sorted = self::$channels;
        usort($sorted, function ($a, $b) {
            return $b->getMemberCount() <=> $a->getMemberCount();
        });
        return $sorted;
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public function getName() : string
    {
        return $this->name;
    }

    public function getMemberCount() : int
    {
        return count($this->members);
    }

    public function getMembers(): array
    {
        return $this->members;
    }

    public function addMember(Person $person)
    {
        if (!in_array($person, $this->members, true)) {
            $this->members[] = $person;
        }
    }

    public function addMessage(Message $msg)
    {
        $this->messages[] = $msg;
    }

    public function getMessages() : array
    {
        return $this->messages;
    }
}

function debug($string)
{
    $separator = (php_sapi_name() == 'cli') ? "\n" : "<br>";
    echo $string . $separator;
}