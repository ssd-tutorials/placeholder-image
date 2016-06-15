<?php


namespace App\Utilities;

use Intervention\Image\ImageManager;

class ImageMaker
{
    /**
     * @var string
     */
    private $directory = '/assets/image-maker/';

    /**
     * @var ImageManager
     */
    private $manager;

    /**
     * @var \Intervention\Image\Image
     */
    private $img;

    /**
     * @var int
     */
    private $width = 600;

    /**
     * @var int
     */
    private $height = 400;

    /**
     * @var string
     */
    private $extension = 'png';

    /**
     * @var string
     */
    private $background = 'ffffff';

    /**
     * @var string
     */
    private $file_name;

    /**
     * ImageMaker constructor.
     * @param ImageManager $manager
     */
    public function __construct(ImageManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Set directory.
     * 
     * @param string $path
     */
    public function setDirectory($path)
    {
        $this->directory = $path;
    }

    /**
     * Get directory.
     * 
     * @return string
     */
    public function getDirectory()
    {
        return rtrim($this->directory, '/');
    }

    /**
     * Get relative path to the placeholder image.
     *
     * @param array $options
     * @return string
     */
    public function canvas(array $options = [])
    {
        $this->init($options);

        if ( ! $this->isFile()) {
            $this->make();
        }

        return $this->relativeFilePath();
    }

    /**
     * Initialise all properties.
     *
     * @param array $options
     * @return void
     */
    private function init(array $options = [])
    {
        array_walk($options, [$this, 'associate']);

        $this->file_name = $this->fileName();
    }

    /**
     * Associate values with properties.
     *
     * @param string|int $value
     * @param string $key
     */
    private function associate($value, $key)
    {
        $this->{$key} = $value;
    }

    /**
     * Create new placeholder image.
     *
     * @return void
     */
    private function make()
    {
        $this->img = $this->manager->canvas(
            $this->width,
            $this->height,
            $this->background
        );

        $this->img->insert($this->watermark(), 'center');
        $this->img->save($this->absoluteFilePath());
    }

    /**
     * Absolute path to the watermark image file.
     *
     * @return string
     */
    private function watermark()
    {
        return $this->absoluteDirectoryPath('watermark.png');
    }

    /**
     * Check if file exists.
     *
     * @return bool
     */
    private function isFile()
    {
        return is_file($this->absoluteFilePath());
    }

    /**
     * Get absolute path to the file.
     *
     * @return string
     */
    private function absoluteFilePath()
    {
        return $this->absoluteDirectoryPath($this->file_name);
    }

    /**
     * Get absolute path to a directory or a file.
     *
     * @param null|string $append
     * @return string
     */
    private function absoluteDirectoryPath($append = null)
    {
        $path = realpath(__DIR__ . '/../../');

        $path .= str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            $this->relativeDirectoryPath($append)
        );

        return $path;
    }

    /**
     * Get relative path to a directory or a file.
     *
     * @param null|string $append
     * @return string
     */
    private function relativeDirectoryPath($append = null)
    {
        if (is_null($append)) {
            return $this->getDirectory();
        }

        return $this->getDirectory() . '/' . ltrim($append, '/');
    }

    /**
     * Get relative path to a file.
     *
     * @return string
     */
    private function relativeFilePath()
    {
        return $this->relativeDirectoryPath($this->file_name);
    }

    /**
     * Get new file name.
     *
     * @return string
     */
    private function fileName()
    {
        return implode('-', [
            $this->width,
            $this->height,
            $this->background
        ]) . '.' . $this->extension;
    }

    /**
     * Call methods statically.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $method = lcfirst(substr($name, 4));
        $self = new static(new ImageManager(['driver' => 'imagick']));

        return call_user_func_array([$self, $method], $arguments);
    }

}















