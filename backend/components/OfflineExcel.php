<?php
namespace backend\components;

require dirname(dirname(__FILE__)).'/components/phpexcel/PHPExcel.php';

class OfflineExcel
{
	//报表导出
	public static function CreateExcelReportingList($result,$money){
		header("content-type:text/html;charset=utf-8");
		/** Error reporting */
		error_reporting(E_ALL);
		/** PHPExcel */
		//include_once '../themes/ace/assets/js/PHPExcel/PHPExcel.php';
	
		/** PHPExcel_Writer_Excel2003用于创建xls文件 */
		//include_once '../themes/ace/assets/js/PHPExcel/PHPExcel/Writer/Excel5.php';
	
		// Create new PHPExcel object
		$objPHPExcel = new \PHPExcel();
	
		// Set properties
		$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
		$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
		$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
	
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', '售票員');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', '電影名');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', '大廳名');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', '座位');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', '售票日期');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', '數量');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', '單價');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', '總價');
		
		foreach ($result as $k=>$v){
			$index = 'A'.($k+2);
			$index_b = 'B'.($k+2);
			$index_c = 'C'.($k+2);
			$index_d = 'D'.($k+2);
			$index_e = 'E'.($k+2);
			$index_f = 'F'.($k+2);
			$index_g = 'G'.($k+2);
			$index_h = 'H'.($k+2);
			
			$objPHPExcel->getActiveSheet()->SetCellValue($index, $v['admin_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue($index_b, $v['movie_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue($index_c, $v['room_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue($index_d, $v['seat_names']);
			$objPHPExcel->getActiveSheet()->SetCellValue($index_e, $v['order_time']);
			$objPHPExcel->getActiveSheet()->SetCellValue($index_f, $v['count']);
			$objPHPExcel->getActiveSheet()->SetCellValue($index_g, $v['price']);
			$objPHPExcel->getActiveSheet()->SetCellValue($index_h, $v['total_money']);
		}
		
		
		$count = count($result)+3;
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$count, "總價：");
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$count, $money);
	
		
		//Set column widths 设置列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		
		$time = date('YmdHis',time());
		
		
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle($time);
	
		$filename = "線下流水".$time.".xls";
	
		// Save Excel 2007 file
		//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
		ob_start();
		$objWriter->save("php://output");
		$xlsData = ob_get_contents();
		ob_end_clean();
		$response =  array(
			'op' => 'ok',
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
			'path' => $filename,
		);
		return json_encode($response);
		
		
// 		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
// 		$objWriter->save($filename);
// 		$objWriter->save(str_replace('.php', '.xls', __FILE__));
// 		header("Pragma: public");
// 		header("Expires: 0");
// 		header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
// 		header("Content-Type:application/force-download");
// 		header("Content-Type:application/vnd.ms-execl");
// 		header("Content-Type:application/octet-stream");
// 		header("Content-Type:application/download");
// 		header("Content-Disposition:attachment;filename=csat.".$filename);
// 		header("Content-Transfer-Encoding:binary");
// 		$objWriter->save("php://output");
	}
	
}