<?php

namespace App\Http\Controllers;

use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//todo 线上未安装拓展
class ResumableController extends Controller
{

    public $tempFolder = '';

    public $uploadFolder = 'D:\project\manageCompanyService\public\upload';

    public $deleteTmpFolder = false;

    protected $request;

    protected $response;

    protected $params;

    protected $chunkFile;

    /**
     * Resumable constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    /**
     *开始续传
     */
    public function process()
    {
            if (!empty($this->request->file())) {
                 return $this->handleChunk();
            } else {
                return 'ncaknc';
            }
    }
    /**
     *
     * @return mixed
     */
    public function handleChunk()
    {
        //获取请求的参数
        $file = $this->file();
        $identifier = $this->request->input('resumableIdentifier');
        $filename = $this->request->input('resumableFilename');
        $chunkNumber = $this->request->input('resumableChunkNumber');
        $chunkSize = $this->request->input('resumableChunkSize');
        $totalSize = $this->request->input('resumableTotalSize');
//判断上传的分块是否存在  如果不存在则存到tmp 下相关分块文件夹
        if (!$this->isChunkUploaded($identifier,$filename,$chunkNumber)) {
            $chunkFile = $this->tmpChunkDir($identifier).DIRECTORY_SEPARATOR.$this->tmpChunkFilename($filename, $chunkNumber);
            $this->moveUploadedFile($file['tmp_name'], $chunkFile);
        }
//合成完整文件并且删除分块路径
        if ($this->isFileUploadComplete($filename,$identifier,$chunkSize, $totalSize)) {
            $tmpFolder = new Folder($this->tmpChunkDir($identifier));
            //Returns an array of the contents of the current directory. 文件数组
            $chunkFiles = $tmpFolder->read(true,true,true)[1];
            //合成
            $this->createFileFromChunks($chunkFiles, $this->uploadFolder.DIRECTORY_SEPARATOR.$filename);
            if ($this->deleteTmpFolder) {
                $tmpFolder->delete();
            }
        }

        return $this->header(200);
    }

    private function _resumableParam($shortName) {
        $resumableParams = $this->resumableParams();
        if (!isset($resumableParams['resumable'.ucfirst($shortName)])) {
            return null;
        }
        return $resumableParams['resumable'.ucfirst($shortName)];
    }

    /**
     * 获取项目的数组
     * @return array|mixed
     */
    public function file()
    {
        if (!isset($_FILES) || empty($_FILES)) {
            return array();
        }
        $files = array_values($_FILES);
        return array_shift($files);
    }

    /**
     * 返回头部信息
     * @param $statusCode
     */
    public function header($statusCode)
    {
        if (200==$statusCode) {
            return header("HTTP/1.0 200 Ok");
        } else if (404==$statusCode) {
            return header("HTTP/1.0 404 Not Found");
        }
        return header("HTTP/1.0 404 Not Found");
    }

    /**
     * 获取请求的数据
     *
     * @return mixed
     */
    public function resumableParams()
    {
        if ($this->request->is('get')) {
            return $this->request->data('get');
        }
        if ($this->request->is('post')) {
            return $this->request->data('post');
        }
    }

    /**
     * 判断上传分块是否完成
     * @param $filename
     * @param $identifier
     * @param $chunkSize
     * @param $totalSize
     * @return bool
     */
    public function isFileUploadComplete($filename, $identifier, $chunkSize, $totalSize)
    {
        if ($chunkSize <= 0) {
            return false;
        }
        $numOfChunks = intval($totalSize / $chunkSize) + ($totalSize % $chunkSize == 0 ? 0 : 1);
        for ($i = 1; $i < $numOfChunks; $i++) {
            if (!$this->isChunkUploaded($identifier, $filename, $i)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 判断分块文件是否存在
     * @param $identifier 唯一标志
     * @param $filename 原始文件名
     * @param $chunkNumber 当前快的索引
     * @return bool
     */
    public function isChunkUploaded($identifier, $filename, $chunkNumber)
    {
        $file = new File($this->tmpChunkDir($identifier) . DIRECTORY_SEPARATOR . $this->tmpChunkFilename($filename, $chunkNumber));
        return $file->exists();
    }

    /**
     * 用于创建路径
     * @param $identifier
     * @return string
     */
    public function tmpChunkDir($identifier)
    {
        $tmpChunkDir = 'tmp' . DIRECTORY_SEPARATOR . $identifier;
        if (!file_exists($tmpChunkDir)) {
            mkdir($tmpChunkDir);
        }
        return $tmpChunkDir;
    }

    /**
     * 用于创建文件名
     * @param $filename
     * @param $chunkNumber
     * @return string
     */
    public function tmpChunkFilename($filename, $chunkNumber)
    {
        return $filename . '.part' . $chunkNumber;
    }

    /**
     * @param $chunkFiles
     * @param $destFile
     * @return bool
     *合成文件
     */
    public function createFileFromChunks($chunkFiles, $destFile)
    {
        $destFile = new File($destFile, true);
        foreach ($chunkFiles as $chunkFile) {
            $file = new File($chunkFile);
            $destFile->append($file->read());
        }
        return $destFile->exists();
    }

    /**
     * @param $file
     * @param $destFile
     * @return bool
     */
    public function moveUploadedFile($file, $destFile)
    {
        $file = new File($file);
        if ($file->exists()) {
            return $file->copy($destFile);
        }
        return false;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

}
