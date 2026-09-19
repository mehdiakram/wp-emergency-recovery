<?php
/**
 * Royal Technologies
 * WordPress Emergency Recovery
 *
 * Website:
 * https://www.royaltechbd.com/
 *
 * GitHub:
 * https://github.com/YOUR-GITHUB-USERNAME/wp-emergency-recovery
 *
 * IMPORTANT:
 * This is a temporary emergency recovery utility.
 * Delete this file from the production server immediately
 * after successful recovery.
 */


/* =========================================================
 * ROYAL TECHNOLOGIES
 * ========================================================= */

define(
    'ROYAL_RECOVERY_VERSION',
    '1.0.0'
);


/* =========================================================
 * CONFIGURATION
 * ========================================================= */

/*
 * Secret token used to protect the recovery URL.
 *
 * IMPORTANT:
 * Never publish your real token on GitHub.
 */
define(
    'RECOVERY_TOKEN',
    'CHANGE_THIS_TO_A_LONG_RANDOM_SECRET'
);


/*
 * Email to check FIRST.
 *
 * If a WordPress user exists with this email,
 * that user will be recovered.
 */
$admin_email = 'your-email@example.com';


/*
 * Username to check SECOND.
 *
 * This is checked only when the email user
 * does not exist.
 */
$admin_username = 'your-username';


/*
 * Password to assign to the recovered/new account.
 *
 * Use a strong temporary password.
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

header(
    'Content-Type: text/html; charset=UTF-8'
);

header(
    'X-Robots-Tag: noindex, nofollow, noarchive',
    true
);


/* =========================================================
 * VARIABLES
 * ========================================================= */

$user    = false;
$user_id = 0;
$matched = '';

$message = '';

$details = array();


/* =========================================================
 * STEP 1
 * CHECK EMAIL FIRST
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
 * STEP 2
 * CHECK USERNAME
 *
 * Only if email was not found.
 * ========================================================= */

if (
    !$user &&
    !empty($admin_username)
) {

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
 * STEP 3
 * EXISTING USER FOUND
 * ========================================================= */

if (
    $user &&
    $user_id > 0
) {

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
    $wp_user = new WP_User(
        $user_id
    );

    $wp_user->set_role(
        'administrator'
    );


    /*
     * Success message.
     */
    $message =
        'Existing WordPress user recovered successfully.';


    $details = array(

        'Action' =>
            'Existing user recovered',

        'User ID' =>
            $user_id,

        'Username' =>
            $user->user_login,

        'Email' =>
            $user->user_email,

        'Matched By' =>
            $matched,

        'Role' =>
            'Administrator'

    );

}


/* =========================================================
 * STEP 4
 * CREATE NEW ADMINISTRATOR
 * ========================================================= */

else {

    /*
     * Check username collision.
     */
    if (
        username_exists(
            $admin_username
        )
    ) {

        http_response_code(409);

        exit(
            'The specified username already exists, ' .
            'but the specified email was not found.'
        );
    }


    /*
     * Check email collision.
     */
    if (
        email_exists(
            $admin_email
        )
    ) {

        http_response_code(409);

        exit(
            'The specified email already exists, ' .
            'but the user could not be retrieved.'
        );
    }


    /*
     * Create new user.
     */
    $user_id = wp_create_user(
        $admin_username,
        $admin_password,
        $admin_email
    );


    /*
     * Check creation error.
     */
    if (
        is_wp_error(
            $user_id
        )
    ) {

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
    $wp_user = new WP_User(
        $user_id
    );

    $wp_user->set_role(
        'administrator'
    );


    /*
     * Success message.
     */
    $message =
        'New WordPress Administrator account created successfully.';


    $details = array(

        'Action' =>
            'New Administrator created',

        'User ID' =>
            $user_id,

        'Username' =>
            $admin_username,

        'Email' =>
            $admin_email,

        'Matched By' =>
            'None',

        'Role' =>
            'Administrator'

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

    <title>
        Royal Technologies | WordPress Recovery
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;
            background: #f4f7fb;
            color: #1f2937;
            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        .royal-wrapper {
            width: 100%;
            max-width: 680px;
            margin: 40px auto;
        }

        .royal-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 35px;
            box-shadow:
                0 12px 40px rgba(
                    0,
                    0,
                    0,
                    0.08
                );
        }

        .royal-brand {
            margin-bottom: 28px;
            text-align: center;
        }

        .royal-brand h1 {
            margin: 0;
            font-size: 27px;
            color: #111827;
        }

        .royal-brand p {
            margin: 8px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .royal-success {
            padding: 17px 20px;
            margin-bottom: 25px;
            border-radius: 9px;
            background: #e8f7ed;
            color: #146c2e;
            font-weight: 600;
            line-height: 1.5;
        }

        .royal-table {
            width: 100%;
            border-collapse: collapse;
        }

        .royal-table td {
            padding: 13px 8px;
            border-bottom:
                1px solid #eeeeee;
            vertical-align: top;
        }

        .royal-table td:first-child {
            width: 36%;
            font-weight: 600;
        }

        .royal-warning {
            margin-top: 25px;
            padding: 17px 20px;
            border-radius: 9px;
            background: #fff3cd;
            color: #664d03;
            line-height: 1.6;
        }

        .royal-footer {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: #6b7280;
        }

        .royal-footer a {
            color: inherit;
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="royal-wrapper">

    <div class="royal-card">

        <div class="royal-brand">

            <h1>
                Royal Technologies
            </h1>

            <p>
                WordPress Emergency Recovery
            </p>

        </div>


        <div class="royal-success">

            <?php
            echo esc_html(
                $message
            );
            ?>

        </div>


        <table class="royal-table">

            <?php
            foreach (
                $details as $key => $value
            ):
            ?>

                <tr>

                    <td>
                        <?php
                        echo esc_html(
                            $key
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo esc_html(
                            $value
                        );
                        ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>


        <div class="royal-warning">

            <strong>
                Security Notice:
            </strong>

            Delete

            <strong>
                royalrecover.php
            </strong>

            from the server immediately
            after completing the recovery.

        </div>

    </div>


    <div class="royal-footer">

        <a
            href="https://www.royaltechbd.com/"
            target="_blank"
            rel="noopener noreferrer"
        >
            Royal Technologies
        </a>

        <br>

        Web Design · Hosting · Software Development

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
    function () use (
        $recovery_file
    ) {

        /*
         * Attempt to delete this file automatically.
         */
        @unlink(
            $recovery_file
        );

    }
);
