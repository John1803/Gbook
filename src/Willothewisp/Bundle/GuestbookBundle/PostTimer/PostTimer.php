<?php

namespace Willothewisp\Bundle\GuestbookBundle\PostTimer;


use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class PostTimer implements PostTimerInterface
{

    public function requestProcess(Request $request, $response)
    {
        $alreadyExistCookie = $request->cookies->get('name');
        $result = array(
            'response'  => $response,
            'message'   => 'You have already created message! Try again lately!',
            'doSave'    => false
        );
        if (!$alreadyExistCookie) {
            $result['cookie'] = new Cookie('name', sha1(1234, 5678), time() + 60);
            $result['message'] = 'Congratulations! Your message was saved! You can create new message after 1 minute!';
            $result['doSave'] = true;
        }
        return $result;
    }


    public function accessCheck(Request $request)
    {
        $session = $request->getSession();

        $createPost = $session->get('createLastPost');

        if ($createPost) {
            $timeNowMinusOneMin = new \DateTime('-1 minutes');

            return $timeNowMinusOneMin > $createPost ? true : false;
        }

        return true;
    }
}
