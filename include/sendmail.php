<?php
// KinderBloom demo / early-access request handler.
// Set the address that should receive demo requests:
$contact_email = 'your_mail@mail.com';

$response = array('error' => '');

parse_str(isset($_POST['data']) ? $_POST['data'] : '', $post_data);

function kb_field($data, $key) {
	$value = isset($data[$key]) ? $data[$key] : '';
	// Strip tags and any line breaks that could be used for header injection
	return trim(str_replace(array("\r", "\n"), ' ', strip_tags(stripslashes($value))));
}

$user_name     = kb_field($post_data, 'username');
$user_email    = kb_field($post_data, 'email');
$user_school   = kb_field($post_data, 'subject');
$user_phone    = kb_field($post_data, 'phone');
$user_role     = kb_field($post_data, 'role');
$user_children = kb_field($post_data, 'children');
$user_msg      = isset($post_data['message']) ? trim(strip_tags(stripslashes($post_data['message']))) : '';

if (trim($contact_email) == '' || !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
	$response['error'] = 'Error send message!';
} else {
	$subj = 'KinderBloom demo request: ' . $user_school;
	$msg  = "New demo request from the KinderBloom landing page\r\n\r\n"
		. "Name: $user_name\r\n"
		. "E-mail: $user_email\r\n"
		. "School: $user_school\r\n"
		. "Phone: $user_phone\r\n"
		. "Role: $user_role\r\n"
		. "Children: $user_children\r\n\r\n"
		. "Message:\r\n$user_msg\r\n";

	$head = "Content-Type: text/plain; charset=\"utf-8\"\r\n"
		. "Reply-To: $user_email\r\n";

	if (!@mail($contact_email, $subj, $msg, $head)) {
		$response['error'] = 'Error send message!';
	}
}

echo json_encode($response);
die();
?>
