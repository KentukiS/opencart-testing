<?php
class ModelAccountNews extends Model {
	public function addNews($customer_id, $data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "news SET status = '" . (int)$data['active'] . "', date_added = NOW()");
		$news_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET 
			news_id = '" . (int)$news_id . "', 
			language_id = '" . (int)$this->config->get('config_language_id') . "',
			title = '" . $this->db->escape($data['title']) . "',
			text = '" . $this->db->escape($data['text']) . "',
			description = '" . $this->db->escape($data['description']) . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "news_customer SET 
			customer_id = '" . (int)$customer_id . "', 
			news_id = '" . (int)$news_id . "'");

		// $this->db->query("INSERT INTO " . DB_PREFIX . "news_files SET 
		// 	customer_id = '" . (int)$customer_id . "', 
		// 	file = '" . $test . "'");

		return $news_id;
	}

	function editNews($customer_id, $news_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET
			status = '" . (int)$data['active'] . "', 
			date_added = NOW()
			WHERE id = '" . (int)$news_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "news_description SET 
			language_id = '" . (int)$this->config->get('config_language_id') . "',
			title = '" . $this->db->escape($data['title']) . "',
			text = '" . $this->db->escape($data['text']) . "',
			description = '" . $this->db->escape($data['description']) . "'
			WHERE news_id = '" . (int)$news_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "news_customer SET 
			customer_id = '" . (int)$customer_id . "', 
			news_id = '" . (int)$news_id . "'
			WHERE news_id = '" . (int)$news_id . "'");
	}

	function updateImg($news_id, $filepath) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET 
			image = '" . $this->db->escape($filepath) . "'
			WHERE id = '" . (int)$news_id . "'");
	}

	function deleteNews($news_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "news WHERE id = '" . (int)$news_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "news_customer WHERE news_id = '" . (int)$news_id . "'");
	}

	function getAllNews(){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "news` n
			LEFT JOIN `" . DB_PREFIX . "news_description` nd ON n.id = nd.news_id 
			WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND n.status = 1");

		return $query->rows;
	}

	function getSingleNews($news_id){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "news` n
			LEFT JOIN `" . DB_PREFIX . "news_description` nd ON n.id = nd.news_id
			WHERE id = '" . (int)$news_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ");

		return $query->row;
	}

	function getAllUserNews($customer_id){

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "news` n
			LEFT JOIN `" . DB_PREFIX . "news_description` nd ON n.id = nd.news_id
			LEFT JOIN `" . DB_PREFIX . "news_customer` nc ON n.id = nc.news_id
			WHERE nc.customer_id = '" . (int)$customer_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;
	}

}