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

include('header.php'); 
?>
<p>This tool will sort your Flickr photostream by the date your photos were taken.  This is done by setting the 'posted'
    date of all of your photos to the date they were taken.</p>
    
<p>For those photos that were taken before your Flickr account was created, their 'posted' date will be set to an arbitrary
    date after the date of your Flickr account creation.</p>
    
<p>Please note, this will be applied to <i>all</i> of your photos, and cannot be undone.  You can make a 
    <a href="/backup.php">backup</a> first if you wish, and <a href="/restore.php">restore</a> later.</p>

<p>Press the button below to proceed: You will be taken to Flickr for authorisation, then you will be returned here
    to confirm whether you wish to proceed.</p>
    
<div class="button"><a href="/apply.php">Proceed</a></div>

<?php include('footer.php'); ?>