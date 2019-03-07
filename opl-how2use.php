<?php
# HOW TO USE OPENLOAD CLASS
require_once 'openloadClass.php';

# Pass your API login & key from the OPENLOAD/STREAMANGO user panel in here
$opl = new OpenloadMain("https://api.openload.co/1","y29psa10mv73x02s","f00b4rz");

# Get account info
$res = $opl->curlBuilder("accountInfo");
print_r($res);

# Get file info
/* Arguments
- Required: 
	FileCode(s); if multiple files, please comma seperate
*/
$res = $opl->curlBuilder("fileInfo",array("file"=>"d5e6f7g8,h9i10j11k"));
print_r($res);

# Get upload server
/* Arguments (in array):
- Optional
	FolderId; folder id; 0 = root
	Sha1; expected sha1
	Http; only use http upload links if set to true
*/
$res = $opl->curlBuilder("uploadServer",array("folder"=>0,"sha1"=>NULL,"httponly"=>NULL));
print_r($res);

# Upload file
/* Arguments (in array):
- Required
	FilePath; the full path of the file you want to upload
	!IMPORTANT please use single quotes for filepath
- Optional
	FolderId;
	Sha1; expected sha1
	Http; only use http upload links if set to true
*/
$res = $opl->uploadFile(array('C:\Users\foo\bar.mp4',0,NULL,NULL));
print_r($res);

# Upload by URL
/* Arguments (in array):
- Required
	VideoURL; link to video
- Optional
	FolderId;
	Headers; optional headers
*/
$res = $opl->curlBuilder("uploadByURL",array("url"=>"http://clips.vorwaerts-gmbh.de/VfE_html5.mp4","folder"=>NULL,"headers"=>NULL));
print_r($res);

# Check remote upload status 
/* Arguments (in array):
- Optional
	Limit; amount of files to show (default 5; max 100)
	RemoteId; remote upload id
*/
$res = $opl->curlBuilder("checkRemoteUpload",array("limit"=>50,"id"=>NULL));
print_r($res);

# Show folders content
/* Arguments:
- Optional
	FolderId;
*/
$res = $opl->curlBuilder("folderList",array("folder"=>0));
print_r($res);

# Rename folder
/* Arguments (in array):
- Required
	FolderId;
	FolderName; new name of the folder id specified
*/
$res = $opl->curlBuilder("folderRename",array("folder"=>123,"name"=>"my awesome folder v2"));
print_r($res);

# Rename file
/* Arguments (in array):
- Required
	FileCode;
	FileName; new name of the file id specified

*/
$res = $opl->curlBuilder("fileRename",array("file"=>"h9i10j11k","name"=>"my awesome file v2"));
print_r($res);

# Delete file
/* Arguments (in array):
- Required
	FileCode;
*/
$res = $opl->curlBuilder("deleteFile",array("file"=>"h9i10j11k"));
print_r($res);

# Convert file
/* Arguments:
- Required
	FileCode;
*/
$res = $opl->curlBuilder("convertFile",array("file"=>"d5e6f7g8"));
print_r($res);

# Check running conversion
/* Arguments:
- Optional
	FolderId;
*/
$res = $opl->curlBuilder("checkRunningConverts",array("folder"=>NULL));
print_r($res);

# Get splash image
/* Arguments:
- Required
	FileCode;
*/
$res = $opl->curlBuilder("getSplashImage",array("file"=>"d5e6f7g8"));
print_r($res);	
?>
