<?php

// PREVENT FROM CSRF 

/**
 * Check fo referer
 *
 * @return boolean Is the current referer valid ?
 */
function isRefererOk(): bool
{
    global $globalURL;
    // var_dump($globalURL);
    return isset($_SERVER['HTTP_REFERER'])
        && str_contains($_SERVER['HTTP_REFERER'], $globalURL);
}


/**
 * Check for CSRF token
 *
 * @param array|null $data Input data
 * @return boolean Is there a valid toekn in user session ?
 */
function isTokenOk(?array $data = null): bool
{
    if (!is_array($data)) $data = $_REQUEST;

    return isset($_SESSION['token'])
        && isset($data['token'])
        && $_SESSION['token'] === $data['token'];
}

/**
 * Verify HTTP referer and token. Redirect with error message.
 *
 * @return void
 */
function preventFromCSRF(): void
{
    if (!isRefererOk()) {
        addError('referer');
        redirectTo();
        exit;
    }

    if (!isTokenOk()) {
        addError('csrf');
        redirectTo();
        exit;
    }
}

/**
 * Prevents from CSRF by checking HTTP_REFERER in $_SERVER and checks if the random token from generateToken() matches in form.
 *
 * @return void
 */
function preventFromCSRFAPI(array $inputData): void
{
    global $globalURL;

    if (!isset($_SERVER['HTTP_REFERER']) || !str_contains($_SERVER['HTTP_REFERER'], $globalURL)) {
        addError('referer');
        echo json_encode(['success' => false, 'message' => $GLOBALS['errors']['referer']]);
        exit;
    }

    if (!isset($_SESSION['token']) || !isset($inputData['token']) || $_SESSION['token'] !== $inputData['token']) {
        addError('csrf');
        echo json_encode(['success' => false, 'message' => $GLOBALS['errors']['csrf']]);
        exit;
    }
}

/**
 * Checks iof a user is connected or redirects toi login page.
 *
 * @param array $session - $_SESSION super global.
 * @return void
 */
function checkConnection(array $session)
{
    if (!isset($session['username'])) {
        redirectTo('index');
        addError('please_connect');
        exit();
    }
}


/**
 * Generates a random token for forms to prevent from CSRF. It also generate a new token after 15 minutes.
 *
 * @return void
 */
function generateToken()
{
    if (
        !isset($_SESSION['token'])
        || !isset($_SESSION['tokenExpire'])
        || $_SESSION['tokenExpire'] < time()
    ) {
        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
        $_SESSION['tokenExpire'] = time() + 60 * 15;
    }
}

/**
 * Checks if form input datas are valid and what was asked from the form.
 *
 * @return void
 */
function checkOperationFormDatas(): void
{
    if (!isset($_POST['operation_description']) || strlen($_POST['operation_description']) > 255) {
        addError('operation_description_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['operation_amount']) || empty($_POST['operation_amount']) || !is_numeric($_POST['operation_amount']) || $_POST['operation_amount'] < 0) {
        addError('operation_amount_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['date']) || empty($_POST['date'])) {
        addError('operation_date_ko');
        redirectTo();
        exit;
    }

    if (!isset($_POST['operation_brand']) || $_POST['operation_brand'] == null) {
        addError('operation_brand_ko');
        redirectTo();
        exit;
    }
}

/**
 * Sanitize input data to prevent XSS attacks. Remove any potentially harmful characters and escape HTML special characters.
 *
 * @param string $input - The input string to sanitize.
 * @return string - The sanitized input string.
 */
function sanitizeInput(string $input): string
{
    return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
}

/**
 * Checks user's client status. If user is not authorized, redirect to dashboard page and add error message.
 *
 * @param array $session - $_SESSION super global.
 * @return void 
 */
function checkUserClientStatus(array $session)
{
    if (!isset($session['client']) || $session['client'] === 1) {
        addError('authorization_ko');
        redirectTo('dashboard');
        exit();
    }
}

/**
 * Checks form campaign inputs for creation or edition.
 *
 * @param $post - $_POST Request.
 */
function checkFormCampaignInputs($post)
{
    if (!isset($post['campaign_name']) || empty($post['campaign_name'])) {
        addError('campaign_name_ko');
        redirectTo();
        exit;
    }

    if (!isset($post['campaign_company']) || empty($post['campaign_company'])) {
        addError('campaign_company_ko');
        redirectTo();
        exit;
    }

    if (!isset($post['campaign_interlocutor']) || empty($post['campaign_interlocutor'])) {
        addError('campaign_interlocutor_ko');
        redirectTo();
        exit;
    }

    if (!isset($post['budget']) || !is_numeric($post['budget']) || $post['budget'] < 0) {
        addError('budget_ko');
        redirectTo();
        exit;
    }

    if (!isset($post['campaign_target']) || empty($post['campaign_target'])) {
        addError('campaign_target_ko');
        redirectTo();
        exit;
    }

    if (!isset($post['date']) || empty($post['date'])) {
        addError('date_ko');
        redirectTo();
        exit;
    }
}
