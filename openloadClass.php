<?php
   /**
   * PHP class for Openload/Streamango API
   * 
   * @author     ToishY
   * @version    1.0.2
   */
	class OpenloadMain{
		private $log;
		private $key;
		public $host;

		function __construct($apiHost, $apiCreds){
			$t = json_decode(file_get_contents($apiCreds),true);
			$this->log = $t['L'];
			$this->key = $t['K']; 
			$this->host = $apiHost;
			unset($t);
		}

		public function curlBuilder($functionName, $functionArgs = NULL){
			$ch = curl_init();
			// call requested method
			$requestUrl = $this->$functionName($functionArgs);
			// setopts
			curl_setopt($ch, CURLOPT_URL, $requestUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);

			$response = curl_exec($ch);
			if (!curl_errno($ch)) {
				$res = json_decode($response,true);
			}else{
				$res = curl_error($ch);
			}
			curl_close($ch);
			return $res;
		}

		private function accountInfo(){
			return $this->host.'/account/info?'.$this->queryBuilder();
		}

		private function fileInfo($inputArray){
			//$inputArray = array("file"=>"d5e6f7g8,h9i10j11k")
			return $this->host.'/file/info?'.$this->queryBuilder($inputArray);
		}

		private function uploadServer($inputArray){
			//$inputArray = array("folder"=>0,"sha1"=>NULL,"httponly"=>NULL)
			return $this->host.'/file/ul?'.$this->queryBuilder($inputArray);
		}

		public function uploadFile($inputArray){
			//$inputArray = array('C:\Users\foo\bar.mp4',0,NULL,NULL)
			list($filePath, $folderId, $sha, $http) = $inputArray;

			// Get upload server
			$vjson = $this->curlBuilder("uploadServer", array("folder"=>$folderId,"sha1"=>$sha,"httponly"=>$http));

			// POST variables
			$postFile = ['file1' => new \CurlFile($filePath, mime_content_type($filePath), basename($filePath))];

		    // Upload file
		    $ch = curl_init($vjson["result"]["url"]);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFile);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		    // Set execution time to inf
		    ini_set('max_execution_time', 0);
		    $res = json_decode(curl_exec($ch),true);
		    if (($res['msg'] !== 'OK')) {
		        $ares = array("code"=>400,"response"=>$res,"curl_error"=>curl_error($ch));
		    }else{
		    	$ares = array("code"=>200,"response"=>$res);
		    }
		    curl_close($ch);
		    return $ares;
		}

		private function uploadByURL($inputArray){
			//$inputArray = array("url"=>"http://clips.vorwaerts-gmbh.de/VfE_html5.mp4","folder"=>NULL,"headers"=>NULL)
			return $this->host.'/remotedl/add?'.$this->queryBuilder($inputArray);
		}

		private function checkRemoteUpload($inputArray){
			//$inputArray = array("limit"=>50,"id"=>NULL)
			return $this->host.'/remotedl/status?'.$this->queryBuilder($inputArray);
		}

		private function folderList($inputArray = NULL){
			//$inputArray = array("folder"=>0)
			return $this->host.'/file/listfolder?'.$this->queryBuilder($inputArray);
		}

		private function folderRename($inputArray){
			//$inputArray = array("folder"=>123,"name"=>"my awesome folder v2")
			return $this->host.'/file/renamefolder?'.$this->queryBuilder($inputArray);
		}

		private function fileRename($inputArray){
			//$inputArray = array("file"=>"h9i10j11k","name"=>"my awesome file v2")
			$res = $this->curlBuilder('fileInfo',array("file"=>$inputArray['file']));
			$inputArray['name'] = $inputArray['name'].'.'.pathinfo($res["result"][$inputArray['file']]["name"], PATHINFO_EXTENSION);
			return $this->host.'/file/rename?'.$this->queryBuilder($inputArray);
		}

		private function deleteFile($inputArray){
			//$inputArray = array("file"=>"h9i10j11k")
			return $this->host.'/file/delete?'.$this->queryBuilder($inputArray);
		}

		private function convertFile($inputArray){
			//$inputArray = array("file"=>"d5e6f7g8")
			return $this->host.'/file/convert?'.$this->queryBuilder($inputArray);
		}

		private function checkRunningConverts($inputArray){
			//$inputArray = array("folder"=>NULL)
			return $this->host.'/file/runningconverts?'.$this->queryBuilder($inputArray);
		}

		private function getSplashImage($inputArray){
			//$inputArray = array("file"=>"d5e6f7g8")
			return $this->host.'/file/getsplash?'.$this->queryBuilder($inputArray);
		}

		private function queryBuilder($query = NULL){
			$qar = (isset($query) ? array_merge(array("login"=>$this->log,"key"=>$this->key),$query) : array("login"=>$this->log,"key"=>$this->key));
			return http_build_query($qar, '', '&', PHP_QUERY_RFC3986);
		}
	}
?>
