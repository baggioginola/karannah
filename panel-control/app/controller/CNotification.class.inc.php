<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 19/feb/2017
 * Time: 18:01
 */
require_once __CONTROLLER__ . 'CBaseController.class.inc.php';
require_once __CONTROLLER__ . 'CNotificationOxxoController.class.inc.php';

class Notification extends BaseController
{
    private static $object = null;
    private $header = 'From:  Sitio Web <informes@iluminamos.com.mx>';
    private $subject = 'Notificación de Pago';
    private $send_to = 'mariocuevas88@gmail.com';

    /**
     * @return Notification|null
     */
    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function charge()
    {
        $body = @file_get_contents('php://input');
        $data = json_decode($body, true);

        if ($data['type'] == 'charge.paid') {


            if (isset($data['data']['object']['payment_method']['reference'])) {
                if ($result = NotificationOxxo::singleton()->getTransaction($data['data']['object']['payment_method']['reference'])) {
                    $msg_cart = "Data: " . print_r($result, 1);
                    $msg_confirm = "Se ha registrado su pago correctamente, gracias.";
                    mail("mariocuevas88@gmail.com", "Webhook Charge", $msg_cart, $this->header);
                    mail($result['result_transaction']['email'], "Pago a través de OXXO", $msg_confirm, $this->header);
                }
                $msg = "Se ha pagado una orden de compra con el siguiente número de Referencia de OXXO: " . $data['data']['object']['payment_method']['reference'];
            } else {
                $msg = "Se ha pagado una orden de compra por medio de OXXO, favor de revisar.";
            }
            $msg_hook = "Data: " . print_r($data, 1);
            mail("mariocuevas88@gmail.com", "Webhook Charge", $msg_hook, $this->header);
            mail($this->send_to, $this->subject, $msg, $this->header);
        }

        return true;
    }
}