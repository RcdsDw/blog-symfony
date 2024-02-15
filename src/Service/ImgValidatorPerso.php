<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImgValidatorPerso {

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger) {
        $this->slugger = $slugger;
    }
    
    function ImageVerifier($imageFile, $directory) {
        if ($imageFile && $directory) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
    
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
            try {
                $imageFile->move(
                    $directory,
                    $newFilename
                );
            } catch (FileException $e) {
                dump($e);
            }
    
            return $newFilename;
        }
    }
}