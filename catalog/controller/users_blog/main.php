<?php
class ControllerUsersBlogMain extends Controller {
	public function index() {
		$this->load->language('account/news');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/news');
		$this->load->model('tool/image');

		$data['heading_title'] = $this->language->get('heading_title');

		$news = $this->model_account_news->getAllNews();

		$data['news'] = [];
		$i=0;
		foreach ($news as $item) {
			$i++;

			$data["news"][] = array(
				"number" => $i,
				"status" => $item['status'],
				"link" => $this->url->link('account/news&id='.$item['id'].'', '', true),
				"title" => $item['title'],
				"description" => $item['description'],
				"image" => $this->model_tool_image->resize('catalog/news/'.$item['image'], 570, 260),
				"date" => $item['date_added'] ,
			);
			// code...
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('users_blog/main', $data));
	}

}
?>