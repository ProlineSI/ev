<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use App\Utility\QrCodeIndireccion;

class InvitadosController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['confirmation','confirmEticket']);
    }
    public function confirmation($qr = null){
        $title = 'Confirmar asistencia al evento:';
        if(isset($qr)){
            $this->loadModel('Etickets');
            $eticket = $this->Etickets->find()->where(['qr' => $qr])->first();
            if($eticket){
                $qrService = new QrCodeIndireccion();
                $eticket->qrImg = $qrService->generateQrCode($eticket->qr);
            }
            $this->set(compact('title', 'eticket'));
        }
    }

    public function confirmEticket(){
        $this->autoRender = false;
        $this->request->allowMethod(['post']);
        $data = $this->request->getData();
        $this->loadModel('Etickets');
        $eticket = $this->Etickets->get($data['id']);
        $data['confirmation'] = 1;
        $eticket = $this->Etickets->patchEntity($eticket, $data);
        if ($this->Etickets->save($eticket)) {
            $resultJ = json_encode(['result' => 'Confirmación al evento exitosa.']);
                $this->response->type('json');
                $this->response->body($resultJ);
                return $this->response;
        } else {
            $resultJ = json_encode(['error' => 'Error, intente nuevamente.']);
                $this->response->type('json');
                $this->response->body($resultJ);
                return $this->response;
        }
    }
}