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


// Load all photos, in ascending order of date taken (oldest first)
$photos = array();
$page = 1;
do {
    $result = $flickr->photos_search(array('user_id' => 'me', 'per_page' => 500, 'page' => $page++, 'sort' => 'date-taken-asc', 'extras' => 'date_taken,date_upload'));
    if ( $result === false ) {
        include('header.php');
        ?>Encountered an error while getting data: <?php echo $flickr->getErrorMsg() ?>.<?php
        include('footer.php');
        exit;
    }
    $photos = array_merge($photos, $result['photo']);
} while ( count($result['photo']) == 500 );


header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="sortmyphotostream-backup.dat"');

echo serialize($photos);

?>