<?php
namespace iirose-lib-php;

class IIRose_Packet
{
    public function parseServerPacketArr($packet)
    {
        $inflated = self::inflatePacket($packet);
        $splited = self::splitPacket($inflated);

        return $splited;
    }

    public function parseServerPacket($packet)
    {
        $inflated = self::inflatePacket($packet);
        $splited = self::splitPacket($inflated);
        $returnval = '';

        foreach ($splited as $splitedEach) {
            if (preg_match('/\\d+/', substr($splitedEach[0], 0, 1))) {
                $returnval .= self::publicMessage($splitedEach);
            }
        }

        if(!$returnval) $returnval = "nothing\n";

        return $returnval;
    }

    protected function publicMessage($message)
    {
        if (count($message) == 11) {
            return self::publicChatMessage($message);
        } elseif (count($message) == 12) {
            return self::publicSystemMessage($message);
        }
    }

    protected function publicChatMessage($message)
    {
        return '['.date('Y-m-d H:i:s', $message[0]).'] '.html_entity_decode($message[2]).' said: '.html_entity_decode($message[3])."\n";
    }

    protected function publicSystemMessage($message)
    {
    }

    protected function inflatePacket($packet)
    {
        if (substr($packet, 0, 1) == chr(1)) {
            return self::gzBody(substr($packet, 1));
        } else {
            return $packet;
        }
    }

    protected function gzBody($gzData)
    {
        if (substr($gzData, 0, 3) == "\x1f\x8b\x08") {
            $i = 10;
            $flg = ord(substr($gzData, 3, 1));
            if ($flg > 0) {
                if ($flg & 4) {
                    list($xlen) = unpack('v', substr($gzData, $i, 2));
                    $i = $i + 2 + $xlen;
                }
                if ($flg & 8) {
                    $i = strpos($gzData, "\0", $i) + 1;
                }
                if ($flg & 16) {
                    $i = strpos($gzData, "\0", $i) + 1;
                }
                if ($flg & 2) {
                    $i = $i + 2;
                }
            }

            return gzinflate(substr($gzData, $i, -8));
        } else {
            return false;
        }
    }

    protected function splitPacket($buffer)
    {
        $msgs = explode('<', $buffer);
        $arr = array();
        foreach ($msgs as $value) {
            $arr[] = explode('>', $value);
        }

        return $arr;
    }
}
