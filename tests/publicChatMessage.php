<?php
use PHPUnit\Framework\TestCase;
use iiroseLib\IIRose_Packet;

class publicChatMessage extends TestCase
{

    public function testPublicChatMessage ()
    {
        $packet = new IIRose_Packet();
        $expected = Array(
                array(
                        "type" => "publicChat",
                        "data" => Array(
                                1552736689,
                                "•rÄË¿Õ",
                                "?"
                        )
                )
        );
        $result = $packet->parseServerPacket(
                "1552736689>https://i.loli.net/2019/03/04/5c7ce94b70eb4.jpg>•rÄË¿Õ>?>fcf2e6>fcf2e6>2>>5c4766c59fc21>'>874912923366");
        $this->assertEquals($expected, $result);
    }
}
