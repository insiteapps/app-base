<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Newsletter Subscription</title>
</head>
<body>
<table id="container" cellpadding="0" cellspacing="0" border="0" style="width: 800px;">
    <tr>
        <td>
            <% include EmailHeader %>
        </td>
    </tr>
    <tr>
        <td>
            <table id="Content" cellspacing="0" cellpadding="0" summary="Email Information" style="width: 800px;">
                <thead>
                <tr>
                    <th scope="col" colspan="2">
                        <h1 class="title">The following email address has submitted on {$SiteConfig.Title}  Newsletter Subscription</h1>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td><% include EmailFooter %></td>
                </tr>
                </tfoot>
                <tbody>
                <tr>
                    <td scope="row" colspan="2" class="typography">
                        <div>Name: $Name</div>
                        <div>Email: <a href="mailto:$Email">$Email:</a></div>
                    </td>
                </tr>

                </tbody>


            </table>
        </td>
    </tr>

</table>
</body>
</html>
