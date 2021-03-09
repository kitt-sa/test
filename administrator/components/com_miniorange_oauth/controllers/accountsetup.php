<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauth
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 * AccountSetup Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauth
 * @since       0.0.9
 */
defined('_JEXEC') or die('Restricted access');

class miniorangeoauthControllerAccountSetup extends JControllerForm
{
    function __construct()
    {
        $this->view_list = 'accountsetup';
        parent::__construct();
    }
    function customerLoginForm() {


        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        // Fields to update.
        $fields = array(
            $db->quoteName('login_status') . ' = '.$db->quote(true),
            $db->quoteName('password') . ' = ' . $db->quote(''),
            $db->quoteName('email_count') . ' = ' . $db->quote(''),
            $db->quoteName('sms_count') . ' = ' . $db->quote(''),
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account');
    }

    function verifyCustomer()
    {
        $post=	JFactory::getApplication()->input->post->getArray();

        $email = '';
        $password = '';

        if( MoOAuthUtility::check_empty_or_null( $post['email'] ) ||MoOAuthUtility::check_empty_or_null( $post['password'] ) ) {
            JFactory::getApplication()->enqueueMessage( 4711, 'All the fields are required. Please enter valid entries.' );
            return;
        } else{
            $email =$post['email'];
            $password =  $post['password'] ;
        }

        $customer = new MoOauthCustomer();
        $content = $customer->get_customer_key($email,$password);

        $customerKey = json_decode( $content, true );
        if( strcasecmp( $customerKey['apiKey'], 'CURL_ERROR') == 0) {
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account',$customerKey['token'],'error');
        } else if( json_last_error() == JSON_ERROR_NONE ) {
            if(isset($customerKey['id']) && isset($customerKey['apiKey']) && !empty($customerKey['id']) && !empty($customerKey['apiKey'])){
                $this->save_customer_configurations($email,$customerKey['id'], $customerKey['apiKey'], $customerKey['token'],$customerKey['phone']);
                $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license','Your account has been retrieved successfully.');
            }else{
                $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account','There was an error in fetching your details. Please try again.','error');
            }
        } else {
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account','Invalid username or password. Please try again.','error');
        }
    }

    function save_customer_configurations($email, $id, $apiKey, $token, $phone) {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // Fields to update.
        $fields = array(
            $db->quoteName('email') . ' = '.$db->quote($email),
            $db->quoteName('customer_key') . ' = '.$db->quote($id),
            $db->quoteName('api_key') . ' = '.$db->quote($apiKey),
            $db->quoteName('customer_token') . ' = '.$db->quote($token),
            $db->quoteName('admin_phone') . ' = '.$db->quote($phone),
            $db->quoteName('login_status') . ' = '.$db->quote(false),
            $db->quoteName('registration_status') .' = ' . $db->quote('SUCCESS'),
            $db->quoteName('password') . ' = ' . $db->quote(''),
            $db->quoteName('email_count') . ' = ' . $db->quote(''),
            $db->quoteName('sms_count') . ' = ' . $db->quote(''),
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
    }

    function saveConfig() {
        $post=	JFactory::getApplication()->input->post->getArray();
        if(count($post)==0){
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup');
            return;
        }

        $clientid                = isset($post['mo_oauth_client_id'])? $post['mo_oauth_client_id'] : '';
        $clientsecret            = isset($post['mo_oauth_client_secret'])? $post['mo_oauth_client_secret'] : '';
        $scope                   = isset($post['mo_oauth_scope'])? $post['mo_oauth_scope'] : 'email';
        $appname                 = isset($post['mo_oauth_app_name'])? $post['mo_oauth_app_name'] : '';
        $customappname           = isset($post['mo_oauth_custom_app_name'])? $post['mo_oauth_custom_app_name'] : '';
        $authorizeurl            = isset($post['mo_oauth_authorizeurl'])? $post['mo_oauth_authorizeurl'] : '';
        $accesstokenurl          = isset($post['mo_oauth_accesstokenurl'])? $post['mo_oauth_accesstokenurl'] : '';
        $resourceownerdetailsurl = isset($post['mo_oauth_resourceownerdetailsurl'])? $post['mo_oauth_resourceownerdetailsurl'] : '';
        $in_header               = isset($post['mo_oauth_in_header'])?$post['mo_oauth_in_header']:'';

        $enableOAuthLoginButton  = isset( $post['login_link_check']) ? $post['login_link_check'] : '0';

        $in_body                 = isset($post['mo_oauth_body'])?$post['mo_oauth_body']:'';
        $in_header_or_body       = "inHeader" ;
        if($in_header=='1' && $in_body=='1')
        {
            $in_header_or_body = "both";
        }
        else if($in_body=='1')
        {
            $in_header_or_body ="inBody";
        }

        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);
        // Fields to update.
        $fields = array(
            $db->quoteName('appname') . ' = '.$db->quote($appname),
            $db->quoteName('custom_app') . ' = '.$db->quote($customappname),
            $db->quoteName('client_id') . ' = '.$db->quote(trim($clientid)),
            $db->quoteName('client_secret') . ' = '.$db->quote(trim($clientsecret)),
            $db->quoteName('app_scope') . ' = '.$db->quote($scope),
            $db->quoteName('authorize_endpoint') . ' = '.$db->quote(trim($authorizeurl)),
            $db->quoteName('access_token_endpoint') . ' = '.$db->quote(trim($accesstokenurl)),
            $db->quoteName('user_info_endpoint') . ' = '.$db->quote(trim($resourceownerdetailsurl)),
            $db->quoteName('in_header_or_body').'='.$db->quote($in_header_or_body),
            $db->quoteName('login_link_check') . ' = '.$db->quote($enableOAuthLoginButton)

        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__miniorange_oauth_config'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();

        // retriving the details

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_oauth_customer'));
        $query->where($db->quoteName('id')." = 1");

        $db->setQuery($query);
        $c_date = $db->loadAssoc();

        if($c_date['cd_plugin']==''){

            $time = time();
            $c_time = date('m/d/Y H:i:s', time());
            // Storing
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $fields = array(
                $db->quoteName('cd_plugin') . ' = '.$db->quote($time),

            );

            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );

            $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            //stored

        }else{

            $c_time = date('m/d/Y H:i:s', $c_date['cd_plugin']);

        }

        $dVar=new JConfig();
        $check_email = $dVar->mailfrom;
        $base_url = JURI::root();
        $dno_ssos = 0;
        $tno_ssos = 0;
        $previous_update = '';
        $present_update = '';
        MoOauthCustomer::plugin_efficiency_check($check_email,$appname,$base_url, $c_time, $dno_ssos, $tno_ssos, $previous_update, $present_update);

        //Save configuration
        $message =  'Your configuration has been saved successfully. Test your configuration: <p style="" class="btn btn-primary" onclick="testConfiguration()">Test Configuration</p>';
        $status = 'success';
        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp='.$post['moOauthAppName'],$message );
    }

    function saveMapping(){
        $post=	JFactory::getApplication()->input->post->getArray();

        $email_attr = isset($post['mo_oauth_email_attr'])? $post['mo_oauth_email_attr'] : '';
        $first_name_attr = isset($post['mo_oauth_first_name_attr'])? $post['mo_oauth_first_name_attr'] : '';

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // Fields to update.
        $fields = array(
            $db->quoteName('email_attr') . ' = '.$db->quote($email_attr),
            $db->quoteName('first_name_attr') . ' = '.$db->quote($first_name_attr),
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__miniorange_oauth_config'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();

        $message =  'Attribute Mapping saved successfully.';
        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=configuration',$message );
    }

    function clearConfig(){
        $post=	JFactory::getApplication()->input->post->getArray();

        $clientid = "";
        $clientsecret = "";
        $scope = "";
        $appname = "";
        $customappname = "";
        $authorizeurl = "";
        $accesstokenurl = "";
        $resourceownerdetailsurl = "";
        $email_attr="";
        $first_name_attr="";

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // Fields to update.
        $fields = array(
            $db->quoteName('appname') . ' = '.$db->quote($appname),
            $db->quoteName('custom_app') . ' = '.$db->quote($customappname),
            $db->quoteName('client_id') . ' = '.$db->quote($clientid),
            $db->quoteName('client_secret') . ' = '.$db->quote($clientsecret),
            $db->quoteName('app_scope') . ' = '.$db->quote($scope),
            $db->quoteName('authorize_endpoint') . ' = '.$db->quote($authorizeurl),
            $db->quoteName('access_token_endpoint') . ' = '.$db->quote($accesstokenurl),
            $db->quoteName('user_info_endpoint') . ' = '.$db->quote($resourceownerdetailsurl),
            $db->quoteName('email_attr') . ' = '.$db->quote($email_attr),
            $db->quoteName('first_name_attr') . ' = '.$db->quote($first_name_attr),
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__miniorange_oauth_config'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();

        //Save configuration
        $message =  'Your configuration has been Reset successfully.';
        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=configuration',$message );
    }

    function registerCustomer(){
        //validate and sanitize

        $email = '';
        $phone = '';
        $password = '';
        $confirmPassword = '';


        $password = (JFactory::getApplication()->input->post->getArray()["password"]);
        $confirmPassword = (JFactory::getApplication()->input->post->getArray()["confirmPassword"]);

        $email=(JFactory::getApplication()->input->post->getArray()["email"]);

        if( MoOAuthUtility::check_empty_or_null( $email ) || MoOAuthUtility::check_empty_or_null($password ) || MoOAuthUtility::check_empty_or_null($confirmPassword ) ) {
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account',  'All the fields are required. Please enter valid entries.','error');
            return;
        } else if( strlen( $password ) < 6 || strlen( $confirmPassword ) < 6){	//check password is of minimum length 6
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account',  'Choose a password with minimum length 6.','error');
            return;
        } else{
            $email = JFactory::getApplication()->input->post->getArray()["email"];
            $email = strtolower($email);
            $phone = JFactory::getApplication()->input->post->getArray()["phone"];
            $password =JFactory::getApplication()->input->post->getArray()["password"];
            $confirmPassword = JFactory::getApplication()->input->post->getArray()["confirmPassword"];
        }

        if( strcmp( $password, $confirmPassword) == 0 ) {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            // Fields to update.
            $fields = array(
                $db->quoteName('email') . ' = ' . $db->quote($email),
                $db->quoteName('admin_phone') . ' = ' . $db->quote($phone),
                $db->quoteName('password') . ' = ' . $db->quote($password),

            );

            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );

            $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();

            $customer = new MoOauthCustomer();
            $content = json_decode($customer->check_customer($email), true);
            if( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND') == 0 ){
                $auth_type = 'EMAIL';
                $content = json_decode($customer->send_otp_token($auth_type, $email), true);
                if(strcasecmp($content['status'], 'SUCCESS') == 0) {

                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $fields = array(
                        $db->quoteName('email_count') . ' = ' . $db->quote(1),
                        $db->quoteName('transaction_id') . ' = ' . $db->quote($content['txId']),
                        $db->quoteName('login_status') . ' = ' . $db->quote(false),
                        $db->quoteName('registration_status') . ' = ' . $db->quote('MO_OTP_DELIVERED_SUCCESS')
                    );
                    // Conditions for which records should be updated.
                    $conditions = array(
                        $db->quoteName('id') . ' = 1'
                    );

                    $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                    $db->setQuery($query);
                    $result = $db->execute();

                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'A One Time Passcode has been sent <b>( 1 )</b> to <b>' . $email . '</b>. Please enter the OTP below to verify your email. ');


                } else {

                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $fields = array(
                        $db->quoteName('login_status') . ' = ' . $db->quote(false),
                        $db->quoteName('registration_status') . ' = ' . $db->quote('MO_OTP_DELIVERED_FAILURE')
                    );
                    // Conditions for which records should be updated.
                    $conditions = array(
                        $db->quoteName('id') . ' = 1'
                    );

                    $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                    $db->setQuery($query);
                    $result = $db->execute();

                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'There was an error in sending email. Please click on Resend OTP to try again. ','error');


                }
            } else if( strcasecmp( $content['status'], 'CURL_ERROR') == 0 ){

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $fields = array(
                    $db->quoteName('login_status') . ' = ' . $db->quote(false),
                    $db->quoteName('registration_status') . ' = ' . $db->quote('MO_OTP_DELIVERED_FAILURE')
                );
                // Conditions for which records should be updated.
                $conditions = array(
                    $db->quoteName('id') . ' = 1'
                );

                $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $result = $db->execute();

                $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', $content['statusMessage'],'error');

            } else{
                $content = $customer->get_customer_key($email,$password);
                $customerKey = json_decode($content, true);
                if(json_last_error() == JSON_ERROR_NONE) {
                    $this->save_customer_configurations($email,$customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['phone']);
                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license', 'Your account has been retrieved successfully.');
                } else {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $fields = array(
                        $db->quoteName('login_status') . ' = ' . $db->quote(true),
                        $db->quoteName('registration_status') . ' = ' . $db->quote('')
                    );
                    // Conditions for which records should be updated.
                    $conditions = array(
                        $db->quoteName('id') . ' = 1'
                    );

                    $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                    $db->setQuery($query);
                    $result = $db->execute();

                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'You already have an account with miniOrange. Please enter a valid password. ','error');

                }
            }

        } else {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $fields = array(
                $db->quoteName('login_status') . ' = ' . $db->quote(false)
            );
            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );

