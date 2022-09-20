<?php
class ControllerExtensionModuleJetimpexFooterLinks extends Controller {
	public function index($setting) {
		switch ($setting['type']) {
			case '0':
			return $this->information();
			break;
			case '1':
			return $this->service();
			break;
			case '2':
			return $this->extras();
			break;
			case '3':
			return $this->account();
			break;
			case '4':
			return $this->contact();
			break;
			case '5':
			return $this->ways();
			break;
			default:
			return false;
			break;
		}
	}

	public function information() {
		$this->load->language('extension/module/jetimpex_footer_links');

		$this->load->model('catalog/information');

		$data['text_information'] = $this->language->get('text_information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
					);
			}
		}

		$data['contact']       = $this->url->link('information/contact');
		$data['return']        = $this->url->link('account/return/add', '', true);

		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_return']  = $this->language->get('text_return');

		return $this->load->view('extension/module/jetimpex_footer_links_information', $data);
	}

	public function service() {
		$this->load->language('extension/module/jetimpex_footer_links');

		$data['contact']       = $this->url->link('information/contact');
		$data['return']        = $this->url->link('account/return/add', '', true);
		$data['sitemap']       = $this->url->link('information/sitemap');

		if(($this->config->has('theme_' . $this->config->get('config_theme') . '_simple_blog_status')) && ($this->config->get('theme_' . $this->config->get('config_theme') . '_simple_blog_status'))) {
			$data['simple_blog_found'] = 1;
			$tmp = $this->config->get('theme_' . $this->config->get('config_theme') . '_simple_blog_footer_heading');
			if (!empty($tmp)) {
				$data['simple_blog_footer_heading'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_simple_blog_footer_heading');
			} else {
				$data['simple_blog_footer_heading'] = $this->language->get('text_simple_blog');
			}
			$data['simple_blog']	= $this->url->link('simple_blog/article');
		}

		$data['text_service']  = $this->language->get('text_service');
		$data['text_contact']  = $this->language->get('text_contact');
		$data['text_return']   = $this->language->get('text_return');
		$data['text_sitemap']  = $this->language->get('text_sitemap');

		return $this->load->view('extension/module/jetimpex_footer_links_service', $data);
	}

	public function extras() {
		$this->load->language('extension/module/jetimpex_footer_links');

		$data['manufacturer']      = $this->url->link('product/manufacturer');
		$data['voucher']           = $this->url->link('account/voucher', '', true);
		$data['affiliate']         = $this->url->link('affiliate/account', '', true);
		$data['special']           = $this->url->link('product/special');
		$data['wishlist']        = $this->url->link('account/wishlist', '', true);
		$data['gift_cards']      = $this->url->link('account/voucher', '', true);
		
		$data['text_extra']        = $this->language->get('text_extra');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_voucher']      = $this->language->get('text_voucher');
		$data['text_affiliate']    = $this->language->get('text_affiliate');
		$data['text_special']      = $this->language->get('text_special');
		$data['text_wishlist']   = $this->language->get('text_wishlist');
		$data['text_gift_cards'] = $this->language->get('text_gift_cards');
		$data['wishlist']        = $this->url->link('account/wishlist', '', true);

		return $this->load->view('extension/module/jetimpex_footer_links_extra', $data);
	}

	public function account() {
		$this->load->language('extension/module/jetimpex_footer_links');

		$data['account']         = $this->url->link('account/account', '', true);
		$data['order']           = $this->url->link('account/order', '', true);
		$data['wishlist']        = $this->url->link('account/wishlist', '', true);
		$data['newsletter']      = $this->url->link('account/newsletter', '', true);
		$data['voucher']           = $this->url->link('account/voucher', '', true);
		$data['affiliate']         = $this->url->link('affiliate/account', '', true);
		$data['special']           = $this->url->link('product/special');
		$data['sitemap']       = $this->url->link('information/sitemap');
		
		$data['text_account']    = $this->language->get('text_account');
		$data['text_order']      = $this->language->get('text_order');
		$data['text_wishlist']   = $this->language->get('text_wishlist');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_voucher']      = $this->language->get('text_voucher');
		$data['text_affiliate']    = $this->language->get('text_affiliate');
		$data['text_sitemap']  = $this->language->get('text_sitemap');

		return $this->load->view('extension/module/jetimpex_footer_links_account', $data);
	}

	public function contact() {
		$this->load->language('extension/module/jetimpex_footer_links');

		$data['address']        = nl2br($this->config->get('config_address'));
		$data['telephone']      = $this->config->get('config_telephone');
		$data['fax']            = $this->config->get('config_fax');
		$data['email']          = $this->config->get('config_email');
		$data['geocode']        = $this->config->get('config_geocode');
		$data['open']           = $this->config->get('config_open');
		
		$data['text_contact']   = $this->language->get('text_contact');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax']       = $this->language->get('text_fax');
		$data['text_email']     = $this->language->get('text_email');

		return $this->load->view('extension/module/jetimpex_footer_links_contact', $data);
	}

	public function ways() {
		$this->load->language('extension/module/jetimpex_footer_links');

		$data['store']           = $this->url->link('information/sitemap', '', true);
		$data['gift_cards']      = $this->url->link('account/voucher', '', true);
		$data['wishlist']        = $this->url->link('account/wishlist', '', true);
		
		$data['text_more']       = $this->language->get('text_more');
		$data['text_store']      = $this->language->get('text_store');
		$data['text_gift_cards'] = $this->language->get('text_gift_cards');
		$data['text_wishlist']   = $this->language->get('text_wishlist');

		return $this->load->view('extension/module/jetimpex_footer_links_ways', $data);
	}
}