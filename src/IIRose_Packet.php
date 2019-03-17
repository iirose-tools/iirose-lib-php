<?php
namespace iiroseLib;

class IIRose_Packet
{

    /**
     * Parse server packet and return raw array
     *
     * @return array
     *
     * @param
     *            string
     */
    public function parseServerPacketArr ($packet)
    {
        $inflated = self::inflatePacket($packet);
        if (! $inflated)
            return array();
        $splited = self::splitPacket($inflated);

        return $splited;
    }

    /**
     * Parse server packet and return parsed array
     *
     * @return array
     * @param
     *            string
     */
    public function parseServerPacket ($packet)
    {
        $inflated = self::inflatePacket($packet);
        if (! $inflated)
            return array();
        $splited = self::splitPacket($inflated);
        $returnval = array();

        foreach ($splited as $splitedEach) {
            if (preg_match('/\\d+/', substr($splitedEach[0], 0, 1))) {
                $returnval[] = self::publicMessage($splitedEach);
            }
        }

        if (! count($returnval))
            $returnval = array();

        return $returnval;
    }

    protected function publicMessage ($message)
    {
        // Got a public chat packet
        if (count($message) == 11) {
            return array(
                    "type" => "publicChat",
                    "data" => self::publicChatMessage($message)
            );
            // Got a public system message packet
        } elseif (count($message) == 12) {
            return array(
                    "type" => "publicSystem",
                    "data" => self::publicSystemMessage($message)
            );
        }
    }

    protected function publicChatMessage ($message)
    {
        $timestamp = $message[0];
        $user = $message[2];
        $msg = $message[3];
        return array(
                $timestamp,
                $user,
                $msg
        );
    }

    protected function publicSystemMessage ($message)
    {}

    protected function inflatePacket ($packet)
    {
        if (substr($packet, 0, 1) == chr(1)) {
            return self::gzBody(substr($packet, 1));
        } else {
            return $packet;
        }
    }

    protected function gzBody ($gzData)
    {
        // gzip packet header detection
        if (substr($gzData, 0, 3) == "\x1f\x8b\x08") {
            $i = 10;
            $flg = ord(substr($gzData, 3, 1));
            if ($flg > 0) {
                if ($flg & 4) {
                    list ($xlen) = unpack('v', substr($gzData, $i, 2));
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

            return gzinflate(substr($gzData, $i, - 8));
            // Bad packet
        } else {
            return false;
        }
    }

    protected function splitPacket ($buffer)
    {
        $msgs = explode('<', $buffer);
        $arr = array();
        foreach ($msgs as $value) {
            $arr[] = explode('>', $value);
        }

        return $arr;
    }
}
