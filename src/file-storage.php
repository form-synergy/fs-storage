<?php
namespace FormSynergy;

/**
 * FormSynergy File Storage.
 *
 * This package is used by the Form Synergy php-api.
 * It enables storing, updating, and retrieving stored data, in a local directory.
 *
 * @author     Joseph G. Chamoun <formsynergy@gmail.com>
 * @copyright  2019 FormSynergy.com
 * @licence    https://github.com/form-synergy/file-storage/blob/dev-master/LICENSE MIT
 */
 
/**
 * File_Storage class
 *
 * @version 1.5
 *
 */
class File_Storage
{
    /**
     * @visibility public
     * @var string $package
     */
    public $package;

    /**
     * @visibility public
     * @var string $get
     */
    public $get = false;

    /**
     * @visibility public
     * @var string $sub
     */
    public $sub = false;

    /**
     * @visibility public
     * @var string $storage
     */
    public $storage;

    /**
     * @visibility public
     * @var string $file
     */
    public $file;

    /**
     * @visibility public
     * @var string $files
     */
    public $files;

    /**
     * @visibility public
     * @var string $action
     */
    public $action;

    /**
     * @visibility public
     * @var string $find
     */
    public $find;

    /**
     * Class constructor.
     *
     *
     * @visibility public
     * @param string $package
     * @param  string $storage
     * @return self
     */
    public function __construct($package, $storage)
    {
        $this->package = $package;
        $this->storage = $storage;
        return $this;
    }
 
    /**
     * Data()
     *
     * The data that needs to be stored.
     *
     *
     * @visibility public
     * @param array $data
     * @return void
     */
    public function Data($data)
    {
        switch ($this->action) {
            case 'store':
                $data = file_put_contents($this->file, json_encode($data));
                $this->files[$this->file] = $data;
                break;
            case 'update':
                $store_data = false;
                $replace    = false;
                if (file_exists($this->file)) {
                    $stored_data = json_decode(file_get_contents($this->file), true);
                }
                if ($stored_data) {
                    $replace = array_replace($stored_data, $data);
                    
                }
                if ($replace) {
                    $this->files[$this->file] = $replace;
                    file_put_contents($this->file, json_encode($replace));
                }
                break;
        }
    }

    /**
     * Html()
     *
     * The data that needs to be stored.
     *
     *
     * @visibility public
     * @param array $data
     * @return void
     */
    public function StoreModule($id, $data)
    {
        $file = $this->storage . '/';
        $file .= '-' . $id . '.html';
        file_put_contents($file, $data);
    }

    /**
     * Store()
     *
     * Will store retrieved responses in json format.
     *
     *
     * @visibility public
     * @param string $name
     * @return self
     */
    public function Store($name)
    {
        $file = $this->storage . '/';
        $file .= $this->package;
        if (!is_null($name)) {
            $file .= '-' . $name . '.json';
        } else {
            $file .= '.json';
        }
        $this->action = 'store';
        $this->file = $file;
        return $this;
    }

    /**
     * Update()
     *
     * Will update a previously stored response.
     *
     *
     * @visibility public
     * @param string $name
     * @return self
     */
    public function Update($name)
    {
        $data = false;
        $replace = false;
        $file = $this->storage . '/';
        $file .= $this->package;
        if (!is_null($name)) {
            $file .= '-' . $name . '.json';
        } else {
            $file .= '.json';
        }
        $this->action = 'update';
        $this->file = $file;
        return $this;
    }

    /**
     * Get()
     *
     * Will retrieve stored data with $name.
     *
     *
     * @visibility public
     * @param string $name
     * @return array $data
     */
    public function Get($name)
    {
        $data = false;
        $replace = false;
        $file = $this->storage . '/';
        $file .= $this->package;
        $file .= '-' . $name . '.json';
        return isset($this->files[$file]) 
            ? $this->files[$file]
            : file_exists($file)
            ? json_decode(file_get_contents($file), true)
            : false;
    }

    /**
     * Find()
     *
     * Will find key and sub within the retrieved data.
     *
     *
     * @visibility public
     * @see self::In()
     * @param string $key
     * @param string $sub
     * @return array
     */
    public function Find($key, $sub = null)
    {
        if (!$this->get) {
            if (!is_null($sub)) {
                $this->sub = $sub;
            }
            $this->find = $key;
            return $this;
        }
        if ($this->get && isset($this->get[$key])) {
            return $this->get[$key];
        }
        return false;
    }

    /**
     * In()
     *
     * Will load the stored data by name
     *
     *
     * @visibility public
     * @param string $name
     * @return array
     */
    public function In($name)
    {
        if ($this->find) {
            $file = $this->storage . '/';
            $file .= $this->package;
            $file .= '-' . $name . '.json';
            $data = $this->Get($file);
            $return = $data && isset($data[$this->find]) 
                ? $data[$this->find] 
                : false;

            if ($this->sub && $return) {
                return isset($return[$this->sub]) 
                    ? $return[$this->sub] 
                    : false;
            }
            return $return;
        }
    }
}
