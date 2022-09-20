<?php
class ControllerAccountNews extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/edit', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/news');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/news');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['save']) && $this->validate()) { //
			
			if (!isset($this->request->post['active']) || $this->request->post['active'] != 1 ) {
				$this->request->post['active'] = 0;
			}

			if(isset($_GET['id']) && $_GET['id'] != ""){
				$this->model_account_news->editNews($this->customer->getId(), $_GET['id'], $this->request->post);
				if($_FILES['myFile']['size'] != 0){
					move_uploaded_file($this->fileTmpPath, $this->destPath);
					$this->model_account_news->updateImg($_GET['id'], $this->fileName);
				}
			} else {
				$news_id = $this->model_account_news->addNews($this->customer->getId(), $this->request->post);
				if($_FILES['myFile']['size'] != 0){
					move_uploaded_file($this->fileTmpPath, $this->destPath);
					$this->model_account_news->updateImg($news_id, $this->fileName);
				}

				$this->response->redirect($this->url->link('account/news&id='.$news_id.'', '', true));
			}

			$this->session->data['success'] = $this->language->get('text_success');

		} elseif(isset($this->request->post['delete'])) {
			$this->model_account_news->deleteNews($_GET['id']);
			$this->response->redirect($this->url->link('account/mynews', '', true));
			$this->session->data['success'] = $this->language->get('text_success');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_my_news'),
			'href' => $this->url->link('account/mynews', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_edit_news'),
			'href' => $this->url->link('account/edit', '', true)
		);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['fields_error'])) {
			$data['fields_error'] = $this->error['fields_error'];
		} else {
			$data['fields_error'] = '';
		}

		$data['deleteBut'] = false;
		$this->image = "";

		if(isset($_GET['id'])){
			$data['action'] = $this->url->link('account/news&id='.$_GET["id"], '', true);
			$news = $this->model_account_news->getSingleNews($_GET['id']);
			$data['deleteBut'] = true;
			if(isset($news['image'])){
				$this->image = $news['image'];
			}
		} else {
			$data['action'] = $this->url->link('account/news', '', true);
		}

		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif(isset($news['title'])){
			$data['title'] = $news['title'];
		} else {
			$data['title'] = '';
		}

		if (isset($this->request->post['text'])) {
			$data['text'] = $this->request->post['text'];
		} elseif(isset($news['text'])){
			$data['text'] = $news['text'];
		} else {
			$data['text'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif(isset($news['description'])){
			$data['description'] = $news['description'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['active'])) {
			$data['active'] = $this->request->post['active'];
		} elseif(isset($news['status'])){
			$data['active'] = $news['status'];
		} else {
			$data['active'] = '';
		}

		$this->load->model('tool/image');

		if (isset($news['image'])) {
			$data['image'] = $this->model_tool_image->resize('catalog/news/'.$news['image'], 100, 100);
		} else {
			$data['image'] = '';
		}

		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/news', $data));
	}

	protected function validate() {
		if ((utf8_strlen(trim($this->request->post['title'])) < 1) || (utf8_strlen(trim($this->request->post['title'])) > 64)) {
			$this->error['fields_error'] = $this->language->get('text_error_title');
		}

		if ((utf8_strlen(trim($this->request->post['description'])) < 1) || (utf8_strlen(trim($this->request->post['description'])) > 256)) {
			$this->error['fields_error'] = $this->language->get('text_error_description');
		}

		if (isset($_FILES['myFile']) && $_FILES['myFile']['error'] === UPLOAD_ERR_OK) {
			$fileTmpPath = $_FILES['myFile']['tmp_name'];
			$fileNameCmps = explode(".", $_FILES['myFile']['name']);
			$fileExtension = strtolower(end($fileNameCmps));
			$fileName = md5(time() . $_FILES['myFile']['name']) . '.' . $fileExtension;

			$allowedfileExtensions = array('jpg', 'png');
			if (in_array($fileExtension, $allowedfileExtensions)) {
				$uploadFileDir = DIR_IMAGE . 'catalog/news/';
				$this->fileTmpPath = $fileTmpPath;
				$this->fileName = $fileName;
				$this->destPath = $uploadFileDir . $fileName;
			} else {
				$this->error['fields_error'] = $this->language->get('text_error_file');
			}
		} elseif($this->image == "") {
			
		} else {
			$this->error['fields_error'] = $this->language->get('text_error_wasted_file');
		}

		// if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
		// 	$this->error['warning'] = $this->language->get('error_exists');
		// }

		return !$this->error;
	}
}
