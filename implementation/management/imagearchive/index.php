<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/common.php');
load_common_include_files("$ADMIN_DIR/imagearchive");
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Image.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/ImageSearch.php');
require_once($_SERVER['DOCUMENT_ROOT']."/$ADMIN_DIR/CampsiteInterface.php");
require_once($_SERVER['DOCUMENT_ROOT']."/$ADMIN_DIR/imagearchive/include.inc.php");

list($access, $User) = check_basic_access($_REQUEST);
if (!$access) {
	header("Location: /$ADMIN/logout.php");
	exit;
}

// Initialize input variables ///////////////////////////////////////////////////
$OrderBy = array_get_value($_REQUEST, 'order_by', 'id');
$OrderDirection = array_get_value($_REQUEST, 'order_direction', 'ASC');
$view = array_get_value($_REQUEST, 'view', 'thumbnail');
$ImageOffset = array_get_value($_REQUEST, 'image_offset', 0);
$SearchDescription = array_get_value($_REQUEST, 'search_description', '');
$SearchPhotographer = array_get_value($_REQUEST, 'search_photographer', '');
$SearchPlace = array_get_value($_REQUEST, 'search_place', '');
$SearchDate = array_get_value($_REQUEST, 'search_date', '');
$SearchInUse = array_get_value($_REQUEST, 'search_inuse', '');
$SearchUploadedBy = array_get_value($_REQUEST, 'search_uploadedby', '');

$imageNav =& new ImageNav($_REQUEST, CAMPSITE_IMAGEARCHIVE_IMAGES_PER_PAGE, $view);

///////////////////////////////////////////////////////////////////////

// build the links for ordering (search results) //////////////////////
if ($OrderDirection == 'DESC') {
	$ReverseOrderDirection = "ASC";
	$OrderSign = "<img src=\"/$ADMIN/img/icon/search_order_direction_down.gif\" border=\"0\">";
} else {
	$ReverseOrderDirection = "DESC";
	$OrderSign = "<img src=\"/$ADMIN/img/icon/search_order_direction_up.gif\" border=\"0\">";
}

$IdHref  = 'index.php?order_by=id'
	.$imageNav->getKeywordSearchLink();
$DescriptionHref  = 'index.php?order_by=description'
	.$imageNav->getKeywordSearchLink();
$PhotographerHref  = 'index.php?order_by=photographer'
	.$imageNav->getKeywordSearchLink();
$PlaceHref  = 'index.php?order_by=place'
	.$imageNav->getKeywordSearchLink();
$DateHref  = 'index.php?order_by=date'
	.$imageNav->getKeywordSearchLink();
$InUseHref = 'index.php?order_by=inuse'
	.$imageNav->getKeywordSearchLink();
///////////////////////////////////////////////////////////////////////
$DescriptionOrderIcon = '';
$PhotographerOrderIcon = '';
$PlaceOrderIcon = '';
$DateOrderIcon = '';
$InUseOrderIcon = '';
$IdOrderIcon = '';
switch ($OrderBy) {
case 'description':
	$DescriptionOrderIcon = $OrderSign;
	$DescriptionHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'photographer':
	$PhotographerOrderIcon = $OrderSign;
	$PhotographerHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'place':
	$PlaceOrderIcon = $OrderSign;
	$PlaceHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'date':
	$DateOrderIcon = $OrderSign;
	$DateHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'inuse':
	$InUseOrderIcon = $OrderSign;
	$InUseHref .= '&order_direction='.$ReverseOrderDirection;
	break;
case 'id':
	$IdOrderIcon = $OrderSign;
	$IdHref .= '&order_direction='.$ReverseOrderDirection;
	break;
}
///////////////////////////////////////////////////////////////////////

$TotalImages = Image::GetTotalImages();
$imageSearch =& new ImageSearch($_REQUEST, CAMPSITE_IMAGEARCHIVE_IMAGES_PER_PAGE);
$imageSearch->run();
$imageData =& $imageSearch->getImages();
$NumImagesFound = $imageSearch->getNumImagesFound();
$uploadedByUsers =& Image::GetUploadUsers();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
	"http://www.w3.org/TR/REC-html40/loose.dtd">
<HTML>
<HEAD>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<META HTTP-EQUIV="Expires" CONTENT="now">
	<TITLE><?php  putGS('Images'); ?></TITLE>
	<LINK rel="stylesheet" type="text/css" href="<?php echo $Campsite['website_url'] ?>/css/admin_stylesheet.css">
</HEAD>
<BODY  BGCOLOR="WHITE" TEXT="BLACK" LINK="DARKBLUE" ALINK="RED" VLINK="DARKBLUE">

<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="1" WIDTH="100%" class="page_title_container">
<TR>
	<TD class="page_title">
		<?php putGS('Image archive'); ?>
	</TD>
</TR>
</TABLE>
<!--<HR NOSHADE SIZE="1" COLOR="BLACK">-->

<table>
  <tr>
