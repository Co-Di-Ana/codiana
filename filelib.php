<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Base class for the settings form for {@link quiz_attempts_report}s.
 *
 * @package   mod_quiz
 * @copyright 2012 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined ('MOODLE_INTERNAL') || die();

interface IFileTransfer {

    /**
     * @param $content
     * @param $path
     * @return bool true on success, false on error
     */
    public function saveFile ($content, $path);



    /**
     * Method moves file from one location to another
     * @param $originalLocation string which file to move
     * @param $finalLocation string where to move it
     * @return bool true on success, false on error
     */
    public function moveFile ($originalLocation, $finalLocation);



    /**
     * Method copies file from one location to another
     * @param $originalLocation string which file to copy
     * @param $finalLocation string where to move it
     * @return bool true on success, false on error
     */
    public function copyFile ($originalLocation, $finalLocation);



    /**
     * @param $path
     * @return bool|string false on failure, content on success
     */
    public function loadFile ($path);



    /**
     * Delete given FILE
     * @param $path
     * @return bool true on success, false on error
     */
    public function deleteFile ($path);



    /**
     * Delete given FOLDER
     * @param $location
     * @return bool true on success, false on error
     */
    public function deleteDir ($location);



    /**
     * Creates folder(s recursively)
     * @param $location
     * @return bool true on success, false on error
     */
    public function mkDir ($location);



    /**
     * Zip given $dirLocation to $zipLocation
     * @param $dirLocation
     * @param $zipLocation
     * @return bool true on success, false on error
     */
    public function zipDir ($dirLocation, $zipLocation);



    /**
     * Unzip given $zipLocation to given $dirLocation
     * @param $zipLocation
     * @param $dirLocation
     * @return bool true on success, false on error
     */
    public function unzip ($zipLocation, $dirLocation);



    /**
     * Returns whether folder/file exists
     * @param $location
     * @return bool true on success, false on error
     */
    public function exists ($location);



    /**
     * Set additional configuration
     * @param $object
     * @return mixed
     */
    public function setConfig ($object);

}



class RemoteFileTransfer implements IFileTransfer {

    /** @var resource */
    private $session = null;

    private $sftp = null;

    /** @var stdClass */
    private $config = array (
        'host'     => 'codiana.nti.tul.cz',
        'port'     => 22,

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

        $info   = stream_get_meta_data ($tmpFile);
        $uri    = $info['uri'];
        $result = ssh2_scp_send ($this->session, $uri, $path);
        fclose ($tmpFile);

        return $result;
    }



    public function loadFile ($path) {
        $this->connect ();

        $tmpFile = tmpfile ();
        if ($tmpFile == false)
            throw new Exception ('Cannot create temp file');

        $info   = stream_get_meta_data ($tmpFile);
        $uri    = $info['uri'];
        $result = ssh2_scp_recv ($this->session, $path, $uri);
        if ($result == false)
            throw new Exception ('Error recieving file from server');

        $result = file_get_contents ($uri, FILE_BINARY);
        fclose ($tmpFile);
        if ($result == false)
            throw new moodle_exception ('error:filedoesnotexists', 'codiana');

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



    public function unzip ($zipLocation, $dirLocation) {
        // TODO: Implement unzip() method.
    }



    public function exists ($location) {
        // TODO: Implement fileExists() method.
    }



    public function setConfig ($object) {
        if (!is_object ($object))
            $object = (object)$object;

        foreach ($object as $key => $value)
            $this->config->$key = $value;
    }



    public function moveFile ($originalLocation, $finalLocation) {
        // TODO Implement moveFile() method
    }



    public function copyFile ($originalLocation, $finalLocation) {
        // TODO Implement moveFile() method
    }
}



class LocalFileTransfer implements IFileTransfer {

    public function saveFile ($content, $path) {
        $result = file_put_contents ($path, $content, FILE_BINARY);
        return $result == false ? false : true;
    }



    public function moveFile ($originalLocation, $finalLocation) {
        $this->mkDir (@dirname ($finalLocation));
        return @rename ($originalLocation, $finalLocation);
    }



    public function copyFile ($originalLocation, $finalLocation) {
        $this->mkDir (dirname ($finalLocation));
        return @copy ($originalLocation, $finalLocation);
    }



    public function loadFile ($path) {
        return @file_get_contents ($path, FILE_BINARY);
    }



    public function deleteFile ($path) {
        return @unlink ($path);
    }



    public function deleteDir ($path) {
        if (is_dir ($path)) {
            $files = @array_diff (@scandir ($path), array ('.', '..'));
            foreach ($files as $file)
                (@is_dir ("$path/$file")) ? $this->deleteDir ("$path/$file") : unlink ("$path/$file");
            return @rmdir ($path);
        }
        return !@file_exists ($path);
    }



    public function mkDir ($location) {
        @mkdir ($location, 0777, true);
        return @file_exists ($location);
    }



    public function zipDir ($dirLocation, $zipLocation) {
        if (!extension_loaded ('zip') || !file_exists ($dirLocation)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open ($zipLocation, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $dirLocation = realpath ($dirLocation);
        $dirLocation = str_replace ('\\', '/', $dirLocation);

        if (is_dir ($dirLocation) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirLocation), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {

                $file = str_replace ('\\', '/', $file);

                // Ignore "." and ".." folders
                if (in_array (substr ($file, strrpos ($file, '/') + 1), array ('.', '..')))
                    continue;

                // path format protection
                $file = str_replace ('\\', '/', realpath ($file));

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



    public function unzip ($zipLocation, $dirLocation) {
        $zip = new ZipArchive;
        $res = $zip->open ($zipLocation);
        if ($res === TRUE) {
            $zip->extractTo ($dirLocation);
            return $zip->close ();
        } else {
            return false;
        }
    }



    public function setConfig ($object) {
        // ignore for now
    }



    public function exists ($location) {
        return @file_exists ($location);
    }

}