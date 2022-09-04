<?php
// api/src/Encoder/MultipartDecoder.php

namespace App\Encoder;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';

    public function __construct(private RequestStack $requestStack) {}

    /**
     * {@inheritdoc}
     */
    public function decode(string $data, string $format, array $context = []): ?array
    {
        $request = $this->requestStack->getCurrentRequest();

        // dd($request);
        if (!$request) {
            return null;
        }
        if(isset($request->request->all()["prix"])){
            $prix = intval($request->request->all()["prix"]);
            $request->request->set('prix',$prix);
        }
        
        // dd($prix);
       
        // dd($request->request->all()["prix"]);
        // $request->request->all()->
        return array_map(static function ($element) {
            // dd($element);
            // Multipart form values will be encoded in JSON.
            // dd($element);
            // dump($element);
            if(is_array($element)){
                $decoded = $element;
            }else{  
                $decoded = json_decode($element, true);
            }
            return \is_array($decoded) ? $decoded : $element;
        }, $request->request->all()) + $request->files->all();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}