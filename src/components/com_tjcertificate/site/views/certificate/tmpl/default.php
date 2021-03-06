<?php
/**
 * @package     TJCertificate
 * @subpackage  com_tjcertificate
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2019 Techjoomla. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Filesystem\File;

$options['relative'] = true;
HTMLHelper::_('jquery.framework');
HTMLHelper::StyleSheet('media/com_tjcertificate/vendors/font-awesome-4.1.0/css/font-awesome.min.css');
HTMLHelper::StyleSheet('media/com_tjcertificate/css/tjCertificate.css');
HTMLHelper::StyleSheet('media/com_tjlms/vendors/artificiers/artficier.css');
HTMLHelper::script('media/com_tjcertificate/vendors/html2canvas/js/html2canvas.js');
HTMLHelper::script('com_tjcertificate/certificateImage.min.js', $options);

$imageUrl = "";

if (File::exists(JPATH_SITE . '/' . $this->mediaPath . $this->fileName))
{
	$imageUrl = $this->imagePath;
}

if ($this->showSearchBox)
{
	?>
	<form action="<?php echo Route::_('index.php?option=com_tjcertificate&view=certificate'); ?>" method="post" name="adminForm" id="adminForm">
		<div class="tj-search-filters">
			<div class="btn-wrapper input-append">
				<input type="text" name="certificate" id="certificate" 
					value="<?php echo $this->uniqueCertificateId;?>" 
					placeholder="<?php echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_TEMPLATE_ENTER_CERTIFICATE_ID'); ?>">
				<button type="submit" class="btn hasTooltip" title="" aria-label="Search" data-original-title="Search">
					<span class="icon-search" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</form>
	<?php
}
?>
<?php
if ($this->certificate)
{
	$document = Factory::getDocument();
	$description = $this->item->description ? $this->item->description : $this->item->short_desc;
	$document->addScriptDeclaration("var certRootUrl = '" . JUri::root() . "'");

	// For facebook and linkedin
	$config = Factory::getConfig();
	$siteName = $config->get('sitename');
	$document->addCustomTag('<meta property="og:title" content="' . $this->escape($this->item->title) . '" />');
	$document->addCustomTag('<meta property="og:image" content="' . $this->imagePath . '" />');
	$document->addCustomTag('<meta property="og:description" content="' . $this->escape($description) . '" />');
	$document->addCustomTag('<meta property="og:site_name" content="' . $this->escape($siteName) . '" />');
	$document->addCustomTag('<meta property="og:url" content="' . $this->certificateUrl . '" />');
	$document->addCustomTag('<meta property="og:type" content="certificate" />');

	// For twitter
	$document->addCustomTag('<meta name="twitter:card" content="summary_large_image" />');
	$document->addCustomTag('<meta name="twitter:site" content="' . $siteName . '">');
	$document->addCustomTag('<meta name="twitter:title" content="' . $this->escape($this->item->title) . '">');
	$document->addCustomTag('<meta name="twitter:description" content="' . $this->escape($description) . '">');
	$document->addCustomTag('<meta name="twitter:image" content="' . $this->imagePath . '">');

?>
	<div class="tj-certificate tjBs3">
		<div class="tj-certificate-top mb-25">
			<h4 class=""><?php echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_DETAIL_VIEW_HEAD');?></h4>
		</div>
		<div class="row mb-25">
			<div class="col-xs-10">
				<h1 class="font-300 m-0"><?php echo $this->item->title; ?></h1>
			</div>
			<div class="col-xs-2">
				<a class="pull-right fs-16 font-600 cursor-pointer" onclick="window.history.back();"><i class="fa fa-arrow-left mr-10" aria-hidden="true"></i><?php echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_BACK_BUTTON');?></a>
			</div>
		</div>
		<div class="row mt-25">
			<div class="col-xs-12 col-md-8">
				<?php
					// Certificate provider info
					if (!empty($this->contentHtml))
					{
						echo $this->contentHtml;
					}
				?>
			</div>
			<div class="col-xs-12 col-md-4">
			<?php 
					if ($this->certificate->getUserId() == Factory::getUser()->id)
					{
					?>	
					<div class="tj-certificate-share-download pull-right">
						<div class="">
						<a id="download-popover" data-container="body" data-placement="bottom" tabindex="0" class="tj-certificate-btn" role="button" data-toggle="popover" data-trigger="focus" title="<?php echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_DOWNLOAD');?>"><i class="fa fa-share-square-o mr-10" aria-hidden="true"></i>
						<?php echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_DOWNLOAD');?>
						</a>
						<div id="download-popover-content" class="hide">
							<a class="d-block mb-15" id="downloadImage" href="<?php echo $imageUrl;?>" download ><i class="fa fa-download mr-5" aria-hidden="true"></i>
								<?php echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_DOWNLOAD_AS_IMAGE'); ?>
							</a>
							<?php
							if ($this->certificate->getDownloadUrl())
							{
								?>
							
									<a class="d-block mb-15" href="<?php echo $this->certificate->getDownloadUrl();?>">
										<i class="fa fa-file-pdf-o mr-5" aria-hidden="true"></i>
										<?php
											echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_DOWNLOAD_PDF');
										?>
									</a>
								
								<?php
							}
							?>
							<span class="btn-print">
							<input type="button" class="btn-print" onclick="certificateImage.printCertificate('certificateContent')" value="<?php echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_PRINT');?>" />
							</span>
						</div>

						<?php 
						if ($this->params->get('social_sharing'))
						{?>
						<a id="sharing-popover" data-container="body" data-placement="bottom" tabindex="0" class="tj-certificate-btn" role="button" data-toggle="popover" data-trigger="focus" title="<?php echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_DOWNLOAD_SHARE');?>"><i class="fa fa-share-square-o mr-10" aria-hidden="true"></i>
						<?php echo Text::_('COM_TJCERTIFICATE_CERTIFICATE_DOWNLOAD_SHARE');?>
						</a>

						<?php
						} ?>
						<div id="sharing-popover-content" class="hide">
							<div class="tj-certificate-sharing">
							<?php
								if (isset($this->item))
								{
									echo $this->loadTemplate('social_sharing');
								}
							?>
							</div>
						</div>
					</div>
					</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
		<div class="col-sm-12 bg-lightblue p-15">
			<div class="fs-16">
				This certificate (ID: <?php echo $this->certificate->unique_certificate_id;?>) verifies that <strong><?php echo Factory::getUser($this->certificate->getUserId())->name; ?></strong> has successfully completed the <strong><?php echo $this->item->title; ?></strong> on <?php echo HTMLHelper::_('date', $this->certificate->issued_on, Text::_('COM_TJCERTIFICATE_CERTIFICATE_DETAIL_VIEW_DATE_FORMAT'));?>.
				<?php 
				if ($this->certificate->getExpiry() != '0000-00-00 00:00:00')
				{
				?>
					This Certificate expires on <strong><?php echo HTMLHelper::_('date', $this->certificate->getExpiry(), Text::_('COM_TJCERTIFICATE_CERTIFICATE_DETAIL_VIEW_DATE_FORMAT'));?></strong>.
				<?php
				}
				?>
			</div>
		</div>
		<div class="col-sm-12 tj-certificate-content mb-15 mt-25">
			<div id="certificateContent">
				<?php
					echo $this->certificate->generated_body;
				?>
			</div>
			<div id="previewImage" class="tj-certificate-image">
				<?php if ($imageUrl) {?>
					<img src="<?php echo $imageUrl;?>">
				<?php } ?>
			</div>
			<input id="certificateId" type="hidden" value="<?php echo $this->certificate->unique_certificate_id;?>"/>
		</div>
	</div>
<?php
}
?>
<script type="text/javascript">

var imageExists = "<?php echo $imageUrl;?>";

jQuery(document).ready(function() {
	if (imageExists)
	{
		jQuery('#certificateContent').hide();
	}
	else
	{
		certificateImage.generateImage(document.querySelector("#certificateContent"));
	}

	certificateImage.enableDownloadShareBtns();
});

</script>
