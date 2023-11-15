<?PHP
ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . "/config/connectDB.php";

require_once __DIR__ . "/tools/crud.tool.php";
require_once __DIR__ . "/tools/function.tool.php";

require_once __DIR__ . "/datatable.php";

// ตัวอย่างฟังชั่น เรียกใช้ DataTable (ตัวอย่างเรียกใช้อยู่ด้านล่างฟังก์ชัน)
Class DataTable extends TableProcessing {
    
    public function __construct($formData,$TableSET){
        parent::__construct($TableSET); //ส่งค่าไปที่ DataTable Class
    }
    public function getTable(){
        return $this->SqlQuery(); 
    }

    public function SqlQuery(){
        //Query 2 อย่าง 1.ข้อมูลที่จะแสดงในตาราง (true) 2.จำนวนนับที่แสดงในตาราง ปัจจุบัน (false)
        $sql      = $this->getSQL(true);
        $sqlCount = $this->getSQL(false);

        try {
            // เรียกใช้ Database
            $con = connect_database(); 
            $obj = new CRUD($con);

            $fetchRow = $obj->fetchRows($sql);
            $numRow   = $obj->getCount($sqlCount);

            $Result   = $this->createArrayDataTable($fetchRow, $numRow);
            
            return $Result;
        } catch (PDOException $e) {
            return "Database connection failed: " . $e->getMessage();
        
        } catch (Exception $e) {
            return "An error occurred: " . $e->getMessage();
        
        } finally {
            $con = null;
        }
    }

    public function getSQL(bool $OrderBY){
       if($OrderBY)
           $sql  = "SELECT * "; // Query ข้อมูล
       else
           $sql  = "SELECT count(id) AS total_row "; // Query แสดงจำนวน Row ใช้ชื่อ total_row
       $sql .= "FROM tb_datatable ";
       $sql .= "WHERE 1=1 ";

    
       $sql .= "$this->query_search ";
       if($OrderBY) {
           $sql .= "ORDER BY ";
           $sql .= "$this->orderBY ";
           $sql .= "$this->dir ";
           $sql .= "$this->limit ";
       }

       return $sql;
    }
    
    public function createArrayDataTable($fetchRow, $numRow){

        $arrData = null;
        $output = array(
            "draw" => intval($this->draw),
            "recordsTotal" => intval(0),
            "recordsFiltered" => intval(0),
            "data" => $arrData,
        );

        if (count($fetchRow) > 0) {
            $No = ($numRow - $this->pStart);
            foreach ($fetchRow as $key => $value) {

                $dataRow = array();
                $dataRow[] = $No . '.';
                // ข้อมูลที่จะแสดงในแต่ละ Field แต่ละ Column
                $dataRow[] = ($fetchRow[$key]['col1'] == '' ? '-' : $fetchRow[$key]['col1']);
                $dataRow[] = ($fetchRow[$key]['col2'] == '' ? '-' : $fetchRow[$key]['col2']);
                $dataRow[] = ($fetchRow[$key]['col3'] == '' ? '-' : $fetchRow[$key]['col3']);
                $dataRow[] = ($fetchRow[$key]['col4'] == '' ? '-' : $fetchRow[$key]['col4']);

                $arrData[] = $dataRow;
                $No--;
            }
        }

        $output = array(
            "draw" => intval($this->draw),
            "recordsTotal" => intval($numRow),
            "recordsFiltered" => intval($numRow),
            "data" => $arrData,
        );

        return $output;
    }

    
}

////////////////////Setting DataTable//////////////////////////////////////////////////////////////
// รับค่าจากพื้นฐานจาก DataTable
$column = $_POST['order']['0']['column'] + 1;
$search = $_POST["search"]["value"];
$start  = $_POST["start"];
$length = $_POST["length"];
$dir    = $_POST['order']['0']['dir'];
$draw   = $_POST["draw"];

//กำหนด ชื่อฟิลด์ในแต่ละ Column เพื่อใช้สำหรับเรียง (Sort)
$DataTableCol = array( 
    0 => "tb_datatable.id", // Id
    1 => "tb_datatable.id", // Id
    2 => "tb_datatable.col1", // Col อื่น ๆ
    3 => "tb_datatable.col2",
    4 => "tb_datatable.col3",
    5 => "tb_datatable.col4",
);

//กำหนด ชื่อฟิลด์ ที่จะสามารถค้นหาคำจากในฟิลด์นั้นๆ ได้
$DataTableSearch = array(
    "col1",
    "col2"
);

$dataGet = array(
    'column'     => $column,
    'search'     => $search,
    'length'     => $length,
    'start'      => $start,
    'dir'        => $dir,
    'draw'       => $draw,
    'dataCol'    => $DataTableCol,
    'dataSearch' => $DataTableSearch
);
///////////////////////////////////////////////////////////////////////////////////

//เรียกใช้ฟังก์ชัน และส่งค่าไปยัง ajax
$Call   = new DataTable($_POST['formData'],$dataGet);
$result = $Call->getTable(); 
// print_r($result);
echo json_encode($result);
exit;
?>