<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 25.12.14
 * Time: 17:04
 */

namespace Willothewisp\Bundle\GuestbookBundle\PostTimer;


use Symfony\Component\HttpFoundation\Request;

interface PostTimerInterface
{
    public function requestProcess(Request $request);
}