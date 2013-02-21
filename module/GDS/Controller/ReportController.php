<?php
namespace GDS\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use ZendPdf;
 
class ReportController
{
    public function indexAction()
	{}
	
	public function monthlyReportPdfAction()
    {
        $model = new PdfModel();
        $model->setOption('paperSize', 'a4');
        $model->setOption('paperOrientation', 'landscape');
		$model->setOption('fileName', 'monthly-report');
 
        return $model;
    }
}