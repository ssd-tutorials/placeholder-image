<?php

namespace App;

use App\Utilities\ImageMaker;

class Post
{
    /**
     * Image directory.
     *
     * @var string
     */
    const DIRECTORY = '/assets/post';

    /**
     * Image width.
     *
     * @var int
     */
    const IMAGE_WIDTH = 600;

    /**
     * Image height.
     *
     * @var int
     */
    const IMAGE_HEIGHT = 400;

    /**
     * Image background.
     *
     * @var string
     */
    const IMAGE_BACKGROUND = '000';

    /**
     * Post title.
     *
     * @var string
     */
    public $title;

    /**
     * Post image.
     *
     * @var string
     */
    public $image;


    /**
     * Get html image tag.
     *
     * @return string
     */
    public function imageTag()
    {
        $img  = '<img src="';
        $img .= $this->imageRelativePath();
        $img .= '" alt="';
        $img .= $this->title;
        $img .= '">';

        return $img;
    }

    /**
     * Get relative path of the image.
     *
     * @return string
     */
    private function imageRelativePath()
    {
        if ($this->hasImage()) {
            return self::DIRECTORY . '/' . $this->image;
        }

        return $this->makeImageRelativePath();
    }

    /**
     * Check if post has an image.
     *
     * @return bool
     */
    private function hasImage()
    {
        return (
            ! empty($this->image) &&
            is_file($this->imageAbsolutePath())
        );
    }

    /**
     * Get absolute path to post's image.
     *
     * @return string
     */
    private function imageAbsolutePath()
    {
        $path = realpath(__DIR__ . '/../');

        $path .= str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            self::DIRECTORY
        );

        $path .= DIRECTORY_SEPARATOR . $this->image;

        return $path;
    }

    /**
     * Get placeholder image relative path.
     *
     * @return string
     */
    private function makeImageRelativePath()
    {
        return ImageMaker::makeCanvas([
            'width' => self::IMAGE_WIDTH,
            'height' => self::IMAGE_HEIGHT,
            'background' => self::IMAGE_BACKGROUND
        ]);
    }


}