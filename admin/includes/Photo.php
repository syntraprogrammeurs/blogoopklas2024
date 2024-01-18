<?php

class Photo extends Db_object{
    /*properties*/
    public $id;
    public $title;
    public $description;
    public $filename;
    public $type;
    public $size;
    public $deleted_at;

    public $tmp_path;
    public $upload_directory = "assets/images/photos";
    public $errors = array();
    public $upload_errors_array = array(
      UPLOAD_ERR_OK => "There is no error",
      UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload max_filesize from php.ini",
        UPLOAD_ERR_FORM_SIZE=> "The uplod file exeeds MAX_FILE_SIZE in php.ini voor een html form",
        UPLOAD_ERR_NO_FILE=>"No file uploaded",
        UPLOAD_ERR_PARTIAL=>"The file was partially uploaded",
        UPLOAD_ERR_NO_TMP_DIR=> "Missing a temporary folder",
        UPLOAD_ERR_CANT_WRITE=>"Failed to write to disk",
        UPLOAD_ERR_EXTENSION=>"A php extension stopped your upload",
    );

    protected static $table_name = "photos";

    /*get properties method*/
    //object variabelen retourneren als een array.
    public function get_properties(){
        return [
            'id'=> $this->id,
            'title' => $this->title,
            'description'=> $this->description,
            'filename'=> $this->filename,
            'type'=> $this->type,
            'size'=> $this->size
        ];
    }

    public function set_file($file){
        if(empty($file) || !$file || !is_array($file)){
           $this->errors[]=  "No file uploaded";
           return false;
        }elseif($file['error'] != 0){
            $this->errors[]= $this->upload_errors_array['error'];
            return false;
        }else{
            //image.jpg
            $date = date('Y_m_d-H-i-s');
            $without_extension = pathinfo(basename($file['name']),PATHINFO_FILENAME);
            $extension = pathinfo(basename($file['name']),PATHINFO_EXTENSION);
            $this->filename = $without_extension.$date.'.'.$extension;
            $this->type = $file['type'];
            $this->size = $file['size'];
            $this->tmp_path = $file['tmp_name'];
        }
    }

    public function save(){
        $target_path = SITE_ROOT.DS."admin".DS.$this->upload_directory.DS.$this->filename;
        if($this->id){
            //schrijft weg naar de tabel photos
            $this->update();
            //fysisch de foto naar het target_path wegschrijven
            if(move_uploaded_file($this->tmp_path,$target_path)){
                if($this->create()){
                    unset($this->tmp_path);
                    return true;
                }
            }
        }else{
            if(!empty($this->errors)){
                return false;
            }
            if(empty($this->filename) || empty($this->tmp_path)){
                $this->errors[]= "File not available";
                return false;
            }
            if(file_exists($target_path)){
                $this->errors[]= "File {$this->filename} EXISTS!";
            }
            if(move_uploaded_file($this->tmp_path, $target_path)){//upload in de images map(photos)
                if($this->create()){//aanmaken in de database
                    unset($this->tmp_path);
                    return true;
                }
            }else{
                $this->errors[]= "This folder has no write rights";
                return false;
            }
        }
    }
    public function picture_path(){
        if($this->filename && file_exists($this->upload_directory.DS.$this->filename)){
            return $this->upload_directory.DS.$this->filename;
        }else{
            return 'https://via.placeholder.com/300';
        }
    }
}

?>