<?php
if ($User->hasPermission('AddImage')) { ?>
    <td><A HREF="add.php?<?php echo $imageNav->getSearchLink(); ?>"><IMG SRC="/<?php echo $ADMIN; ?>/img/tol.gif" BORDER="0" alt="<?php  putGS('Add new image'); ?>"></A></TD><TD><A HREF="add.php?<?php echo $imageNav->getSearchLink(); ?>"><B><?php  putGS('Add new image'); ?></B></A></TD>
<?php } ?>
    
    <td><a href="index.php?view=<?php echo $view; ?>"><IMG SRC="/<?php echo $ADMIN; ?>/img/tol.gif" BORDER="0" ALT="<?php putGS("Reset search conditions"); ?>"></a></td><td><a href="index.php?view=<?php echo $view; ?>"><b><?php echo putGS('Reset search conditions'); ?></b></a></td>

    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><B><?php putGS('View', 'View'); ?>:</b></td>
    <td><A HREF="index.php?<?php echo $imageNav->getSearchLink(); ?>&view=thumbnail"><IMG SRC="/<?php echo $ADMIN; ?>/img/tol.gif" BORDER="0" alt="<?php  putGS('Thumbnail'); ?>"></A></TD><TD><A HREF="index.php?<?php echo $imageNav->getSearchLink(); ?>&view=thumbnail"><B><?php putGS('Thumbnail'); ?></B></A></TD>
    <td><A HREF="index.php?<?php echo $imageNav->getSearchLink(); ?>&view=gallery"><IMG SRC="/<?php echo $ADMIN; ?>/img/tol.gif" BORDER="0" alt="<?php  putGS('Gallery'); ?>"></A></TD><TD><A HREF="index.php?<?php echo $imageNav->getSearchLink(); ?>&view=gallery"><B><?php putGS('Gallery'); ?></B></A></TD>
    <td><A HREF="index.php?<?php echo $imageNav->getSearchLink(); ?>&view=flat"><IMG SRC="/<?php echo $ADMIN; ?>/img/tol.gif" BORDER="0" alt="<?php  putGS('Text only'); ?>"></A></TD><TD><A HREF="index.php?<?php echo $imageNav->getSearchLink(); ?>&view=flat"><B><?php  putGS('Text only'); ?></B></A></TD>
  </tr>
</table>

<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="3" style="border: 1px solid #00008b; margin-bottom: 10px; margin-top: 5px;" align="center">
<form method="POST" action="index.php">
<input type="hidden" name="order_by" value="<?php echo $OrderBy; ?>">
<input type="hidden" name="order_direction" value="<?php echo $OrderDirection; ?>">
<input type="hidden" name="view" value="<?php echo $view; ?>">
<input type="hidden" name="image_offset" value="0">
<tr BGCOLOR="#C0D0FF">
	<td style="padding-left: 10px; color: #00008b;"><?php putGS('Description')?>:</td>
	<td><input type="text" name="search_description" value="<?php echo $SearchDescription; ?>" style="border: 1px solid #00008b; background-color: #f0f0ff; text-indent: 3px; width: 150px;"></td>
	<td style="color: #00008b;"><?php putGS('Photographer'); ?>:</td>
	<td><input type="text" name="search_photographer" value="<?php echo $SearchPhotographer; ?>"  style="border: 1px solid #00008b; background-color: #f0f0ff; text-indent: 3px; width: 100px;"></td>
	<td style="color: #00008b;"><?php putGS('Place'); ?>:</td>
	<td><input type="text" name="search_place" value="<?php echo $SearchPlace; ?>" style="border: 1px solid #00008b; background-color: #f0f0ff; text-indent: 3px; width: 100px;"></td>
	<td style="color: #00008b;"><?php putGS('Date'); ?>:</td>
	<td><input type="text" name="search_date" value="<?php echo $SearchDate; ?>" style="border: 1px solid #00008b; background-color: #f0f0ff; text-indent: 3px; width: 80px;"></td>
	<td style="color: #00008b;" nowrap>Uploaded by:</td>
	<td>
		<select name="search_uploadedby" class="input_select" style="width: 100px;">
		<option value="0"></option>
		<?php 
		foreach ($uploadedByUsers as $tmpUser) {
			?>
			<option value="<?php echo $tmpUser->getId(); ?>" <?php if ($tmpUser->getId() == $SearchUploadedBy)  { echo "selected"; } ?>><?php echo htmlspecialchars($tmpUser->getName()); ?></option>
			<?php
		}
		?>
		</select>
	</td>
	<td><input type="submit" name="submit_button" value="Search" class="button"></td>
</tr>
<tr BGCOLOR="#C0D0FF">
	<td colspan="11" align="center" >
		Additional searches: &nbsp;
		<a href="index.php?<?php $imageNav->getSearchLink() ?>&order_by=time_created" style="font-size: 9pt; font-weight: bold; text-decoration: underline;">Most Recently Added</a><?php if ($OrderBy == "time_created") { echo "*"; } ?>
		&nbsp;
		<a href="index.php?<?php $imageNav->getSearchLink() ?>&order_by=last_modified" style="font-size: 9pt; font-weight: bold; text-decoration: underline;">Most Recently Modified</a><?php if ($OrderBy == "last_modified") { echo "*"; } ?>
	</td>
	
</tr>
</form>
</table>

<?php
if (count($imageData) > 0) {
    switch ($view) {
    case 'flat':
    	include('view_flat.inc.php');
    	break;
    case 'gallery':
    	include('view_gallery.inc.php');
    	break;
    case 'thumbnail':
    default:
    	include('view_thumbnail.inc.php');
        break;
    }

} else {
    ?><BLOCKQUOTE><LI><?php  putGS('No images.'); ?></LI></BLOCKQUOTE>
<?php
}
?>
<?php CampsiteInterface::CopyrightNotice(); ?>