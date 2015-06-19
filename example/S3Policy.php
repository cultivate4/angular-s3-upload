<?php

class S3Policy
{
    private $bucket_name;
    private $aws_access_key_id;
    private $aws_secret_key;

    public function __construct($bucket, $secret, $key)
    {
        $this->bucket_name = $bucket;
        $this->aws_secret_key = $secret;
        $this->aws_access_key_id = $key;
    }

    public function access_token()
    {
        $now = time() + (12 * 60 * 60 * 1000);
        $expire = gmdate('Y-m-d\TH:i:s\Z', $now);
        $url = 'https://' . $this->bucket_name . '.s3.amazonaws.com';
        $policyDocument = [
            'expiration' => $expire,
            'conditions' => [
                ['bucket' => $this->bucket_name],
                ['acl' => 'public-read'],
                ['content-length-range', 0, 10485760], // 10MB!
                ['starts-with', '$Content-Type', ''],
                ['starts-with', '$key', '']
            ]
        ];
        $encodedPolicyDocument = json_encode($policyDocument);
        $policy = base64_encode($encodedPolicyDocument);
        $hash = $this->hmacsha1($this->aws_secret_key, $policy);
        $signature = $this->hex2b64($hash);
        $token = [
            'policy'    => $policy,
            'signature' => $signature,
            'key'       => $this->aws_access_key_id
        ];
        return $token;
    }

    private function hmacsha1($key, $data)
    {
        $blocksize = 64;
        $hashfunc = 'sha1';
        if (strlen($key) > $blocksize) {
            $key = pack('H*', $hashfunc($key));
        }
        $key = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);
        $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));
        return bin2hex($hmac);
    }

    private function hex2b64($str)
    {
        $raw = '';
        for ($i = 0; $i < strlen($str); $i += 2) {
            $raw .= chr(hexdec(substr($str, $i, 2)));
        }
        return base64_encode($raw);
    }
}
