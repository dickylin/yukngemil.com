<?php
/**
 *	d_social_like_lite
 *	catalog controller
 *	@author: Dreamvention
 */

class ControllerModuleDSocialLikeLite extends Controller {

	private $id = 'd_social_like_lite';
	private $route = 'module/d_social_like_lite';

	public function index($setting) {
		$this->language->load('module/d_social_like_lite');
		
		if ((($setting['language_id'] == (int)$this->config->get('config_language_id')) || ($setting['language_id'] == -1)) && (($setting['store_id'] == (int)$this->config->get('config_store_id')) || ($setting['store_id'] == -1))) {

			$this->document->addScript('//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4d8b33027d80e2ce');

			$data['heading_like_us'] = $this->language->get('heading_like_us');
			$data['button_aready_liked'] = $this->language->get('button_aready_liked');
			$data['button_like_us'] = $this->language->get('button_like_us');

			$this->config->load($this->id);
			$config_setting = ($this->config->get($this->id)) ? $this->config->get($this->id) : array();

			if (!empty($setting)) {
				$setting = array_replace_recursive($config_setting, $setting);
			}

			$data['view'] = $setting['view_id'];
			$data['url'] = $setting['url'];

			$this->document->addStyle('catalog/view/theme/default/stylesheet/d_social_like_lite/icons/'.$setting['design']['icon_theme'].'/styles.css');

			$sort_order = array();

			foreach ($setting['social_likes'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $setting['social_likes']);

			$data['social_likes'] = array(); 
			$data['count'] = 0;
			$data['design'] = $setting['design'];

			foreach ($setting['social_likes'] as $social_like){
				if($social_like['enabled']){
					$data['count']++;
					$id = $social_like['id'];
					$data['social_likes'][$id] = $social_like;
					$data['social_likes'][$id]['code'] = $this->$id($social_like);
				}
			}

			if (VERSION >= '2.2.0.0') {
				return $this->load->view($this->route, $data);
			} elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->route . '.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/' . $this->route . '.tpl', $data);
			} else {
				return $this->load->view('default/template/' . $this->route . '.tpl', $data);
			}

		}
	}
	
	public function google($social_like){
		$result ='<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>';
		return $result;
	}
	public function linkedin($social_like){
		$result ='<a class="addthis_button_linkedin_counter"></a>';
		return $result;
	}
}
?>