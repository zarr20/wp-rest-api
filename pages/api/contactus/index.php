<?php
add_action('rest_api_init', 'register_contactus_routes');

function register_contactus_routes()
{
    register_rest_route('api', '/contact/', array(
        'methods'  => 'POST',
        'callback' => 'handle_contact_form',
        'permission_callback' => '__return_true',
    ));
}

function handle_contact_form($request)
{
    $params = $request->get_params();

    if (
        empty($params['name']) ||
        empty($params['email']) ||
        empty($params['subject']) ||
        empty($params['message'])
    ) {
        return new WP_REST_Response(array('message' => 'Incomplete data'), 400);
    }

    $name = sanitize_text_field($params['name']);
    $email = sanitize_email($params['email']);
    $telp = sanitize_text_field($params['telp']);
    $subject = sanitize_text_field($params['subject']);
    $message = sanitize_textarea_field($params['message']);

    $email_to = 'zarralghifari@gmail.com'; // gunakan get options
    $email_subject = "[contact-us] " . $subject;
    $email_message = "Name: $name\n";
    $email_message .= "Email: $email\n";
    $email_message .= "Telp: $telp\n";
    $email_message .= "Message: $message\n";

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    $sent = wp_mail($email_to, $email_subject, $email_message, $headers);

    if ($sent) {
        return new WP_REST_Response(array(
            'message' => 'Email sent successfully',
            'request' => array(
                'name' => $name,
                'email' => $email,
                'telp' => $telp,
                'subject' => $subject,
                'message' => $message,
            )
        ), 200);
    } else {
        return new WP_REST_Response(array('message' => 'Failed to send email'), 500);
    }
}
