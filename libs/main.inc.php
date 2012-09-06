<?php

require_once(__DIR__ . '/phphttpd.inc.php');

if (isset($_GET['ACTION'])) {
    switch ($_GET['ACTION']) {
    case 'view':
        if (isset($_GET['doc']) && is_file($doc = $_SERVER['DOCUMENT_ROOT'] . '/' . $_GET['doc'] . '/index.html')) {
?>
        <html>
            <head>
                <base href="<?php printf('http://%s/%s/', $_SERVER['HTTP_HOST'], $_GET['doc']); ?>" />
                <script type="text/javascript">
                function fixFrames() {
                    var frameset      = document.getElementById('content_frameset');
                    var toc_frame     = document.getElementById('toc_frame');
                    var content_frame = document.getElementById('content_frame');

                    var toc_width     = window.frames['toc'].document.body.scrollWidth;
                    var content_width = window.frames['content'].document.body.scrollWidth;

                    if (toc_width > toc_frame.scrollWidth) {
                        var max_width = Math.floor((toc_frame.scrollWidth + content_frame.scrollWidth) * 0.4);

                        frameset.cols = Math.min(toc_width, max_width) + ',*';
                    }
                }
                </script>
            </head>
            <frameset id="content_frameset" cols="250,*" onload="fixFrames()">
                <frame id="toc_frame" name="toc" src="toc.html" />
                <frame id="content_frame" name="content" src="" />
            </frameset>
        </html>
<?php
        } else {
?>
        <html>
            <head>
                <style type="text/css">
                body, table {
                    font-family:      Verdana, Arial, Helvetica, sans-serif;
                    font-size:        0.9em;
                }
                body {
                    margin:  0;
                    padding: 0;
                }
                </style>
            </head>
            <body>
                <table width="100%" height="100%">
                    <tr>
                        <td align="center" valign="middle">
                            <h1>octris documentation browser</h1>
                            <p>
                                select the documentation to view from above
                            </p>
                        </tdY
                    </tr>
                </table>
            </body>
        </html>
<?php
        }
        exit(0);
        break;
    case 'nav':
        $dirs = glob($_SERVER['DOCUMENT_ROOT'] . '/*', GLOB_ONLYDIR);
?>
        <html>
            <head>
                <style type="text/css">
                body {
                    font-family:      Verdana, Arial, Helvetica, sans-serif;
                    font-size:        0.9em;
                    margin:           0;
                    padding:          0;
                    background-color: #444;
                }
                form {
                    margin:      0;
                    padding:     0;
                    line-height: 25px
                }
                #border_bottom {
                    position:   fixed;
                    background: #000;
                    bottom:     0;
                    left:       0;
                    right:      0;
                    height:     1px;
                }
                </style>
            </head>
            <body>
                <form id="browser_form" action="/" target="docs" method="GET">
                    <input type="hidden" name="ACTION" value="view" />
                    <select name="doc" onchange="this.form.submit()">
                        <option value="">-</option>
<?php
        foreach ($dirs as $dir) {
            if (is_file($dir . '/meta.json')) {
                if (!(is_null($meta = json_decode(@file_get_contents($dir . '/meta.json'), true))) && is_array($meta)) {
                    if (array_key_exists('title', $meta)) {
                        if (!($title = trim($meta['title']))) {
                            $title = basename($dir);
                        }
?>
                        <option value="<?php print basename($dir); ?>"><?php print $title; ?></option>
<?php
                    }
                }
            }
        }
?>
                    </select>
                </form>
                <div id="border_bottom" />
            </body>
        </html>
<?php
        exit(0);
        break;
    }
} elseif (preg_match('/^\/.+\//', $_SERVER['REQUEST_URI'])) {
    if (is_file($_SERVER['SCRIPT_FILENAME'])) {
        readfile($_SERVER['SCRIPT_FILENAME']);
    }
} else {
?>
<html>
    <head>
        <title>octris documentation browser</title>
    </head>
    <frameset rows="25,*" frameborder="0" framespacing="0" border="0">
        <frame src="/?ACTION=nav" scrolling="no" noresize="noresize" />
        <frame name="docs" src="/?ACTION=view" scrolling="no" />
    </frameset>
</html>
<?php
}
