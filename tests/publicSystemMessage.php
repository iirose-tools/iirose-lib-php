<?php
// 1552736715>https://i.loli.net/2019/02/06/5c5a941a34ecf.jpg>Ê¬º¡>'1>s>281c0e>1>>5bb36dee4e52b>0±°±ÉµÄ±ÜÊÀÕß'>>15c1747c035f81'n''''
use PHPUnit\Framework\TestCase;
use iiroseLib\IIRose_Packet;

class publicSystemMessage extends TestCase
{

    public function testPublicSystemMessage ()
    {
        $packet = new IIRose_Packet();
        $expected = Array(
                array(
                        "type" => "publicSystem",
                        "data" => Array(
                                1552736715,
                                "Ê¬º¡",
                                "joined"
                        )
                )
        );
        $result = $packet->parseServerPacket(
                "1552736715>https://i.loli.net/2019/02/06/5c5a941a34ecf.jpg>Ê¬º¡>'1>s>281c0e>1>>5bb36dee4e52b>0±°±ÉµÄ±ÜÊÀÕß'>>15c1747c035f81'n''''");
        $this->assertEquals($expected, $result);
    }
}
