<?php
use Helpers\Url;

class Settings extends Controller
{
	protected $backup;
    public function index()
    {
        $this->backup = $this->model('Backup');
        echo '<pre>' . $this->backup->getBackupAsString() . '</pre>';
    }
}