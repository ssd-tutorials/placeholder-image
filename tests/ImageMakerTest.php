<?php

use org\bovigo\vfs\vfsStream;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;

use App\Utilities\ImageMaker;

class ImageMakerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $directory;

    public function setUp()
    {
        $this->directory = vfsStream::url('test-directory');
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function generates_new_image_with_pre_defined_width_height_and_background()
    {
        $image = Mockery::mock(Image::class);
        $image->shouldReceive('insert')
              ->andReturn(Image::class);
        $image->shouldReceive('save')
              ->andReturn(Image::class);

        $imageManager = Mockery::mock(ImageManager::class);
        $imageManager->shouldReceive('canvas')
                     ->once()
                     ->andReturn($image);

        $imageMaker = new ImageMaker($imageManager);
        $imageMaker->setDirectory($this->directory);

        $path = $imageMaker->canvas([
            'width' => 600,
            'height' => 600,
            'background' => 'aaa',
            'extension' => 'jpg'
        ]);

        $this->assertEquals(
            $this->preDefinedFilePath(),
            $path
        );
    }

    private function preDefinedFilePath()
    {
        return $this->directory . '/' . implode('-', [
            600,
            600,
            'aaa'
        ]) . '.jpg';
    }

    /**
     * @test
     */
    public function generates_new_image_with_default_width_height_and_background()
    {
        $image = Mockery::mock(Image::class);
        $image->shouldReceive('insert')
              ->andReturn(Image::class);
        $image->shouldReceive('save')
              ->andReturn(Image::class);

        $imageManager = Mockery::mock(ImageManager::class);
        $imageManager->shouldReceive('canvas')
                     ->once()
                     ->andReturn($image);

        $imageMaker = new ImageMaker($imageManager);
        $imageMaker->setDirectory($this->directory);

        $path = $imageMaker->canvas();

        $this->assertEquals(
            $this->defaultFilePath(),
            $path
        );
    }

    private function defaultFilePath()
    {
        return $this->directory . '/' . implode('-', [
            600,
            400,
            'ffffff'
        ]) . '.png';
    }
}















