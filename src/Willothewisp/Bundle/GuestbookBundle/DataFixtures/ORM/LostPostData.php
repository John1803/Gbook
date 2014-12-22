<?php

namespace Willothewisp\Bundle\GuestbookBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;
use Willothewisp\Bundle\GuestbookBundle\Entity\Post;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $posts = Yaml::parse(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'posts.yml'));

        foreach ($posts['posts'] as $key => $value) {
            $post = new Post();
            $post
                ->setAuthor($value['author'])
                ->setPost($value['post'])
                ->setUrl($value['url'])
                ->setRating($value['rating'])
            ;

            $manager->persist($post);
        }

        $manager->flush();
    }
}