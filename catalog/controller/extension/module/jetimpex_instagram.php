<?php
class ControllerExtensionModuleJetimpexInstagram extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/jetimpex_instagram');
		$this->document->addScript('catalog/view/theme/' . $this->config->get('theme_' . $this->config->get('config_theme') . '_directory') . '/js/jetimpex_instagram/instafeed.min.js');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['_get']           = $setting['_get'];
		$data['tag_name']      = $setting['tag_name'];
		$data['user_id']       = $setting['user_id'];
		$data['accesstoken']   = $setting['accesstoken'];
		$data['limit']         = $setting['limit'];

		return $this->load->view('extension/module/jetimpex_instagram', $data);
	}
}