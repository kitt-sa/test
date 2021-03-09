<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauth
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('jquery.framework');
JHtml::stylesheet(JURI::base() . 'components/com_miniorange_oauth/assets/css/miniorange_oauth.css', array(), true);
JHtml::stylesheet(JURI::base() . 'components/com_miniorange_oauth/assets/css/bootstrap-tour-standalone.css', array(), true);
JHtml::stylesheet(JURI::base() . 'components/com_miniorange_oauth/assets/css/miniorange_boot.css', array(), true);
JHtml::script(JURI::base() . 'components/com_miniorange_oauth/assets/js/bootstrap-tour-standalone.min.js');
JHtml::script(JURI::base() . 'components/com_miniorange_oauth/assets/js/jeswanthscript.js');
JHtml::stylesheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), true);

if (MoOAuthUtility::is_curl_installed() == 0) { ?>
    <p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL
            extension</a> is not installed or disabled) Please go to Troubleshooting for steps to enable curl.</p>
    <?php
}
$active_tab = JFactory::getApplication()->input->get->getArray();
$oauth_active_tab = isset($active_tab['tab-panel']) && !empty($active_tab['tab-panel']) ? $active_tab['tab-panel'] : 'configuration';
$license_tab_link="index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license";
$current_user = JFactory::getUser();
if(!JPluginHelper::isEnabled('system', 'miniorangeoauth')) {
    ?>
    <div id="system-message-container">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <div class="alert alert-error">
            <h4 class="alert-heading">Warning!</h4>
            <div class="alert-message">
                <h4>
                    This component requires System Plugin to be activated. Please activate the following plugin
                    to proceed further: System - miniOrange OAuth Client
                </h4>
                <h4>Steps to activate the plugins:</h4>
                <ul>
                    <li>In the top menu, click on Extensions and select Plugins.</li>
                    <li>Search for miniOrange in the search box and press 'Search' to display the plugins.</li>
                    <li>Now enable the System plugin.</li>
                </ul>
            </div>
            </h4>
        </div>
    </div>
<?php } ?>
    <input type="button" id="end_oa_tour_oauth" value="Start Plugin tour" onclick="restart_oatour_oauth();" style=" float: right; margin-right:10px" class="btn btn-medium btn-danger"/>
    <div class="nav-tab-wrapper mo_idp_nav-tab-wrapper" id="myTabTabs">
        <a id="configtab" class="mo_nav-tab <?php echo $oauth_active_tab == 'configuration' ? 'active' : ''; ?>" href="#configuration"
            onclick="add_css_tab('#configtab');"
            data-toggle="tab"><?php echo JText::_('COM_MINIORANGE_OAUTH_TAB2_CONFIGURE_OAUTH'); ?>
        </a>
        <a id="attributetab" class="mo_nav-tab <?php echo $oauth_active_tab == 'attrrolemapping' ? 'active' : ''; ?>" href="#attrrolemapping"
            onclick="add_css_tab('#attributetab');"
            data-toggle="tab"><?php echo "Attribute/Role Mapping"; ?>
        </a>
        <a id="advancetab" class="mo_nav-tab <?php echo $oauth_active_tab == 'loginlogoutsettings' ? 'active' : ''; ?>" href="#loginlogoutsettings"
            onclick="add_css_tab('#advancetab');"
            data-toggle="tab"><?php echo "Advanced Settings"; ?>
        </a>
        <a id="licensetab" class="mo_nav-tab <?php echo $oauth_active_tab == 'license' ? 'active' : ''; ?>" href="#licensing-plans"
            onclick="add_css_tab('#licensetab');"
            data-toggle="tab"><?php echo JText::_('com_miniorange_oauth_TAB3_LICENSING_PLANS');?>
        </a>
        <a id="accounttab" class="mo_nav-tab <?php echo $oauth_active_tab == 'account' ? 'active' : ''; ?>" href="#description"
            onclick="add_css_tab('#accounttab');"
            data-toggle="tab"><?php echo JText::_('com_miniorange_oauth_TAB1_ACCOUNT_SETUP'); ?>
        </a>
        <a id="supports" class="mo_nav-tab <?php echo $oauth_active_tab =='support'?'active':'';?>" href="#support"
            onclick="add_css_tab('#supports');"
            data-toggle="tab"><?php echo "Support" ?>
        </a>
        <a id="addons" class="mo_nav-tab <?php echo $oauth_active_tab =='addon'?'active':'';?>" href="#addon"
            onclick="add_css_tab('#addons');"
            data-toggle="tab"><?php echo "Add-On" ?>
        </a>
    </div>
    <script>
        function add_css_tab(element) {
            jQuery(".mo_nav_tab_active").removeClass("mo_nav_tab_active").removeClass("active");
            jQuery(element).addClass("mo_nav_tab_active");
        }
        jQuery(document).ready(function(){
            var myID=jQuery('a[href="#<?php echo $oauth_active_tab;?>"]').attr('id');
            jQuery("#"+myID).addClass("mo_nav_tab_active");

        });
        function restart_oatour_oauth() {
            jQuery('.nav-tabs a[href=#configuration]').tab('show');
            oatour.restart();
        }

        var base_url = '<?php echo JURI::root();?>';
        var oatour = new Tour({
            name: "oatour",
            steps: [
                {
                    element: "#configtab",
                    title: "Configuration Tab",
                    content: "Configure your server with client here to perform SSO.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onNext: function () {
                        jQuery('a[href=#attrrolemapping]').tab('show');
                    }
                }, {
                    element: "#attributetab",
                    title: "Mapping Tab",
                    content: "You can do attribute mapping and role mapping here",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('a[href=#configuration]').tab('show');
                    },
                    onNext: function () {
                        jQuery('a[href=#loginlogoutsettings]').tab('show');
                    }
                },{
                    element: "#advancetab",
                    title: "Advance Setting Tab",
                    content: "You can check out our all advance feature here",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('a[href=#attrrolemapping]').tab('show');
                    },
                    onNext: function () {
                        jQuery('a[href=#licensing-plans]').tab('show');
                    }
                },{
                    element: "#licensetab",
                    title: "Upgrade Plans",
                    content: "You can compare our licensed versions and their features.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('a[href=#loginlogoutsettings]').tab('show');
                    },
                    onNext: function () {
                        jQuery('a[href=#description]').tab('show');
                    }

                }, {
                    element: "#accounttab",
                    title: "Account Setup",
                    content: "You could login or register yourself to miniorange here.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('a[href=#licensing-plans]').tab('show');
                    },
                    onNext: function () {
                        jQuery('a[href=#support]').tab('show');
                    }

                }, {
                    element: "#supports",
                    title: "Help",
                    content: "If you need any help you can contact us here or you could find solutions for most popular queries.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('a[href=#description]').tab('show');
                    },
                    onNext: function () {
                        jQuery('a[href=#addon]').tab('show');
                    }

                }, {
                    element: "#addons",
                    title: "Add on",
                    content: "Check out all our AddOns to extend the functionality",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('a[href=#support]').tab('show');
                    },
                },{
                    element: "#oacconf_end_tour",
                    title: "Tab Tour.",
                    content: " You could find the start tour button on each tab which will help you to configure the tab /get the inforamtion from that tab.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('a[href=#addon]').tab('show');
                    }

                }, {
                    element: "#end_oa_tour_oauth",
                    title: "Tab Tour.",
                    content: " By clicking on start Plugin tour button you will take to over all plugin tour and explain what each tab does.",
                    backdrop: 'body',
                    backdropPadding: '6',
                }
            ]
        });
    </script>
    <style>
        .mo_floating_support{
            writing-mode: vertical-rl;
            transform:rotate(180deg);
            position:fixed!important;
            right:0;
            border-radius:5px;
            color:white;
            font-size:16px;
            text-decoration:none!important;
            border:1px solid blue;
        }
    </style>
    <div class="tab-content" id="myTabContent">
        <a class="mo_floating_support  mo_boot_px-2 mo_boot_py-4 mo_boot_bg-success" href="<?php echo JURI::base()?>index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=support">
            Support
        </a>
        <div id="description" class="tab-pane <?php echo $oauth_active_tab == 'account' ? 'active' : ''; ?>">
            <div class="mo_boot_row mo_boot_m-1" style="background-color:#e0e0d8;">
                <?php
                $customer_details = MoOAuthUtility::getCustomerDetails();
                $registration_status = $customer_details['registration_status'];
                if ($customer_details['login_status']) {  //Show Login Page?>
                    <div class="mo_boot_col-sm-8">
                        <?php mo_oauth_login_page(); ?>
                    </div>
                    <div class="mo_boot_col-sm-4">
                        <?php  echo mo_oauth_support();?>
                    </div>
                <?php
                } else {  // Show Registration Page
                    if ($registration_status == 'MO_OTP_DELIVERED_SUCCESS' || $registration_status == 'MO_OTP_VALIDATION_FAILURE' || $registration_status == 'MO_OTP_DELIVERED_FAILURE') {
                        ?>
                        <div class="mo_boot_col-sm-8">
                            <?php mo_otp_show_otp_verification(); ?>
                        </div>
                        <div class="mo_boot_col-sm-4">
                            <?php  echo mo_oauth_support();?>
                        </div>
                    <?php
                    }
                    else if (!MoOAuthUtility::is_customer_registered()) {
                        ?>
                            <div class="mo_boot_col-sm-8">
                                <?php mo_oauth_registration_page(); ?>
                            </div>
                            <div class="mo_boot_col-sm-4">
                                <?php  echo mo_oauth_support();?>
                            </div>
                        <?php
                    }
                    else
                    {
                        ?>
                            <div class="mo_boot_col-sm-8">
                                <?php mo_oauth_account_page(); ?>
                            </div>
                            <div class="mo_boot_col-sm-4">
                                <?php  echo mo_oauth_support();?>
                            </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <div id="configuration" class="tab-pane <?php echo $oauth_active_tab == 'configuration' ? 'active' : ''; ?>">
            <div class="mo_boot_row mo_boot_m-1" style="background-color:#e0e0d8;">
                <div class="mo_boot_col-sm-8">
                    <?php selectAppByIcon(); ?>
                </div>
                <div id="confsupport" class="mo_boot_col-sm-4">
                    <?php grant_type_settings(); ?>
                </div>
            </div>
        </div>
        <div id="attrrolemapping" class="tab-pane <?php echo $oauth_active_tab == 'attrrolemapping' ? 'active' : ''; ?>">
            <div class="mo_boot_row mo_boot_m-1" style="background-color:#e0e0d8;">
                <div class="mo_boot_col-sm-8">
                    <?php attributerole(); ?>
                </div>
                <div id="confsupport" class="mo_boot_col-sm-4">
                    <?php  echo mo_oauth_support();?>
                </div>
            </div>
        </div>
        <div id="loginlogoutsettings" class="tab-pane <?php echo $oauth_active_tab == 'loginlogoutsettings' ? 'active' : ''; ?>">
            <div class="mo_boot_row mo_boot_m-1" style="background-color:#e0e0d8;">
                <div class="mo_boot_col-sm-8">
                    <?php loginlogoutsettings(); ?>
                </div>
                <div id="confsupport" class="mo_boot_col-sm-4">
                    <?php  echo mo_oauth_support();?>
                </div>
            </div>
        </div>
        <div id="support" class="tab-pane <?php echo $oauth_active_tab == 'support' ? 'active' : ''; ?>">
            <div class="mo_boot_row mo_boot_m-1" style="background-color:#e0e0d8;">
                <div class="mo_boot_col-sm-8">
                    <?php support();   ?>
                </div>
                <div id="confsupport" class="mo_boot_col-sm-4">
                    <?php sideaddon()?>
                </div>
            </div>
        </div>
        <div id="addon" class="tab-pane <?php echo $oauth_active_tab == 'addon' ? 'active' : ''; ?>">
            <div class="mo_boot_row mo_boot_m-1" style="background-color:#e0e0d8;">
                <div class="mo_boot_col-sm-8">
                    <?php addOn();?>
                </div>
                <div id="confsupport" class="mo_boot_col-sm-4">
                    <?php  echo mo_oauth_support();?>
                </div>
            </div>
        </div>

        <div id="licensing-plans" class="tab-pane <?php echo $oauth_active_tab == 'license' ? 'active' : ''; ?>">
            <div class="mo_boot_row mo_boot_m-1" style="background-color:#e0e0d8;">
                <div class="mo_boot_col-sm-12">
                    <?php
                        $result = MoOAuthUtility::getCustomerDetails();
                        $email = $result['email'];
                        $hostName = 'https://www.miniorange.com';
                        $loginUrl = $hostName . '/contact';
                        echo showLicensingPlanDetails();
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!--
        *End Of Tabs for accountsetup view.
        *Below are the UI for various sections of Account Creation.
    -->
<?php


function mo_oauth_login_page()
{
    $result = MoOAuthUtility::getCustomerDetails();
    $admin_email = $result["email"];
    ?>
    <form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.verifyCustomer'); ?>">
        <div class="mo_boot_row mo_boot_mx-1 mo_boot_my-3" style="background:white;">
            <div class="mo_boot_col-sm-12">
                <h3>Login with miniOrange</h3>
                <hr>
                <p>
                    Please enter your miniOrange account credentials. If you forgot your password then enter your email and click
                    on <b>Forgot your password</b> link. If you are not registered with miniOrange then click on <b>Back To
                    Registration</b> link.
                </p>
            </div>
            <div class="mo_boot_col-sm-12 mo_boot_p-3">
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <b><font color="#FF0000">*</font>Email:</b>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input class="mo_boot_form-control oauth-textfield" type="email" name="email" id="email" required placeholder="person@example.com" value="<?php echo $admin_email; ?>"/>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <b><font color="#FF0000">*</font>Password:</b>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input class="mo_boot_form-control oauth-textfield" required type="password" name="password" placeholder="Enter your miniOrange password"/>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2 mo_boot_text-center">
                    <div class="mo_boot_col-sm-12">
                        <input type="submit" class="mo_boot_btn mo_boot_btn-success" value="Login"/>&nbsp;&nbsp;
                        <a href="#mo_oauth_forgot_password_link">Forgot your password?</a>&nbsp;&nbsp;
                        <a href="#oauth_cancel_link">Back To Registration</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="oauth_forgot_password_form" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.forgotpassword'); ?>">
        <input type="hidden" name="current_admin_email" id="current_admin_email" value=""/>
    </form>
    <form id="oauth_cancel_form" method="post"
          action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.cancelform'); ?>">
    </form>
    <script>
        jQuery("a[href='#oauth_cancel_link']").click(function () {
            jQuery('#oauth_cancel_form').submit();
        });
        jQuery("a[href='#mo_oauth_forgot_password_link']").click(function () {
            var email = jQuery('#email').val();
            jQuery('#current_admin_email').val(email);
            jQuery('#oauth_forgot_password_form').submit();
        });
    </script>
    <?php
}

/* Show otp verification page*/
function mo_otp_show_otp_verification()
{
    ?>
    <div class="mo_boot_row mo_boot_mx-1 mo_boot_my-3" style="background:white;">
        <div class="mo_boot_col-sm-12">
            <form name="f" method="post" id="otp_form" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.validateOtp'); ?>">
                <div class="mo_boot_row">
                    <div class="mo_boot_col-sm-12">
                        <h3>Verify Your Email</h3>
                        <hr/>
                    </div>
                    <div class="mo_boot_col-sm-12">
                        <div class="mo_boot_row mo_boot_mt-2">
                            <div class="mo_boot_col-sm-3">
                                <b><font color="#FF0000">*</font>Enter OTP:</b>
                            </div>
                            <div class="mo_boot_col-sm-6">
                                <input class="mo_boot_form-control" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP"/>
                            </div>
                            <div class="mo_boot_col-sm-2">
                                <a href="#mo_otp_resend_otp_email">Resend OTP</a>
                            </div>
                        </div>
                        <div class="mo_boot_row mo_boot_mt-2 mo_boot_text-center">
                            <div class="mo_boot_col-sm-12">
                                <input type="submit" value="Validate OTP" class="mo_boot_btn mo_boot_btn-success"/>&nbsp;&nbsp;&nbsp;
                                <input type="button" value="Back" id="back_btn" class="mo_boot_btn mo_boot_btn-danger"/>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </form>

            <form method="post"
                action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.cancelform'); ?>"
                id="mo_otp_cancel_form">
            </form>

            <form name="f" id="resend_otp_form" method="post"
                action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.resendOtp'); ?>">
            </form>


            <form id="phone_verification" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.phoneVerification'); ?>">
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-12">
                        <h3>I did not receive any email with OTP . What should I do ?</h3>
                        <p>If you can't see the email from miniOrange in your mails, please check your <b>SPAM Folder</b>. If you don't see
                        an email even in SPAM folder, verify your identity with our alternate method.</p>
                        <p><b>Enter your valid phone number here and verify your identity using one time passcode sent to your
                        phone.</b></p>
                    </div>
                    <div class="mo_boot_col-sm-4">
                        <input class="mo_boot_form-control" required="true" pattern="[\+]\d{1,3}\d{10}" autofocus="true" type="text"
                            name="phone_number" id="phone_number" placeholder="Enter Phone Number with country code"
                            title="Enter phone number without any space or dashes with country code."/>
                    </div>
                    <div class="mo_boot_col-sm-2">
                        <input type="submit" value="Send OTP" class="mo_boot_btn mo_boot_btn-success"/>
                    </div>
                    <div class="mo_boot_col-sm-12">
                        <hr>
                        <p style="color:#b42f2f;">If you face any issues while registration then please contact us at <a href="mailto:joomlasupport@xecurify.com">joomlasupport@xecurify.com</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        jQuery('#back_btn').click(function () {
            jQuery('#mo_otp_cancel_form').submit();
        });
        jQuery('a[href=#mo_otp_resend_otp_email]').click(function () {
            jQuery('#resend_otp_form').submit();
        });
    </script>
    <?php
}

/* Create Customer function */
function mo_oauth_registration_page()
{
    $current_user = JFactory::getUser();
    ?>
    <!--Register with miniOrange-->
    <div class="mo_boot_row mo_boot_mx-1 mo_boot_my-3" style="background:white;">
        <div class="mo_boot_col-sm-12">
            <form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.registerCustomer'); ?>">
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-8">
                        <h3>Register with miniOrange</h3>
                    </div>
                    <div class="mo_boot_col-sm-4">
                        <input type="button" id="oacrg_end_tour" value="Start-tour" onclick="restart_tourrg();" style=" float: right;"
                        class="mo_boot_btn mo_boot_btn-success"/>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-12">
                        <hr>
                        <p class='alert alert-info'>You should register so that in case you need help, we can help you with step by step
                        instructions. We support all known Servers -Google,Facebook,Auth0,etc.,
                        <b>You will also need a miniOrange
                        account to upgrade to the premium version of the plugins</b>.
                         We do not store any information except the
                        email that you will use to register with us.<br><br></p><i>
                        <p style="color: #b42f2f;">If you face any issues during registraion then you can <a href="https://www.miniorange.com/businessfreetrial" target="_blank"><b>click here</b></a> to quick register your account with miniOrange and use the same credentials to login into the plugin.</p></i>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <b><font color="#FF0000">*</font>Email:</b>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input class="mo_boot_form-control oauth-textfield" type="email" name="email"
                            style="border-radius:4px;resize: vertical;"
                            required placeholder="person@example.com"
                            value="<?php echo $current_user->email; ?>"/>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <b>Phone number:</b>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input class="mo_boot_form-control oauth-textfield" type="tel" id="phone"
                            style="border-radius:4px;resize: vertical;"
                            pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" name="phone"
                            title="Phone with country code eg. +1xxxxxxxxxx"
                            placeholder="Phone with country code eg. +1xxxxxxxxxx"
                        />
                        <br>
                        <i>We will call only if you call for support</i>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <b><font color="#FF0000">*</font>Password:</b>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input class="mo_boot_form-control oauth-textfield" required type="password"
                                style="border-radius:4px;resize: vertical;"
                                name="password" placeholder="Choose your password (Min. length 6)"/>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <b><font color="#FF0000">*</font>Confirm Password:</b>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input class="mo_boot_form-control oauth-textfield" required type="password"
                            style="border-radius:4px;resize: vertical;"
                            name="confirmPassword" placeholder="Confirm your password"/>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2 mo_boot_text-center">
                    <div class="mo_boot_col-sm-12">
                        <input type="submit" value="Register" class="mo_boot_btn mo_boot_btn-success"/>&nbsp;&nbsp;
                        <a href="#oauth_account_exist" class="mo_boot_btn mo_boot_btn-success">Already registered with miniOrange?</a>
                    </div>
                </div>
            </form>
            <form name="f" id="oauth_account_already_exist" method="post"
                action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.customerLoginForm'); ?> ">
            </form>
        </div>
    </div>
    <script>
        var base_url = '<?php echo JURI::root();?>';


        var tabtour = new Tour({
            name: "tabtour",
            steps: [
                {
                    element: "#configtab",
                    title: "Configuration Tab",
                    content: "Configure your server with client here to perform SSO.",
                    backdrop: 'body',
                    backdropPadding: '6',


                }, {
                    element: "#moJoom-OauthClient-supportButton-SideButton",
                    title: "Contact Us",
                    content: "Feel free to contact us for any queries or issues regarding plugin. We will help you with configuration also",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onNext: function () {
                        jQuery('.nav-tabs a[href=#licensing-plans]').tab('show');
                    }

                }, {
                    element: "#licensetab",
                    title: "License",
                    content: "You can find premium features and can upgrade to our premium plans.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('.nav-tabs a[href=#configuration]').tab('show');
                    },
                    onNext: function () {
                        jQuery('.nav-tabs a[href=#help]').tab('show');
                    }

                }, {
                    element: "#faqstab",
                    title: "Help",
                    content: "You could find solutions for most popular quries.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('.nav-tabs a[href=#licensing-plans]').tab('show');
                    },
                    onNext: function () {
                        jQuery('.nav-tabs a[href=#configuration]').tab('show');
                    }

                }, {
                    element: "#oacconf_end_tour",
                    title: "Tab Tour.",
                    content: " You could find the start tour button on each tab which will help you to configure the tab /get the inforamtion from that tab.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    onPrev: function () {
                        jQuery('.nav-tabs a[href=#help]').tab('show');
                    }

                }, {
                    element: ".mo_oauth_support_configure",
                    title: "Contact Us",
                    content: "Feel free to contact us for any queries or issues regarding plugin. We will help you with configuration also",
                    backdrop: 'body',
                    backdropPadding: '6',


                }, {
                    element: "#end_oa_tour_oauth",
                    title: "Tab Tour.",
                    content: " By clicking on start Plugin tour button you will take to over all plugin tour and explain what each tab does.",
                    backdrop: 'body',
                    backdropPadding: '6',

                }


            ]
        });

        tabtour.init();
        tabtour.start();


    </script>
    <script>

        function restart_tourrg() {
            tourrg.restart();
        }

        var tourrg = new Tour({
            name: "tour",
            steps: [
                {
                    element: "#oacemail",
                    title: "Register with Us",
                    content: "Please register here to configure and get further help regarding plugin..",
                    backdrop: 'body',
                    backdropPadding: '6'
                },

                {
                    element: ".mo_oauth_support_configure",
                    title: "Contact Us",
                    content: "Feel free to contact us for any queries or issues regarding plugin. We will help you with configuration too.",
                    backdrop: 'title',
                    backdropPadding: '6'
                },
                {
                    element: "#oacrg_end_tour",
                    title: "Tour ends",
                    content: "Click here to restart tour",
                    backdrop: 'body',
                    backdropPadding: '6'
                }

            ]
        });


        //  tourrg.init();
        //tourrg.start();
    </script>
    <script>
        jQuery("a[href='#oauth_account_exist']").click(function () {
            jQuery('#oauth_account_already_exist').submit();
        });
    </script>
    <?php
}

