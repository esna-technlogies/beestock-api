<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2016 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\JoseBundle\Controller;

use Jose\Object\JWKSetInterface;
use Symfony\Component\HttpFoundation\Response;

final class JWKSetController
{
    /**
     * @var \Jose\Object\JWKSetInterface
     */
    private $jwkset;

    /**
     * JWKSetController constructor.
     *
     * @param \Jose\Object\JWKSetInterface $jwkset
     */
    public function __construct(JWKSetInterface $jwkset)
    {
        $this->jwkset = $jwkset;
    }

    /**
     * @param string $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($_format)
    {
        if ('json' === $_format) {
            $value = json_encode($this->jwkset);
            $ct = 'application/jwk-set+json; charset=UTF-8';
        } else {
            $value = json_encode($this->jwkset->toPEM());
            $ct = 'application/json; charset=UTF-8';
        }

        return new Response($value, Response::HTTP_OK, ['content-type' => $ct]);
    }
}
