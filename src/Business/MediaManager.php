<?php

namespace App\Business;

use App\Entity\Lesson;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class MediaManager
{
    private EntityManagerInterface $em;
    private SluggerInterface $slugger;

    public function __construct(
        EntityManagerInterface $em,
        SluggerInterface $slugger
    )
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }


    public function hydrateMedia(Lesson $lesson): void
    {
        foreach ($lesson->getMedia() as $media) {
            if ($this->urlBelongsTo($media->getUrl(), ["youtube.com", "youtu.be"])) {
                $media->setType(Media::SOURCE_YOUTUBE);
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $media->getUrl(), $match);
                $media->setUrl('https://www.youtube.com/embed/' . $match[1]);
            } elseif ($this->urlBelongsTo($media, ["vimeo.com"])) {
                $media->setType(Media::SOURCE_VIMEO);
            } else {
                $media->setType(Media::SOURCE_OTHER);
            }
        }
    }


    private function urlBelongsTo(string $url, $domains = []): bool
    {
        $urlDomain = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));

        foreach ($domains as $domain) {
            if ($urlDomain === $domain) {
                return true;
            }
        }
        return false;
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}