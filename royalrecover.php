<?php
/**
 * Royal WordPress Emergency Recovery
 *
 * Temporary WordPress account recovery script.
 *
 * IMPORTANT:
 * 1. Change all configuration values before use.
 * 2. Never commit real credentials or recovery tokens to GitHub.
 * 3. Delete this file from the production server immediately after use.
 */


/* =========================================================
 * CONFIGURATION
 * ========================================================= */

/*
 * Random secret used to protect the recovery URL.
 *
 * Example:
 * https://example.com/royalrecover.php?token=YOUR_SECRET
 */
define(
    'RECOVERY_TOKEN',
    'CHANGE_THIS_TO_A_LONG_RANDOM_SECRET'
);


/*
 * Target email.
 *
 * Email is checked FIRST.
 */
$admin_email = 'your-email@example.com';


/*
 * Target username.
 *
 * Username is checked ONLY if the email does not exist.
 */
$admin_username = 'your-username';


/*
 * Password to set for the recovered/new account.
 */
$admin_password = 'CHANGE_THIS_TO_A_STRONG_PASSWORD';


/* =========================================================
 * SECURITY CHECK
 * ========================================================= */

if (
    !isset($_GET['token']) ||
    !hash_equals(
        RECOVERY_TOKEN,
        (string) $_GET['token']
    )
) {
    http_response_code(403);

    exit('Access denied.');
}


/* =========================================================
 * LOAD WORDPRESS
 * ========================================================= */

$wp_load = __DIR__ . '/wp-load.php';

if (!file_exists($wp_load)) {
    http_response_code(500);

    exit('wp-load.php not found.');
}

require_once $wp_load;


/* =========================================================
 * VERIFY WORDPRESS
 * ========================================================= */

if (!function_exists('get_user_by')) {
    http_response_code(500);

    exit('WordPress could not be loaded.');
}


/* =========================================================
 * SECURITY HEADERS
 * ========================================================= */

nocache_headers();

header('Content-Type: text/html; charset=UTF-8');

header(
    'X-Robots-Tag: noindex, nofollow, noarchive',
    true
);


/* =========================================================
 * RECOVERY VARIABLES
 * ========================================================= */

$user    = false;
$user_id = 0;
$matched = '';

$message = '';

$details = array();


/* =========================================================
 * STEP 1: CHECK EMAIL FIRST
 * ========================================================= */

if (!empty($admin_email)) {

    $user = get_user_by(
        'email',
        $admin_email
    );

    if ($user) {

        $user_id = (int) $user->ID;

        $matched = 'Email';
    }
}


/* =========================================================
 * STEP 2: CHECK USERNAME
 *
 * Only runs if email was not found.
 * ========================================================= */

if (!$user && !empty($admin_username)) {

    $user = get_user_by(
        'login',
        $admin_username
    );

    if ($user) {

        $user_id = (int) $user->ID;

        $matched = 'Username';
    }
}


/* =========================================================
 * STEP 3: EXISTING USER FOUND
 * ========================================================= */

if ($user && $user_id > 0) {

    /*
     * Change password.
     */
    wp_set_password(
        $admin_password,
        $user_id
    );


    /*
     * Assign Administrator role.
     */
    $wp_user = new WP_User($user_id);

    $wp_user->set_role(
        'administrator'
    );


    /*
     * Success message.
     */
    $message =
        'Existing WordPress user recovered successfully.';


    $details = array(
        'Action'   => 'Existing user recovered',
        'User ID'  => $user_id,
        'Username' => $user->user_login,
        'Email'    => $user->user_email,
        'Matched'  => $matched,
        'Role'     => 'Administrator'
    );
}


/* =========================================================
 * STEP 4: CREATE NEW ADMINISTRATOR
 *
 * Runs only when neither email nor username exists.
 * ========================================================= */

else {

    /*
     * Check username collision.
     */
    if (username_exists($admin_username)) {

        http_response_code(409);

        exit(
            'The specified username already exists, ' .
            'but the specified email was not found.'
        );
    }


    /*
     * Check email collision.
     */
    if (email_exists($admin_email)) {

        http_response_code(409);

        exit(
            'The specified email already exists, ' .
            'but the user could not be retrieved.'
        );
    }


    /*
     * Create new WordPress user.
     */
    $user_id = wp_create_user(
        $admin_username,
        $admin_password,
        $admin_email
    );


    /*
     * Check for creation error.
     */
    if (is_wp_error($user_id)) {

        http_response_code(500);

        exit(
            'Could not create user: ' .
            esc_html(
                $user_id->get_error_message()
            )
        );
    }


    /*
     * Assign Administrator role.
     */
    $wp_user = new WP_User($user_id);

    $wp_user->set_role(
        'administrator'
    );


    /*
     * Success message.
     */
    $message =
        'New WordPress Administrator account created successfully.';


    $details = array(
        'Action'   => 'New Administrator created',
        'User ID'  => $user_id,
        'Username' => $admin_username,
        'Email'    => $admin_email,
        'Matched'  => 'None',
        'Role'     => 'Administrator'
    );
}


/* =========================================================
 * OUTPUT
 * ========================================================= */

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="robots"
        content="noindex,nofollow,noarchive"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Royal WordPress Recovery</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;
            background: #f5f7fa;
            color: #1f2937;
            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        .royal-recovery {
            width: 100%;
            max-width: 650px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 32px;
            box-shadow:
                0 10px 35px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin: 0 0 25px;
            font-size: 26px;
        }

        .success {
            padding: 16px 18px;
            margin-bottom: 25px;
            border-radius: 8px;
            background: #e8f7ed;
            color: #146c2e;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 12px 8px;
            border-bottom: 1px solid #eeeeee;
            vertical-align: top;
        }

        td:first-child {
            width: 35%;
            font-weight: 600;
        }

        .warning {
            margin-top: 25px;
            padding: 16px 18px;
            border-radius: 8px;
            background: #fff3cd;
            color: #664d03;
            line-height: 1.6;
        }

    </style>

</head>

<body>

<div class="royal-recovery">

    <h1>
        Royal WordPress Recovery
    </h1>

    <div class="success">

        <?php
        echo esc_html($message);
        ?>

    </div>

    <table>

        <?php foreach ($details as $key => $value): ?>

            <tr>

                <td>
                    <?php
                    echo esc_html($key);
                    ?>
                </td>

                <td>
                    <?php
                    echo esc_html($value);
                    ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </table>

    <div class="warning">

        <strong>Important:</strong>

        Delete
        <strong>royalrecover.php</strong>
        from the server immediately after recovery.

    </div>

</div>

</body>

</html>

<?php


/* =========================================================
 * AUTO DELETE
 * ========================================================= */

$recovery_file = __FILE__;

register_shutdown_function(
    function () use ($recovery_file) {

        /*
         * Attempt to delete this recovery script
         * after the response has been generated.
         */
        @unlink($recovery_file);

    }
);