            $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'Password and Confirm password do not match.','error');
        }
    }

    function validateOtp(){

        $otp_token =JFactory::getApplication()->input->post->getArray()["otp_token"];
        //validation and sanitization
        //$otp_token = '';
        if( MoOAuthUtility::check_empty_or_null( $otp_token) ) {
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'Please enter a valid OTP.','error');
            return;
        } else{
            $otp_token =  JFactory::getApplication()->input->post->getArray()['otp_token'] ;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('transaction_id');
        $query->from($db->quoteName('#__miniorange_oauth_customer'));
        $query->where($db->quoteName('id')." = 1");

        $db->setQuery($query);
        $transaction_id = $db->loadResult();

        $customer = new MoOauthCustomer();
        $content = json_decode($customer->validate_otp_token($transaction_id, trim($otp_token) ),true);
        if(strcasecmp($content['status'], 'SUCCESS') == 0) {
            $customerKey = json_decode($customer->create_customer(), true);

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $fields = array(
                $db->quoteName('email_count') . ' = ' . $db->quote(''),
                $db->quoteName('sms_count') . ' = ' . $db->quote('')
            );
            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );
            $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            if(strcasecmp($customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0) {	//admin already exists in miniOrange
                $content = $customer->get_customer_key();
                $customerKey = json_decode($content, true);
                if(json_last_error() == JSON_ERROR_NONE) {
                    $this->save_customer_configurations($customerKey['email'], $customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['phone']);
                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup','Your account has been retrieved successfully.');
                } else {
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $fields = array(
                        $db->quoteName('login_status') . ' = ' . $db->quote(true),
                        $db->quoteName('password') . ' = ' . $db->quote(''),
                    );
                    // Conditions for which records should be updated.
                    $conditions = array(
                        $db->quoteName('id') . ' = 1'
                    );

                    $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                    $db->setQuery($query);
                    $result = $db->execute();

                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'You already have an account with miniOrange. Please enter a valid password.','error');

                }
            } else if(strcasecmp($customerKey['status'], 'SUCCESS') == 0) {

                //registration successful
                $this->save_customer_configurations($customerKey['email'], $customerKey['id'], $customerKey['apiKey'], $customerKey['token'],$customerKey['phone']);
                $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license','Your account has been created successfully.');
            }else if(strcasecmp($customerKey['status'],'INVALID_EMAIL_QUICK_EMAIL')==0){

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $fields = array(
                    $db->quoteName('registration_status') . ' = ' . $db->quote(''),
                    $db->quoteName('email') . ' = ' . $db->quote(''),
                    $db->quoteName('password') . ' = ' . $db->quote(''),
                    $db->quoteName('transaction_id') . ' = ' . $db->quote(''),
                );
                // Conditions for which records should be updated.
                $conditions = array(
                    $db->quoteName('id') . ' = 1'
                );

                $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $result = $db->execute();

                $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account','There was an error creating an account for you. You may have entered an invalid Email-Id. <br><b>(We discourage the use of disposable emails)</b><br>
												Please try again with a valid email.','error');

            }
            //update_option('mo_saml_local_password', '');
        } else if( strcasecmp( $content['status'], 'CURL_ERROR') == 0) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $fields = array(
                $db->quoteName('registration_status') . ' = ' . $db->quote('MO_OTP_VALIDATION_FAILURE')
            );
            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );

            $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', $content['statusMessage'],'error');

        } else {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $fields = array(
                $db->quoteName('registration_status') . ' = ' . $db->quote('MO_OTP_VALIDATION_FAILURE')
            );
            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );

            $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account','Invalid one time passcode. Please enter a valid OTP.','error');

        }
    }

    function resendOtp(){


        $customer = new MoOauthCustomer();
        $auth_type = 'EMAIL';

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('email');
        $query->from($db->quoteName('#__miniorange_oauth_customer'));
        $query->where($db->quoteName('id')." = 1");

        $db->setQuery($query);
        $email = $db->loadResult();

        $content = json_decode($customer->send_otp_token($auth_type, $email), true);
        if(strcasecmp($content['status'], 'SUCCESS') == 0) {

            $customer_details = MoOAuthUtility::getCustomerDetails();
            $email_count = $customer_details['email_count'];
            $admin_email = $customer_details['email'];

            if($email_count != '' && $email_count >= 1){
                $email_count = $email_count + 1;

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $fields = array(
                    $db->quoteName('email_count') . ' = ' . $db->quote($email_count),
                    $db->quoteName('transaction_id') . ' = ' . $db->quote($content['txId']),
                    $db->quoteName('registration_status') . ' = ' . $db->quote('MO_OTP_DELIVERED_SUCCESS')
                );
                // Conditions for which records should be updated.
                $conditions = array(
                    $db->quoteName('id') . ' = 1'
                );

                $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $result = $db->execute();

                $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'Another One Time Passcode has been sent to <b>' . ( $admin_email) . '</b>. Please enter the OTP below to verify your email.');

            }else{
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $fields = array(
                    $db->quoteName('email_count') . ' = ' . $db->quote(1),
                    $db->quoteName('transaction_id') . ' = ' . $db->quote($content['txId']),
                    $db->quoteName('registration_status') . ' = ' . $db->quote('MO_OTP_DELIVERED_SUCCESS')
                );
                // Conditions for which records should be updated.
                $conditions = array(
                    $db->quoteName('id') . ' = 1'
                );

                $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $result = $db->execute();
                $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account',  'An OTP has been sent to <b>' . ($admin_email) . '</b>. Please enter the OTP below to verify your email.');

            }

        } else if( strcasecmp( $content['status'], 'CURL_ERROR') == 0) {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $fields = array(
                $db->quoteName('registration_status') . ' = ' . $db->quote('MO_OTP_DELIVERED_FAILURE')
            );
            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );

            $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account',  $content['statusMessage'],'error');

        } else{
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $fields = array(
                $db->quoteName('registration_status') . ' = ' . $db->quote('MO_OTP_DELIVERED_FAILURE')
            );
            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );

            $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account',  'There was an error in sending email. Please click on Resend OTP to try again.','error');

        }
    }

    function cancelform(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('email') . ' = ' . $db->quote(''),
            $db->quoteName('password') . ' = ' . $db->quote(''),
            $db->quoteName('customer_key') . ' = ' . $db->quote(''),
            $db->quoteName('admin_phone') . ' = ' . $db->quote(''),
            $db->quoteName('customer_token') . ' = ' . $db->quote(''),
            $db->quoteName('api_key') . ' = ' . $db->quote(''),
            $db->quoteName('registration_status') . ' = ' . $db->quote(''),
            $db->quoteName('login_status') . ' = ' . $db->quote(false),
            $db->quoteName('transaction_id') . ' = ' . $db->quote(''),
            $db->quoteName('email_count') . ' = ' . $db->quote(''),
            $db->quoteName('sms_count') . ' = ' . $db->quote(''),
        );
        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account');

    }

    function phoneVerification(){
        $phone = JFactory::getApplication()->input->post->getArray()['phone_number'];
        $phone = str_replace(' ', '', $phone);

        $pattern = "/[\+][0-9]{1,3}[0-9]{10}/";

        if(preg_match($pattern, $phone, $matches, PREG_OFFSET_CAPTURE)){
            $auth_type = 'SMS';
            $customer = new MoOauthCustomer();
            $send_otp_response = json_decode($customer->send_otp_token($auth_type, $phone));
            if($send_otp_response->status == 'SUCCESS'){

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('sms_count');
                $query->from($db->quoteName('#__miniorange_oauth_customer'));
                $query->where($db->quoteName('id')." = 1");

                $db->setQuery($query);
                $sms_count = $db->loadResult();

                if($sms_count != '' && $sms_count >= 1){
                    $sms_count = $sms_count + 1;
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $fields = array(
                        $db->quoteName('sms_count') . ' = ' . $db->quote($sms_count),
                        $db->quoteName('transaction_id') . ' = ' . $db->quote($send_otp_response->txId)
                    );
                    // Conditions for which records should be updated.
                    $conditions = array(
                        $db->quoteName('id') . ' = 1'
                    );

                    $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                    $db->setQuery($query);
                    $result = $db->execute();

                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'Another One Time Passcode has been sent <b>(' . $sms_count . ')</b> for verification to ' . $phone);


                } else{
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $fields = array(
                        $db->quoteName('sms_count') . ' = ' . $db->quote(1),
                        $db->quoteName('transaction_id') . ' = ' . $db->quote($send_otp_response->txId)
                    );
                    // Conditions for which records should be updated.
                    $conditions = array(
                        $db->quoteName('id') . ' = 1'
                    );

                    $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
                    $db->setQuery($query);
                    $result = $db->execute();
                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'A One Time Passcode has been sent ( <b>1</b> ) for verification to ' . $phone);
                }

            } else{
                $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'An error occurred while sending OTP to phone. Please try again.');
            }
        }else{

            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'Please enter the phone number in the following format: <b>+##country code## ##phone number##','error');
        }
    }

    function requestForDemoPlan()
    {
        $post=	JFactory::getApplication()->input->post->getArray();
        if(count($post)==0){
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup');
            return;
        }
        $email          = $post['email'];
        $plan           = $post['plan'];
        $description    = $post['description'];
        $customer       = new MoOauthCustomer();

        if($plan == "Not Sure")
            $description = $post['description'];
        $response = json_decode($customer->request_for_demo($email, $plan, $description));

        if($response->status != 'ERROR')
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', 'Someone from our company will contact you shortly.');
        else
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', 'An error occured, please try again.', 'error');
    }


    function forgotPassword(){

        $post = JFactory::getApplication()->input->post->getArray();

        // $jinput = JFactory::getApplication()->input;
        // $post = jinput::get( 'post' );

        $admin_email = $post['current_admin_email'];

        if(MoOAuthUtility::check_empty_or_null( $admin_email )){

            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account','Please enter your email below registered with miniOrange and then click on Forgot Password link.','error');
            return;
        }

        $customer = new MoOauthCustomer();
        $forgot_password_response = json_decode($customer->mo_otp_forgot_password($admin_email));
        if($forgot_password_response->status == 'SUCCESS'){

            $message = 'You password has been reset successfully. A new password has been sent to your registered mail.';
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', $message);

        } else {

            $message = 'An error occurred while reseting the password. Please try again.';
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', $forgot_password_response->message, 'error');
        }
    }
    function callContactUs() {
        $post = JFactory::getApplication()->input->post->getArray();
        if(count($post)==0){
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup');
            return;
        }
        $query_email = $post['mo_oauth_setup_call_email'];
        $query       = $post['mo_oauth_setup_call_issue'] ;
        $description =$post['mo_oauth_setup_call_desc'];
        $callDate    =$post['mo_oauth_setup_call_date'];
        $timeZone    =$post['mo_oauth_setup_call_timezone'];
        if( MoOAuthUtility::check_empty_or_null( $timeZone ) ||MoOAuthUtility::check_empty_or_null( $callDate ) ||MoOAuthUtility::check_empty_or_null( $query_email ) || MoOAuthUtility::check_empty_or_null( $query)||MoOAuthUtility::check_empty_or_null( $description) ) {
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', 'Mentioned fields are mandatory', 'error');
            return;
        } else{
            $contact_us = new MoOauthCustomer();
            $submited = json_decode($contact_us->request_for_demo($query_email, $query, $description, $callDate, $timeZone),true);
            if(json_last_error() == JSON_ERROR_NONE) {
                if(is_array($submited) && array_key_exists('status', $submited) && $submited['status'] == 'ERROR'){
                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', $submited['message'],'error');
                }else{
                    if ( $submited == false ) {
                        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', 'Your query could not be submitted. Please try again.','error');
                    } else {
                        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', 'Thanks for getting in touch! We shall get back to you shortly.');
                    }
                }
            }

        }
    }
    function contactUs() {
        $post = JFactory::getApplication()->input->post->getArray();
        if(count($post)==0){
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup');
            return;
        }
        $query_email = $post['query_email'];
        $query       = $post['query'] ;
        $phone       = $post['query_phone'];

        if( MoOAuthUtility::check_empty_or_null( $query_email ) || MoOAuthUtility::check_empty_or_null( $query) ) {
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', 'Please submit your query with email.', 'error');
            return;
        } else{
            $contact_us = new MoOauthCustomer();
            $submited = json_decode($contact_us->submit_contact_us($query_email, $phone, $query),true);
            if(json_last_error() == JSON_ERROR_NONE) {
                if(is_array($submited) && array_key_exists('status', $submited) && $submited['status'] == 'ERROR'){
                    $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', $submited['message'],'error');
                }else{
                    if ( $submited == false ) {
                        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', 'Your query could not be submitted. Please try again.','error');
                    } else {
                        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', 'Thanks for getting in touch! We shall get back to you shortly.');
                    }
                }
            }

        }
 
 
    }
    function removeAccount()
    {
        $nameOfDatabase = '#__miniorange_oauth_customer';
        $updateFieldsArray = array(
            'email'               => '',
            'password'            => '',
            'customer_key'        => '',
            'api_key'             => '',
            'customer_token'      => '',
            'admin_phone'         => '',
            'login_status'        => false,
            'registration_status' => 'SUCCESS',
            'email_count'         => '',
            'sms_count'           => '',
        );
        $this->updateDatabaseQuery($nameOfDatabase, $updateFieldsArray);
        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', 'Your account has been removed successfully.');
    }
    function updateDatabaseQuery($database_name, $updatefieldsarray){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        foreach ($updatefieldsarray as $key => $value)
        {
            $database_fileds[] = $db->quoteName($key) . ' = ' . $db->quote($value);
        }
        $query->update($db->quoteName($database_name))->set($database_fileds)->where($db->quoteName('id')." = 1");
        $db->setQuery($query);
        $db->execute();
    }
}