function mo_oauth_account_page()
{

    $result = MoOAuthUtility::getCustomerDetails();
    $email = $result['email'];
    $customer_key = $result['customer_key'];
    $api_key = $result['api_key'];
    $customer_token = $result['customer_token'];

    if (!JPluginHelper::isEnabled('system', 'miniorangeoauth')) {

        ?>
        <div id="system-message-container">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <div class="alert alert-error">
                <h4 class="alert-heading">Warning!</h4>
                <div class="alert-message">
                    <h4>This component requires User and System Plugin to be activated. Please activate the following 2
                        plugins
                        to proceed further.</h4>
                    <li>System -miniOrange OTP Verification</li>
                    </ul>
                    <h4>Steps to activate the plugins.</h4>
                    <ul>
                        <li>In the top menu, click on Extensions and select Plugins.</li>
                        <li>Search for miniOrange in the search box and press 'Search' to display the plugins.</li>
                        <li>Now enable both User and System plugin.</li>
                    </ul>
                </div>
                </h4>
            </div>
        </div>
    <?php }
    ?>
    <div class="mo_boot_row mo_boot_mx-1 mo_boot_my-3" style="background:white;">
        <div class="mo_boot_col-sm-12">
            <p class="mo_oauth_welcome_message">Thank You for registering with miniOrange.<p><br>
            <h3>Your Profile</h3><br/>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_table-responsive">
            <table class="mo_boot_table mo_boot_table-striped mo_boot_table-hover mo_boot_table-bordered">
                <tr>
                    <td><b>Username/Email</b></td>
                    <td><?php echo $email ?></td>
                </tr>
                <tr>
                    <td><b>Customer ID</b></td>
                    <td><?php echo $customer_key ?></td>
                </tr>
                <tr>
                    <td><b>API Key</b></td>
                    <td><?php echo $api_key ?></td>
                </tr>
                <tr>
                    <td><b>Token Key</b></td>
                    <td><?php echo $customer_token ?></td>
                </tr>
            </table>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_text-center">
            <form method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.removeAccount'); ?>">
                
                <input type="submit"  class="mo_boot_btn mo_boot_btn-danger mo_boot_my-3" value="Remove Account">
            </form>
        </div>
    </div>
    <?php
}
function getAppJason(){
    return '{
    "azure": {
        "label":"Azure AD", "type":"openidconnect", "image":"azure.png", "scope": "openid", "authorize": "https://login.microsoftonline.com/[tenant-id]/oauth2/authorize", "token": "https://login.microsoftonline.com/[tenant]/oauth2/token", "userinfo": "https://login.windows.net/common/openid/userinfo", "guide":"https://plugins.miniorange.com/configure-azure-ad-with-joomla", "logo_class":"fa fa-windowslive"
    },
    "azureb2c": {
		"label":"Azure B2C", "type":"openidconnect", "image":"azure.png", "scope": "openid", "authorize": "https://{tenant}.b2clogin.com/{tenant}.onmicrosoft.com/{policy}/oauth2/v2.0/authorize", "token": "https://{tenant}.b2clogin.com/{tenant}.onmicrosoft.com/{policy}/oauth2/v2.0/token", "userinfo": "", "guide":"", "logo_class":"fa fa-windowslive"
	},
    "cognito": {
        "label":"AWS Cognito", "type":"oauth", "image":"cognito.png", "scope": "openid", "authorize": "https://<cognito-app-domain>/oauth2/authorize", "token": "https://<cognito-app-domain>/oauth2/token", "userinfo": "https://<cognito-app-domain>/oauth2/userInfo", "guide":"https://plugins.miniorange.com/configure-aws-cognito-oauthopenid-connect-server-joomla    ", "logo_class":"fa fa-amazon"
    },
    "adfs": {
        "label":"ADFS", "type":"openidconnect", "image":"adfs.png", "scope": "openid", "authorize": "https://{yourADFSDomain}/adfs/oauth2/authorize/", "token": "https://{yourADFSDomain}/adfs/oauth2/token/", "userinfo": "", "guide":"", "logo_class":"fa fa-windowslive"
    },
    "whmcs": {
        "label":"WHMCS", "type":"oauth", "image":"whmcs.png", "scope": "openid profile email", "authorize": "https://{yourWHMCSdomain}/oauth/authorize.php", "token": "https://{yourWHMCSdomain}/oauth/token.php", "userinfo": "https://{yourWHMCSdomain}/oauth/userinfo.php?access_token=", "guide":"https://plugins.miniorange.com/configure-whmcs-as-an-oauth-openid-connect-server-in-joomla", "logo_class":"fa fa-lock"
    },
    "keycloak": {
        "label":"keycloak", "type":"openidconnect", "image":"keycloak.png", "scope": "openid", "authorize": "{your-domain}/auth/realms/{realm}/protocol/openid-connect/auth", "token": "{your-domain}/auth/realms/{realm}/protocol/openid-connect/token", "userinfo": "{your-domain}/auth/realms/{realm}/protocol/openid-connect/userinfo", "guide":"https://plugins.miniorange.com/configure-keycloak-as-an-oauth-openid-connect-server-in-joomla", "logo_class":"fa fa-lock"
    },
    "slack": {
        "label":"Slack", "type":"oauth", "image":"slack.png", "scope": "users.profile:read", "authorize": "https://slack.com/oauth/authorize", "token": "https://slack.com/api/oauth.access", "userinfo": "https://slack.com/api/users.profile.get", "guide":"https://plugins.miniorange.com/configure-slack-as-an-oauth-openid-connect-server-in-joomla", "logo_class":"fa fa-slack"
    },
    "discord": {
        "label":"Discord", "type":"oauth", "image":"discord.png", "scope": "identify email", "authorize": "https://discordapp.com/api/oauth2/authorize", "token": "https://discordapp.com/api/oauth2/token", "userinfo": "https://discordapp.com/api/users/@me", "guide":"", "logo_class":"fa fa-lock"
    },
    "invisioncommunity": {
        "label":"Invision Community", "type":"oauth", "image":"invis.png", "scope": "email", "authorize": "https://{invision-community-domain}/oauth/authorize/", "token": "https://{invision-community-domain}/oauth/token/", "userinfo": "https://{invision-community-domain}/oauth/me", "guide":"", "logo_class":"fa fa-lock"
    },
    "bitrix24": {
        "label":"Bitrix24", "type":"oauth", "image":"bitrix24.png", "scope": "user", "authorize": "https://{your-id}.bitrix24.com/oauth/authorize", "token": "https://{your-id}.bitrix24.com/oauth/token", "userinfo": "https://{your-id}.bitrix24.com/rest/user.current.json?auth=", "guide":"https://plugins.miniorange.com/configure-bitrix24-oauthopenid-connect-server-joomla", "logo_class":"fa fa-clock-o"
    },
    "wso2": {
        "label":"WSO2", "type":"oauth", "image":"wso2.png", "scope": "openid", "authorize": "https://<wso2-app-domain>/wso2/oauth2/authorize", "token": "https://<wso2-app-domain>/wso2/oauth2/token", "userinfo": "https://<wso2-app-domain>/wso2/oauth2/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "okta": {
        "label":"Okta", "type":"openidconnect", "image":"okta.png", "scope": "openid", "authorize": "https://{yourOktaDomain}.com/oauth2/default/v1/authorize", "token": "https://{yourOktaDomain}.com/oauth2/default/v1/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "onelogin": {
        "label":"OneLogin", "type":"openidconnect", "image":"onelogin.png", "scope": "openid", "authorize": "https://<site-url>.onelogin.com/oidc/auth", "token": "https://<site-url>.onelogin.com/oidc/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "gapps": {
        "label":"Google", "type":"oauth", "image":"google.png", "scope": "email", "authorize": "https://accounts.google.com/o/oauth2/auth", "token": "https://www.googleapis.com/oauth2/v4/token", "userinfo": "https://www.googleapis.com/oauth2/v1/userinfo", "guide":"https://plugins.miniorange.com/configure-google-apps-oauth-server-joomla", "logo_class":"fa fa-google-plus"
    },
    "fbapps": {
        "label":"Facebook", "type":"oauth", "image":"facebook.png", "scope": "public_profile email", "authorize": "https://www.facebook.com/dialog/oauth", "token": "https://graph.facebook.com/v2.8/oauth/access_token", "userinfo": "https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link", "guide":"https://plugins.miniorange.com/configure-facebook-oauth-server-joomla", "logo_class":"fa fa-facebook"
    },
    "gluu": {
        "label":"Gluu Server", "type":"oauth", "image":"gluu.png", "scope": "openid", "authorize": "http://<gluu-server-domain>/oxauth/restv1/authorize", "token": "http://<gluu-server-domain>/oxauth/restv1/token", "userinfo": "http:///<gluu-server-domain>/oxauth/restv1/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "linkedin": {
        "label":"LinkedIn", "type":"oauth", "image":"linkedin.png", "scope": "r_basicprofile", "authorize": "https://www.linkedin.com/oauth/v2/authorization", "token": "https://www.linkedin.com/oauth/v2/accessToken", "userinfo": "https://api.linkedin.com/v2/me", "guide":"https://plugins.miniorange.com/configure-linkedin-oauth-openid-connect-server-joomla-client", "logo_class":"fa fa-linkedin-square"
    },
    "strava": {
        "label":"Strava", "type":"oauth", "image":"strava.png", "scope": "public", "authorize": "https://www.strava.com/oauth/authorize", "token": "https://www.strava.com/oauth/token", "userinfo": "https://www.strava.com/api/v3/athlete", "guide":"", "logo_class":"fa fa-lock"
    },
    "fitbit": {
        "label":"FitBit", "type":"oauth", "image":"fitbit.png", "scope": "profile", "authorize": "https://www.fitbit.com/oauth2/authorize", "token": "https://api.fitbit.com/oauth2/token", "userinfo": "https://www.fitbit.com/1/user", "guide":"https://plugins.miniorange.com/configure-fitbit-oauth-server-joomla", "logo_class":"fa fa-lock"
    },
    "box": {
        "label":"Box", "type":"oauth", "image":"box.png", "scope": "root_readwrite", "authorize": "https://account.box.com/api/oauth2/authorize", "token": "https://api.box.com/oauth2/token", "userinfo": "https://api.box.com/2.0/users/me", "guide":"", "logo_class":"fa fa-lock"
    },
    "github": {
        "label":"GitHub", "type":"oauth", "image":"github.png", "scope": "user repo", "authorize": "https://github.com/login/oauth/authorize", "token": "https://github.com/login/oauth/access_token", "userinfo": "https://api.github.com/user", "guide":"", "logo_class":"fa fa-github"
    },
    "gitlab": {
        "label":"GitLab", "type":"oauth", "image":"gitlab.png", "scope": "read_user", "authorize": "https://gitlab.com/oauth/authorize", "token": "http://gitlab.com/oauth/token", "userinfo": "https://gitlab.com/api/v4/user", "guide":"", "logo_class":"fa fa-gitlab"
    },
    "clever": {
        "label":"Clever", "type":"oauth", "image":"clever.png", "scope": "read:students read:teachers read:user_id", "authorize": "https://clever.com/oauth/authorize", "token": "https://clever.com/oauth/tokens", "userinfo": "https://api.clever.com/v1.1/me", "guide":"https://plugins.miniorange.com/configure-clever-oauthopenid-connect-server-in-joomla", "logo_class":"fa fa-lock"
    },
    "salesforce": {
        "label":"Salesforce", "type":"oauth", "image":"salesforce.png", "scope": "email", "authorize": "https://login.salesforce.com/services/oauth2/authorize", "token": "https://login.salesforce.com/services/oauth2/token", "userinfo": "https://login.salesforce.com/services/oauth2/userinfo", "guide":"https://plugins.miniorange.com/configure-salesforce-as-an-oauth-openid-connect-server-in-joomla", "logo_class":"fa fa-lock"
    },
    "reddit": {
        "label":"Reddit", "type":"oauth", "image":"reddit.png", "scope": "identity", "authorize": "https://www.reddit.com/api/v1/authorize", "token": "https://www.reddit.com/api/v1/access_token", "userinfo": "https://www.reddit.com/api/v1/me", "guide":"https://plugins.miniorange.com/guide-to-configure-reddit-as-oauth-openid-connect-server-in-joomla", "logo_class":"fa fa-reddit"
    },
    "paypal": {
        "label":"PayPal", "type":"openidconnect", "image":"paypal.png", "scope": "openid", "authorize": "https://www.paypal.com/signin/authorize", "token": "https://api.paypal.com/v1/oauth2/token", "userinfo": "", "guide":"https://plugins.miniorange.com/configure-paypal-as-an-oauth-openid-connect-server-in-joomla", "logo_class":"fa fa-paypal"
    },
    "swiss-rx-login": {
        "label":"Swiss RX Login", "type":"openidconnect", "image":"swiss-rx-login.png", "scope": "anonymous", "authorize": "https://www.swiss-rx-login.ch/oauth/authorize", "token": "https://swiss-rx-login.ch/oauth/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "yahoo": {
        "label":"Yahoo", "type":"openidconnect", "image":"yahoo.png", "scope": "openid", "authorize": "https://api.login.yahoo.com/oauth2/request_auth", "token": "https://api.login.yahoo.com/oauth2/get_token", "userinfo": "", "guide":"", "logo_class":"fa fa-yahoo"
    },
    "spotify": {
        "label":"Spotify", "type":"oauth", "image":"spotify.png", "scope": "user-read-private user-read-email", "authorize": "https://accounts.spotify.com/authorize", "token": "https://accounts.spotify.com/api/token", "userinfo": "https://api.spotify.com/v1/me", "guide":"", "logo_class":"fa fa-spotify"
    },
    "eveonlinenew": {
        "label":"Eve Online", "type":"oauth", "image":"eveonline.png", "scope": "publicData", "authorize": "https://login.eveonline.com/oauth/authorize", "token": "https://login.eveonline.com/oauth/token", "userinfo": "https://esi.evetech.net/verify", "guide":"", "logo_class":"fa fa-lock"
    },
    "vkontakte": {
        "label":"VKontakte", "type":"oauth", "image":"vk.png", "scope": "openid", "authorize": "https://oauth.vk.com/authorize", "token": "https://oauth.vk.com/access_token", "userinfo": "https://api.vk.com/method/users.get?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=", "guide":"", "logo_class":"fa fa-vk"
    },
    "pinterest": {
        "label":"Pinterest", "type":"oauth", "image":"pinterest.png", "scope": "read_public", "authorize": "https://api.pinterest.com/oauth/", "token": "https://api.pinterest.com/v1/oauth/token", "userinfo": "https://api.pinterest.com/v1/me/", "guide":"", "logo_class":"fa fa-pinterest"
    },
    "vimeo": {
        "label":"Vimeo", "type":"oauth", "image":"vimeo.png", "scope": "public", "authorize": "https://api.vimeo.com/oauth/authorize", "token": "https://api.vimeo.com/oauth/access_token", "userinfo": "https://api.vimeo.com/me", "guide":"", "logo_class":"fa fa-vimeo"
    },
    "deviantart": {
        "label":"DeviantArt", "type":"oauth", "image":"devart.png", "scope": "browse", "authorize": "https://www.deviantart.com/oauth2/authorize", "token": "https://www.deviantart.com/oauth2/token", "userinfo": "https://www.deviantart.com/api/v1/oauth2/user/profile", "guide":"", "logo_class":"fa fa-deviantart"
    },
    "dailymotion": {
        "label":"Dailymotion", "type":"oauth", "image":"dailymotion.png", "scope": "email", "authorize": "https://www.dailymotion.com/oauth/authorize", "token": "https://api.dailymotion.com/oauth/token", "userinfo": "https://api.dailymotion.com/user/me?fields=id,username,email,first_name,last_name", "guide":"", "logo_class":"fa fa-lock"
    },
    "meetup": {
        "label":"Meetup", "type":"oauth", "image":"meetup.png", "scope": "basic", "authorize": "https://secure.meetup.com/oauth2/authorize", "token": "https://secure.meetup.com/oauth2/access", "userinfo": "https://api.meetup.com/members/self", "guide":"", "logo_class":"fa fa-lock"
    },
    "autodesk": {
        "label":"Autodesk", "type":"oauth", "image":"autodesk.png", "scope": "user:read user-profile:read", "authorize": "https://developer.api.autodesk.com/authentication/v1/authorize", "token": "https://developer.api.autodesk.com/authentication/v1/gettoken", "userinfo": "https://developer.api.autodesk.com/userprofile/v1/users/@me", "guide":"", "logo_class":"fa fa-lock"
    },
    "zendesk": {
        "label":"Zendesk", "type":"oauth", "image":"zendesk.png", "scope": "read write", "authorize": "https://{subdomain}.zendesk.com/oauth/authorizations/new", "token": "https://{subdomain}.zendesk.com/oauth/tokens", "userinfo": "https://{subdomain}.zendesk.com/api/v2/users", "guide":"", "logo_class":"fa fa-lock"
    },
    "laravel": {
        "label":"Laravel", "type":"oauth", "image":"laravel.png", "scope": "", "authorize": "http://your-laravel-site-url/oauth/authorize", "token": "http://your-laravel-site-url/oauth/token", "userinfo": "http://your-laravel-site-url/api/user/get", "guide":"", "logo_class":"fa fa-lock"
    },
    "identityserver": {
        "label":"Identity Server", "type":"oauth", "image":"identityserver.png", "scope": "openid", "authorize": "https://<your-identityserver-domain>/connect/authorize", "token": "https://<your-identityserver-domain>/connect/token", "userinfo": "https://your-domain/connect/introspect", "guide":"", "logo_class":"fa fa-lock"
    },
    "nextcloud": {
        "label":"Nextcloud", "type":"oauth", "image":"nextcloud.png", "scope": "", "authorize": "https://<your-nextcloud-domain>/apps/oauth2/authorize", "token": "https://<your-nextcloud-domain>/apps/oauth2/api/v1/token", "userinfo": "https://<your-nextcloud-domain>/ocs/v2.php/cloud/user?format=json", "guide":"", "logo_class":"fa fa-lock"
    },
    "twitch": {
        "label":"Twitch", "type":"oauth", "image":"twitch.png", "scope": "Analytics:read:extensions", "authorize": "https://id.twitch.tv/oauth2/authorize", "token": "https://id.twitch.tv/oauth2/token", "userinfo": "https://id.twitch.tv/oauth2/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "wildApricot": {
        "label":"Wild Apricot", "type":"oauth", "image":"wildApricot.png", "scope": "auto", "authorize": "https://<your_account_url>/sys/login/OAuthLogin", "token": "https://oauth.wildapricot.org/auth/token", "userinfo": "https://api.wildapricot.org/v2.1/accounts/<account_id>/contacts/me", "guide":"https://plugins.miniorange.com/guide-to-configure-wildapricot-as-an-oauth-openid-connect-server-with-joomla-as-client", "logo_class":"fa fa-lock"
    },
    "connect2id": {
        "label":"Connect2id", "type":"oauth", "image":"connect2id.png", "scope": "openid", "authorize": "https://c2id.com/login", "token": "https://<your-base-server-url>/token", "userinfo": "https://<your-base-server-url>/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "miniorange": {
        "label":"miniOrange", "type":"oauth", "image":"miniorange.png", "scope": "openid", "authorize": "https://login.xecurify.com/moas/idp/openidsso", "token": "https://login.xecurify.com/moas/rest/oauth/token", "userinfo": "https://logins.xecurify.com/moas/rest/oauth/getuserinfo", "guide":"https://plugins.miniorange.com/login-with-miniorange-as-an-oauth-openid-connect-server", "logo_class":"fa fa-lock"
    },
    "orcid": {
        "label":"ORCID", "type":"openidconnect", "image":"orcid.png", "scope": "openid", "authorize": "https://orcid.org/oauth/authorize", "token": "https://orcid.org/oauth/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "diaspora": {
        "label":"Diaspora", "type":"openidconnect", "image":"diaspora.png", "scope": "openid", "authorize": "https://<your-diaspora-domain>/api/openid_connect/authorizations/new", "token": "https://<your-diaspora-domain>/api/openid_connect/access_tokens", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "timezynk": {
        "label":"Timezynk", "type":"oauth", "image":"timezynk.png", "scope": "read:user", "authorize": "https://api.timezynk.com/api/oauth2/v1/auth", "token": "https://api.timezynk.com/api/oauth2/v1/token", "userinfo": "https://api.timezynk.com/api/oauth2/v1/userinfo", "guide":"", "logo_class":"fa fa-lock"
    },
    "other": {
        "label":"Custom OAuth", "type":"oauth", "image":"customapp.png", "scope": "", "authorize": "", "token": "", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    },
    "openidconnect": {
        "label":"Custom OpenID Connect App", "type":"openidconnect", "image":"customapp.png", "scope": "", "authorize": "", "token": "", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
    }
}';
}
function selectAppByIcon(){
    $appArray = json_decode(getAppJason(),TRUE);
    $app = JFactory::getApplication();
    $get = $app->input->get->getArray();
    $attribute = getAppDetails();
    $isAppConfigured = empty($attribute['client_secret']) || empty($attribute['client_id']) || empty($attribute['custom_app'])?FALSE:TRUE;
    if( isset($get['moAuthAddApp']) && !empty($get['moAuthAddApp']) ){
        configuration($appArray[$get['moAuthAddApp']],$get['moAuthAddApp']);
        return;
    }
    if($isAppConfigured){
        configuration($appArray[$attribute['appname']],$attribute['appname']);
        return;
    }
    $ImagePath=JURI::base().'components/com_miniorange_oauth/assets/images/';
    $imageTableHtml = "<table id='moAuthAppsTable'>";
    $i=1;
    $PreConfiguredApps = array_slice($appArray,0,count($appArray)-2);
    foreach ($PreConfiguredApps as $key => $value) {
        $img=$ImagePath.$value['image'];
        if($i%6==1){
            $imageTableHtml.='<tr>';
        }
        $imageTableHtml=$imageTableHtml."<td moAuthAppSelector='".$value['label']."'><a href='".JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp='.$key)."''><img style='max-height:60px;max-width:60px;' src='".$img."'><br><p>".$value['label']."</p></a></td>";
        if($i%6==0 || $i==count($appArray)){
            $imageTableHtml.='</tr>';
        }
        $i++;
    }
    $imageTableHtml.='</table>';
    ?>
    <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" style="background:white;">
        <div class="mo_boot_col-sm-12 mo_boot_mt-4">
            <input type="text" style="width:100%;margin:0;height:38px;" name="appsearch" id="moAuthAppsearchInput" value="" placeholder="Select Application">
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <h6>Pre-Configured Applications
                <div class="moAuthtooltip">
                    &ensp;
                    <img src="<?php echo  $ImagePath.'icon3.png'; ?>" style="height:18px;">
                    <span class="moAuthtooltiptext">By selecting pre-configured applications, the configuration would already be half-done!</span>
                </div>
            </h6>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <?php
                echo $imageTableHtml;
            ?>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <hr>
            <h6>Custom Applications
                <div class="moAuthtooltip">
                    &ensp;
                    <img src="<?php echo  $ImagePath.'icon3.png'; ?>" style="height:18px;">
                    <span class="moAuthtooltiptext">Your provider is not in the list? You can select the type of your provider and configure it yourself!</span>
                </div>
            </h6>
        </div>
        <div class="mo_boot_col-sm-6 mo_boot_text-center" moAuthAppSelector='moCustomOuth2App'>
            <a href="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=other');?>"><img style='max-height:60px;max-width:60px;' src="<?php echo  $ImagePath.$appArray['other']['image']; ?>"><br><p><?php echo $appArray['other']['label'];?></p></a>
        </div>
        <div class="mo_boot_col-sm-6 mo_boot_text-center"  moAuthAppSelector='moCustomOpenIdConnectApp'>
            <a href="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=openidconnect');?>"><img style='max-height:60px;max-width:60px;' src="<?php echo  $ImagePath.$appArray['openidconnect']['image']; ?>"><br><p><?php echo $appArray['openidconnect']['label'];?></p></a>
        </div>
    </div>
    <?php
}
function getAppDetails(){
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__miniorange_oauth_config'));
    $query->where($db->quoteName('id') . " = 1");
    $db->setQuery($query);
    return $db->loadAssoc();
}
function configuration($OauthApp,$appLabel)
{
    $attribute = getAppDetails();
    $appJson=json_decode(getAppJason(),true);
    $guide="";
    if($appJson[$appLabel]["guide"]!="")
    {
        $guide=$appJson[$appLabel]["guide"];
    }
    else
    {
        $guide="https://plugins.miniorange.com/guide-to-enable-joomla-oauth-client";
    }
    $mo_oauth_app = $appLabel;
    $custom_app = "";
    $client_id = "";
    $client_secret = "";
    $redirecturi = JURI::root();
    $email_attr = "";
    $first_name_attr = "";
    $isAppConfigured = FALSE;
    $mo_oauth_in_header = "checked=true";
    $mo_oauth_in_body   = "";
    $login_link_check="1";
    if( isset($attribute['in_header_or_body']) && $attribute['in_header_or_body']=='both' ){
        $mo_oauth_in_header = "checked=true";
        $mo_oauth_in_body   = "checked=true";
    }
    else if(isset($attribute['in_header_or_body']) && $attribute['in_header_or_body']=='inBody'){
        $mo_oauth_in_header = "";
        $mo_oauth_in_body   = "checked=true";
    }


    if (isset($attribute['client_id'])) {
        $mo_oauth_app = empty($attribute['appname'])?$appLabel:$attribute['appname'];
        $custom_app = $attribute['custom_app'];
        $client_id = $attribute['client_id'];
        $client_secret = $attribute['client_secret'];
        $isAppConfigured = empty($client_id) || empty($client_secret) || empty($custom_app)?FALSE:TRUE;
        $app_scope = empty($attribute['app_scope'])?$OauthApp['scope']:$attribute['app_scope'];
        $authorize_endpoint = empty($attribute['authorize_endpoint'])?$OauthApp['authorize']:$attribute['authorize_endpoint'];
        $access_token_endpoint = empty($attribute['access_token_endpoint'])?$OauthApp['token']:$attribute['access_token_endpoint'];
        $user_info_endpoint = empty($attribute['user_info_endpoint'])?$OauthApp['userinfo']:$attribute['user_info_endpoint'];
        $email_attr = $attribute['email_attr'];
        $first_name_attr = $attribute['first_name_attr'];
    }
    ?>
    <script>
        window.addEventListener('DOMContentLoaded', function(){
                selectapp();
            }
        );
        function selectapp() {
            document.getElementById("instructions").innerHTML = "";
            document.getElementById('mo_oauth_authorizeurl').value = "<?php echo $authorize_endpoint; ?>";
            document.getElementById('mo_oauth_accesstokenurl').value = "<?php echo $access_token_endpoint; ?>";
            document.getElementById('mo_oauth_resourceownerdetailsurl').value = "<?php echo $user_info_endpoint ;?>";
            document.getElementById('mo_oauth_scope').value = "<?php echo $app_scope; ?>";
            document.getElementById('loginUrl').value = "<?php echo JURI::root() . '?morequest=oauthredirect&app_name='.$mo_oauth_app;?>";
        }
        function testConfiguration() {
            var appname = "<?php echo $custom_app; ?>";
            var winl = ( screen.width - 400 ) / 2,
                wint = ( screen.height - 800 ) / 2,
                winprops = 'height=' + 600 +
                    ',width=' + 800 +
                    ',top=' + wint +
                    ',left=' + winl +
                    ',scrollbars=1'+
                    ',resizable';
            var myWindow = window.open('<?php echo JURI::root(); ?>' + '?morequest=testattrmappingconfig&app=' + appname, "Test Attribute Configuration", winprops);
        }
    </script>
    <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" style="background:white;">
        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
            <h3 style="display:inline-block;"><?php echo JText::_('COM_MINIORANGE_OAUTH_APP_CONFIGURATION'); ?></h3>
            <input type="button" id="oacconf_end_tour" value="Start-tour" onclick="restart_tourconf();" style=" float: right;" class="btn btn-medium btn-success"/>
        </div>
        <div class="mo_boot_col-sm-12">
            <hr>
        </div>
        <div class="mo_boot_col-sm-12">
            <form id="oauth_config_form" name="oauth_config_form" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveConfig'); ?>">
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                        <strong><font color="#FF0000">*</font>Application</strong>
                    </div>
                    <div class="mo_boot_col-sm-4">
                        <?php echo $OauthApp['label']."&emsp;".($isAppConfigured==FALSE?"<a align=\"left\"
                            href='index.php?option=com_miniorange_oauth&view=accountsetup'
                            class=\"mo_boot_btn mo_boot_btn-success\">Change Application</a>":"<a align=\"left\"
                            href='index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.clearConfig'
                            class=\"mo_boot_btn mo_boot_btn-danger\">Delete Application</a>&emsp;");?>
                            <input type="hidden" name="mo_oauth_app_name" value="<?php echo $mo_oauth_app; ?>">
                    </div>
                    <div class="mo_boot_col-sm-4">
                    <a href="<?php echo $guide;?>" target="_blank" class="mo_boot_btn mo_boot_btn-primary mo_boot_py-0">Guides</a>
                        <a href="https://www.youtube.com/playlist?list=PL2vweZ-PcNpdkpUxUzUCo66tZsEHJJDRl" target="_blank" class="mo_boot_btn mo_boot_btn-primary mo_boot_py-0">Video Guides</a>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                        <strong>Login URL:</strong>
                    </div>
                    <div class="mo_boot_col-sm-7">
                        <input class="mo_boot_form-control" id="loginUrl" type="text" readonly="true" value='<?php echo JURI::root() . '?morequest=oauthredirect&app_name=' . $mo_oauth_app; ?>'>
                    </div>
                    <div class="mo_boot_col-sm-1">
                        <i class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#loginUrl');" style="color:red;background:#ccc;" ;>
                            <span class="copytooltiptext">Copied!</span>
                        </i>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                        <strong><?php echo JText::_('COM_MINIORANGE_OAUTH_CALLBACK_URL'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-7">
                        <input class="mo_boot_form-control  " id="callbackurl" type="text" readonly="true" value='<?php echo $redirecturi; ?>'>
                    </div>
                    <div class="mo_boot_col-sm-1">
                        <i class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#callbackurl');" style="color:red;background:#ccc;" ;>
                            <span class="copytooltiptext">Copied!</span>
                        </i>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                        <strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_CUSTOM_APP_NAME'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-7">
                        <input class="mo_boot_form-control" type="text" id="mo_oauth_custom_app_name" name="mo_oauth_custom_app_name" value='<?php echo $custom_app; ?>' required>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                        <strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_CLIENT_ID'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-7">
                        <input class="mo_boot_form-control" required="" type="text" name="mo_oauth_client_id" id="mo_oauth_client_id" value='<?php echo $client_id; ?>'>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                        <strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_CLIENT_SECRET'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-7">
                        <input class="mo_boot_form-control" type="text" id="mo_oauth_client_secret" name="mo_oauth_client_secret" value='<?php echo $client_secret; ?>'>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                        <strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_APP_SCOPE'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-7">
                        <input class="mo_boot_form-control" required type="text" id="mo_oauth_scope" name="mo_oauth_scope" value='<?php echo $app_scope; ?>'>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                        <strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_AUTHORIZE_ENDPOINT'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-7">
                        <input class="mo_boot_form-control" type="text" id="mo_oauth_authorizeurl" name="mo_oauth_authorizeurl" value='<?php echo $authorize_endpoint; ?>' required>
                    </div>
                    <div class="mo_boot_col-sm-1">
                        <i class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" ; onclick="copyToClipboard('#mo_oauth_authorizeurl');" style="color:red;background:#ccc;" ;>
                            <span class="copytooltiptext">Copied!</span>
                        </i>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                        <strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_TOKEN_ENDPOINT'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-7">
                        <input class="mo_boot_form-control" type="text" id="mo_oauth_accesstokenurl" name="mo_oauth_accesstokenurl" value='<?php echo $access_token_endpoint; ?>' required>
                    </div>
                    <div class="mo_boot_col-sm-1">
                        <i class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#mo_oauth_accesstokenurl');" style="color:red;background:#ccc;" ;>
                            <span class="copytooltiptext">Copied!</span>
                        </i>
                    </div>
                </div>
                <?php if(!isset($OauthApp['type']) || $OauthApp['type']=='oauth'){?>
                    <div class="mo_boot_row mo_boot_mt-2" id="mo_oauth_resourceownerdetailsurl_div">
                        <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                            <strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_INFO_ENDPOINT'); ?></strong>
                        </div>
                        <div class="mo_boot_col-sm-7">
                            <input class="mo_boot_form-control" type="text" id="mo_oauth_resourceownerdetailsurl" name="mo_oauth_resourceownerdetailsurl" value='<?php echo $user_info_endpoint; ?>' required>
                        </div>
                        <div class="mo_boot_col-sm-1">
                            <i class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#mo_oauth_resourceownerdetailsurl');" style="color:red;background:#ccc;" ;>
                                <span class="copytooltiptext">Copied!</span>
                            </i>
                        </div>
                    </div>
                <?php } ?>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-7 mo_boot_offset-sm-4">
                        <input type="checkbox" style='vertical-align: -2px;' name="mo_oauth_in_header" value="1" <?php echo " ".$mo_oauth_in_header; ?>>&nbsp;Set client credentials in Header
                        <input type="checkbox" style='vertical-align: -2px;' class="mo_table_textbox" name="mo_oauth_body" value="1" <?php echo " ".$mo_oauth_in_body; ?> >&nbsp; Set client credentials in Body
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <?php
                        $attributeDetails=getAppDetails();
                        $linkCheck=$attributeDetails['login_link_check'];
                        $checked="";
                        if($linkCheck=="1")
                        {
                            $checked="checked";
                        }
                        else
                        {
                            $checked="";
                        }
                    ?>
                    <div class="mo_boot_col-sm-3 mo_boot_offset-1"></div>
                    <div class="mo_boot_col-sm-7">
                        <input type="checkbox" style='vertical-align: -2px;' name="login_link_check" value="1" <?php echo $checked?> >
                            Show link on login page
                        <span style="padding:0px 0px 0px 8px;"></span>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                    <div class="mo_boot_col-sm-12">
                        <input type="hidden" name="moOauthAppName" value="<?php echo $appLabel; ?>">
                        <input type="submit" name="send_query" id="send_query" value='<?php echo JText::_('COM_MINIORANGE_OAUTH_SAVE_SETTINGS_BUTTON'); ?>' class="mo_boot_btn mo_boot_btn-success"/>&nbsp;&nbsp;
                        <input type="button" id="test_config_button"
                                title='<?php echo JText::_('COM_MINIORANGE_OAUTH_TEST_CONFIGURATION_MESSAGE'); ?>'
                                class="mo_boot_btn mo_boot_btn-primary"
                                value='<?php echo JText::_('COM_MINIORANGE_OAUTH_TEST_CONFIGURATION_BUTTON'); ?>'
                                onclick="testConfiguration()">&nbsp;&nbsp;
                            <a href='index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.clearConfig'
                            id="clear_config_button"
                            class="mo_boot_btn mo_boot_btn-danger"><?php echo JText::_('COM_MINIORANGE_OAUTH_CLEAR_SETTINGS_BUTTON'); ?></a>
                    </div>
                </div>
            </form>
        </div>
        <div class="mo_boot_col-sm-12">
            <hr>
            <form id="oauth_mapping_form" name="oauth_config_form" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveMapping'); ?>">
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-12">
                        <h3><?php echo JText::_('COM_MINIORANGE_OAUTH_ATTRIBUTE_MAPPING'); ?></h3>
                        <h6><?php echo JText::_('COM_MINIORANGE_OAUTH_ATTRIBUTE_MAPPING_MESSAGE'); ?></h6>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_EMAIL_ATTR'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input class="mo_boot_form-control" required="" type="text" id="mo_oauth_email_attr" name="mo_oauth_email_attr" value='<?php echo $email_attr; ?>'>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <strong><font color="#FF0000">*</font><?php echo JText::_('COM_MINIORANGE_OAUTH_FIRST_NAME_ATTR'); ?></strong>
                    </div>
                    <div class="mo_boot_col-sm-6">
                        <input class="mo_boot_form-control" required="" type="text" id="mo_oauth_first_name_attr" name="mo_oauth_first_name_attr" value='<?php echo $first_name_attr; ?>'>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_mt-2 mo_boot_text-center">
                    <div class="mo_boot_col-sm-12">
                        <input type="submit" name="send_query" id="send_query" value='<?php echo JText::_('COM_MINIORANGE_OAUTH_SAVE_MAPPING_BUTTON'); ?>' class="mo_boot_btn mo_boot_btn-success"/>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function copyToClipboard(element) {
            var temp = jQuery("<input>");
            jQuery("body").append(temp);
            temp.val(jQuery(element).val()).select();
            document.execCommand("copy");
            temp.remove();
        }
        function restart_tourconf() {
            tourconf.restart();
        }
        var tourconf = new Tour({
            name: "tour",
            steps: [
                {
                    element: "#mo_oauth_app",
                    title: "Select Application",
                    content: "Please select your server to configure. Select Other if your server not listed.",
                    backdrop: 'body',
                    backdropPadding: '20'
                },
                {
                    element: "#callbackurl",
                    title: "Redirect / Callback URL",
                    content: "Use this URL to configure your server.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_custom_app_name",
                    title: "Custom App Name",
                    content: "Give a name to identify your server.",
                    backdrop: 'body',
                    backdropPadding: '6'
                }, {
                    element: "#mo_oauth_client_id",
                    title: "Client ID",
                    content: "You can get the Client ID from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_client_secret",
                    title: "Client Secret",
                    content: "You can get the Client Secret from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_scope",
                    title: "Scope",
                    content: "Get the Scope from Server to get particular information",
                    backdrop: 'body',
                    backdropPadding: '6'
                }, {
                    element: "#mo_oauth_authorizeurl",
                    title: "Authorize Endpoint ",
                    content: "You can get the Authorize Endpoint from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_accesstokenurl",
                    title: "Access Token Endpoint",
                    content: "You can get the Access Token Endpoint from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_resourceownerdetailsurl",
                    title: "Get User Info Endpoint",
                    content: "You can get the User Info Endpoint from your server application.",
                    backdrop: 'body',
                    backdropPadding: '6'
                }, {
                    element: "#test_config_button",
                    title: "Test Configuration",
                    content: "Click here to know the attributes sent by the server to configure in attribute mapping.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_email_attr",
                    title: "Email Attribute ",
                    content: "Please enter attribute name which holds email address here. You can find this in test Configuration",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#mo_oauth_first_name_attr",
                    title: "Username Attribute",
                    content: "Enter the Username Attribute which holds name. You can find this in test configuration.",
                    backdrop: 'body',
                    backdropPadding: '6'
                }, {
                    element: ".mo_oauth_support_configure",
                    title: "Support",
                    content: "Unable to Configure/ any issue related to plugin feel free to reach us we will help you.",
                    backdrop: 'body',
                    backdropPadding: '6',
                    backdropHeight: '20'

                },
                {
                    element: "#oacconf_end_tour",
                    title: "Tour ends",
                    content: "Click here to restart tour",
                    backdrop: 'body',
                    backdropPadding: '6'
                },

            ]
        });

    </script>
    <?php
}
function attributerole()
{
    ?>
    <script>
        jQuery(document).ready(function () {
            jQuery('.premium').click(function () {
                jQuery('.nav-tabs a[href=#licensing-plans]').tab('show');
            });
        });
    </script>
    <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" style="background:white;">
        <div class="mo_boot_col-sm-12">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-9 mo_boot_mt-3">
                    <h3>
                        <?php echo JText::_('COM_MINIORANGE_OAUTH_ATTRIBUTE_MAPPING1');?><sup>
                        <font size="2px" >[<b><a href='<?php echo $license_tab_link;?>' class='premium'><b>Standard</b></a>, <a href='<?php echo $license_tab_link;?>' class='premium'><b>Premium</b></a>,
                        <a href='<?php echo $license_tab_link;?>' class='premium'><b>Enterprise</b></a></b>]</font></sup>
                    </h3>
                    <hr>
                    <h6><?php echo JText::_('COM_MINIORANGE_OAUTH_ATTRIBUTE_MAPPING_MESSAGE1'); ?></h6>
                </div>
                <div class="mo_boot_col-sm-3">
                    <input type="button" id="oacconf_end_tour" value="Start tour" onclick="restart_tourconf();" style=" float: right;" class="mo_boot_btn mo_boot_btn-success mo_boot_mt-3"/>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2" id="mo_oauth_attributemapping">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-3 mo_boot_offset-sm-1">
                    <strong><font color="#FF0000">*</font>Username:</strong>
                </div>
                <div class="mo_boot_col-sm-6">
                    <input class="mo_boot_form-control" type="text" id="mo_oauth_uname_attr" name="mo_oauth_uname_attr" value='' disabled required>
                </div>
            </div>
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-3 mo_boot_offset-sm-1">
                    <strong><font color="#FF0000">*</font>Email</strong>
                </div>
                <div class="mo_boot_col-sm-6">
                    <input class="mo_boot_form-control" type="text" id="mo_oauth_email_attr" name="mo_oauth_email_attr" value='' disabled required>
                </div>
            </div>
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-3 mo_boot_offset-sm-1">
                    <strong><font color="#FF0000">*</font>Display Name:</strong>
                </div>
                <div class="mo_boot_col-sm-6">
                    <input class="mo_boot_form-control" type="text" style="border-bottom: 10px;" id="mo_oauth_dname_attr" name="mo_oauth_dname_attr" value='' disabled>
                </div>
            </div>
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-center">
                    <input type="submit" name="send_query" id="send_query" value='<?php echo "Save Attribute Mapping"; ?>' disabled style="margin-bottom:3%;" class="mo_boot_btn mo_boot_btn-success"/>
                </div>
            </div>
        </div>

        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <h3> Add Joomla's User Profile Attributes
                <sup>
                <a href='<?php echo $license_tab_link;?>' class='premium'><b>[Premium</b></a>,
                <a href='<?php echo $license_tab_link;?>' class='premium'><b>Enterprise]</b></a>
                </sup>
                <input type="button" class="mo_boot_btn mo_boot_btn-primary moOauthAttributeMappingButtons" disabled="true"  value="+" />
                <input type="button" class="mo_boot_btn mo_boot_btn-danger" disabled="true" value="-" />
            </h3>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <p class="alert alert-info" style="color: #151515;">NOTE: During registration or login of the user, the value corresponding to 'Value from OAuth server' will be updated for the User Profile Attribute field in the User Profile table. Customized attribute mapping options shown above are configurable in the <a href='<?php echo $license_tab_link;?>' class='premium'><b>Premium </a> </b>and <a href='<?php echo $license_tab_link;?>' class='premium'> <b>Enterprise</b></a> versions of plugin.</p>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-4">
                    <b>User Profile Attribute</b>
                </div>
                <div class="mo_boot_col-sm-4">
                    <b>OAuth Server Attribute</b>
                </div>
            </div>
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-4">
                    <input class="mo_boot_form-control" type="text" disabled="disabled"/>
                </div>
                <div class="mo_boot_col-sm-4">
                    <input type="text"  class="mo_boot_form-control" disabled="disabled"/>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-4">
            <h3>
                Add Field Attributes
                <sup><a href='<?php echo $license_tab_link;?>' class='premium'><b>[Enterprise]</b></a></sup>
                <input type="button" class="mo_boot_btn mo_boot_btn-primary moOauthAttributeMappingButtons"  value="+" disabled/>
                <input type="button" class="mo_boot_btn mo_boot_btn-danger" value="-" disabled/>
            </h3>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <p class="alert alert-info">NOTE: During registration or login of the user, the value corresponding to User Profile Attributes Mapping Value from OAuth Server will be updated for the User Field Attributes field in User field table.</p>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-4">
                    <b>User Field Attribute</b>
                </div>
                <div class="mo_boot_col-sm-4">
                    <b>OAuth Server Attribute</b>
                </div>
            </div>
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-4">
                    <input class="mo_boot_form-control" type="text" disabled/>
                </div>
                <div class="mo_boot_col-sm-4">
                    <input class="mo_boot_form-control" type="text" disabled/>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-4">
            <h3>Role Mapping<sup><font size="2px" >[<a href='<?php echo $license_tab_link;?>' class='premium'><b>Standard, Premium, Enterprise</b></a>]</font></sup></h3>
            <hr>
            <h6><?php echo "(Configure the 'Group Attribute Names' field in Attribute Mapping section above in order to configure Advanced Role Mapping.)"; ?></h6>

        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <input type="checkbox" name="enable_role_mapping" id="enable_role_mapping" value="1" disabled style="margin-right:25px;"/><strong><?php echo "Enable Role Mapping"; ?></strong>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <div class="mo_boot_row mo_boot_mt-3">
                <div class="mo_boot_col-sm-12">
                    <h3>Group Mapping <sup>
                        [<b><a href='<?php echo $license_tab_link;?>' class='premium'><b>Premium</b></a>,
                        <a href='<?php echo $license_tab_link;?>' class='premium'><b>Enterprise</b></a></b>]
                    </sup></h3>
                    <hr>
                </div>
                <div class="mo_boot_col-sm-4">
                    <b>Select default group for the new users:</b>
                </div>
                <div class="mo_boot_col-sm-6">
                    <?php
                        $db = JFactory::getDbo();
                        $db->setQuery($db->getQuery(true)
                            ->select('*')
                            ->from("#__usergroups")
                        );
                        $groups = $db->loadRowList();

                        echo '<select class="mo_boot_form-control" name="mapping_value_default" id="default_group_mapping"  disabled>';

                        foreach ($groups as $group)
                        {
                            if ($group[4] != 'Super Users')
                                echo '<option selected="selected" value = "' . $group[0] . '">' . $group[4] . '</option>';
                        }
                    ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <div class="mo_boot_row mo_boot_mt-2">
                <div class="mo_boot_col-sm-4">
                    <strong>Group Attribute Names:</strong>
                </div>
                <div class="mo_boot_col-sm-6">
                    <input class="mo_boot_form-control" type="text" id="mo_oauth_gname_attr" name="mo_oauth_gname_attr" value='' disabled>
                </div>
            </div>
        </div>
        <div class=" mo_boot_m-1 mo_boot_my-2" style="background-color:lightgray;width:98.8%!important;">
            <div class="mo_boot_row mo_boot_mt-3">
                <div class="mo_boot_col-sm-4 mo_boot_offset-sm-1">
                    <b>Group Name in Joomla</b>
                </div>
                <div class="mo_boot_col-sm-6">
                    <b>Group/Role Name in the Configured App</b>
                </div>
            </div>
            <div class="mo_boot_row mo_boot_mt-3">
                <?php
                    $user_role = array();
                    if (empty($role_mapping_key_value)) {
                        foreach ($groups as $group) {
                            if ($group[4] != 'Super Users') {
                                echo '<div class="mo_boot_col-sm-4 mo_boot_offset-sm-1"><b>' . $group[4] . '</b></div><div class="mo_boot_col-sm-6"><input class="mo_boot_form-control"  disabled type="text" id="oauth_group_attr_values' . $group[0] . '" name="oauth_group_attr_values' . $group[0] . '" value= "" placeholder="Semi-colon(;) separated Group/Role value for ' . $group[4] . '" "' . ' /></div>';
                            }
                        }
                    }
                    else
                    {
                        foreach ($groups as $group)
                        {
                            if ($group[4] != 'Super Users')
                            {
                                $role_value = array_key_exists($group[0], $role_mapping_key_value) ? $role_mapping_key_value[$group[0]] : "";
                                echo '<div class="mo_boot_col-sm-4 mo_boot_offset-sm-1"><b>' . $group[4] . '</b></div><div class="mo_boot_col-sm-6"><input  class="mo_boot_form-control"  disabled type="text" id="oauth_group_attr_values' . $group[0] . '" name="oauth_group_attr_values' . $group[0] . '" value= "' . $role_value . '" placeholder="Semi-colon(;) separated Group/Role value for ' . $group[4] . '" "' . ' /></div>';
                            }
                        }
                    }
                ?>
            </div>
            <div class="mo_boot_row mo_boot_mt-3">
                <div class="mo_boot_col-sm-12 mo_boot_text-center">
                    <input type="submit" name="send_query" id="send_query"  value='<?php echo "Save role Mapping"; ?>' disabled style="margin-bottom:3%;" class="mo_boot_btn mo_boot_btn-success"/>
                </div>
            </div>
        </div>
    </div>
    <script>
        function restart_tourconf() {
            tourconf.restart();
        }
        var tourconf = new Tour({
            name: "tour",
            steps: [
                {
                    element: "#mo_oauth_attributemapping",
                    title: "Attribute Mapping",
                    content: "Please select your server to configure. Select Other if your server not listed.",
                    backdrop: 'body',
                    backdropPadding: '20'
                },
                {
                    element: "#callbackurl",
                    title: "Redirect / Callback URL",
                    content: "Use this URL to configure your server.",
                    backdrop: 'body',
                    backdropPadding: '6'
                },
                {
                    element: "#oacconf_end_tour",
                    title: "Tour ends",
                    content: "Click here to restart tour",
                    backdrop: 'body',
                    backdropPadding: '6'
                },

            ]
        });
    </script>
    <?php
}
function grant_type_settings() {
    ?>
    <div class="mo_boot_row mo_boot_mr-1 mo_boot_my-3" style="background:white;">
        <div class="mo_boot_col-sm-12 mo_boot_mt-4">
            <h3 style="display: inline;">Grant Settings&emsp;<code><small><a href="<?php echo $license_tab_link;?>"  rel="noopener noreferrer">[PREMIUM, ENTERPRISE]</a></small></code></h3>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <h4>Select Grant Type:</h4>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2 grant_types">
            <input checked disabled type="checkbox">&emsp;<strong>Authorization Code Grant</strong>&emsp;<code><small>[DEFAULT]</small></code>
            <blockquote>
                The Authorization Code grant type is used by web and mobile apps.<br/>
                It requires the client to exchange authorization code with access token from the server.
                <br/><small>(If you have doubt on which settings to use, you can leave this checked and disable all others.)</small>
            </blockquote>
            <input disabled type="checkbox">&emsp;<strong>Implicit Grant</strong>
            <blockquote>
                The Implicit grant type is a simplified version of the Authorization Code Grant flow.<br/>
                OAuth providers directly offer access token when using this grant type.
            </blockquote>
            <input disabled type="checkbox">&emsp;<strong>Password Grant</strong>
            <blockquote>
                Password grant is used by application to exchange user's credentials for access token.<br/>
                This, generally, should be used by internal applications.
            </blockquote>
            <input disabled type="checkbox">&emsp;<strong>Refresh Token Grant</strong>
            <blockquote>
                The Refresh Token grant type is used by clients.<br/>
                This can help in keeping user session persistent.
            </blockquote>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <hr>
            <h3 style="display: inline;">JWT Validation&emsp;<code><small><a href="<?php echo $license_tab_link;?>"  rel="noopener noreferrer">[PREMIUM, ENTERPRISE]</a></small></code></h3>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <strong>Enable JWT Verification:</strong>
            <input type="checkbox" value="" disabled/>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <strong>JWT Signing Algorithm:</strong>
            <select disabled>
                <option>HSA</option>
                <option>RSA</option>
            </select>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_my-2">
            <div class="notes">
                <hr />
                Grant Type Settings and JWT Validation are configurable in <a href="<?php echo $license_tab_link;?>" rel="noopener noreferrer">premium and enterprise</a> versions of the plugin.
            </div>
        </div>
    </div>
    <?php
}
function loginlogoutsettings()
{
    ?>
    <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" style="background:white;">
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <h3>Advanced Settings</h3>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
            <input type="checkbox" name="mo_oauth_auto_redirect" id="mo_oauth_auto_redirect" value="1" disabled style="margin-right:10px;"/><strong><?php echo "Restrict site to logged in users (Users will be auto redirected to OAuth Provider's login page if not logged in) "; ?></strong>
            [<a href='<?php echo $license_tab_link;?>' class='premium'><b>Premium</b></a>,
            <a href='<?php echo $license_tab_link;?>' class='premium'><b>Enterprise</b></a>]
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
            <input type="checkbox" name="mo_oauth_dont_auto_register" id="mo_oauth_dont_auto_register" value="1" disabled style="margin-right:10px;"/><strong><?php echo "Do Not Auto Create Users (If checked, only existing users will be able to log-in) "; ?></strong>
            [<a href='<?php echo $license_tab_link;?>' class='premium'><b>Premium</b></a>,
            <a href='<?php echo $license_tab_link;?>' class='premium'><b>Enterprise</b></a>]
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <details>
                <summary>
                    Domain Settings
                    <small><a href='<?php echo $license_tab_link;?>' class='premium'>[<b>Standard</b></a>,
                    <a href='<?php echo $license_tab_link;?>' class='premium'><b>Premium</b></a>,
                    <a href='<?php echo $license_tab_link;?>' class='premium'><b>Enterprise</b>]</a></small>
                </summary>
                <div class="mo_boot_row mo_boot_m-1 mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <strong>Restricted Domains</strong>
                    </div>
                    <div class="mo_boot_col-sm-8">
                        <input class="mo_boot_form-control" type="text" id="mo_oauth_restricted_domains" name="mo_oauth_restricted_domains" value='' disabled placeholder="domain1.com,domain2.com,....">
                        <p><i><b>Note:</b>In this feature you can restrict some domains to login using SSO.</i></p>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_m-1 mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <strong>Allowed Domains:</strong>
                    </div>
                    <div class="mo_boot_col-sm-8">
                        <input class="mo_boot_form-control" type="text" id="mo_oauth_allowed_domains" name="mo_oauth_allowed_domains" value='' disabled placeholder="domain1.com,domain2.com,....">
                        <p><i><b>Note:</b>In this feature you can allow some domains to login using SSO.</i></p>
                    </div>
                </div>
            </details>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <details>
                <summary>
                    Login Settings
                    <small><a href='<?php echo $license_tab_link;?>' class='premium'>[<b>Standard</b></a>,
                    <a href='<?php echo $license_tab_link;?>' class='premium'><b>Premium</b></a>,
                    <a href='<?php echo $license_tab_link;?>' class='premium'><b>Enterprise</b>]</a></small>
                </summary>
                <div class="mo_boot_row mo_boot_m-1 mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <strong>Login Redirect URL:</strong>
                    </div>
                    <div class="mo_boot_col-sm-8">
                        <input class="mo_boot_form-control" type="text" id="mo_oauth_allowed_domains" name="mo_oauth_allowed_domains" value='' disabled placeholder="domain1.com,domain2.com,....">
                        <p><i><b>Note:</b>You can redirect the user to the particular URL after login using SSO.</i></p>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_m-1 mo_boot_mt-2">
                    <div class="mo_boot_col-sm-3">
                        <strong>Logout Redirect URL:</strong>
                    </div>
                    <div class="mo_boot_col-sm-8">
                        <input class="mo_boot_form-control" type="text" id="mo_oauth_allowed_domains" name="mo_oauth_allowed_domains" value='' disabled placeholder="domain1.com,domain2.com,....">
                        <p><i><b>Note:</b>You can redirect the user to the particular URL after the logout.</i></p>
                    </div>
                </div>
            </details>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <details>
                <summary>
                    Customize Icon
                    <small><a href='<?php echo $license_tab_link;?>' class='premium'>[<b>Standard</b></a>,
                    <a href='<?php echo $license_tab_link;?>' class='premium'><b>Premium</b></a>,
                    <a href='<?php echo $license_tab_link;?>' class='premium'><b>Enterprise</b>]</a></small>
                </summary>
                <div class="mo_boot_row mo_boot_m-1 mo_boot_mt-3">
                    <div class="mo_boot_col-sm-12">
                        <p class="highlight">
                            This feature allows you to use custom CSS on the login buttons.You can configure the Height, Width and Margins of the Widget Button.
                            You can also add in the CSS for the button.
                        </p>
                    </div>
                    <div class="mo_boot_col-sm-12">
                        <div class="mo_boot_row">
                            <div class="mo_boot_col-sm-3">
                                <strong>Icon Width:</strong>
                            </div>
                            <div class="mo_boot_col-sm-9">
                                <input class="mo_boot_form-control" disabled type="text" placeholder="e.g. 200px or 100%">
                            </div>
                        </div>
                        <div class="mo_boot_row">
                            <div class="mo_boot_col-sm-3">
                                <strong>Icon Height:</strong>
                            </div>
                            <div class="mo_boot_col-sm-9">
                                <input class="mo_boot_form-control" disabled type="text"  placeholder="e.g. 50px or auto">
                            </div>
                        </div>
                        <div class="mo_boot_row">
                            <div class="mo_boot_col-sm-3">
                                <strong>Icon Margins:</strong>
                            </div>
                            <div class="mo_boot_col-sm-9">
                                <input class="mo_boot_form-control" disabled type="text" placeholder="e.g. 2px 0px or auto">
                            </div>
                        </div>
                        <div class="mo_boot_row">
                            <div class="mo_boot_col-sm-3">
                                <strong>Custom CSS:</strong>
                            </div>
                            <div class="mo_boot_col-sm-9">
                                <textarea disabled type="text" style="resize: vertical;width:100%;"  rows="6"></textarea><br/><b>Example CSS:</b>
                                <pre>.oauthloginbutton{background: #7272dc;height:40px;padding:8px;text-align:center;color:#fff;}</pre>
                            </div>
                        </div>
                        <div class="mo_boot_row">
                            <div class="mo_boot_col-sm-3">
                                <strong>Custom Logout button text:</strong>
                            </div>
                            <div class="mo_boot_col-sm-9">
                                <input class="mo_boot_form-control" disabled type="text" style="resize: vertical;width:100%;" placeholder ="Howdy ,##user##"> <b>##user##</b> is replaced by Username
                            </div>
                        </div>
                        <div class="mo_boot_row mo_boot_mt-4 mo_boot_text-center">
                            <div class="mo_boot_col-sm-12">
                                <input disabled value="Save settings "  class="mo_boot_btn mo_boot_btn-primary" />
                            </div>
                        </div>
                    </div>
                </div>
            </details>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <details>
                <summary>
                    User Analytics and Transaction Reports
                    <small><a href='<?php echo $license_tab_link;?>' class='premium'>[<b>Enterprise</b>]</a></small>
                </summary>
                <div class="mo_boot_row mo_boot_m-1 mo_boot_mt-3">
                    <div class="mo_boot_col-sm-12">
                        <div class="mo_boot_row mo_boot_mt-2">
                            <div class="mo_boot_col-sm-12">
                                <input disabled type="button" class="mo_boot_btn mo_boot_btn-danger" id="cleartext" value="Clear Reports" style="float:right" />
                                <input disabled type="button" class="mo_boot_btn mo_boot_btn-primary" id="refreshtext" value="Refresh" style="float:right;margin-right:10px;"/>
                            </div>
                        </div>
                        <div class="mo_boot_row mo_boot_mt-3">
                            <div class="mo_boot_col-sm-12 mo_boot_table-responsive">
                                <table class="mo_boot_table mo_boot_table-striped mo_boot_table-hover mo_boot_table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Application</th>
                                            <th>Status</th>
                                            <th>Login Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mo_boot_row mo_boot_mt-3 mo_boot_text-center">
                            <div class="mo_boot_col-sm-12">
                                <input disabled value="Save settings"  class=" mo_boot_btn mo_boot_btn-primary"/>
                            </div>
                        </div>
                    </div>
                </div>
            </details>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2 mo_boot_mb-4">
            <details>
                <summary>
                    Page Restriction
                    <small><a href='<?php echo $license_tab_link;?>' class='premium'>[<b>Enterprise</b>]</a></small>
                </summary>
                <div class="mo_boot_row mo_boot_m-1 mo_boot_mt-3">
                    <div class="mo_boot_col-sm-12">
                        <b>Enter the list of semicolon separated relative URLs of your pages in the textarea.</b>
                    </div>
                    <div class="mo_boot_col-sm-12">
                        <div class="mo_boot_row mo_boot_mt-2">
                            <div class="mo_boot_col-sm-12">
                                <p>
                                    <textarea rows="10" id="mo_oauth_page_restricted_urls" name="mo_oauth_page_restricted_urls"
                                    placeholder="Enter the semicolon(;) separated relative urls."
                                    style="width: 100%;" disabled><?php //echo ""; ?></textarea>
                                </p>
                            </div>
                        </div>
                        <div class="mo_boot_row mo_boot_mt-3 mo_boot_text-center">
                            <div class="mo_boot_col-sm-12">
                                <input disabled value="Save settings "  class="mo_boot_btn mo_boot_btn-primary" />
                            </div>
                        </div>
                    </div>
                </div>
            </details>
        </div>
    </div>
    <?php


}
function support()
{
    ?>
    <div class="mo_boot_row mo_boot_m-1 mo_boot_mt-3" style="background:white">
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <h2>
                Support Feature's
                <span style="float:right;" id="mini-icons">
                    <a href="https://faq.miniorange.com/kb/oauth-openid-connect/" target="_blank" class="mo_boot_btn mo_boot_btn-success mo_boot_py-1">FAQ's</a>
                    <a href="https://plugins.miniorange.com/joomla-oauth-client" target="_blank" title="Website" style="padding:5px;border:1px solid lightgray;"><i style="color:#2384d3" class="fa fa-globe"></i></a>
                    <a href="https://www.miniorange.com/contact" target="_blank" title="Contact-Us" style="padding:5px;border:1px solid lightgray;"><i style="color:#2384d3" class="fa fa-comment"></i></a>
                    <a href="https://extensions.joomla.org/extension/miniorange-oauth-client/" target="_blank" title="Rate us" style="padding:5px;border:1px solid lightgray;"><i style="color:#2384d3" class="fa fa-star"></i></a>
                </span>
            </h2>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <details open>
                <summary>Support</summary>
                    <hr>
                    <div class="mo_boot_row mo_boot_m-2">
                        <?php
                            $current_user = JFactory::getUser();
                            $result = MoOAuthUtility::getCustomerDetails();
                            $admin_email = empty(trim($result['email']))?$current_user->email:$result['email'];
                            $admin_phone = $result['admin_phone'];
                        ?>
                        <form name="f" style="width:100%;" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.contactUs'); ?>">
                            <div class="mo_boot_col-sm-12">
                                <p style="background-color: #e2e6ea; padding: 10px;">Need any help? Just send us a query and we will get back to you soon.</p>
                                <br>
                            </div>
                            <div class="mo_boot_col-sm-12">
                                <div class="mo_boot_row">
                                    <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                                        <strong>Email:<span style="color: red;">*</span></strong>
                                    </div>
                                    <div class="mo_boot_col-sm-6">
                                        <input type="email" class="mo_boot_form-control oauth-table" style="border-radius:4px;resize: vertical;width:100%" id="query_email" name="query_email" value="<?php echo $admin_email; ?>" placeholder="Enter your email" required />
                                    </div>
                                </div>
                                <div class="mo_boot_row">
                                    <div class="mo_boot_col-sm-3 mo_boot_offset-1"> <strong>Mobile no.</strong></div>
                                    <div class="mo_boot_col-sm-6">
                                        <input type="text" class="mo_boot_form-control oauth-table" style="border-radius:4px;resize: vertical;width:100%" name="query_phone" id="query_phone" value="<?php echo $admin_phone; ?>" placeholder="Enter your phone with country code"/>
                                    </div>
                                </div>
                                <div class="mo_boot_row">
                                    <div class="mo_boot_col-sm-3 mo_boot_offset-1"><strong>Query</strong></div>
                                    <div class="mo_boot_col-sm-6">
                                        <textarea id="query" name="query" style="border-radius:4px;resize: vertical;width:100%" cols="52" rows="6" onkeyup="mo_otp_valid(this)" onblur="mo_otp_valid(this)" onkeypress="mo_otp_valid(this)" placeholder="Write your query here" required></textarea>
                                    </div>
                                </div>
                                <div class="mo_boot_row mo_boot_text-center">
                                    <div class="mo_boot_col-sm-12">
                                        <input type="submit" name="send_query" id="send_query" value="Submit Query" class="mo_boot_btn mo_boot_btn-success"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div hidden id="mosaml-feedback-overlay"></div>
                        <br/>
                        <script>
                            function mo_otp_valid(f) {
                                !(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
                            }
                        </script>
                    </div>
            </details>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <details>
                <summary>Request For Demo</summary>
                <hr>
                <div class="mo_boot_row mo_boot_m-2">
                    <div class="mo_boot_col-sm-12">
                        <div style="background-color: #e2e6ea; padding: 10px;"><p>If you want to try the upgraded version of the plugin then
                            we can setup a demo Joomla site for you on our cloud and provide you with its credentials. You can configure
                            it with your Oauth server, test the SSO and play around with the premium features.
                            </p><b>Note:</b> Please describe your use-case in the <b>Description</b> below.
                        </div><br>
                    </div>
                    <div class="mo_boot_col-sm-12">
                        <form id="demo_request" name="demo_request" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.requestForDemoPlan'); ?>">
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                                    <strong>Email:<span style="color: red;">*</span></strong>
                                </div>
                                <div class="mo_boot_col-sm-6">
                                    <input required class="mo_boot_form-control" onblur="validateEmail(this)" type="email" style="border-radius:4px;resize: vertical;" name="email" placeholder="person@example.com" value="<?php echo JFactory::getUser()->email; ?>"/>
                                    <p style="display: none;color:red" id="email_error">Invalid Email</p>
                                </div>
                            </div>
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                                    <strong>Request a demo for:<span style="color: red;">*</span></strong>
                                </div>
                                <div class="mo_boot_col-sm-6">
                                    <select required class="mo_boot_form-control" name="plan" id="rfd_id">
                                        <option value="">----------- Select ------------------</option>
                                        <option value="Joomla OAuth Client Standard Plugin">Joomla OAuth Client Standard Plugin</option>
                                        <option value="Joomla OAuth Client Premium Plugin">Joomla OAuth Client Premium Plugin</option>
                                        <option value="Joomla OAuth Client Enterprise Plugin">Joomla OAuth Client Enterprise Plugin</option>
                                        <option value="Not Sure">Not Sure</option>
                                    </select>
                                </div>

                            </div>
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                                    <strong>Description:<span style="color: #ff7316;">*</span></strong>
                                </div>
                                <div class="mo_boot_col-sm-6">
                                    <textarea required type="text" name="description" style="resize: vertical; width:100%; height:100px;" rows="4" placeholder="Need assistance? Write us about your requirement and we will suggest the relevant plan for you." value=""></textarea>
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_text-center">
                                <div class="mo_boot_col-sm-12">
                                    <input type="submit" name="submit" value="Submit" class="mo_boot_btn mo_boot_btn-success"/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script type="text/javascript">
                        function validateEmail(emailField) {
                            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                            if (reg.test(emailField.value) == false) {
                                document.getElementById('email_error').style.display = "block";
                                document.getElementById('submit_button').disabled = true;
                            } else {
                                document.getElementById('email_error').style.display = "none";
                                document.getElementById('submit_button').disabled = false;
                            }
                        }
                    </script>
                </div>
            </details>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2 mo_boot_mb-4">
            <details>
                <summary>Setup a Call/Screen-Share</summary>
                <hr>
                <?php
                    // Get the contents of the JSON file
                    $strJsonFileContents = file_get_contents(JURI::root()."/administrator/components/com_miniorange_oauth/assets/json/timezones.json");
                    //Convert to array
                    $timezoneJsonArray = json_decode($strJsonFileContents, true);
                    $current_user = JFactory::getUser();
                    $result = MoOAuthUtility::getCustomerDetails();
                    $admin_email = empty(trim($result['email']))?$current_user->email:$result['email'];
                    $admin_phone = $result['admin_phone'];

                ?>
                <form name="f" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.callContactUs'); ?>">
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-12 mo_boot_px-5">
                            <p  style="background-color: #e2e6ea; padding: 10px;">Need any help? Just send us a query and we will get back to you soon.</p>
                        </div>
                        <div class="mo_boot_col-sm-12">
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                                    <strong><font color="#FF0000">*</font>Email:</strong>
                                </div>
                                <div class="mo_boot_col-sm-6">
                                    <input class="mo_boot_form-control"  type="email" placeholder="user@example.com" name="mo_oauth_setup_call_email" value="<?php echo $admin_email; ?>"  required>
                                </div>
                            </div>
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                                    <strong><font color="#FF0000">*</font>Issue:</strong>
                                </div>
                                <div class="mo_boot_col-sm-6">
                                    <select id="issue_dropdown"  class="mo_callsetup_table_textbox mo_boot_form-control" name="mo_oauth_setup_call_issue" required>
                                        <option disabled selected>--------Select Issue type--------</option>
                                        <option id="sso_setup_issue">SSO Setup Issue</option>
                                        <option>Custom requirement</option>
                                        <option id="other_issue">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                                    <strong><font color="#FF0000">*</font>Date:</td></strong>
                                </div>
                                <div class="mo_boot_col-sm-6">
                                    <input class="mo_boot_form-control mo_callsetup_table_textbox" name="mo_oauth_setup_call_date" type="date" id="calldate" required>
                                </div>
                            </div>
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                                    <strong><font color="#FF0000">*</font>Time:</td></strong>
                                </div>
                                <div class="mo_boot_col-sm-6">
                                    <select class="mo_callsetup_table_textbox" style="width:100%;" name="mo_oauth_setup_call_timezone" id="timezone" required>
                                    <?php
                                        foreach($timezoneJsonArray as $data)
                                        {
                                            echo "<option>".$data."</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3 mo_boot_offset-1">
                                    <strong><font id="required_mark" color="#FF0000" style="display: none;">*</font>Description:</strong>
                                </div>
                                <div class="mo_boot_col-sm-6">
                                    <textarea id="issue_description" style="width:100%;" class="mo_callsetup_table_textbox" name="mo_oauth_setup_call_desc" minlength="15" placeholder="Enter your query" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_text-center">
                                <div class="mo_boot_col-sm-12">
                                    <input type="submit" name="send_query" id="send_query" value="Submit Query" class="mo_boot_btn mo_boot_btn-success">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <script>
                    let screenShareButton =document.getElementById("setup_call_button");
                    screenShareButton.addEventListener("click",function () {
                        let screenShareLayout = document.getElementById("mo_setup_call_layout");
                        if(screenShareLayout.style.getPropertyValue("display") === "none"){
                            screenShareLayout.style.setProperty("display","block");
                            screenShareLayout.style.setProperty("margin-bottom","20px")
                        }
                        else{
                            screenShareLayout.style.setProperty("display","none");
                        }
                    });
                    function mo_otp_valid(f) {
                        !(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
                    }
                </script>
            </details>
        </div>
    </div>
    <style>
        details{
            border:2px solid lightgray;
            border-radius:3px;
            margin-top:10px;
        }
        details:hover{
            transform:scale(1.01);
        }
        details>summary:focus{
            outline:none;
            background-color: #e2e6ea;
        }
        details>summary{
            padding:10px;
            background:#f8f9fa;
            font-size:18px;
            color:#bd3858!important;
        }
        #mini-icons>a:hover>i{
            transform:scale(1.3);
        }
    </style>
    <?php
}
function sideaddon(){
    ?>
    <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_boot_py-4 mo_boot_text center" style="background:white;">
        <div class="mo_boot_col-sm-12">
            <h2 class="mo_boot_p-2 mo_boot_mt-3 mo_boot_text-center">Add-Ons</h2>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12">
            <div class="mo_boot_row mo_boot_m-2 mo_boot_p-2 add_on_box" >
                <div class="mo_boot_col-sm-4">
                    <img style="margin-top:2em;" src="<?php  echo JURI::root(); ?>administrator/components/com_miniorange_oauth/assets/images/scim-icon.png" width="100px" height="100px">
                </div>
                <div class="mo_boot_col-sm-8">
                    <h3 style="margin-top:2em;">Real Time User Provisioning with SCIM</h3>
                    <p>This plugin allows user provisioning with SCIM standard. System for Cross-domain Identity Management is a standard for automating the exchange of user identity information between identity domains, or IT systems.</p>
                    <a href="https://plugins.miniorange.com/joomla-scim-user-provisioning" class="mo_boot_btn mo_boot_btn-primary mo_boot_py-0" target="_blank" style="text-decoration:none;">Know More</a>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-12">
            <div class="mo_boot_row mo_boot_m-2 mo_boot_p-2 add_on_box">
                <div class="mo_boot_col-sm-4">
                    <img src="<?php  echo JURI::root(); ?>administrator/components/com_miniorange_oauth/assets/images/miniorange.png" width="100px" height="100px">
                </div>
                <div class="mo_boot_col-sm-8">
                    <h3 style="margin-top:1em;">Sweet Alert</h3>
                    <p>Sweet Alert add-on helps you to get every message's in a very pretty modal window</p>
                    <a href="https://www.miniorange.com/contact" class="mo_boot_btn mo_boot_btn-primary mo_boot_py-0" target="_blank" style="text-decoration:none;">Know More</a>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-12">
            <div class="mo_boot_row mo_boot_m-2 mo_boot_p-2 add_on_box">
                <div class="mo_boot_col-sm-4">
                    <img src="<?php  echo JURI::root(); ?>administrator/components/com_miniorange_oauth/assets/images/discord.png" width="100px" height="100px">
                </div>
                <div class="mo_boot_col-sm-8">
                    <h3 style="margin-top:1em;">Discord Role Mapping</h3>
                    <p>Discord Role Mapping add-on helps you to get roles from your discord server and maps it to Joomla user while SSO.</p>
                    <a href="https://www.miniorange.com/contact" class="mo_boot_btn mo_boot_btn-primary mo_boot_py-0" target="_blank" style="text-decoration:none;">Know More</a>
                </div>
            </div>
        </div>
    </div>
    <style>
        .add_on_box{
            border:1px solid lightgray;
            box-shadow:0px 0px 5px 2px lightgray;
        }
        .add_on_box:hover{
            transform:scale(1.01);
        }

    </style>
    <?php
}

function showLicensingPlanDetails() {

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__miniorange_oauth_customer'));
    $query->where($db->quoteName('id')." = 1");

    $db->setQuery($query);
    $useremail = $db->loadAssoc();


    if(isset($useremail))
        $user_email =$useremail['email'];
    else
        $user_email="xyz";


    ?>
    <style>
        .modal {
            display:none;
            position: fixed;
            z-index: 1;
            left: 40%!important;
            top: 0!important;
            width: 100%!important;
            height: 100%!important;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4)!important;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 25%;
            height: auto;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <div id="myModal" class="modal">
        <div class="modal-content mo_boot_text-center">
            <span class="close">&times;</span><br><br><br>
            <p style="font-size:20px;line-height:30px;">You Need to Login / Register in Account Setup tab to Upgrade your License </p>
            <br><br>
            <a href="<?php echo JURI::base()?>index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account" class="btn btn-primary">LOGIN / REGISTER</a>
        </div>
    </div>
    <div class="tab-content" >
        <div class="tab-pane active text-center" id="cloud">
            <div class="cd-pricing-container cd-has-margins"><br>
                <ul class="cd-pricing-list cd-bounce-invert" >
                    <li class="cd-black">
                        <ul class="cd-pricing-wrapper"  style="height: 500px";>
                            <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible cd-singlesite" style="width: 100%">
                                <header class="cd-pricing-header" style="height: 230px">
                                    <h2 style="margin-bottom: 10px" >Free<br/><br/><br></h2>
                                    <div class="cd-price" >
                                        <br><br>
                                        <b style="font-size: large">You are automatically on this plan</b>
                                    </div>
                                </header> <!-- .cd-pricing-header -->
                                </a>
                                <footer class="cd-pricing-footer">
                                    <a class="cd-select" style="font-size: 85.5%;" >Current Active Plan</a>
                                </footer><br>
                                <!--                                <b style="color: coral;">See the Standard Plugin features list below</b>-->
                                <div class="cd-pricing-body">

                                    <ul class="cd-pricing-features">
                                        <li style="font-size: medium"> Limited authentications</li>
                                        <li style="font-size: medium">Auto fill OAuth servers configuration</li>
                                        <li style="font-size: medium">Basic Attribute Mapping(Username , Email)</li>
                                        <li style="font-size: medium">Login using the link</li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"> <br></li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"><br><br></li>
                                        <li style="font-size: medium"> <br></li>
                                        <li style="font-size: medium"> <br></li>
                                        <li style="font-size: medium"> <br></li>
                                        <li style="font-size: medium"><br> <br></li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"><br><br><br><br><br><br></li>
                                    </ul>
                                </div>
                            </li>
                        </ul> <!-- .cd-pricing-wrapper -->
                    </li>
                    <li class="cd-black">
                        <ul class="cd-pricing-wrapper"  style="height: 500px";>
                            <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible cd-singlesite" style="width: 100%">
                                <header class="cd-pricing-header" style="height: 230px">
                                    <h2 style="margin-bottom: 10px" >Standard<br/></h2>(Unlimited Authentications, Unlimited user creations, Advance Attribute Mapping)<br>
                                    <div class="cd-price" ><br><br><br>
                                        <span id="plus_total_price" style="font-weight: bolder;font-size: xx-large">$249*</span>
                                        <br><br>
                                        <b style="font-size: large"></b>
                                    </div>
                                </header> <!-- .cd-pricing-header -->
                                </a>
                                <footer class="cd-pricing-footer">
                                    <?php
                                    if (!MoOAuthUtility::is_customer_registered())
                                    {
                                        echo '<button class="cd-select" style="font-size: 85.5%; cursor: pointer;width:100%;border:none" id="upgrade_Btn1">UPGRADE NOW</button>';
                                    }
                                    else
                                    {
                                        $redirect3= "https://login.xecurify.com/moas/login?username=".$user_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_oauth_client_standard_plan";
                                        echo '<a target="_blank" class="cd-select" style="font-size: 85.5%; cursor: pointer;"  href="'.$redirect3.'" >UPGRADE NOW</a>';
                                    }
                                    ?>
                                </footer><br>
                                <!--                                <b style="color: coral;">See the Standard Plugin features list below</b>-->
                                <div class="cd-pricing-body">
                                    <ul class="cd-pricing-features">
                                        <li style="font-size: medium"> Unlimited authentications</li>
                                        <li style="font-size: medium">Auto fill OAuth servers configuration</li>
                                        <li style="font-size: medium">Attribute Mapping(Username , Email)</li>
                                        <li style="font-size: medium">Login using the link</li>
                                        <li style="font-size: medium">Auto register users Unlimited</li>
                                        <li style="font-size: medium">Login Widget Customization</li>
                                        <li style="font-size: medium">Custom Redirect URL after login and logout</li>
                                        <li style="font-size: medium">Basic Group Mapping</li>
                                        <li style="font-size: medium"> <br></li>
                                        <li style="font-size: medium"> <br><br></li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"><br><br></li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"> <b>Add-Ons **</b><br>Purchase Separately<br><a style="color:blue;" href="https://www.miniorange.com/contact" target='_blank'><br><b>Contact us</b></a><br><br><br></li>
                                    </ul>
                                </div>
                            </li>
                        </ul> <!-- .cd-pricing-wrapper -->
                    </li>
                    <li class="cd-black">
                        <ul class="cd-pricing-wrapper">
                            <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible" style="height=600px; width: 100%; left: 30%; ">
                                <header class="cd-pricing-header" style="height: 230px">
                                    <h2 style="margin-bottom: 10px">Premium<br/></h2>(Advanced Group Mapping, OpenId Connect)<br/>
                                    <div class="cd-price" ><br><br><br><br>
                                        <span id="plus_total_price" style="font-weight: bolder;font-size: xx-large">$399*</span> <br/></h3>
                                    </div>
                                </header> <!-- .cd-pricing-header -->
                                </a>
                                <footer class="cd-pricing-footer">
                                    <?php
                                        if (!MoOAuthUtility::is_customer_registered())
                                        {
                                            echo '<button class="cd-select" style="font-size: 85.5%; cursor: pointer;width:100%;border:none" id="upgrade_Btn2">UPGRADE NOW</button>';
                                        }
                                        else
                                        {
                                            $redirect3= "https://login.xecurify.com/moas/login?username=".$user_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_oauth_client_premium_plan";
                                            echo '<a target="_blank" class="cd-select" style="font-size: 85.5%; cursor: pointer;"  href="'.$redirect3.'" >UPGRADE NOW</a>';
                                        }
                                    ?>
                                </footer><br>
                                <div class="cd-pricing-body">
                                    <ul class="cd-pricing-features">
                                        <li style="font-size: medium"> Unlimited authentications</li>
                                        <li style="font-size: medium">Auto fill OAuth servers configuration</li>
                                        <li style="font-size: medium">Advanced Attribute Mapping</li>
                                        <li style="font-size: medium">Login using the link</li>
                                        <li style="font-size: medium">Auto register users Unlimited</li>
                                        <li style="font-size: medium">Login Widget Customization</li>
                                        <li style="font-size: medium">Custom Redirect URL after login and logout</li>
                                        <li style="font-size: medium">Advanced Group Mapping</li>
                                        <li style="font-size: medium">Force authentication/Protect complete site</li>
                                        <li style="font-size: medium">OpenId Connect Support (Login using OpenId Connect Server)</li>
                                        <li style="font-size: medium">Domain specific registration</li>
                                        <li style="font-size: medium">JWT Validation</li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"><br><br></li>
                                        <li style="font-size: medium"><br></li>
                                        <li style="font-size: medium"> <b>Add-Ons **</b><br>Purchase Separately<br><a style="color:blue;" href="https://www.miniorange.com/contact" target='_blank'><br><b>Contact us</b></a><br><br><br></li>
                                    </ul>
                                </div> <!-- .cd-pricing-body -->
                            </li>
                        </ul> <!-- .cd-pricing-wrapper -->
                    </li>
                    <li class="cd-black">
                        <ul class="cd-pricing-wrapper">
                            <li id="singlesite_tab" data-type="singlesite" class="mosslp is-visible" style="width: 100%; left: 60%;">
                                <header class="cd-pricing-header" style="height: 230px">
                                    <h2 style="margin-bottom:10px;">Enterprise<br/></h2>(Additional end point for getting user groups from your OAuth/Open ID provider, Login Reports/Analysis)<br/>
                                    <div class="cd-price" ><br><br><br>
                                        <span id="pro_total_price" style="font-weight: bolder;font-size: xx-large">$449*</span> <br/></h3>
                                    </div>
                                </header> <!-- .cd-pricing-header -->
                                <footer class="cd-pricing-footer">
                                    <?php
                                        if (!MoOAuthUtility::is_customer_registered())
                                        {
                                            echo '<button class="cd-select" style="font-size: 85.5%; cursor: pointer;width:100%;border:none" id="upgrade_Btn3">UPGRADE NOW</button>';
                                        }
                                        else
                                        {
                                            $redirect3= "https://login.xecurify.com/moas/login?username=".$user_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_oauth_client_enterprise_plan";
                                            echo '<a target="_blank" class="cd-select" style="font-size: 85.5%; cursor: pointer;"  href="'.$redirect3.'" >UPGRADE NOW</a>';
                                        }
                                    ?>
                                </footer><br>
                                <!--                                <b style="color: coral;">See the Enterprise Plugin features list below</b>-->
                                <div class="cd-pricing-body">
                                    <ul class="cd-pricing-features">
                                        <li style="font-size: medium"> Unlimited authentications</li>
                                        <li style="font-size: medium">Auto fill OAuth servers configuration</li>
                                        <li style="font-size: medium">Advanced Attribute Mapping</li>
                                        <li style="font-size: medium">Login using the link</li>
                                        <li style="font-size: medium">Auto register users Unlimited</li>
                                        <li style="font-size: medium">Login Widget Customization</li>
                                        <li style="font-size: medium">Custom Redirect URL after login and logout</li>
                                        <li style="font-size: medium">Advanced Group Mapping</li>
                                        <li style="font-size: medium">Force authentication/Protect complete site</li>
                                        <li style="font-size: medium">OpenId Connect Support (Login using OpenId Connect Server)</li>
                                        <li style="font-size: medium">Domain specific registration</li>
                                        <li style="font-size: medium">JWT Validation</li>
                                        <li style="font-size: medium">Grant Settings</li>
                                        <li style="font-size: medium">Additional end point for getting user groups from your OAuth/Open ID provider.</li>
                                        <li style="font-size: medium">Login Reports/Analytics</li>
                                        <li style="font-size: medium"> <b>Add-Ons **</b><br>Purchase Separately<br><a style="color:blue;" href="https://www.miniorange.com/contact" target='_blank'><br><b>Contact us</b></a><br><br><br></li>
                                    </ul>
                                </div> <!-- .cd-pricing-body -->
                                <!-- .cd-pricing-body -->
                            </li>
                        </ul> <!-- .cd-pricing-wrapper -->
                    </li>
                </ul> <!-- .cd-pricing-list -->
            </div> <!-- .cd-pricing-container -->
        </div>
        <!-- Modal -->
        <br/><br/>
        <br/><div style=" background:white;" class="mo_boot_p-4">
            <table>
                <tr><td><h2><sup>*</sup></h2></td><td><h4 class="moOauthPointerCursor">This is the price for 1 instance. <i>Buying multiple licenses does not mean you have to pay the same amount for all the licenses.</i> We provide a deep discount from the second instance onwards. Check our <a onclick= "<?php if(! MoOAuthUtility::is_customer_registered()){ echo " window.location.href='index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account' "; } else { echo " window.open('https://login.xecurify.com/moas/login?username=".$user_email."&redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=joomla_oauth_client_enterprise_plan')"; } ?>">pricing page</a> for full details.</h4></td></tr>
            </table>
            <div class="moOauthClientIndent">
                <h4>10 Days Return Policy -</h4>
                <p class="moOauthClientIndent">At miniOrange, we want to ensure you are 100% happy with your purchase. If the plugin you purchased is not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get resolved, we will refund the whole amount given that you have a raised a request for refund within the first 10 days of the purchase. Please email us at <a href="mailto:joomlasupport@xecurify.com">joomlasupport@xecurify.com</a> for any queries regarding the return policy.</p>
                <h4>Steps for Upgrade to licensed version of the Plugin -</h4>
                <div class="moOauthClientIndent">
                    <p>1. You will be redirected to miniOrange Login Console. Enter your username and password with which you created an account with us. After that you will be redirected to payment page.</p>
                    <p>2. Enter your card details and complete the payment. On successful payment completion, you will see the link to download the premium plugin.</p>
                    <p>3. Once you download the premium plugin, first uninstall existing plugin ( free version ) then install the premium plugin. <br>
                </div>
            </div>
            <h3 >** Add-Ons List</h3>
            <p class="moOauthClientIndent">Integration with Community Builder, SCIM (User Provisioning), Page Restriction,Sweet Alert,Discord</p>
            <br>
        </div><br>
    </div>
    <style>
        .cd-black :hover #singlesite_tab.is-visible{
            margin-right : 4px;
            transition : 0.4s;
            -moz-transition : 0.4s;
            -webkit-transition : 0.4s;
            border-radius: 8px;
            transform: scale(1.03);
            -ms-transform: scale(1.03); /* IE 9 */
            -webkit-transform: scale(1.03); /* Safari */

            box-shadow: 0 0 4px 1px rgba(255,165, 0, 0.8);
        }
        h1 {
            margin: .67em 0;
            font-size: 2em;
        }

        ul {
            list-style: none; /* Remove HTML bullets */
            padding: 0;
            margin: 0;
        }

        li {
            list-style: none; /* Remove HTML bullets */
            padding: 0;
            margin: 0;
        }
    </style>
    <script>
            jQuery("#upgrade_Btn1").click(function(){
                jQuery("#myModal").css("display","block");
            });
            jQuery("#upgrade_Btn2").click(function(){
                jQuery("#myModal").css("display","block");
            });
            jQuery("#upgrade_Btn3").click(function(){
                jQuery("#myModal").css("display","block");
            });
            jQuery(".close").click(function(){
                jQuery("#myModal").css("display","none");
            });
        </script>
    <?php
}


function addOn()
{
    ?>
    <div class="mo_boot_row mo_boot_my-3 mo_boot_m-1" style="background:white;">
        <div class="mo_boot_col-sm-12">
            <h2>AddOn's</h2>
            <hr>
        </div>
        <div class="mo_boot_col-sm-5 mo_boot_m-4 add_on_box">
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_boot_py-4 mo_boot_text center">
                <div class="mo_boot_col-sm-4">
                    <img src="<?php  echo JURI::root(); ?>administrator/components/com_miniorange_oauth/assets/images/miniorange.png" width="100px" height="100px">
                </div>
                <div class="mo_boot_col-sm-8">
                    <h3 style="margin-top:1em;">Sweet Alert</h3>
                    <p>Sweet Alert add-on helps you to get every message's in a very pretty modal window</p>
                    <a href="https://www.miniorange.com/contact" class="mo_boot_btn mo_boot_btn-primary mo_boot_py-0" target="_blank" style="text-decoration:none;">Know More</a>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-5 mo_boot_m-4 add_on_box">
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_boot_py-4 mo_boot_text center">
                <div class="mo_boot_col-sm-4">
                    <img src="<?php  echo JURI::root(); ?>administrator/components/com_miniorange_oauth/assets/images/discord.png" width="100px" height="100px">
                </div>
                <div class="mo_boot_col-sm-8">
                    <h3 style="margin-top:1em;">Discord Role Mapping</h3>
                    <p>Discord Role Mapping add-on helps you to get roles from your discord server and maps it to Joomla user while SSO.</p>
                    <a href="https://www.miniorange.com/contact" class="mo_boot_btn mo_boot_btn-primary mo_boot_py-0" target="_blank" style="text-decoration:none;">Know More</a>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-5 mo_boot_m-4 add_on_box">
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 mo_boot_py-2 mo_boot_pb-4 mo_boot_text center">
                <div class="mo_boot_col-sm-4">
                    <img style="margin-top:2em;" src="<?php  echo JURI::root(); ?>administrator/components/com_miniorange_oauth/assets/images/scim-icon.png" width="100px" height="100px">
                </div>
                <div class="mo_boot_col-sm-8">
                    <h3 style="margin-top:2em;">Real Time User Provisioning with SCIM</h3>
                    <p>This plugin allows user provisioning with SCIM standard. System for Cross-domain Identity Management is a standard for automating the exchange of user identity information between identity domains, or IT systems.</p>
                    <a href="https://plugins.miniorange.com/joomla-scim-user-provisioning" class="mo_boot_btn mo_boot_btn-primary mo_boot_py-0" target="_blank" style="text-decoration:none;">Know More</a>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-5 mo_boot_m-4 add_on_box">
            <div class="mo_boot_row mo_boot_text-center mo_boot_m-1 mo_boot_my-3 mo_boot_py-4">
                <div class="mo_boot_col-sm-4">
                    <img src="<?php echo JURI::root(); ?>administrator/components/com_miniorange_oauth/assets/images/page-restriction.png" width="100px" height="100px">
                </div>
                <div class="mo_boot_col-sm-8">
                    <h3 style="margin-top:1em;">Page Restriction</h3>
                    <p>Allows to restrict access to Joomla users based on their login status, thereby preventing them from unauthorized access.</p>
                    <a href="https://www.miniorange.com/contact" target="_blank" class="mo_boot_btn mo_boot_btn-primary mo_boot_py-0" style="background:#2384d3;text-decoration:none;">Know More</a>
                </div>
            </div>
        </div>
    </div>
    <style>
        .add_on_box{
            border:1px solid lightgray;
            box-shadow:0px 0px 10px 0px gray;
        }
        .add_on_box:hover{
            transform:scale(1.01);
        }

    </style>
    <?php
}
function mo_oauth_support($buttonIndex = "")
{
    ?>
    <div class="mo_boot_row mo_boot_mr-1 mo_boot_mb-3 mo_boot_mt-3" style="background:white;">
        <?php
            $current_user = JFactory::getUser();
            $result = MoOAuthUtility::getCustomerDetails();
            $admin_email = empty(trim($result['email']))?$current_user->email:$result['email'];
            $admin_phone = $result['admin_phone'];
        ?>
        <form name="f" style="width:100%;" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.contactUs'); ?>">
            <div class="mo_boot_col-sm-12">
                <h3>Support</h3>
                <hr>
            </div>
            <div class="mo_boot_col-sm-12">
                <p style="background-color: #e2e6ea; padding: 10px;">Need any help? Just send us a query and we will get back to you soon.</p>
                <br>
            </div>
            <div class="mo_boot_col-sm-12">
                <div class="mo_boot_row">
                    <div class="mo_boot_col-sm-2">
                        <strong>Email:<span style="color: red;">*</span></strong>
                    </div>
                    <div class="mo_boot_col-sm-10">
                        <input type="email" class="mo_boot_form-control oauth-table" style="border-radius:4px;resize: vertical;width:100%" id="query_email" name="query_email" value="<?php echo $admin_email; ?>" placeholder="Enter your email" required />
                    </div>
                </div>
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-2"> <strong>Mobile no.</strong></div>
                    <div class="mo_boot_col-sm-10">
                        <input type="text" class="mo_boot_form-control oauth-table" style="border-radius:4px;resize: vertical;width:100%" name="query_phone" id="query_phone" value="<?php echo $admin_phone; ?>" placeholder="Enter your phone with country code"/>
                    </div>
                </div>
                <div class="mo_boot_row">
                    <div class="mo_boot_col-sm-2"><strong>Query</strong></div>
                    <div class="mo_boot_col-sm-10">
                        <textarea id="query" name="query" style="border-radius:4px;resize: vertical;width:100%" cols="52" rows="6" onkeyup="mo_otp_valid(this)" onblur="mo_otp_valid(this)" onkeypress="mo_otp_valid(this)" placeholder="Write your query here" required></textarea>
                    </div>
                </div>
                <div class="mo_boot_row mo_boot_text-center">
                    <div class="mo_boot_col-sm-12">
                        <input type="submit" name="send_query" id="send_query" value="Submit Query" class="mo_boot_btn mo_boot_btn-success"/>
                    </div>
                </div>
            </div>
        </form>
        <div hidden id="mosaml-feedback-overlay"></div>
        <br/>
    </div>
    <?php
}
