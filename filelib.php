<?php

interface IFileTransfer {

    public function saveFile ($content, $path);



    public function loadFile ($path);



    public function deleteFile ($path);



    public function deleteDir ($location);



    public function mkDir ($location);



    public function zipDir ($dirLocation, $zipLocation);



    public function setConfig ($object);
}



class RemoteFileTransfer implements IFileTransfer {

    /** @var resource */
    private $session = null;

    private $sftp = null;

    /** @var stdClass */
    private $config = array (
        'host' => 'codiana.nti.tul.cz',
        'port' => 22,

        'username' => 'root',
        'password' => ''
    );



    private function is_file ($file) {
        $file = ltrim ($file, '/');
        return is_file ('ssh2.sftp://' . $this->sftp . '/' . $file);
    }



    public function __construct () {
        $this->config = (object)$this->config;
    }



    private function connect () {
        if ($this->session !== null)
            return $this->session;

        $this->session = ssh2_connect (
            $this->config->host,
            $this->config->port
        );

        if ($this->session == false)
            throw new Exception ('Cannot connect to server ' . $this->config->host);

        $result = ssh2_auth_password (
            $this->session,
            $this->config->username,
            $this->config->password
        );

        if ($result == false)
            throw new Exception ('Incorrect password for user' . $this->config->username);

        $this->sftp = ssh2_sftp ($this->session);
        if ($this->sftp == false)
            throw new Exception ('Cannot retriece SFTP subsystem ');
    }



    public function saveFile ($content, $path) {
        $this->connect ();

        $tmpFile = tmpfile ();
        if ($tmpFile == false)
            throw new Exception ('Cannot create temp file');

        $result = file_put_contents ($tmpFile, $content, FILE_BINARY);
        if ($result == false)
            fclose ($tmpFile);

        $uri = stream_get_meta_data ($tmpFile)['uri'];
        $result = ssh2_scp_send ($this->session, $uri, $path);
        fclose ($tmpFile);

        return $result;
    }



    public function loadFile ($path) {
        $this->connect ();

        $tmpFile = tmpfile ();
        if ($tmpFile == false)
            throw new Exception ('Cannot create temp file');

        $uri = stream_get_meta_data ($tmpFile)['uri'];
        $result = ssh2_scp_recv ($this->session, $path, $uri);
        if ($result == false)
            throw new Exception ('Error recieving file from server');

        $result = file_get_contents ($uri, FILE_BINARY);
        fclose ($tmpFile);
        if ($result == false)
            throw new Exception ('Error reading temp file');

        return $result;
    }



    public function deleteFile ($path) {
        $this->connect ();
        return ssh2_sftp_unlink ($this->sftp, $path);
    }



    public function deleteDir ($file) {
//        if ($this->is_file ($file))
//            return ssh2_sftp_unlink ($this->session, $file);
//        $filelist = $this->dirlist ($file);
//        if (is_array ($filelist)) {
//            foreach ($filelist as $filename => $fileinfo) {
//                $this->deleteDir ($file . '/' . $filename);
//            }
//        }
        return ssh2_sftp_rmdir ($this->session, $file);
    }



    public function mkDir ($location) {
        // TODO Implement mkDir () method
    }



    public function zipDir ($dirLocation, $zipLocation) {
        // TODO: Implement zipDir() method.
    }



    public function setConfig ($object) {
        if (!is_object ($object))
            $object = (object)$object;

        foreach ($object as $key => $value)
            $this->config->$key = $value;
    }

}



class LocalFileTransfer implements IFileTransfer {

    public function saveFile ($content, $path) {
        $result = file_put_contents ($path, $content, FILE_BINARY);
        return $result == false ? false : true;
    }



    public function loadFile ($path) {
        return file_get_contents ($path, FILE_BINARY);
    }



    public function deleteFile ($path) {
        return unlink ($path);
    }



    public function deleteDir ($path) {
        if (is_dir ($path)) {
            $files = array_diff (scandir ($path), array ('.', '..'));
            foreach ($files as $file)
                (is_dir ("$path/$file")) ? $this->deleteDir ("$path/$file") : unlink ("$path/$file");
            return rmdir ($path);
        }
    }



    public function mkDir ($location) {
        return @mkdir ($location, 0777, true);
    }



    public function zipDir ($dirLocation, $zipLocation) {
        if (!extension_loaded ('zip') || !file_exists ($dirLocation)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open ($zipLocation, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $dirLocation = str_replace ('\\', '/', realpath ($dirLocation));

        if (is_dir ($dirLocation) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirLocation), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                $file = str_replace ('\\', '/', $file);

                // Ignore "." and ".." folders
                if (in_array (substr ($file, strrpos ($file, '/') + 1), array ('.', '..')))
                    continue;

                $file = realpath ($file);

                if (is_dir ($file) === true) {
                    $zip->addEmptyDir (str_replace ($dirLocation . '/', '', $file . '/'));
                } else if (is_file ($file) === true) {
                    $zip->addFromString (str_replace ($dirLocation . '/', '', $file), file_get_contents ($file));
                }
            }
        } else if (is_file ($dirLocation) === true) {
            $zip->addFromString (basename ($dirLocation), file_get_contents ($dirLocation));
        }

        return $zip->close ();
    }



    public function setConfig ($object) {
        // ignore for now
    }

}