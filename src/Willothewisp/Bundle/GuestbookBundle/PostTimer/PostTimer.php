<?php

namespace Willothewisp\Bundle\GuestbookBundle\PostTimer;


use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;


class PostTimer implements PostTimerInterface
{

    public function requestProcess(Request $request)
    {
        $alreadyExistCookie = $request->cookies->get('name');
        $result = array(
            'response'  => new RedirectResponse('/'),
            'message'   => 'You have already created message! Try again lately!',
            'doSave'    => false
        );
        if (!$alreadyExistCookie) {
            $cookie = new Cookie('name', sha1(1234, 5678), time() + 60);
            $response = new RedirectResponse('/');
            $response->headers->setCookie($cookie);
            $result['response'] = $response;
            $result['message'] = 'Congratulations! Your message was saved! You can create new message after 1 minute!';
            $result['doSave'] = true;
        }
        return $result;
    }


}
