<?php

//turn on debugging messages
ini_set('display_errors', 'On');
//error_reporting(E_ALL);


//instantiate the program object

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class
class Manage {
    public static function autoload($class) {
        //you can put any file name or directory here
        include $class . '.php';
    }
}

spl_autoload_register(array('Manage', 'autoload'));

//instantiate the program object
$obj = new main();


class main {

    public function __construct()
    {
        //print_r($_REQUEST);
        //set default page request when no parameters are in URL
        $pageRequest = 'homepage';
        //check if there are parameters
        if(isset($_REQUEST['page'])) {
            //load the type of page the request wants into page request
            $pageRequest = $_REQUEST['page'];
        }
        //instantiate the class that is being requested
         $page = new $pageRequest;


        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $page->get();
        } else {
            $page->post();
        }

    }

}

abstract class page {
    protected $html;

    public function __construct()
    {
        $this->html .= '<html>';
        //$this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';
    }
    public function __destruct()
    {
        $this->html .= '</body></html>';
        //stringFunctions::printThis($this->html);
        print($this->html);
    }

    /**public function get() {
   
        echo 'default get message';
    }

    public function post() {
        print_r($_POST);
    }**/
   
}

class homepage extends page {

    public function get()
    {
        $form = '<form method="post" enctype="multipart/form-data">';
        $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
        $form .= '<input type="submit" value="Upload Image" name="submit">';
        $form .= '</form> ';
        $this->html .= '<h1>Upload Form</h1>';
        $this->html .= $form;

    }

    public function post() {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    //$uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $imageFileName = pathinfo($target_file,PATHINFO_BASENAME);
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    header('Location: index.php?page=htmlTable&filename='.$target_file);

    }
}

class htmlTable extends page {

public function get(){
	 // $csvname = $_GET['filename'];
	  $convert = fopen($_GET['filename'], "r");
	  while (!feof($convert)) {
      $tabledata=(fgetcsv($convert));
      echo '<table border="3">';
      $n = count($tabledata);
      for($row=1;$row<$n;$row++) {
        echo '<tr>';
        for($col=0;$col<$n;$col++) {
            echo '<td>' . $tabledata[$col] . '</td>';
        }
        echo '</tr>';
      }
      echo '</table>';

		//print_r(fgetcsv($convert));
	  }
	  fclose($tabledata);
	}
}
/********    $row = count($convert) / 5;
$col = 5;

echo'<table border="1" width="700">';

for( $i = 0; $i < $row; $i++ )
{
    echo'<tr>';
    for( $j = 0; $j < $col; $j++ ) {
        if( ! empty( $convert[$j] ) ) {
            echo '<td>'.$convert[$j].'</td>';
        }
    }
    echo'</tr>';
}
echo'</table>';********/

    
/*********************$row = 1;
if (($handle = fopen($_GET['filename'], "r" )) !== false ) {

    echo '<table border="1">';

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        if ($row == 1) {
            echo '<thead><tr>';
        }else{
            echo '<tr>';
        }

        for ($c=0; $c < $num; $c++) {
            //echo $data[$c] . "<br />\n";
            if(empty($data[$c])) {
               $value = "&nbsp;";
            }else{
               $value = $data[$c];
            }
            if ($row == 1) {
                echo '<th>'.$value.'</th>';
            }else{
                echo '<td>'.$value.'</td>';
            }
        }

        if ($row == 1) {
            echo '</tr></thead><tbody>';
        }else{
            echo '</tr>';
        }
        $row++;
    }

    echo '</tbody></table>';
    fclose($handle);
}

  }

}***************************************/
  /**class stringFunctions {
     static public function printThis($inputText) {
        return print($inputText);
     }
     static public function stringLength($text) {
        return strLen($text);
     }  
  }**/

?>