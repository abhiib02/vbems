<?php

namespace App\Controllers;

use Config\Services;

use App\Models\User;

class EmailController extends BaseController {
    protected $helpers = ['form'];

    public $data, $UserModel;

    /* =================================== */
    //
    //  All SMTP setting is in 'app/config/Email.php';
    //
    /* =================================== */

    public function __construct() {
        $this->session = \Config\Services::session();
        $this->UserModel = new User();
    }
    /*----------- reset password ---------------*/
    public function reset_password_mail($usermail, $generatedToken) {

        $token = $generatedToken;
        $to = $usermail;

        $from = env('no_reply_email');
        $subject = env('app.name') . ' | Reset Password Link';

        $url = base_url() . 'resetform?token=' . $token;

        $message = $this->reset_mail_email_template($url, env('app.name'), env('appLogoWebPath'));

        $email = Services::email();
        $email->setTo($to);
        $email->setFrom($from, env('app.name'));

        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) {
            return $this->RedirectWithtoast('Reset Password Link Sent to Mail', 'Success', 'auth.login');            
        } else {
            //$data = $email->printDebugger(['headers']);
            //print_r($data);
            return $this->RedirectWithtoast('Some Error Occured', 'Danger', 'auth.login');
        }
    }
    public function reset_mail_email_template($url, $name, $logo) {
        $message = '<!DOCTYPE html>
                    <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
                    <head>
                        <title></title>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                        <meta name="viewport" content="width=device-width,initial-scale=1">
                        <!--[if mso]>
                        <xml>
                            <o:OfficeDocumentSettings>
                                <o:PixelsPerInch>96</o:PixelsPerInch>
                                <o:AllowPNG/>
                            </o:OfficeDocumentSettings>
                        </xml>
                        <![endif]--><!--[if !mso]><!-->
                        <link
                            href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700;900&amp;display=swap" rel="stylesheet" type="text/css">
                        <!--<![endif]-->
                        <style>
                            *{box-sizing:border-box}body{margin:0;padding:0}a[x-apple-data-detectors]{color:inherit!important;text-decoration:inherit!important}#MessageViewBody a{color:inherit;text-decoration:none}p{line-height:inherit}.desktop_hide,.desktop_hide table{mso-hide:all;display:none;max-height:0;overflow:hidden}.image_block img+div{display:none} @media (max-width:770px){.mobile_hide{display:none}.row-content{width:100%!important}.stack .column{width:100%;display:block}.mobile_hide{min-height:0;max-height:0;max-width:0;overflow:hidden;font-size:0}.desktop_hide,.desktop_hide table{display:table!important;max-height:none!important}.row-2 .column-1{padding:30px!important}.row-3 .column-1{padding:0 30px!important}}
                        </style>
                    </head>
                    <body class="body" style="background-color:#e9e9e9;margin:0;padding:0;-webkit-text-size-adjust:none;text-size-adjust:none">
                        <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#e9e9e9">
                            <tbody>
                                <tr>
                                <td>
                                    <table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                        style="mso-table-lspace:0;mso-table-rspace:0;background-color:#0068a5">
                                        <tbody>
                                            <tr>
                                            <td>
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;color:#000;width:750px;margin:0 auto" width="750">
                                                    <tbody>
                                                        <tr>
                                                        <td class="column column-1" width="100%"
                                                            style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:20px;padding-top:20px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                            <table class="text_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                <tr>
                                                                    <td class="pad">
                                                                    <div style="font-family:sans-serif">
                                                                        <div class
                                                                            style="font-size:14px;font-family:Roboto,Tahoma,Verdana,Segoe,sans-serif;mso-line-height-alt:16.8px;color:#0068a5;line-height:1.2">
                                                                            <p style="margin:0;font-size:14px;text-align:center;mso-line-height-alt:16.8px"><span style="font-size:24px;"><strong>' . $name . '</strong></span></p>
                                                                        </div>
                                                                    </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                        style="mso-table-lspace:0;mso-table-rspace:0;background-color:#0068a5">
                                        <tbody>
                                            <tr>
                                            <td>
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;border-radius:0;color:#000;width:750px;margin:0 auto" width="750">
                                                    <tbody>
                                                        <tr>
                                                        <td class="column column-1" width="100%"
                                                            style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:30px;padding-top:30px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                            <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                                                <tr>
                                                                    <td class="pad" style="width:100%">
                                                                    <div class="alignment" align="center" style="line-height:10px">
                                                                        <div style="max-width:180px"><img
                                                                            src="' . $logo . '" style="display:block;height:auto;border:0;width:100%" width="180" height="auto"></div>
                                                                    </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                        style="mso-table-lspace:0;mso-table-rspace:0;background-color:#0068a5">
                                        <tbody>
                                            <tr>
                                            <td>
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;border-radius:0;color:#000;width:750px;margin:0 auto" width="750">
                                                    <tbody>
                                                        <tr>
                                                        <td class="column column-1" width="100%"
                                                            style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                            <table class="text_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                <tr>
                                                                    <td class="pad">
                                                                    <div style="font-family:sans-serif">
                                                                        <div class
                                                                            style="font-size:14px;font-family:Roboto,Tahoma,Verdana,Segoe,sans-serif;mso-line-height-alt:16.8px;color:#0068a5;line-height:1.2">
                                                                            <p style="margin:0;font-size:14px;text-align:center;mso-line-height-alt:16.8px"><span style="font-size:26px;"><strong>FORGOT YOUR PASSWORD</strong></span></p>
                                                                        </div>
                                                                    </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                <tr>
                                                                    <td class="pad" style="padding-top:15px">
                                                                    <div style="font-family:sans-serif">
                                                                        <div class style="font-size:14px;font-family:Roboto,Tahoma,Verdana,Segoe,sans-serif;mso-line-height-alt:16.8px;color:#000;line-height:1.2">
                                                                            <p style="margin:0;font-size:14px;text-align:center;mso-line-height-alt:16.8px"><span style="font-size:16px;">We have received a request to reset your password.&nbsp;We’re here to help!&nbsp;</span></p>
                                                                        </div>
                                                                    </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table class="text_block block-3" width="100%"
                                                                border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                                <tr>
                                                                    <td class="pad" style="padding-top:5px">
                                                                    <div style="font-family:sans-serif">
                                                                        <a href="" class style="font-size:14px;font-family:Roboto,Tahoma,Verdana,Segoe,sans-serif;mso-line-height-alt:16.8px;color:#000;line-height:1.2">
                                                                            <p style="margin:0;font-size:14px;text-align:center;mso-line-height-alt:16.8px">
                                                                                <span style="font-size:16px;">Simply click on the button to set a new password!</span>
                                                                            </p>
                                                                        </a>
                                                                    </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#0068a5">
                                        <tbody>
                                            <tr>
                                            <td>
                                                <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                                    style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff;border-radius:0;color:#000;width:750px;margin:0 auto" width="750">
                                                    <tbody>
                                                        <tr>
                                                        <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:30px;padding-top:30px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                            <table class="button_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                                                                style="mso-table-lspace:0;mso-table-rspace:0">
                                                                <tr>
                                                                    <td class="pad" style="text-align:center">
                                                                    <div class="alignment" align="center">
                                                                        <!--[if mso]>
                                                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{url}" style="height:38px;width:188px;v-text-anchor:middle;" arcsize="11%" stroke="false" fillcolor="#0068a5">
                                                                            <w:anchorlock/>
                                                                            <v:textbox inset="0px,0px,0px,0px">
                                                                                <center dir="false" style="color:#ffffff;font-family:Tahoma, Verdana, sans-serif;font-size:14px">
                                                                                <![endif]-->
                                                                                <a href="' . $url . '" target="_blank" style="background-color:#0068a5;border-bottom:0px solid transparent;border-left:0px solid transparent;border-radius:4px;border-right:0px solid transparent;border-top:0px solid transparent;color:#ffffff;display:inline-block;font-family:Roboto, Tahoma, Verdana, Segoe, sans-serif;font-size:14px;font-weight:400;mso-border-alt:none;padding-bottom:5px;padding-top:5px;text-align:center;text-decoration:none;width:auto;word-break:keep-all;"><span style="padding-left:30px;padding-right:30px;font-size:14px;display:inline-block;letter-spacing:normal;"><span style="word-break: break-word; line-height: 28px;">Set a New Password</span></span></a>
                                                                                <!--[if mso]>
                                                                                </center>
                                                                            </v:textbox>
                                                                        </v:roundrect>
                                                                        <![endif]-->
                                                                    </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                </tr>
                            </tbody>
                        </table>';
        return $message;
    }
    /*----------- employee leave mail ---------------*/
    public function employee_leave_mail($data) {

        $to = env('contact_form_receiver_email');
        $from = env('no_reply_email');
        $subject = $data['NAME'] . ' | ' . $data['EMAIL'] . ' | Applied for Leave Request';
        $message = $this->employee_leave_mail_template($data);

        $email = Services::email();
        $email->setTo($to);
        $email->setFrom($from, env('app.name'));

        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) {
            return 1;
        } else {
            //$data = $email->printDebugger(['headers']);
            //print_r($data);
            return 0;
        }
    }
    public function employee_leave_mail_template($data) {
        $message = "
                    <!DOCTYPE html>
                            <html>
                            <head>
                                <style>
                                    table {
                                        width:100%;
                                        border:1px solid #b3adad;
                                        border-collapse:collapse;
                                        padding:5px;
                                    }
                                    table th {
                                        border:1px solid #b3adad;
                                        padding:5px;
                                        background: #f0f0f0;
                                        color: #313030;
                                    }
                                    table td {
                                        border:1px solid #b3adad;
                                        text-align:center;
                                        padding:5px;
                                        background: #ffffff;
                                        color: #313030;
                                    }
                                </style>
                            </head>
                            <body>
                    <table>
                        <tr>
                            <td>ID</td>
                            <td>" . $data['USER_ID'] . "</td>
                        </tr>
                        <tr>
                            <td>NAME</td>
                            <td>" . $data['NAME'] . "</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>" . $data['EMAIL'] . "</td>
                        </tr>
                        <tr>
                            <td>FROM_DATE</td>
                            <td>" . $data['FROM_DATE'] . "</td>
                        </tr>
                        <tr>
                            <td>TO_DATE</td>
                            <td>" . $data['TO_DATE'] . "</td>
                        </tr>
                        <tr>
                            <td>DAYS</td>
                            <td>" . $data['DAYS'] . " Days</td>
                        </tr>
                        
                        <tr>
                            <td>TYPE</td>
                            <td>" . $data['TYPE'] . "</td>
                        </tr>
                        <tr>
                            <td>ZERO LEAVE CREDIT</td>
                            <td>" . $data['ZEROLEAVECREDIT'] . "</td>
                        </tr>
                        <tr>
                            <td>REASON</td>
                            <td>" . $data['REASON'] . "</td>
                        </tr>
                    </table>
                    </body>
                            </html>
        ";
        return $message;
    }
    /*----------- admin leave request approve/reject mail ---------------*/
    public function leaveRequestProcessed_mail($data, $status) {

        $EmployeeEmail = $this->UserModel->getUserEmailByID($data->USER_ID);
        $to = $EmployeeEmail;
        $from = env('no_reply_email');
        if ($status == 1) {
            $subject = "Request for Leave on ($data->FROM_DATE TO $data->TO_DATE) is Approved";
        }
        if ($status == 0) {
            $subject = "Request for Leave on ($data->FROM_DATE TO $data->TO_DATE) is Rejected";
        }

        $message = $this->leaveRequestProcessed_mail_template($data);

        $email = Services::email();
        $email->setTo($to);
        $email->setFrom($from, env('app.name'));

        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) {
            return 1;
        } else {
            //$data = $email->printDebugger(['headers']);
            //print_r($data);
            return 0;
        }
    }
    public function leaveRequestProcessed_mail_template($data) {
        $message = "
                    <!DOCTYPE html>
                            <html>
                            <head>
                                <style>
                                    table {
                                        width:100%;
                                        border:1px solid #b3adad;
                                        border-collapse:collapse;
                                        padding:5px;
                                    }
                                    table th {
                                        border:1px solid #b3adad;
                                        padding:5px;
                                        background: #f0f0f0;
                                        color: #313030;
                                    }
                                    table td {
                                        border:1px solid #b3adad;
                                        text-align:center;
                                        padding:5px;
                                        background: #ffffff;
                                        color: #313030;
                                    }
                                    .Approved{
                                        background:#2EBD85 !important;
                                    }
                                    .Pending{
                                        background:#FCD535 !important;
                                    }
                                    .Rejected{
                                        background:#F6465D !important;
                                    }    
                                </style>
                            </head>
                            <body>
                    <table>
                        <tr>
                            <td>FROM_DATE</td>
                            <td>" . $data->FROM_DATE . "</td>
                        </tr>
                        <tr>
                            <td>TO_DATE</td>
                            <td>" . $data->TO_DATE . "</td>
                        </tr>
                        <tr>
                            <td>DAYS</td>
                            <td>" . $data->DAYS . " Days</td>
                        </tr>
                        <tr>
                            <td>TYPE</td>
                            <td>" . $data->TYPE . "</td>
                        </tr>
                        <tr>
                            <td>STATUS</td>
                            <td class=" . $data->STATUS . ">" . $data->STATUS . "</td>
                        </tr>
                    </table>
                    </body>
                            </html>
        ";
        return $message;
    }
}
