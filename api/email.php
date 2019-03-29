<?php


/**
 * Validates the data send from user
 */
function oc_validate_data( $data ) {

    $errors = array();

    if(!isset( $data['name'])  || empty($data['name']))  $errors['name'] = 'Por favor dinos como te llamas';
    if(!isset( $data['email']) || empty($data['email'])) $errors['email'] = 'Compartenos tu email para poder confirmarte la cita';
    if(!isset( $data['type'])  || empty($data['type']))  $errors['type'] = 'Selecciona un tipo de cita a la que te gustaría asistir';
    if(!isset( $data['day'])  || empty($data['day']))  $errors['day'] = 'No olvides seleccionar una fecha para tu cita';

    return $errors;
}

/**
 * Send an email appointment request
 */
function oc_api_email_appointment($data) {
  
    $errors = oc_validate_data($data);
    $result = array();

    if (!empty($errors)){
        $result['success'] = false;
        $result['errors'] = $errors;
    }else{
        $name    = sanitize_text_field($data['name']);
        $email   = sanitize_text_field($data['email']);
        $phone   = sanitize_text_field($data['phone']);
        $type    = sanitize_text_field($data['type']);
        $day    = sanitize_text_field($data['day']);
        $message = sanitize_text_field($data['message']);
    
        switch ($type) {
            case '1': $type = 'Ortografía Cerebral'; break;
            case '2': $type = 'Desbloqueo emocional y financiero'; break;
            case '3': $type = 'Lectura del inconsciente y alineación cerebral con cuarzos'; break;
        }
    
        $content =  '<strong>Nombre:</strong> ' . $name .
                    '<br/><strong>Email:</strong> ' . $email .
                    '<br/><strong>Teléfono:</strong> ' . $phone .
                    '<br/><strong>Consulta Solicitada:</strong> ' . $type .
                    '<br/><strong>Fecha Solicitada:</strong> ' . $day .
                    '<br/><strong>Mensaje Agregado:</strong> ' . $message;

        try {
            // $headers = "Content-type: text/html; charset=charset=UTF-8";
            // $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'Cc: Code Inc. México <codeincmx@gmail.com>';
            //PRODUCTION TODO: exchange commente lines
            // wp_mail("mirna@ortografiacerebral.com", "SOLICITUD DE CONSULTA", $content, $headers );
            wp_mail("codeincmx@gmail.com", "SOLICITUD DE CONSULTA", $content, $headers );
            $result['success'] = true;
            $result['message'] = 'success';

        }catch(Exception $e){
            $result['success'] = false;
            $result['errors'] = $e->getMessage();
        }
    }
    return $result;
}

add_action('rest_api_init', function () {
    register_rest_route('email/v1', 'appointment', array(
        'methods' => 'POST',
        'callback' => 'oc_api_email_appointment'
    ));

    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
	add_filter( 'rest_pre_serve_request', function( $value ) {
		header( 'Access-Control-Allow-Origin: *' );
        header( 'Access-Control-Allow-Methods: POST' );
		header( 'Access-Control-Allow-Credentials: true' );

		return $value;
		
    });   
});
