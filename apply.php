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
    
if ( !isset($_REQUEST['confirm']) ) :
?>

<h2>Confirmation</h2>

<p>Are you sure you wish to proceed?  This will set the 'posted' date of all of your photos in your Flickr account
    to the date they were taken.</p>
    
<p>If you wish, you can download a <a href="/backup.php">backup of the photo metadata</a>, which can be <a href="/restore.php">restored</a> later.</p>
    
<p>This operation may take a few minutes to complete. Please do not interrupt your browser.</p>
    
<div class="button"><a href="/apply.php?confirm">Proceed</a></div>

<?php 
include('footer.php');
exit;
endif;

// Determine the id of the user
$result = $flickr->test_login();
if ( $result === false ) {
    doError($flickr->getErrorMsg());
    exit;
}
$userId = $result['id'];

// Get the date the first photo was uploaded to this account
$result = $flickr->people_getInfo($userId);
if ( $result === false ) {
    doError($flickr->getErrorMsg());
    exit;
}
$firstUploadedDate = $result['photos']['firstdate'];
$url = $result['photosurl'];

set_time_limit(0);
$counter = $firstUploadedDate;

// Load all photos, in ascending order of date taken (oldest first)
$photos = array();
$page = 1;
do {
    $result = $flickr->photos_search(array('user_id' => 'me', 'per_page' => 500, 'sort' => 'date-taken-asc', 'extras' => 'date_taken,date_upload', 'page' => $page++));
    if ( $result === false ) {
        doError($flickr->getErrorMsg());
        exit;
    }
    $photos = $result['photo'];
    
    // Apply dates
    foreach ( $photos as $photo ) {
        
        $date = strtotime($photo['datetaken']);
        if ( $date < $counter ) {
            $date = $counter++;
        }
        
        if ( $photo['dateupload'] != $date ) {
            $result = $flickr->photos_setDates($photo['id'], $date);

            if ( $result === false ) {
                doError($flickr->getErrorMsg());
                exit;
            }
        }
    }
} while ( count($photos) == 500 );

?>
<h2>Finished</h2>
<p class="center">Your <a href="<?php echo $url ?>">photostream</a> has now been sorted.</p>

<?php
include('footer.php');
?>