<?php
 /**
 * @package E-Commerce WD
 * @author Web-Dorado
 * @copyright (C) 2014 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') || die('Access Denied');


class EcommercewdControllerReports extends EcommercewdController {
    ////////////////////////////////////////////////////////////////////////////////////////
    // Events                                                                             //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Constants                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Variables                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Constructor & Destructor                                                           //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Public Methods                                                                     //
    ////////////////////////////////////////////////////////////////////////////////////////
	public function export_report(){	
		$app = JFactory::getApplication();
		// Include PHPExcel library			
		require_once JPATH_COMPONENT . '/libraries/exportreports/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
		$model = WDFHelper::get_model('reports');
		$date_range = $model->get_date_range();
		
		$worksheet_title = "report-".$date_range->tab_index;
		
		// Create Excel worksheet
		$WorkSheet = $objPHPExcel->createSheet(0);
		$objPHPExcel->getSheet(0)->setTitle($worksheet_title);
		

		// Set cells width
		$objPHPExcel->getSheet(0)->getColumnDimension('A')->setWidth(30);				
		$objPHPExcel->getSheet(0)->getColumnDimension('B')->setWidth(25);				
		$objPHPExcel->getSheet(0)->getColumnDimension('C')->setWidth(25);				
		$objPHPExcel->getSheet(0)->getColumnDimension('D')->setWidth(25);				
		$objPHPExcel->getSheet(0)->getColumnDimension('E')->setWidth(25);				
				
		// Define headers
		$WorkSheet->getCell('A1')->setValue(WDFText::get('DATE'));
		$WorkSheet->getCell('B1')->setValue(WDFText::get('ITEMS_PURCHASED'));
		$WorkSheet->getCell('C1')->setValue(WDFText::get('ORDERS_PLACED'));
		$WorkSheet->getCell('D1')->setValue(WDFText::get('CHARGED_FOR_SHIPPING'));
		$WorkSheet->getCell('E1')->setValue(WDFText::get('SALES_AMOUNT'));
		
		$reports_data = $model->get_reports_data_array();
	
		$i = 2;
		foreach($reports_data as $report_data){					
			$WorkSheet->getCell('A'.$i)->setValue($report_data->date);
			$WorkSheet->getCell('B'.$i)->setValue((int)$report_data->items_count);
			$WorkSheet->getCell('C'.$i)->setValue((int)$report_data->orders_count);
			$WorkSheet->getCell('D'.$i)->setValue((float)$report_data->total_shipping_seals);
			$WorkSheet->getCell('E'.$i)->setValue((float)$report_data->total_seals);
			$i++;
		}


		//Set active sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Remove last empty sheet
		$objPHPExcel->removeSheetByIndex(1);
		// Save xlxs file
		$objWriter->save('components/com_'.WDFHelper::get_com_name().'/'.$worksheet_title.'.xlsx');	
		// Redirect xlxs file to browser
		$app->redirect(JURI::base().'components/com_'.WDFHelper::get_com_name().'/'.$worksheet_title.'.xlsx');
		
	
	}
	

    ////////////////////////////////////////////////////////////////////////////////////////
    // Getters & Setters                                                                  //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Private Methods                                                                    //
    ////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////
    // Listeners                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
}