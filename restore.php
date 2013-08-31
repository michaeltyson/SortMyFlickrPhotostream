<?php
/**
 * SortMyPhotoStream
 *
 * @author Michael Tyson <michael@tyson.id.au>
 * @version 0.1.4
 *
 *  Copyright 2008 Michael Tyson <michael@tyson.id.au>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 
require_once('includes/config.inc.php');
require_once('phpFlickr/phpFlickr.php');

$flickr = new phpFlickr(API_KEY, API_SECRET);
$flickr->auth('write');


function doError($message) {
    ?>
    <h2>Something went wrong</h2>
    <p>An error occurred: <i><?php echo $message; ?></i></p>
    </body></html>
    <?php
}

include('header.php');

if ( !isset($_REQUEST["submit"]) ) :
?>

<h2>Restore</h2>

<p class="center">Use this facility to restore photo metadata from a previous <a href="/backup.php">backup</a>.

<form enctype="multipart/form-data" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
<p class="center">Choose a file to upload: <input name="restorefile" type="file" /></p>
<input class="button" name="submit" type="submit" value="Restore" />

</form>

<?php
include('footer.php');
exit;
endif;

if ( !is_uploaded_file($_FILES['restorefile']['tmp_name']) ) {
    doError('Upload failed: Error '.intval($_FILES['restorefile']['error']));
    exit;
}

$photos = unserialize(file_get_contents($_FILES['restorefile']['tmp_name']));

if ( !is_array($photos) || !isset($photos[0]['id']) ) {
    doError('Invalid backup file');
    exit;
}

set_time_limit(0);
$errors = array();
$count=0;
foreach ( $photos as $photo ) {
    $result = $flickr->photos_setDates($photo['id'], $photo['dateupload']);
    if ( $result === false ) {
        $errors[] = array($photo, $flickr->getErrorMsg());
    } else {
        $count++;
    }
    
}

?>
<h2>Restore complete</h2>
<?php

if ( count($errors) > 0 ) {
    ?>
    <p>Some errors were encountered:</p>
    <ul>
    <?php
    foreach ( $errors as $error ) {
        ?><li><?php echo $error[0]['id'] ?> - <?php echo $error[1] ?></li><?php
    }
    ?>
    </ul>
    <p class="center">Metadata of <?php echo $count ?> photos was restored</p>
    <?php
} else {
    ?>
    <p class="center">The restoration completed successfully</p>
    <p class="center">Metadata of <?php echo $count ?> photos was restored</p>
    <?php
}

include('footer.php');
?>