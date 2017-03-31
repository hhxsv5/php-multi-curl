<?php


namespace Hhxsv5\PhpMultiCurl;


abstract class BaseCurl
{
    protected $handle;

    /**
     * [errno, errstr]
     * @var array
     */
    protected $error;

    public function __construct(array $options = [])
    {
        $this->init($options);
    }

    abstract protected function init(array $options = []);

    abstract protected function exec(array $options = []);

    abstract protected function hasError();

    public function getError()
    {
        return $this->error;
    }

    public function getHandle()
    {
        return $this->handle;
    }

    abstract public function __destruct();
}