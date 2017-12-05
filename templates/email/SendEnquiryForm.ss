<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Enquiry Submission</title>

    <style type="text/css">
        h1 {

        }

        body {
            margin: 0;
            padding: 0;
        }

        img {
            border: 0 none;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        a img {
            border: 0 none;
        }

        .imageFix {
            display: block;
        }

        table, td {
            border-collapse: collapse;
        }

        table, td, th {
            text-align: left;
        }

        #bodyTable {
            height: 100% !important;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }

        .composite {
            display: block;
            border: 1px solid #ccc;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -ms-border-radius: 4px;
            border-radius: 4px;
            background-clip: padding-box;
            padding: 15px;
            margin: 15px 0 30px;
            position: relative;
        }

        .composite .box-title {
            padding: 0 4px;
            border: 0;
            width: auto;
            font-weight: 700;
            text-transform: uppercase;
            top: -9px;
            background: #fff;
            position: absolute;
        }

        .box-content {
            color: #000;
        }

        .box-content a {
            color: #000;
            text-decoration: none;
            padding-top: 5px;
        }

        .box-content a:hover {
            color: #000;
            text-decoration: underline;
        }

        dl {

        }

        .box-content-title {
            font-weight: bold;
            width: 40%;
        }


    </style>
</head>
<body>

<table class="table" style="text-align: center">
    <tr>
        <td>

            <table id="container" cellpadding="0" cellspacing="0" border="0" style="width: 800px;">
                <tr>
                    <td>
                        <% include EmailHeader %>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table id="Content" cellspacing="0" cellpadding="0" summary="Email Information"
                               style="width: 750px;">

                            <tbody>
                            <tr>
                                <td scope="row" class="typography">
                                    <h1 class="title">Property Enquiry Submission</h1>
                                    <div class="composite">
                                        <div class="box-title">{$Subject}</div>
                                        <div class="box-content">

                                            <table class="table" style="width: 100%;text-align: center">
                                                <tr>
                                                    <td class="box-content-title">
                                                        Name
                                                    </td>
                                                    <td>
                                                        {$Name}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="box-content-title">
                                                        Email
                                                    </td>
                                                    <td>
                                                        {$Email}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="box-content-title">
                                                        Number of adults
                                                    </td>
                                                    <td>
                                                        {$AdultsQuantity}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="box-content-title">
                                                        Number of children
                                                    </td>
                                                    <td>
                                                        {$ChildrenQuantity}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="box-content-title">
                                                        Arrival date
                                                    </td>
                                                    <td>
                                                        {$ArrivalDate}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="box-content-title">
                                                        Departure date
                                                    </td>
                                                    <td>
                                                        {$DepartureDate}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="box-content-title">
                                                        Telephone number
                                                    </td>
                                                    <td>
                                                        {$Telephone}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="box-content-title">
                                                        Country of origin
                                                    </td>
                                                    <td>
                                                        {$Country}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="box-content-title">
                                                        Message
                                                    </td>
                                                    <td>
                                                        {$Message}
                                                    </td>
                                                </tr>

                                            </table>

                                        </div>
                                    </div>
                                </td>
                            </tr>

                            </tbody>

                        </table>
                    </td>
                </tr>
                <tfoot>
                <tr>
                    <td><% include EmailFooter %></td>
                </tr>
                </tfoot>
            </table>

        </td>
    </tr>
</table>
</body>
</html>
